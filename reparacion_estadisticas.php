<?php
  require_once('includes/load.php');
  page_require_level(2);
  $topMarcas = $db->while_loop($db->query("SELECT `marca`, COUNT(`marca`) AS `cantidad` FROM ( (SELECT `reparacion_maquina`.`id` AS `id_reparacion`, `maquina`.`id` AS `id_maquina`, `maquina_modelo`.`id` AS `id_modelo`, `maquina_marca`.`descripcion` AS `marca` FROM `reparacion_maquina` JOIN `maquina` ON `reparacion_maquina`.`id_maquina`=`maquina`.`id` JOIN `maquina_modelo` ON `maquina`.`modelo_id`=`maquina_modelo`.`id` JOIN `maquina_marca` ON `maquina_modelo`.`marca_id`=`maquina_marca`.`id` ORDER BY 4 ) AS dummy1 ) GROUP BY `marca` ORDER BY 2 DESC LIMIT 5"));
  $cantReparacionesFinalizadas = $db->num_rows($db->query("SELECT `id` FROM `reparacion_maquina` WHERE `id_estado` = 8"));
  $cantReparacionesSinFinalizar = $db->num_rows($db->query("SELECT `id` FROM `reparacion_maquina` WHERE `id_estado` <> 8"));
  $cantReparaciones = $db->num_rows($db->query("SELECT `id` FROM `reparacion_maquina`"));
  $topTiposMaquinas = $db->while_loop($db->query("SELECT `tipo_maquina`, COUNT(`tipo_maquina`) AS `cantidad` FROM ( (SELECT `reparacion_maquina`.`id` AS `id_reparacion`, `maquina`.`id` AS `id_maquina`, `maquina_modelo`.`id` AS `id_modelo`, `maquina_tipo`.`descripcion` AS `tipo_maquina` FROM `reparacion_maquina` JOIN `maquina` ON `reparacion_maquina`.`id_maquina`=`maquina`.`id` JOIN `maquina_modelo` ON `maquina`.`modelo_id`=`maquina_modelo`.`id` JOIN `maquina_tipo` ON `maquina_tipo`.`id`=`maquina_modelo`.`tipo_id` ORDER BY 4 ) AS dummy1 ) GROUP BY `tipo_maquina` ORDER BY 2 DESC LIMIT 5"));
  $topRepuestos = $db->while_loop($db->query("SELECT `maquina_repuesto`.`id`, `maquina_repuesto`.`descripcion`, `maquina_repuesto`.`codigo`, `maquina_repuesto`.`parte`, `maquina_modelo`.`codigo` AS `modelo`, `maquina_modelo`.`descripcion` AS `descripcion_modelo`, `maquina_marca`.`descripcion` AS `marca`, COUNT(`maquina_repuesto`.`id`) AS `cantidad_de_reparaciones`, SUM(`cantidad`) AS `cantidad_unidades` FROM `reparacion_repuesto` JOIN `maquina_repuesto` ON `maquina_repuesto`.`id`=`reparacion_repuesto`.`repuesto_id` JOIN `maquina_modelo` ON `maquina_modelo`.`id`=`maquina_repuesto`.`id_modelo` JOIN `maquina_marca` ON `maquina_marca`.`id`=`maquina_modelo`.`marca_id` WHERE `reparacion_id` IN ( SELECT `id` FROM `reparacion_maquina` WHERE `id_estado` = 8) AND `reparacion_repuesto`.`elegido` = 1 AND `maquina_marca`.`id` <> 37 GROUP BY `maquina_repuesto`.`id` ORDER BY `cantidad_unidades` DESC LIMIT 5"));
  $maquinasActivas = $db->num_rows($db->query("SELECT `id` FROM `maquina` WHERE `sin_reparacion` = 0"));
  $maquinasInactivas = $db->num_rows($db->query("SELECT `id` FROM `maquina` WHERE `sin_reparacion` = 1"));
  $allMaquinas = $maquinasActivas + $maquinasInactivas;
  $datosReparacionFinalizada = $db->fetch_assoc($db->query("SELECT SUM(`repuestos`) AS `repuestos`, SUM(`flete`) AS `flete`, SUM(`total`) AS `total` FROM `reparacion_cotizacion` WHERE `fecha_entrega_maquina` IS NOT NULL"));
  $datosReparacionSinFinalizar = $db->fetch_assoc($db->query("SELECT SUM(`repuestos`) AS `repuestos`, SUM(`flete`) AS `flete`, SUM(`total`) AS `total` FROM `reparacion_cotizacion` WHERE `fecha_entrega_maquina` IS NULL"));  
  $gastosTotalesFinalizadas = $datosReparacionFinalizada['repuestos'] + $datosReparacionFinalizada['flete'];
  $gastosTotalesSinFinalizar = $datosReparacionSinFinalizar['repuestos'] + $datosReparacionSinFinalizar['flete'];
