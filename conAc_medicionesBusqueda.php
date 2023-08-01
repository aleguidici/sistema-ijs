<?php
  $page_title = 'Buscar mediciones';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(3);
  $all_mediciones = find_all('datos_medicion');
  $all_provincias = find_all('provincia');
?>

<?php include_once('layouts/header.php'); ?>

  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>

  <div class="row">
    <div class="col-md-12">
      <?php echo display_msg($msg); ?>
    </div>
  </div>
  <div class="row">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <b>Mediciones - </b> Búsqueda avanzada
        </strong>
      </div>
      <div class="panel-body">
        <!-- <form method="post" action="mediciones_busqueda.php"> -->
          <div class="form-group">
            
            <div class="row">
              <div class="col-md-11">
                <label for="direccion" class="control-label">Filtrar por dirección  </label>
                <input type="checkbox" id="direccion" onClick="check_direccion()">  <br>
              </div>
            </div>
            <div class="row">
              <div class="col-md-11">
                <input type="text" class="form-control" id="direc" disabled>
              </div>
            </div>
            
            <script>              
              function check_direccion()
              {
                var isChecked = document.getElementById("direccion").checked;
                document.getElementById("direc").disabled = !isChecked;
                document.getElementById("direc").value = "";
              }
            </script>

            <hr>

            <div class="row">
              <div class="col-md-5">
                <label for="Provincia" class="control-label">Filtrar por provincia / estado </label>
                <input type="checkbox" id="Provincia" onClick="check_provincia()">  <br>
              </div>
              <div class="col-md-1"></div>
              <div class="col-md-5">
                <label for="Localidad" class="control-label">Filtrar por localidad  </label>
                <input type="checkbox" id="Localidad" onClick="check_localidad()">  <br>
              </div>
            </div>
            <div class="row">
              <div class="col-md-5">
                <select required="required" class="form-control" id="provin" disabled="true">
                  <option value="" disabled selected>Seleccione una provincia</option>
                  <?php  foreach ($all_provincias as $prov): ?>
                    <option value="<?php echo (int) $prov['id_provincia']?>">
                  <?php echo remove_junk(utf8_encode($prov['nombre']));?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-1"></div>
              <div class="col-md-5">
                <input type="text" class="form-control" id="Localid" disabled>
              </div>
            </div>
            
            <script>
              function check_provincia()
              {
                var isChecked = document.getElementById("Provincia").checked;
                document.getElementById("provin").disabled = !isChecked;
                if (!isChecked)
                  document.getElementById("provin").selectedIndex = 0;
                else 
                  document.getElementById("provin").selectedIndex = 1;
              }

              function check_localidad()
              {
                var isChecked = document.getElementById("Localidad").checked;
                document.getElementById("Localid").disabled = !isChecked;
                document.getElementById("Localid").value = "";
              }
              
            </script>

            <hr>

            <div class="row">
              <div class="col-md-5">
                <label for="Cumple" class="control-label">Filtrar por cumplimiento de la medición  </label>
                <input type="checkbox" id="Cumple" onClick="check_cumple()">  <br>
              </div>
              <div class="col-md-1"></div>
              <div class="col-md-5">
                <label for="Rango" class="control-label">Filtrar por rango de fechas  </label>
                <input type="checkbox" id="Rango" onClick="check_rango()">  <br>
              </div>
            </div>
            <div class="row">
              <div class="col-md-5">
                <select class="form-control" id="cumpl" disabled="true">
                  <option value="">Seleccione una opción</option>
                  <option value="1">Cumple</option>
                  <option value="2">No cumple</option>
                </select>
              </div>
              <div class="col-md-1"></div>
              <div class="col-md-2">
                <p> Desde: <br><input autocomplete="off" type="text" class="datepicker form-control" id="desde" disabled></p>
              </div>
              <div class="col-md-2">
                <p> Hasta: <br><input autocomplete="off" type="text" class="datepicker form-control" id="hasta" disabled></p>
              </div>
            </div>
            <script>
              function check_rango()
              {
                var isChecked = document.getElementById("Rango").checked;
                $("#desde").prop('disabled', !isChecked);
                $("#hasta").prop('disabled', true);
                $('#desde').val("").datepicker("update");
                $('#hasta').val("").datepicker("update");
              }

              $('#desde').datepicker( {
                endDate: '0d',
                format: 'dd-mm-yyyy',
                autoclose: true
              }).on('changeDate', dateChanged);

              function dateChanged(selected) {
                  $("#hasta").attr('disabled', false);
                  $('#hasta').val("").datepicker("update");
                  var minDate = new Date(selected.date.valueOf());
                  $('#hasta').datepicker('setStartDate', minDate);
              }

              $('#hasta').datepicker( {
                format: 'dd-mm-yyyy',
                autoclose: true,
                endDate: '0d'
              });
            </script>

            <hr>

            <div class="row">
              <div class="col-md-9"></div>
              <div class="col-md-2">
                <button onClick="boton()" name="realizar_busqueda" class="btn btn-primary pull-right">Buscar</button>
              </div>
            </div>
            
            <a href="conAc.php" class="btn btn-success">Volver a mediciones</a>
            
            <script>
              function check_cumple()
              {
                var isChecked = document.getElementById("Cumple").checked;
                document.getElementById("cumpl").disabled = !isChecked;
                if (!isChecked)
                  document.getElementById("cumpl").selectedIndex = 0;
                else 
                  document.getElementById("cumpl").selectedIndex = 1;
              }
            </script>

          </div>
        <!-- </form> -->
      </div>
    </div>
  </div>

  <script>
    function boton() {
      // var MyTable = document.getElementById("tablita");
      // while(MyTable.hasChildNodes())
      // {
      //    MyTable.removeChild(MyTable.firstChild);
      // }

      var sql = "SELECT * FROM datos_medicion WHERE NOT id_medicion = '0'";

      if (document.getElementById("direccion").checked) {
        direccion_medicion = document.getElementById("direc").value;
        sql = sql + " AND num_suc IN (SELECT id FROM cliente WHERE direccion LIKE '%" + direccion_medicion + "%')";
      }

      if (document.getElementById("Provincia").checked) {
        provincia_medicion = document.getElementById("provin").selectedIndex;
        sql = sql + " AND num_suc IN (SELECT id FROM cliente WHERE provincia = '" + provincia_medicion + "')";
      }

      if (document.getElementById("Localidad").checked) {
        localidad_medicion = document.getElementById("Localid").value;
        sql = sql + " AND num_suc IN (SELECT id FROM cliente WHERE localidad LIKE '%" + localidad_medicion + "%')";
      }

      if (document.getElementById("Rango").checked) {
        desde_medicion = document.getElementById("desde").value.split("-");
        hasta_medicion = document.getElementById("hasta").value.split("-");

        var desde_ok = desde_medicion[2] + "-" + desde_medicion[1] + "-" + desde_medicion[0];
        var hasta_ok = hasta_medicion[2] + "-" + hasta_medicion[1] + "-" + hasta_medicion[0];

        sql = sql + " AND fecha_medicion >= '" + desde_ok + "' AND fecha_medicion <= '" + hasta_ok +"'";
      }

      if (document.getElementById("Cumple").checked) {
        cumple_medicion = document.getElementById("cumpl").selectedIndex;
        if (cumple_medicion == 1)
          sql = sql + " AND id_medicion IN (SELECT id_medicion FROM detalles_medicion WHERE cumple = 'Si')";
        else
          sql = sql + " AND id_medicion IN (SELECT id_medicion FROM detalles_medicion WHERE cumple = 'No')";
      }

      window.open('conAc_medicionesResultado.php?consulta='+sql);
      // var NewRow = MyTable.insertRow(-1); 
      // var Newcell1 = NewRow.insertCell(0); 
      // var Newcell2 = NewRow.insertCell(1); 
      // Newcell1.innerHTML = sql; 
      // Newcell2.innerHTML = "Sudo Placement";
    }
  </script>

<?php include_once('layouts/footer.php'); ?>
