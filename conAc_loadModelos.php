<?php
  require_once('includes/load.php');
  $idMarca = remove_junk($db->escape($_GET['id_marca']));
  $modelo = remove_junk($db->escape($_GET['modelo']));
  $allModelos = $db->while_loop($db->query("SELECT `maquina_modelo`.`id`, `marca_id`, `codigo`, `maquina_modelo`.`descripcion` AS `descripcion`, `maquina_tipo`.`descripcion` AS `tipo`, `maquina_tamanio`.`descripcion` AS `tamanio`, `inalambrico`, `anio` FROM `maquina_modelo` JOIN `maquina_tipo` ON `maquina_tipo`.`id` = `maquina_modelo`.`tipo_id` JOIN `maquina_tamanio` ON `maquina_tamanio`.`id` = `maquina_modelo`.`tamanio_id` WHERE `marca_id` = ".$idMarca." ORDER BY 5"));
 ?>
<script type="text/javascript">
	$(document).ready(function() { 
	  $('#modelo').selectpicker();
	  //console.log('<?php echo $modelo ?>');
	});
</script>

 <div id="div-new-modelos">
  <label for="modelo" class="control-label">Modelo: *</label><a id='buttom-crear-mod' data-toggle="modal" class='btn open-modal_nuevoModelo' title='Nuevo Modelo' style="padding-top: 0px; padding-bottom: 0px;margin-bottom: -10px;margin-top: -12px;" data-target="#modal_nuevoModelo" data-id="" data-marca=""><i class="fa fa-1 fa-plus-square" style="color:#008a55;" aria-hidden="true"></i></a>
  <select class="selectpicker" data-show-subtext="true" data-live-search="true" data-width="100%" name="modelo" id="modelo">
  	<?php
    if($modelo == "the-first-time") { ?>
    <option value="" disabled selected>Seleccione un modelo</option>
    <?php } 
  	foreach ($allModelos as $unModelo):
      if($unModelo['inalambrico']){$inalambrico = " (Inalámbrico/a - ";}else{$inalambrico=" (";}
      if($unModelo['anio']){$anio = " - Año: ".$unModelo['anio'];}else{$anio="";}
  	if($unModelo['codigo'] == strtoupper($modelo)) { ?>
  		<option selected value="<?php echo $unModelo['id']; ?>"><?php echo $unModelo['tipo']." '".$unModelo['codigo']."'".$inalambrico."Tamaño: ".$unModelo['tamanio']."".$anio.")"; ?></option>
  	<?php 
    } else { ?>
    	<option value="<?php echo $unModelo['id']; ?>"><?php echo $unModelo['tipo']." '".$unModelo['codigo']."'".$inalambrico."Tamaño: ".$unModelo['tamanio']."".$anio.")"; ?></option>
    <?php
    }	endforeach; ?>
  </select>
</div>
