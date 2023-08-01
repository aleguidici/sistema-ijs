<?php
	$page_title = 'Ver reparaciones de las máquinas';
	require_once('includes/load.php');
	include_once('layouts/newHeader.php');
	$idCliente = remove_junk($db->escape($_POST['id_cliente']));
	$thisCliente = $db->fetch_assoc($db->query("SELECT * FROM `clientemaquina` WHERE `id` = '".$idCliente."'"));
	//$allMaquinas = $db->while_loop($db->query("SELECT `id`, `modelo_id`, `num_serie` FROM `maquina` WHERE `id_cliente` = '".$idCliente."'"));
	$allMaquinas = $db->while_loop($db->query("SELECT `maquina`.`id`, `num_serie`, `codigo`, `maquina_marca`.`descripcion` AS `marca`, `maquina_tipo`.`descripcion` AS `tipo`, `sin_reparacion`, `razon_noreparacion` FROM `maquina` JOIN `maquina_modelo` ON `maquina_modelo`.`id` = `maquina`.`modelo_id` JOIN `maquina_marca` ON `maquina_marca`.`id` = `maquina_modelo`.`marca_id` JOIN `maquina_tipo` ON `maquina_tipo`.`id` = `maquina_modelo`.`tipo_id` WHERE `maquina`.`id_cliente` = '".$idCliente."' ORDER BY 5 ASC"));
?>
<style type="text/css">
	.p-for-cliente-reparaciones {
		font-size: 13px;
		font-weight: bold;
		margin: 0px;
		padding: 0px;
		line-height: 20px;
		color: rgba(255,255,255,0.9);
	}
</style>

<script type="text/javascript">
	$(window).load(function() {
		$('.loader2').fadeOut('fast');
		$('#sel_maquinas').selectpicker('refresh');
	});
	
	function cargarReparaciones() {
		var idMaquina = document.getElementById("sel_maquinas").value;
		alertify.success("Cargando, por favor espere.");
		$('.loader2').show();
		$('.loader2').fadeOut(2150);
		$('#contenedor-repuestos').fadeOut(1000);
		setTimeout(function() {              
         $('#contenedor-repuestos').load('reparacion_verPorMaquinasDatos.php?id_maquina='+idMaquina+'');
         $('#contenedor-repuestos').fadeIn(1000);
      },1000);
	}

</script>
<div id="loader2" class="loader2"></div>
<div class="row">
	<div class="col-md-4">
		<div>
			<br>
			<label for="sel_maquinas" class="control-label">Seleccione una máquina:</label>
			<select class="selectpicker" data-show-subtext="true" data-live-search="true" name="sel_maquinas" id="sel_maquinas" data-width="100%" onchange="javascript:cargarReparaciones();">
            <option value="" disabled selected >Seleccione una máquina:</option>
         <?php foreach ($allMaquinas as $unaMaquina) :
         $cantReparaciones = mysqli_num_rows($db->query("SELECT `id` FROM `reparacion_maquina` WHERE `id_maquina` = '".$unaMaquina['id']."'"));
         if ($unaMaquina['sin_reparacion'] == 1) { ?>
         	<option style="background-color: rgba(255,0,0,0.3);" value="<?php echo $unaMaquina['id']; ?>"><?php if ($unaMaquina['num_serie']) { echo "IJS-ME: ".$unaMaquina['id']." - ".$unaMaquina['tipo']." - ".$unaMaquina['marca']." - ".$unaMaquina['codigo']." - N° Serie: ".$unaMaquina['num_serie']." - Cantidad de reparaciones: ".$cantReparaciones; } else { echo "IJS-ME: ".$unaMaquina['id']." - ".$unaMaquina['tipo']." - ".$unaMaquina['marca']." - ".$unaMaquina['codigo']." - N° Serie: Sin definir - Cantidad de reparaciones: ".$cantReparaciones; } ?></option>
         				<?php } else { ?>
            <option value="<?php echo $unaMaquina['id']; ?>"><?php if ($unaMaquina['num_serie']) { echo "IJS-ME: ".$unaMaquina['id']." - ".$unaMaquina['tipo']." - ".$unaMaquina['marca']." - ".$unaMaquina['codigo']." - N° Serie: ".$unaMaquina['num_serie']." - Cantidad de reparaciones: ".$cantReparaciones; } else { echo "IJS-ME: ".$unaMaquina['id']." - ".$unaMaquina['tipo']." - ".$unaMaquina['marca']." - ".$unaMaquina['codigo']." - N° Serie: Sin definir - Cantidad de reparaciones: ".$cantReparaciones; } ?></option>
         <?php } endforeach; ?>                   
         </select>
		</div>
	</div>
	<div class="col-md-6">
		<iframe src="particulas/demo/index.html" frameborder="0" scrolling="no" name="contenedor" style="border-radius: 15px 45px;height: 100px;width: 575px;"></iframe>
		<div class="row" style="/*background-color: rgba(22,160,133,0.5);*/border-radius: 15px 45px;width: 550px;">
			
					<div class="col-md-4" style="margin-top: -95px;position: relative;">
						<p class="p-for-cliente-reparaciones" style="text-align: right;">Cliente:</p>
						<p class="p-for-cliente-reparaciones" style="text-align: right;">Dni/Cuit:</p>
						<p class="p-for-cliente-reparaciones" style="text-align: right;">Dirección:</p>
						<p class="p-for-cliente-reparaciones" style="text-align: right;">Tel/Cel:</p>
					</div>
					<div class="col-md-8" style="margin-top: -95px;position: relative;">
						<p class="p-for-cliente-reparaciones" style="text-align: left;"><?php echo $thisCliente['razon_social']; ?></p>
						<p class="p-for-cliente-reparaciones" style="text-align: left;"><?php echo $thisCliente['cuit']; ?></p>
						<p class="p-for-cliente-reparaciones" style="text-align: left;"><?php echo $thisCliente['direccion']; ?></p>
						<p class="p-for-cliente-reparaciones" style="text-align: left;"><?php if ($thisCliente['tel']) { echo $thisCliente['tel']; } else { echo $thisCliente['cel']; } ?></p>
					</div>
		</div>
	</div>
	<div class="col-md-2">
	</div>
</div>
<hr>
<div id="contenedor-repuestos"></div>
<?php include_once('layouts/newFooter.php'); ?>