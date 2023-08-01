<?php
  $page_title = 'Nueva Reparación';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
  /*$estaMaquina = find_by_id('maquina',$_GET['id_maquina']);
  $esteModelo = find_by_id('maquina_modelo',$estaMaquina['modelo_id']);
  $estaMarca = find_by_id('maquina_marca',$esteModelo['marca_id']);*/
  $estaMaquina = $db->fetch_assoc($db->query("SELECT `maquina`.`id`, `maquina`.`num_serie`, `maquina`.`detalles` AS `detalle_maquina`, `maquina_modelo`.`codigo` AS `modelo`, `maquina_modelo`.`descripcion` AS `descripcion_modelo`, `maquina_modelo`.`inalambrico`, `maquina_tipo`.`descripcion` AS `tipo`, `maquina_modelo`.`tamanio_id` AS `id_tamanio`, `maquina_tamanio`.`descripcion` AS `tamanio`, `maquina_marca`.`descripcion` AS `marca` FROM `maquina` JOIN `maquina_modelo` ON `maquina_modelo`.`id` = `maquina`.`modelo_id` JOIN `maquina_tipo` ON `maquina_tipo`.`id` = `maquina_modelo`.`tipo_id` JOIN `maquina_tamanio` ON `maquina_tamanio`.`id` = `maquina_modelo`.`tamanio_id` JOIN `maquina_marca` ON `maquina_marca`.`id` = `maquina_modelo`.`marca_id` WHERE `maquina`.`id` = '{$_GET['id_maquina']}'"));
  //$estaMaquina = find_this_maquina_for_reparacion('$_GET['idMaquina']');

  if (isset($_POST['btnCrear'])) {   
    $idMaquinaok = remove_junk($db->escape($_POST['idMaquinaFinal']));
    $senia = remove_junk($db->escape($_POST['senia']));
    $fecha = date('Y-m-d');
    $hora = date('H:i:s');
    $observacionesPost = remove_junk($db->escape($_POST['observaciones']));
    if ($observacionesPost != null) {
      $observaciones = $observacionesPost ;
    } else {
      $observaciones = "" ;
    }
    $query  = "INSERT INTO `reparacion_maquina` (`id_maquina`, `fecha_ingreso`, `hora_ingreso`, `senia`, `id_estado`, `descripcion`) VALUES ('{$idMaquinaok}', '{$fecha}', '{$hora}', '{$senia}', '1', '{$observaciones}')";
    if ($db->query($query)) {
      $session->msg("s", "Reparación agregada exitosamente.");
      redirect('conAc.php',false);
    } else {
      $session->msg("d", "Lo siento, el registro falló");
      redirect('conAc.php',false);
    }  
  }
?>
      
<?php echo display_msg($msg); ?>

