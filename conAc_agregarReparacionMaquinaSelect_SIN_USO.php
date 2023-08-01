<?php
  $page_title = 'Nueva Reparación';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
  $allClientesMaquina = find_all('clientemaquina');
 
  include_once('layouts/header.php'); ?>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round|Open+Sans">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="http://weareoutman.github.io/clockpicker/dist/bootstrap-clockpicker.min.css">
    <script src="http://weareoutman.github.io/clockpicker/dist/bootstrap-clockpicker.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
      
    <div class="col-md-12">
      <?php echo display_msg($msg); ?>
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Nuevo registro - Reparación de máquina eléctrica</span>
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
              <select class="selectpicker" data-show-subtext="true" data-live-search="true" name="ban" id="ban" required="required" data-width="100%" onchange="javascript:clienteSel()">
                <option value="" disabled selected>Seleccione un cliente</option>
                <?php  foreach ($allClientesMaquina as $cliMaq): ?>
                  <option value="<?php echo (int) $cliMaq['id']?>">
                    <?php 
                      $provincia = find_by_id_prov('provincia',$cliMaq['provincia']);
                      echo utf8_encode($cliMaq['razon_social'])." (".$cliMaq['localidad'].", ".$provincia['nombre']. " - DNI/CUIT: ".$cliMaq['cuit'];
                      if ($cliMaq['tel'] != null) {
                        echo " - Tel: ".$cliMaq['tel'];
                      }
                      if ($cliMaq ['cel'] != null) {
                        echo " - Cel: ".$cliMaq['cel'];
                      }
                      echo ")";
                    ?>  
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <br><br>

  <!-- "seleccionar un cliente" -->
        
          <div class="row">
            <div class="col-md-10">
              <label for="ban" class="control-label">Máquina:</label>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <select class="selectpicker" data-show-subtext="true" data-live-search="true" name="selectMaquina" id="selectMaquina" required="required" data-width="100%"  disabled onchange="javascript:maquinaSel()">
                <option value="" disabled selected>Seleccione una máquina</option>
              </select>
            </div>
          </div>
          <br><br>

          <div class="form-group text-right">
            <a class="btn btn-primary" href="conAc.php" role="button">Volver</a>
            <button type="button" id="btnContinuar" class="btn btn-success" disabled>Continuar</button>
          </div>

          <script>
            function maquinaSel() {
              document.getElementById("btnContinuar").disabled = false;
            }

            document.getElementById("btnContinuar").onclick = function () {
              //location.href = "conAc_agregarReparacionMaquina.php?idMaquina="+$('#selectMaquina').val();
              var idMaquinaVal = document.getElementById("selectMaquina").value;
              var bande = 0;
              <?php
                 $allMaquinasSin = find_all_maquinas_sin_reparacion_activa();
                 foreach ($allMaquinasSin as $unaMaquinaSin):
                 $estaMaquinaSin = find_by_id('maquina',$unaMaquinaSin['id']);
              ?>
                if ("<?php echo $estaMaquinaSin['id'];?>" == idMaquinaVal) {
                  if ("<?php echo $estaMaquinaSin['sin_reparacion'];?>" == 1) {
                    var bande = 1;
                    var razon = "<?php echo $estaMaquinaSin['razon_noreparacion'];?>"
                  }
                }
              <?php endforeach ?>
              if ( bande == 1) {
                window.alert("Esta maquina no tiene mas reparación"+"\nRazón:\n"+razon);
              } else {
                location.href = "conAc_agregarReparacionMaquina.php?idMaquina="+$('#selectMaquina').val();
              }              
            };


            function clienteSel(){               
              document.getElementById("selectMaquina").disabled = false;
              $('#selectMaquina').selectpicker('refresh');
              var clienteSeleccionado = document.getElementById("ban").value;
              var clienteNombre = $("#ban option:selected").text();
              var seleccionado = document.getElementById("selectMaquina");
              //$('#buttom-crear-mod').prop("disabled",false);
              //$('#buttom-crear-mod').attr("disabled",false);
              //$('#buttom-crear-mod').data("id", marca);
              //$('#buttom-crear-mod').data("marca", marca_nombre);
              $('#selectMaquina').find('option').remove().end().append('<option value="" disabled selected>Seleccione una máquina</option>');
              <?php 
                $allMaquinas = find_all_maquinas_sin_reparacion_activa();
                foreach ($allMaquinas as $unaMaquina):
                  $estaMaquina = find_by_id('maquina',$unaMaquina['id']);
                  $esteModelo = find_by_id('maquina_modelo',$unaMaquina['modelo_id']);
                  $estaMarca = find_by_id('maquina_marca',$esteModelo['marca_id']);
                  $esteTipo = find_by_id('maquina_tipo',$esteModelo['tipo_id']);
                ?>
                if ("<?php echo $unaMaquina['id_cliente'];?>" == clienteSeleccionado) {                  
                  var opt = document.createElement('option');
                  if ("<?php echo $estaMaquina['sin_reparacion'];?>" == 1) {
                    var clienteNombreMost = "<?php echo "IJS-ME: ".$estaMaquina['id']." - ".$esteTipo['descripcion']." - ".$estaMarca['descripcion']." - "."(Modelo: ".$esteModelo['codigo'].") - (N° Serie: ".htmlspecialchars_decode($estaMaquina['num_serie'],ENT_NOQUOTES).")"." -- NO TIENE REPARACIÓN -- ";?>";
                  } else {
                    var clienteNombreMost = "<?php echo "IJS-ME: ".$estaMaquina['id']." - ".$esteTipo['descripcion']." - ".$estaMarca['descripcion']." - "."(Modelo: ".$esteModelo['codigo'].") - (N° Serie: ".htmlspecialchars_decode($estaMaquina['num_serie'],ENT_NOQUOTES).")";?>";
                  }
                  opt.appendChild( document.createTextNode(clienteNombreMost) );
                  opt.value = parseInt("<?php echo $unaMaquina['id'];?> "); 
                  seleccionado.appendChild(opt); 
                }
              <?php endforeach; ?> 
              $('#selectMaquina').selectpicker('refresh');
            }         
          </script>

        </div>      
      </div>
    </div>

<?php include_once('layouts/footer.php'); ?>