?>
<div class="row">
	<div class="col-md-1"style="min-width: 10px;max-width: 5%;"></div>
	<!-- BOX MARCAS MAS REPARADAS -->
	<div class="col-md-3" style="min-width: 500px;max-width: 30%;">
		<div class="small-box bg-warning" style="height: 220px;">
			<div class="inner">
				<p style="font-size: 22px;font-weight: 600;">Marcas más reparadas</p>
				<hr style="margin-left: 0px;margin-top: -10px;margin-bottom: 8px; width: 70%;border-color: black;">
				<div class="table-responsive" style="width: 78%;">
					<table id="tbl_marcas-mas-reparadas" class="table table-hover table-condensed table-striped" style="color: rgba(0, 0, 0, 0.8);background-color: white;">
					  <thead>
					  </thead>
					  <tbody>
					  <?php
					  	$v = 0;
					  	foreach ($topMarcas as $unTopMarca): 
					  	$v = $v + 1; ?>
					  	<tr>
					  		<td class="text-center" style="width: 5%;"><?php echo $v."°"; ?></td>
					  		<td style="width: 55%;"><?php echo $unTopMarca['marca']; ?></td>
					  		<td class="" style="width: 15%;text-align: right;"><?php echo $unTopMarca['cantidad']; ?></td>
					  		<td class="" style="width: 25%;">Reparaciones</td>
					  	</tr>
					  <?php endforeach; ?>
					  </tbody>
					  <tfoot>
					  </tfoot>
					</table>
				</div>
			</div>
			<div class="icon">
				<i class="fas fa-tools"></i>
			</div>
			<a type="button" class="small-box-footer" onclick="" style="margin-top: -23px;"><i class="fas fa-arrow-circle-right"></i></a>	
		</div>	
	</div>
	<!-- END BOX MARCAS MAS REPARADAS -->
	<!-- BOX TIPOS MAS REPARADOS -->
	<div class="col-md-3"style="min-width: 500px;max-width: 30%;">
		<div class="small-box bg-warning" style="height: 220px;">
			<div class="inner">
				<p style="font-size: 22px;font-weight: 600;">Tipos más reparados de máquinas</p>
				<hr style="margin-left: 0px;margin-top: -10px;margin-bottom: 8px; width: 70%;border-color: black;">
				<div class="table-responsive" style="width: 78%;">
					<table id="tbl_marcas-mas-reparadas" class="table table-hover table-condensed table-striped" style="color: rgba(0, 0, 0, 0.8);background-color: white;">
					  <thead>
					  </thead>
					  <tbody>
					  <?php
					  	$v = 0;
					  	foreach ($topTiposMaquinas as $unTopTipo): 
					  	$v = $v + 1; ?>
					  	<tr>
					  		<td class="text-center" style="width: 5%;"><?php echo $v."°"; ?></td>
					  		<td style="width: 55%;"><?php echo $unTopTipo['tipo_maquina']; ?></td>
					  		<td class="" style="width: 15%;text-align: right;"><?php echo $unTopTipo['cantidad']; ?></td>
					  		<td class="" style="width: 25%;">Reparaciones</td>
					  	</tr>
					  <?php endforeach; ?>
					  </tbody>
					  <tfoot>
					  </tfoot>
					</table>
				</div>
			</div>
			<div class="icon">
				<i class="fas fa-tools"></i>
			</div>
			<a type="button" class="small-box-footer" onclick="" style="margin-top: -23px;"><i class="fas fa-arrow-circle-right"></i></a>	
		</div>	
	</div>
	<!-- END BOX TIPOS MAS REPARADOS -->
	<!-- BOX REPUESTOS MAS UTILIZADOS -->
	<div class="col-md-3"style="min-width: 500px;max-width: 30%;">
		<div class="small-box bg-warning" style="height: 220px;">
			<div class="inner">
				<p style="font-size: 22px;font-weight: 600;">Repuestos más utilizados</p>
				<hr style="margin-left: 0px;margin-top: -10px;margin-bottom: 8px; width: 70%;border-color: black;">
				<div class="table-responsive" style="width: 78%;">
					<table id="tbl_marcas-mas-reparadas" class="table table-hover table-condensed table-striped" style="color: rgba(0, 0, 0, 0.8);background-color: white;">
					  <thead>
					  </thead>
					  <tbody>
					  <?php
					  	$v = 0;
					  	foreach ($topRepuestos as $unTopRepuesto): 
					  	$v = $v + 1; ?>
					  	<tr data-toggle="toolbox" title="Éste repuesto pertenece a la marca <?php echo $unTopRepuesto['marca']; ?>, modelo <?php echo $unTopRepuesto['modelo'];?> <?php echo $unTopRepuesto['descripcion_modelo']; ?>">
					  		<td class="text-center" style="width: 5%;"><?php echo $v."°"; ?></td>
					  		<td style="width: 60%;"><?php echo $unTopRepuesto['descripcion']; ?></td>
					  		<td class="text-center" style="width: 10%;"><?php echo $unTopRepuesto['cantidad_unidades']; ?></td>
					  		<td style="width: 25%;">Unidades</td>
					  	</tr>
					  <?php endforeach; ?>
					  </tbody>
					  <tfoot>
					  </tfoot>
					</table>
				</div>
			</div>
			<div class="icon">
				<i class="fas fa-cogs"></i>
			</div>
			<a type="button" class="small-box-footer" onclick="" style="margin-top: -23px;"><i class="fas fa-arrow-circle-right"></i></a>	
		</div>	
	</div>
	<!-- END BOX REPUESTOS MAS UTILIZADOS -->

	<div class="col-md-1"style="min-width: 10px;max-width: 5%;"></div>
