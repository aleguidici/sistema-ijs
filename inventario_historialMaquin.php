<?php
  $page_title = 'Historial';
  require_once('includes/load.php');
  page_require_level(2);
  $current_user_ok = current_user();
  $historial_maquin = find_historial_maquin_by_id('inv_maq_historial', $_GET['id']);
?>

<?php include_once('layouts/header.php'); ?>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> 
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.15.3/dist/bootstrap-table.min.css">
    <link rel="stylesheet" type="text/css" href="libs/alertifyjs/css/alertify.css">
    <link rel="stylesheet" type="text/css" href="libs/alertifyjs/css/themes/default.css">

    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>
    <script src="libs/alertifyjs/alertify.js"></script> 

  <!-- datatable -->
  <script type="text/javascript">
      $(document).ready(function() {
          $('#historial').DataTable();
      } );
  </script>
  

  <h2><b>Historial de Maquinaria o Instrumento </b></h2>
  <em><h4>Cod. IJS: <?php echo $_GET['id'];?></h4></em>
  
  <div class="pull-right">
    <a class="btn btn-primary" href="inventario.php" role=button>Volver a Inventario</a>
    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalActualizarEstado">
      <span class="glyphicon glyphicon-plus-sign"></span> Actualizar estado
    </button>
  </div>
  <br><br>

  <div class="modal fade bd-example-modal-lg" id="modalActualizarEstado" tabindex="-1" role="dialog" aria-labelledby="modalActuEst" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
            <h4 class="modal-title" id="modalActuEst">Actualizar estado</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-4">
              <label>Estado actual:</label>
              <select required class="form-control" name="estado_act" id="estado_act">
                <option value="" disabled selected>Seleccione un estado</option>
                <option value="1"> Muy malo </option>
                <option value="2"> Malo </option>
                <option value="3"> Regular </option>
                <option value="4"> Bueno </option>
                <option value="5"> Muy bueno </option>
              </select>
            </div>
            <div class="col-md-8">
              <label>Detalles:</label>
              <input type="name" id="detalle_act" class="form-control" placeholder="Detalles" required maxlength="350">
            </div>
          </div>
          <br>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary" data-dismiss="modal" id="guardarestado">Aceptar</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function(){
      $('#guardarestado').click(function(){
        estado_nuevo=null;
        if (!($('#estado_act').val() == '0')) {
          switch ($('#estado_act').val()){
            case "1":
              estado_nuevo="Muy malo";
              break;
            case "2":
              estado_nuevo="Malo";
              break;
            case "3":
              estado_nuevo="Regular";
              break;
            case "4":
              estado_nuevo="Bueno";
              break;
            case "5":
              estado_nuevo="Muy bueno";
              break;
          }
        }

        detalle_nuevo = $('#detalle_act').val();
        detalle_nuevo = detalle_nuevo.charAt(0).toUpperCase() + detalle_nuevo.slice(1);

        id_maquin = <?php echo $_GET['id'];?>;

        if (estado_nuevo && detalle_nuevo) {
          cadena = "estado_nuevo=" + estado_nuevo + "&detalle_nuevo=" + detalle_nuevo + "&id_maquin=" + id_maquin;

          $.ajax({
            type:"POST",
            url:"inventario_agregarHistorialMaquin.php",
            data:cadena,
            success:function(r){
              if(r==1){
                location.reload();
              }else{
                alertify.error("Error.");
              }
            }
          });
        } else {
          window.alert("Cambios no guardados. Campos obligatorios no completados.");
        }
      });
    });

    $('#modalActualizarEstado').on('hidden.bs.modal', function (e) {
      $(this)
        .find("input,textarea,select, name, text")
           .val('')
           .end()
        .find("input[type=checkbox], input[type=radio]")
           .prop("checked", "")
           .end();
    })
  </script>

  <div class="table-responsive">
    <table id="historial" class="table table-striped table-bordered">
      <thead>
        <tr>
          <th style="width: 25px;">Fecha de estado</th>
          <th style="width: 25px;">Estado</th> 
          <th style="width: 345px;">Detalles</th>
          <th style="width: 9px; visibility:hidden; display:none;"></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($historial_maquin as $un_detalle):?>
          <tr>
            <td class="text-center"><?php 
                list($año, $mes, $dia) = explode('-', $un_detalle['fecha']);
                echo remove_junk($dia."/".$mes."/".$año);?></td>
            <td><?php 
              switch ($un_detalle['estado']){
                  case "Muy malo":
                    echo '<b><span style="color:#6C0000;">5. '.$un_detalle['estado'].'</span></b>';
                    break;
                  case "Malo":
                    echo '<b><span style="color:#EC1919;">4. '.$un_detalle['estado'].'</span></b>';
                    break;
                  case "Regular":
                    echo '<b><span style="color:#EC8F19;">3. '.$un_detalle['estado'].'</span></b>';
                    break;
                  case "Bueno":
                    echo '<b><span style="color:#66AD00;">2. '.$un_detalle['estado'].'</span></b>';
                    break;
                  case "Muy bueno":
                    echo '<b><span style="color:#088300;">1. '.$un_detalle['estado'].'</span></b>';
                    break;
              };?>
            </td>
            <td ><?php echo $un_detalle['detalle'];?></td>
            <td style="visibility:hidden; display:none;"><?php echo $un_detalle['id'];?></td>
          </tr>
        <?php endforeach; 
        ?>
      </tbody>
      <tfoot>
        <tr>
          <th style="width: 75px;">Fecha de estado</th>
          <th style="width: 15px;">Estado</th> 
          <th style="width: 145px;">Detalles</th>
          <th style="width: 9px; visibility:hidden; display:none;"></th>
        </tr>
      </tfoot>
    </table>

    <script>
      $('#historial').DataTable({ "order": [[ 3, "desc" ]] });
      $('.dataTables_length').addClass('bs-select');
    </script>
  </div>

                         

<?php include_once('layouts/footer.php'); ?>