<?php
  $page_title = 'Matriculas';
  require_once('includes/load.php');
  page_require_level(2);
  $personal = find_by_id('personal', $_GET['id']);
  $matriculas = find_matriculas_by_personal($_GET['id']);
  $provincias = find_prov_by_pais('1');
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

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>


  <!-- datatable -->
  <script type="text/javascript">
      $(document).ready(function() {
          $('#matriculas').DataTable();
      } );
  </script>

  <h2><b>Matriculas de <?php echo $personal['apellido'].', '.$personal['nombre'];?></b></h2>

  <div class="row">
    <div class="col-md-12">
      <?php echo display_msg($msg); ?>
    </div>
  </div>

  <div class="col-md-7">
    <div class="panel-heading clearfix">
      <div class="pull-right">
        <button type="button" class="btn btn-success open-addMatricula" data-toggle="modal" data-id="<?php echo $personal['id'];?>" href="#addMatricula"> Agregar matrícula </button>
      </div>
    </div>

    <div class="modal fade bd-example-modal-lg" id="addMatricula" tabindex="-1" role="dialog" aria-labelledby="modalMattt" data-backdrop="static" data-keyboard="false" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title" id="modalMattt">Agregar Matrícula</h4>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-4">
                <label>N° de matrícula:</label>
                <input style="border:1px solid #000000" type="name" id="num_mat" class="form-control" placeholder='Ej.: "M.P. 12345"' required maxlength="50" onkeypress="return blockSpecialChar(event)">
              </div>
              <div class="col-md-8">
                <label>Provincia: </label>
                <select class="selectpicker" data-show-subtext="true" data-live-search="true" name="provinc" id="provinc" required="required" data-width="100%">
                <option value="" selected>Seleccione una Provincia</option>
                <?php foreach ($provincias as $una_prov): 
                  $ban = 0;
                  foreach ($matriculas as $una_matri):
                    if ($una_prov['id_provincia'] == $una_matri['id_provincia']){
                      $ban = 1;
                      $temp = $una_matri['num_matricula'];
                    }
                  endforeach;

                  if ($ban == 0) { ?>
                    <option value="<?php echo (int) $una_prov['id_provincia']?>">
                    <?php echo $una_prov['nombre'];?></option>
                  <?php } else { ?>
                    <option value="<?php echo (int) $una_prov['id_provincia']?>" disabled>
                    <?php echo $una_prov['nombre']." - Mat.: ".$temp;?></option>
                  <?php }
                endforeach; ?>
              </select>
              </div>
            </div>
            <br>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-primary" id="aceptar_mat">Aceptar</button>
          </div>
        </div>
      </div>
    </div>

    <script>
      $('#addMatricula').on('hidden.bs.modal', function () {
        location.reload();
      });

      $(document).ready(function(){
        $('#aceptar_mat').click(function(){
          perso = <?php echo $_GET['id'];?>;
          num = $('#num_mat').val();
          num = num.charAt(0).toUpperCase() + num.slice(1);
          prooo = $('#provinc').val();
          
          if (num && prooo) {
            cadena_p = "num=" + num + "&prooo=" + prooo + "&perso=" + perso;

            $.ajax({
              type:"POST",
              url:"entidades_matPersonal.php",
              data:cadena_p,
              success:function(r){
                if(r==1){
                  location.reload();
                } else {
                  alertify.error("ERROR.");
                }
              }
            });
          } else {
            alertify.error("Complete todos los campos.");
          }
        });
      });

      $('#addMatricula').on('hidden.bs.modal', function (e) {
        $(this)
          .find("input,textarea,select, name, text")
             .val('')
             .end()
          .find("input[type=checkbox], input[type=radio]")
             .prop("checked", "")
             .end();
      });


      function blockSpecialChar(e) {
        var k = e.keyCode;
        return (!(k == 34 || k == 39));
      };
    </script>

  
    <div class="table-responsive">
      <table id="matriculas" class="table table-striped table-bordered" style="width:100%">
        <thead>
          <tr>
            <th style="width: 50%;">Matrícula</th> 
            <th style="width: 50%;">Provincia</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($matriculas as $una_mat):
            $una_provi = find_by_id_prov('provincia', $una_mat['id_provincia']);?>
            <tr>
              <td class="text-center"><?php echo $una_mat['num_matricula'];?></td>
              <td class="text-center"><?php echo $una_provi['nombre'];?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
        <tfoot>
          <tr>
            <th style="width: 50%;">Matrícula</th> 
            <th style="width: 50%;">Provincia</th>
          </tr>
        </tfoot>
      </table>

      <script>
        $('#matriculas').DataTable({ "order": [[ 0, "desc" ]] });
        $('.dataTables_length').addClass('bs-select');
      </script>
    </div>

    <br><br>
    <div class="form-group text-right">   
      <a class="btn btn-primary" href="entidades_personal.php" role=button>Volver a Personal</a>
    </div>
  </div>

                         

<?php include_once('layouts/footer.php'); ?>