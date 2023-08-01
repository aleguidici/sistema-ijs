<?php
  require_once('includes/load.php');
  $idCliente = $_GET['idCliente'];
  $allMaquinas = $db->while_loop($db->query("SELECT * FROM `maquina` WHERE `id_cliente` = ".$idCliente));
  //$lastId = $db->insert_id();
 ?>


<div class="row">
  <div class="col-md-10">
    <label for="selectMaquina" class="control-label">Máquina:</label>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <select class="selectpicker" data-show-subtext="true" data-live-search="true" name="selectMaquina" id="selectMaquina" required="required" data-width="95%" onchange="javascript:maquinaSel()">
    </select>
    <button type="button" class="btn btn-success" id="btn_show-add-maquina" onclick="javascript:showAddMaquina();" data-toggle="tooltip" title="Agregar una máquina"><span class="glyphicon glyphicon-plus"></span></button>
  </div>      
</div>
<input hidden type="text" id="inp_last-id">
<script type="text/javascript">
  $(document).ready(function() {     
    $('#selectMaquina').selectpicker();    
    var input_last_id = document.getElementById("inp_last-id").value;
    var seleccionado = document.getElementById("selectMaquina");
    if(last_id_maquina) {
      $('#selectMaquina').find('option').remove().end().append('<option value="" disabled>Seleccione una máquina</option>');
    } else {
      $('#selectMaquina').find('option').remove().end().append('<option value="" disabled selected>Seleccione una máquina</option>');
    }
    <?php 
    foreach ($allMaquinas as $unaMaquina):
      $estaMaquina = find_by_id('maquina',$unaMaquina['id']);
      $esteModelo = find_by_id('maquina_modelo',$unaMaquina['modelo_id']);
      $estaMarca = find_by_id('maquina_marca',$esteModelo['marca_id']);
      $esteTipo = find_by_id('maquina_tipo',$esteModelo['tipo_id']);
      $estaEnReparacion = $db->fetch_assoc($db->query("SELECT `id_maquina` FROM `reparacion_maquina` WHERE `id_maquina` = ".$unaMaquina['id']." AND `id_estado` <> 8"));       
    ?>
    if ("<?php echo $unaMaquina['id_cliente'];?>" == "<?php echo $idCliente;?>") {                  
      var opt = document.createElement('option');
      if ("<?php echo $estaMaquina['sin_reparacion'];?>" == 1) {
        var clienteNombreMost = "<?php echo "IJS-ME: ".$estaMaquina['id']." - ".$esteTipo['descripcion']." - ".$estaMarca['descripcion']." - "."(Modelo: ".$esteModelo['codigo'].") - (N° Serie: ".htmlspecialchars_decode($estaMaquina['num_serie'],ENT_NOQUOTES).")"." -- NO TIENE REPARACIÓN -- ";?>";
        opt.style = "background-color: #F2DEDE; color: #A94442;"
        opt.disabled = "true";
      } else {
        if ("<?php echo $estaEnReparacion['id_maquina']; ?>" == "<?php echo $unaMaquina['id'];?>") { //controla si esta en reparacion
          var clienteNombreMost = "<?php echo "IJS-ME: ".$estaMaquina['id']." - ".$esteTipo['descripcion']." - ".$estaMarca['descripcion']." - "."(Modelo: ".$esteModelo['codigo'].") - (N° Serie: ".htmlspecialchars_decode($estaMaquina['num_serie'],ENT_NOQUOTES).")"." -- ACTUALMENTE EN REPARACIÓN -- ";?>";
          opt.disabled = "true";
          opt.style = "background-color: #FCF8E3; color: #8B6E3C;";
        } else {
          var clienteNombreMost = "<?php echo "IJS-ME: ".$estaMaquina['id']." - ".$esteTipo['descripcion']." - ".$estaMarca['descripcion']." - "."(Modelo: ".$esteModelo['codigo'].") - (N° Serie: ".htmlspecialchars_decode($estaMaquina['num_serie'],ENT_NOQUOTES).")";?>";
        }
      }
      opt.appendChild( document.createTextNode(clienteNombreMost) );
      opt.value = parseInt("<?php echo $unaMaquina['id'];?> "); 
      seleccionado.appendChild(opt);      
    }    
    if ("<?php echo $unaMaquina['id']; ?>" == last_id_maquina) {   
      opt.selected = "true";      
    }
    <?php
    endforeach;
    ?>
    $('#selectMaquina').selectpicker('refresh');    
  });
</script>
