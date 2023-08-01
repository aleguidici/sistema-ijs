<?php
  $page_title = 'Nueva Máquina';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
  //$all_clientesMaq = find_all('clientemaquina');
  $all_clientesMaq = $db->while_loop($db->query("SELECT `id`, `cuit`, `razon_social` FROM `clientemaquina` WHERE `estado` = 1 ORDER BY `razon_social`"));
  $allMaquinas = $db->while_loop($db->query("SELECT * FROM `maquina`"));
  //$allMaquinasEnReparacion = $db->while_loop($db->query("SELECT `id_maquina` FROM `reparacion_maquina` WHERE `id_estado` <> 8"));
  include_once('layouts/newHeader.php'); ?>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round|Open+Sans">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<!--  <link rel="stylesheet" href="http://weareoutman.github.io/clockpicker/dist/bootstrap-clockpicker.min.css">
  <script src="http://weareoutman.github.io/clockpicker/dist/bootstrap-clockpicker.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>-->
<script type="text/javascript">
  function clienteSel() {
    $('#contenedor_datos-cliente-maquina').load('conAc_agregarMaquina.php?idCli='+$("#ban").val());
    $('#contenedor_select-maquina').load('conAc_loadMaquinas.php?idCliente='+$('#ban').val());
    $('#inp_last-id').val('');
  }

  function maquinaSel() {
    $('#datos_crear-reparacion').load('conAc_agregarReparacionMaquina.php?idMaquina='+$("#selectMaquina").val());
    $('#datos_crear-reparacion').prop('hidden', false);
    $('#div_datos-maquina').prop('hidden', true);
  }

  function showAddMaquina() {
    $('#div_datos-maquina').prop('hidden', false);
    $('#datos_crear-reparacion').prop('hidden', true);
  }

  function addCliente() {
    $('#contenedor_crear-nuevo-cliente').load('conAc_agregarClienteMaquina.php');
  }
</script>
  

  <div class="row">  
    <div class="col-md-12">       
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Nuevo registro:</span>
          </strong>
        </div>

  <!-- "seleccionar un cliente" -->
        <div class="panel-body">
          <div class="row">
            <div class="col-md-10">
              <label for="ban" class="control-label">Cliente:</label>
            </div>
          </div>
          <div class="row">             
            <div class="col-md-12">
              <select class="selectpicker" data-show-subtext="true" data-live-search="true" name="ban" id="ban" required="required" data-width="95%" onchange="javascript:clienteSel()">
                <option value="" disabled selected>Seleccione un cliente</option>
                <?php  foreach ($all_clientesMaq as $cliMaq): ?>
                  <option value="<?php echo (int) $cliMaq['id']?>">
                    <?php 
                      //$provincia = find_by_id_prov('provincia',$cliMaq['provincia']);
                      echo $cliMaq['razon_social']." - DNI/CUIT: ".$cliMaq['cuit'];
                    ?>  
                  </option>
                <?php endforeach; ?>
              </select>
              <button type="button" class="btn btn-success" id="btn_add-cliente" onclick="javascript:addCliente();"><span class="glyphicon glyphicon-plus"></span></button>
            </div>            
          </div>
          <br>
          <div id="contenedor_select-maquina">
            <div class="row">
              <div class="col-md-10">
                <label for="selectMaquina" class="control-label" disabled>Máquina:</label>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <select class="selectpicker" data-show-subtext="true" data-live-search="true" name="selectMaquina-static" id="selectMaquina-static" required="required" data-width="95%" disabled>
                  <option value="" disabled selected>Seleccione una máquina</option>
                </select>
                <button type="button" class="btn btn-success" id="btn_show-add-maquina" disabled><span class="glyphicon glyphicon-plus"></span></button>
              </div>      
            </div>
          </div>
          <!--<div class="form-group text-right">
            <a class="btn btn-primary" href="conAc.php" role="button">Volver</a>
            <button type="button" id="btnMaq" class="btn btn-success" disabled>Continuar</button>
          </div>-->

 
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