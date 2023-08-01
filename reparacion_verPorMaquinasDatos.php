<?php
	require_once('includes/load.php');
	page_require_level(2);
	$idMaquina = remove_junk($db->escape($_GET['id_maquina']));
	$thisMaquina = $db->fetch_assoc($db->query("SELECT `maquina_tipo`.`descripcion` AS `tipo`,`maquina_marca`.`descripcion` AS `marca`, `maquina_modelo`.`codigo`, `num_serie` FROM `maquina` JOIN `maquina_modelo` ON `maquina_modelo`.`id` = `maquina`.`modelo_id` JOIN `maquina_tipo` ON `maquina_tipo`.`id` = `maquina_modelo`.`tipo_id` JOIN `maquina_marca` ON `maquina_marca`.`id` = `maquina_modelo`.`marca_id` WHERE `maquina`.`id` = '".$idMaquina."'"));
	$estadoMaquina = $db->fetch_assoc($db->query("SELECT `sin_reparacion`, `razon_noreparacion` FROM `maquina` WHERE `id` = '".$idMaquina."'"));
	//$allReparaciones = $db->while_loop($db->query("SELECT * FROM `reparacion_maquina` WHERE `id_maquina` = '".$idMaquina."'"));
	$allReparaciones = $db->while_loop($db->query("SELECT `reparacion_maquina`.`id`, `id_maquina`, `fecha_ingreso`, `hora_ingreso`, `fecha_egreso`, `hora_egreso`, `id_estado`, `reparacion_estado`.`descripcion` AS `estado`, `reparacion_cotizacion`.`total` FROM `reparacion_maquina` JOIN `reparacion_estado` ON `reparacion_estado`.`id` = `reparacion_maquina`.`id_estado` LEFT JOIN `reparacion_cotizacion` ON `reparacion_cotizacion`.`reparacion_id` = `reparacion_maquina`.`id` WHERE `id_maquina` = '".$idMaquina."'"));
?>
<style type="text/css">
	.hover-reparaciones:hover {
		box-shadow: 0px 0px 15px 2px rgba(0,0,0,0.5);
		-webkit-box-shadow: 0px 0px 15px 2px rgba(0,0,0,0.5);
		-moz-box-shadow: 0px 0px 15px 2px rgba(0,0,0,0.5);
		z-index: 99;
		transform: scale(1.05);
	}
</style>
<script type="text/javascript">
	function irReparacion(idMaquina,idReparacion) {
		$('.loader2').show();
		$('.loader2').fadeOut(2500);
		alertify.success("Redireccionando a la reparación, por favor espere.");
		setTimeout(function() {              
            window.open('repuestos.php?idMaquina='+idMaquina+'&idReparacion='+idReparacion+'','_blank');
        },2000);
	}
</script>
<div class="row">
  <div class="col-md-12">
  	<?php if($estadoMaquina['sin_reparacion'] == 1) { ?>
  		<div class="alert alert-danger"><p><?php echo "Ésta máquina está registrada bajo el concepto 'SIN REPARACIÓN', el motivo: ".$estadoMaquina['razon_noreparacion']; ?></p><a href="#" style="margin-top: -22px;" class="close" data-dismiss="alert">&times;</a></div>
  	<?php } else {} ?>
  	<?php if ($idMaquina) { ?>
  		<div class="alert alert-success"><p style="font-size: 15px;"><?php if ($thisMaquina['num_serie']) { echo "Mostrando las reparaciones de: IJS-ME: ".$idMaquina." - ".$thisMaquina['tipo']." - ".$thisMaquina['marca']." - ".$thisMaquina['codigo']." - Número de serie: ".$thisMaquina['num_serie']; } else { echo "Mostrando las reparaciones de: IJS-ME: ".$idMaquina." - ".$thisMaquina['tipo']." - ".$thisMaquina['marca']." - ".$thisMaquina['codigo']." - Número de serie: Sin definir"; } ?></p><a href="#" style="margin-top: -22px;" class="close" data-dismiss="alert">&times;</a></div>
  	<?php } else {} ?>
  </div>
</div>
<div class="row" style="margin-bottom: 25px;">
	<?php
		$banderita = 0; 
		foreach ($allReparaciones as $unaReparacion) : 
		$banderita = $banderita + 1;
	?>
	<div class="col-md-4" style="">
		<?php if ($unaReparacion['id_estado'] != 0) { 
					if($unaReparacion['id_estado'] == 1) { ?>
		<div class="small-box bg-danger hover-reparaciones" style="margin: 0px;height: 110px;">
		<?php 	} elseif ($unaReparacion['id_estado'] == 8) { ?>
		<div class="small-box bg-info hover-reparaciones" style="margin: 0px;height: 110px;">
		<?php 	} elseif ($unaReparacion['id_estado'] == 7) { ?>
		<div class="small-box bg-success hover-reparaciones" style="margin: 0px;height: 110px;">
		<?php 	} else { ?>
		<div class="small-box bg-warning hover-reparaciones" style="margin: 0px;height: 110px;"> 
		<?php 	} }?>	
			<div class="inner">
				<div class="row">
					<div class="col-md-6">
					 	<h3 style="font-size: 18px;margin-top: 0;margin-bottom: 5px;font-weight: bold; text-align: left;"><?php echo "Reparación N°: ".$unaReparacion['id']; ?></h3></div>
					<div class="col-md-4"><h3 style="font-size: 18px;margin-top: 0;margin-bottom: 5px;font-weight: bold;text-align: right;"><?php echo $unaReparacion['estado']; ?></h3></div>
					<div class="col-md-2"></div>
				</div>
				<div class="row">
					<div class="col-md-6">	
						<p style="margin-top: 0;margin-bottom: 0px;font-size: 14px;font-weight: normal;"><?php echo "Ingreso: ".$unaReparacion['fecha_ingreso']."&emsp;".$unaReparacion['hora_ingreso']; ?></p>
						<p style="margin-top: 0;margin-bottom: 0px;font-size: 14px;font-weight: normal;"><?php echo "Egreso:  ".$unaReparacion['fecha_egreso']."&emsp;".$unaReparacion['hora_egreso']; ?></p>
					</div>
					<div class="col-md-4"><p style="margin-top: 0;margin-bottom: 0px;font-size: 14px;font-weight: normal;text-align: right;"><?php if ($unaReparacion['total'] >= 0) { echo "Cotizado: $".$unaReparacion['total']; } else { echo "Cotizado: Sin cotizar"; } ?></p></div>
					<div class="col-md-2"></div>
				</div>
			</div>
			<div class="icon" style="margin-top: 3px;">
				<i class="fas fa-cogs" style="margin-top: -15px;"></i>
			</div>
			<a type="button" class="small-box-footer" onclick="javascript:irReparacion('<?php echo $unaReparacion['id_maquina']; ?>','<?php echo $unaReparacion['id']; ?>');">Ir a la reparación&emsp;<i class="fas fa-arrow-circle-right"></i></a>
		</div>	
	</div>

	<?php
		if ($banderita == 3) { $banderita = 0;  ?>
</div>
<div class="row" style="margin-bottom: 25px;">
	<?php	} else {}		
		endforeach; 
	?>
</div>





				