</div>


<div class="row">
	<div class="col-md-1" style="min-width: 10px;max-width: 5%;"></div>
	<!-- BOX MAQUINAS REGISTRADAS -->
	<div class="col-md-3" style="min-width: 500px;max-width: 30%;">
		<div class="small-box bg-info" style="height: 220px;/*background-color: rgba(22, 160, 133, 1) !important;*/">
			<div class="inner">
				<p style="font-size: 22px;font-weight: 600;">Registro histórico de máquinas</p>
				<hr style="margin-left: 0px;margin-top: -10px;margin-bottom: 8px; width: 70%;">
				<p style="font-size: 20px;font-weight: 500;margin-top: 15px;width: 70%;text-align: right;">Alta de Máquinas: <span style="font-size: 28px;font-weight: 500;"><?php echo $allMaquinas; ?></span></p>
				<p style="font-size: 20px;font-weight: 500;margin-top: 5px;width: 70%;text-align: right;">Baja de Máquinas: <span style="font-size: 28px;font-weight: 500;"><?php echo $maquinasInactivas; ?></span></p>
				<hr style="margin-left: 5%;margin-top: -10px;margin-bottom: 8px; width: 65%;">
				<p style="font-size: 20px;font-weight: 500;margin-top: 5px;width: 70%;text-align: right;">Máquinas Activas: <span style="font-size: 28px;font-weight: 500;"><?php echo $maquinasActivas; ?></span></p>
			</div>
			<div class="icon">
				<i class="fas fa-chart-line"></i>
			</div>
			<a type="button" class="small-box-footer" onclick="" style="margin-top: -19px;"><i class="fas fa-arrow-circle-right"></i></a>	
		</div>	
	</div>
	<!-- END BOX MAQUINAS REGISTRADAS -->
	<!-- BOX CANT REPARACIONES FINALIZADAS -->
	<div class="col-md-3" style="min-width: 500px;max-width: 30%;">
		<div class="small-box bg-info" style="height: 220px;/*background-color: rgba(22, 160, 133, 1) !important;*/">
			<div class="inner">
				<p style="font-size: 22px;font-weight: 600;">Historial de reparaciones</p>
				<hr style="margin-left: 0px;margin-top: -10px;margin-bottom: 8px; width: 70%;">
				<p style="font-size: 20px;font-weight: 500;margin-top: 15px;width: 70%;text-align: right;">Reparaciones Finalizadas: <span style="font-size: 28px;font-weight: 500;"><?php echo $cantReparacionesFinalizadas; ?></span></p>
				<p style="font-size: 20px;font-weight: 500;margin-top: 5px;width: 70%;text-align: right;">Reparaciones Sin Finalizar: <span style="font-size: 28px;font-weight: 500;"><?php echo $cantReparacionesSinFinalizar; ?></span></p>
				<hr style="margin-left: 5%;margin-top: -10px;margin-bottom: 8px; width: 65%;">
				<p style="font-size: 20px;font-weight: 500;margin-top: 5px;width: 70%;text-align: right;">Total Histórico: <span style="font-size: 28px;font-weight: 500;"><?php echo $cantReparaciones; ?></span></p>
			</div>
			<div class="icon">
				<i class="fas fa-tools"></i>
			</div>
			<a type="button" class="small-box-footer" onclick="" style="margin-top: -19px;"><i class="fas fa-arrow-circle-right"></i></a>	
		</div>	
	</div>
	<!-- END BOX CANT REPARACIONES FINALIZADAS -->
	<div class="col-md-1" style="min-width: 20%;max-width: 20%;"></div>