<div id="contenido_crear-reparacion">
  <form method="post" action="conAc_agregarReparacionMaquina.php">     
    <h4 style="margin-bottom: 2px;"><label class="control-label">Máquina:</label></h4>
    <div class="row">
    </div>

    <div class="row" style="margin-bottom: 4px; margin-top: 4px">
      <div class="col-md-2">
        &emsp;&emsp;
        <b>Código IJS: </b>
      </div>
      <div class="col-md-4"><label class="label label-success" style="font-size: 12px;">
        <?php echo "IJS-ME: ".$estaMaquina['id'];?>
      </label>
      </div>

      <div class="col-md-2">
        &emsp;&emsp;
        <b>Número de serie: </b>
      </div>
      <div class="col-md-4">
        <?php if ($estaMaquina['num_serie']) echo $estaMaquina['num_serie']; else echo "-- --";?>
      </div>
    </div>

    <div class="row" style="margin-bottom: 4px; margin-top: 4px">
      <div class="col-md-2">
        &emsp;&emsp;
        <b>Tipo: </b>
      </div>
      <div class="col-md-4">
        <?php echo $estaMaquina['tipo'];?>
      </div>
      <div class="col-md-2">
        &emsp;&emsp;
        <b>Tamaño: </b>
      </div>
      <div class="col-md-4">
      <?php if ($estaMaquina['id_tamanio'] == 1) {?>
        <label class="label label-info" style="font-size: 12px;"><?php echo $estaMaquina['tamanio'];?>
      <?php } if ($estaMaquina['id_tamanio'] == 2) {?>
        <label class="label label-success" style="font-size: 12px;"><?php echo $estaMaquina['tamanio'];?>
      <?php } if ($estaMaquina['id_tamanio'] == 3) {?>
        <label class="label label-warning" style="font-size: 12px;"><?php echo $estaMaquina['tamanio'];?>
      <?php } if ($estaMaquina['id_tamanio'] == 4) {?>
        <label class="label label-danger" style="font-size: 12px;"><?php echo $estaMaquina['tamanio'];?>
      <?php } ?>
        </label>
      </div>
    </div>

    <div class="row" style="margin-bottom: 4px; margin-top: 4px">
      <div class="col-md-2">
        &emsp;&emsp;
        <b>Marca: </b>
      </div>
      <div class="col-md-4">
        <?php echo $estaMaquina['marca'];?>
      </div>
      <div class="col-md-2">
        &emsp;&emsp;
        <b>Inalámbrico/a: </b>
      </div>
      <div class="col-md-4">
        <?php if ($estaMaquina['inalambrico'] == 1) { ?><label class="label label-success" style="font-size: 12px;"><?php echo "Sí"; } else { ?><label class="label label-danger" style="font-size: 12px;"><?php echo "No"; } ?></label>
      </div>
    </div>

    <div class="row" style="margin-bottom: 4px; margin-top: 4px">
      <div class="col-md-2">
        &emsp;&emsp;
        <b>Modelo: </b>
      </div>
      <div class="col-md-4">
        <?php echo $estaMaquina['modelo'];?>
      </div>
      <div class="col-md-2">
        &emsp;&emsp;
        <b>Descripción modelo: </b>
      </div>
      <div class="col-md-4">
        <?php if ($estaMaquina['descripcion_modelo']) echo $estaMaquina['descripcion_modelo']; else echo " -- -- ";?>
      </div>
    </div>

    <hr>

    <div class="row">
      <div class="form-group col-xs-4 ">
        <label for="senia" class="control-label">Observaciones para la reparación: <sup> (opcional)</sup></label>
        <textarea type="text" class="form-control" name="observaciones" id="observaciones" rows="3" placeholder="Observaciones para la reparación" maxlength="235" style="resize: none;" onkeypress="return noEnter(this.value, event)"></textarea>           
      </div>
      <div class="form-group col-xs-2 ">
        <label for="senia" class="control-label">Seña: *</label>
        <input type="number" min="0" step="50" class="form-control" name="senia" id="senia" placeholder="Admitidos múltiplos de 50" maxlength="10" required onchange="javascript:seniado()">
        <input type="hidden" class="form-control" name="idMaquinaFinal" value="<?php echo ($_GET['id_maquina'])?>">
      </div>
      <div class="form-group col-xs-3">
      </div>
      <div class="form-group col-xs-3" style="margin-bottom: 0;">
        <div class="form-group text-right">
        <br><br><br>
          <button type="button" class="btn btn-primary" onclick="javascript:rePage();">Cerrar</button>   
          <button type="submit" name="btnCrear" id="btnCrear" class="btn btn-danger">Crear Reparación</button>
        </div>
      </div>
    </div>
  </form>
</div>
<script>

  $(document).ready(function(){
    $('#btnCrear').prop("disabled",true);
    $('#btnCrear').attr("disabled",true);    
  });

  function seniado() {
    document.getElementById("btnCrear").disabled = false;
    $('#btnCrear').removeClass("btn-danger").addClass("btn-success");
  }
</script>

