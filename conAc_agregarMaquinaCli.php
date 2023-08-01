<?php
  $page_title = 'Nueva Máquina';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
  $all_clientesMaq = $db->while_loop($db->query("SELECT `id`, `cuit`, `razon_social` FROM `clientemaquina` WHERE `estado` = 1 ORDER BY `razon_social`"));
  $allMaquinas = $db->while_loop($db->query("SELECT * FROM `maquina`"));
  include_once('layouts/newHeader.php'); ?>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round|Open+Sans">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <style type="text/css">
    div b {
      font-size: 14px;
    }
  </style>
<script type="text/javascript">
  var last_id_maquina;
  $(document).ready(function() {  
    $('#contenedor_select-clientes').load('conAc_loadClientesMaquinas.php');
  });

  function clienteSel() {
    $('#contenedor_datos-cliente-maquina').prop('hidden', false);
    $('#contenedor_datos-cliente-maquina').load('conAc_agregarMaquina.php?idCli='+$("#select_clientes").val());
    $('#contenedor_select-maquina').load('conAc_loadMaquinas.php?idCliente='+$('#select_clientes').val());
    $('#inp_last-id').val('');
    $('#contenedor_crear-nuevo-cliente').prop('hidden', true);
  }

  function maquinaSel() {
    $('#contenedor_crear-reparacion').prop('hidden', false);
    $('#datos_crear-reparacion').load('conAc_agregarReparacionMaquina.php?id_maquina='+$("#selectMaquina").val());
    $('#datos_crear-reparacion').prop('hidden', false);
    $('#div_datos-maquina').prop('hidden', true);
  }

  function showAddMaquina() {
    $('#div_datos-maquina').prop('hidden', false);
    $('#datos_crear-reparacion').prop('hidden', true);
    $('#selectMaquina').val('');
    $('#selectMaquina').selectpicker('refresh');
  }

  function addCliente() {
    $('#contenedor_crear-nuevo-cliente').prop('hidden', false);
    $('#contenedor_crear-nuevo-cliente').load('conAc_agregarClienteMaquina.php');
    $('#contenedor_datos-cliente-maquina').prop('hidden', true);
    $('#contenedor_crear-reparacion').prop('hidden', true);
    $('#select_clientes').val('');
    $('#selectMaquina').val('');
    $('#selectMaquina').prop('disabled', true);
    $('#select_clientes').selectpicker('refresh');
    $('#selectMaquina').selectpicker('refresh'); 
    $('#btn_show-add-maquina').prop('disabled', true);
    $('#btn_show-add-maquina').attr('disabled', true);
  }

  function rePage() {
    location.reload();
  }
</script>
  

  <div class="row">  
    <div class="col-md-12">       
      <div class="panel panel-default">
        <div class="panel-heading">
          <div class="row">
            <div class="col-md-10">
              <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Nuevo registro:</span>
              </strong>
            </div>
            <div class="col-md-2 text-right"><a type="button" class="btn btn-warning btn-xs" onclick="javascript:rePage();" data-toggle="tooltip" title="Recargar página"><span class="glyphicon glyphicon-refresh" style="color: white;"></span></a></div>
          </div>
        </div>

  <!-- "seleccionar un cliente" -->
        <div class="panel-body">
          <div id="contenedor_select-clientes">
            <div class="row">
              <div class="col-md-10">
                <label for="select_clientes-static" class="control-label">Clientes:</label>
              </div>
            </div>
            <div class="row">             
              <div class="col-md-12">
                <select class="selectpicker" data-show-subtext="true" data-live-search="true" name="select_clientes-static" id="select_clientes-static" required="required" data-width="95%">
                  <option value="" disabled selected>Seleccione un cliente</option>
                </select>
                <button type="button" class="btn btn-success" id="btn_add-cliente" onclick="javascript:addCliente();"><span class="glyphicon glyphicon-plus"></span></button>
              </div>            
            </div>
          </div>
          <br>
          <div id="contenedor_select-maquina">
            <div class="row">
              <div class="col-md-10">
                <label for="selectMaquina-static" class="control-label" disabled>Máquina:</label>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <select class="selectpicker" data-show-subtext="true" data-live-search="true" name="selectMaquina-static" id="selectMaquina-static" required="required" data-width="95%" disabled>
                  <option value="" disabled selected>Seleccione una máquina</option>
                </select>
                <button type="button" class="btn btn-success" id="btn_show-add-maquina-static" disabled><span class="glyphicon glyphicon-plus"></span></button>
              </div>      
            </div>
          </div>
          <br>
          <div class="form-group text-left">
            <a class="btn btn-danger" href="conAc.php" role="button">Volver</a>
          </div> 
        </div>      
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div id="div_msg">
        <div id="div_msg-show">
        <?php echo display_msg($msg); ?>
        </div>
      </div>
    </div>    
  </div>

  <div id="contenedor_datos-cliente-maquina"></div>
  <div id="contenedor_crear-reparacion"></div>
  <div id="contenedor_crear-nuevo-cliente"></div>

<?php include_once('layouts/NewFooter.php'); ?>