</div>

<div class="row">
	<!-- BOX SUMA GASTOS EN REPUESTOS -->
	<div class="col-md-6">
		<div class="small-box bg-danger" style="height: 220px;/*background-color: rgba(22, 160, 133, 1) !important;*/">
			<div class="inner">
				<div class="row">
					<div class="col-md-5">
						<p style="font-size: 18px;font-weight: 600;">Gastos Máquinas Entregadas</p>
						<hr style="margin-left: 0px;margin-top: -10px;margin-bottom: 8px; width: 100%;">
						<p style="font-size: 16px;font-weight: 500;margin-top: 15px;width: 100%;text-align: right;">Gastos de repuestos: <span style="font-size: 28px;font-weight: 500;"><?php echo "$ ".$datosReparacionFinalizada['repuestos']; ?></span></p>
						<p style="font-size: 16px;font-weight: 500;margin-top: 5px;width: 100%;text-align: right;">Gastos de fletes: <span style="font-size: 28px;font-weight: 500;"><?php echo "$ ".$datosReparacionFinalizada['flete']; ?></span></p>
						<hr style="margin-right: 0px;margin-top: -10px;margin-bottom: 8px; width: 65%;">
						<p style="font-size: 16px;font-weight: 500;margin-top: 5px;width: 100%;text-align: right;">Total: <span style="font-size: 28px;font-weight: 500;"><?php echo "$ ".$gastosTotalesFinalizadas; ?></span></p>
					</div>
					<!------><div class="col-md-1" style="min-width: 1px;max-width: 3px;border-right: 1.5px solid rgba(255, 255, 255, 0.7);height: 180px;"></div>
					<div class="col-md-5">
						<p style="font-size: 18px;font-weight: 600;">Gastos Máquinas Sin Entregar</p>
						<hr style="margin-left: 0px;margin-top: -10px;margin-bottom: 8px; width: 100%;">
						<p style="font-size: 16px;font-weight: 500;margin-top: 15px;width: 100%;text-align: right;">Gastos en Repuestos: <span style="font-size: 28px;font-weight: 500;"><?php echo "$ ".$datosReparacionSinFinalizar['repuestos']; ?></span></p>
						<p style="font-size: 16px;font-weight: 500;margin-top: 5px;width: 100%;text-align: right;">Gastos en Fletes: <span style="font-size: 28px;font-weight: 500;"><?php echo "$ ".$datosReparacionSinFinalizar['flete']; ?></span></p>
						<hr style="margin-right: 0px;margin-top: -10px;margin-bottom: 8px; width: 65%;">
						<p style="font-size: 16px;font-weight: 500;margin-top: 5px;width: 100%;text-align: right;">Total: <span style="font-size: 28px;font-weight: 500;"><?php echo "$ ".$gastosTotalesSinFinalizar; ?></span></p>
					</div>
				</div>
			</div>
			<div class="icon">
				<i class="fas fa-hand-holding-usd"></i>
			</div>
			<a type="button" class="small-box-footer" onclick="" style="margin-top: -13px;"><i class="fas fa-arrow-circle-right"></i></a>	
		</div>	
	</div>
	<!-- END BOX SUMA GASTOS EN REPUESTOS -->
	<!-- BOX SUMA COTIZACIONES -->
	<div class="col-md-6">
		<div class="small-box bg-success" style="height: 220px;/*background-color: rgba(22, 160, 133, 1) !important;*/">
			<div class="inner">
				<div class="row">
					<div class="col-md-5">
						<p style="font-size: 17px;font-weight: 600;">Cotizaciones Máquinas Entregadas</p>
						<hr style="margin-left: 0px;margin-top: -10px;margin-bottom: 8px; width: 100%;">
						<p style="font-size: 16px;font-weight: 500;margin-top: 15px;width: 100%;text-align: right;">Monto: <span style="font-size: 28px;font-weight: 500;"><?php echo "$ ".$datosReparacionFinalizada['total']; ?></span></p>
					<!--	<p style="font-size: 16px;font-weight: 500;margin-top: 5px;width: 100%;text-align: right;">Gastos de fletes: <span style="font-size: 28px;font-weight: 500;"><?php// echo "$ ".$datosReparacionFinalizada['flete']; ?></span></p>
						<hr style="margin-right: 0px;margin-top: -10px;margin-bottom: 8px; width: 65%;">
						<p style="font-size: 16px;font-weight: 500;margin-top: 5px;width: 100%;text-align: right;">Total: <span style="font-size: 28px;font-weight: 500;"><?php// echo "$ ".$gastosTotalesFinalizadas; ?></span></p>
					-->
					</div>
					<!------><div class="col-md-1" style="min-width: 1px;max-width: 3px;border-right: 1.5px solid rgba(255, 255, 255, 0.7);height: 180px;"></div>
					<div class="col-md-5">
						<p style="font-size: 17px;font-weight: 600;">Cotizaciones Máquinas sin Entregar</p>
						<hr style="margin-left: 0px;margin-top: -10px;margin-bottom: 8px; width: 100%;">
						<p style="font-size: 16px;font-weight: 500;margin-top: 15px;width: 100%;text-align: right;">Monto: <span style="font-size: 28px;font-weight: 500;"><?php echo "$ ".$datosReparacionSinFinalizar['total']; ?></span></p>
						<!--<p style="font-size: 16px;font-weight: 500;margin-top: 5px;width: 100%;text-align: right;">Gastos en Fletes: <span style="font-size: 28px;font-weight: 500;"><?php// echo "$ ".$datosReparacionSinFinalizar['flete']; ?></span></p>
						<hr style="margin-right: 0px;margin-top: -10px;margin-bottom: 8px; width: 65%;">
						<p style="font-size: 16px;font-weight: 500;margin-top: 5px;width: 100%;text-align: right;">Total: <span style="font-size: 28px;font-weight: 500;"><?php// echo "$ ".$gastosTotalesSinFinalizar; ?></span></p>
					-->
					</div>
				</div>
			</div>
			<div class="icon">
				<i class="fas fa-donate"></i>
			</div>
			<a type="button" class="small-box-footer" onclick="" style="margin-top: -3px;"><i class="fas fa-arrow-circle-right"></i></a>	
		</div>	
	</div>
	<!-- END BOX SUMA COTIZACIONES -->
	

</div>