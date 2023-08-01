<?php
  require_once('includes/load.php');
  $allClientes = $db->while_loop($db->query("SELECT `id`, `cuit`, `razon_social` FROM `clientemaquina` WHERE `estado` = 1 ORDER BY `razon_social`"));
  //$lastId = $db->insert_id();
 ?>
<div class="row">
  <div class="col-md-10">
    <label for="select_clientes" class="control-label">Clientes:</label>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <select class="selectpicker" data-show-subtext="true" data-live-search="true" name="select_clientes" id="select_clientes" required="required" data-width="95%" onchange="javascript:clienteSel()">
    </select>
    <button href="#" type="button" class="btn btn-success" id="btn_show-add-cliente" onclick="javascript:addCliente();" data-toggle="tooltip" title="Agregar un cliente"><span class="glyphicon glyphicon-plus"></span></button>
  </div>      
</div>
<script type="text/javascript">
  var last_id_cliente;
  $(document).ready(function() {  
    $('#select_clientes').selectpicker();    
    var select_cliente = document.getElementById("select_clientes");
    if(last_id_cliente) {
      $('#select_clientes').find('option').remove().end().append('<option value="" disabled>Seleccione un cliente</option>');
    } else {
      $('#select_clientes').find('option').remove().end().append('<option value="" disabled selected>Seleccione un cliente</option>');
    }
    <?php 
    foreach ($allClientes as $unCliente): 
    ?>                
    var opt = document.createElement('option');
    var clienteNombre = "<?php echo $unCliente['razon_social']." - DNI/CUIT: ".$unCliente['cuit']; ?>";
    opt.appendChild( document.createTextNode(clienteNombre) );
    opt.value = parseInt("<?php echo $unCliente['id'];?> "); 
    select_cliente.appendChild(opt);
    if ("<?php echo $unCliente['id']; ?>" == last_id_cliente) {   
      opt.selected = "true";      
    }
    <?php
    endforeach;
    ?>
    $('#select_clientes').selectpicker('refresh');    
  });
</script>
