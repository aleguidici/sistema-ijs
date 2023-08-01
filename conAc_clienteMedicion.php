<?php
  $page_title = 'Medición';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
  $all_instrumentos = find_all('inv_instrumentos');
  $all_clientes = find_all('cliente');
  $all_personal = find_all('personal');
  $all_matriculados = find_all_matriculados('personal_matriculas');
  $mydata = Array();
  $tabla = "";
  $query_parts = Array();

  include_once('layouts/header.php'); ?>

<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round|Open+Sans">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="http://weareoutman.github.io/clockpicker/dist/bootstrap-clockpicker.min.css">
<script src="http://weareoutman.github.io/clockpicker/dist/bootstrap-clockpicker.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
      
<div class="col-md-7">
  <?php echo display_msg($msg); ?>
  <div class="panel panel-default">
    <div class="panel-heading">
      <strong>
        <span class="glyphicon glyphicon-th"></span>
        <span>Nueva medición</span>
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
          <select class="selectpicker" data-show-subtext="true" data-live-search="true" name="ban" id="ban" required="required" data-width="100%" onchange="javascript:continua()">
            <option value="" disabled selected>Seleccione un cliente</option>
            <?php  foreach ($all_clientes as $banc): ?>
              <option value="<?php echo (int) $banc['id']?>">
              <?php if(!empty($banc['num_suc']))
                echo 'Sucursal Nº ',$banc['num_suc'] , ' - ';
              echo $banc['razon_social'], ' - ',  $banc['direccion'] , ' - ',  $banc['localidad'], ' - ', find_by_id_prov('provincia', $banc['provincia'])['nombre']?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <br><br>
      <div class="form-group text-right">
        <a class="btn btn-primary" href="conAc_tablaMediciones.php" role="button">Volver a Mediciones</a>
        <button type="button" id="btnCli" class="btn btn-success" disabled>Continuar</button>
        <div id='response'> </div>
      </div>

      <script>
        function continua() {
          document.getElementById("btnCli").disabled = false;
        }

        document.getElementById("btnCli").onclick = function () {
          location.href = "conAc_datosMedicion.php?idCli="+$('#ban').val();
        };
      </script>
    </div>      
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>