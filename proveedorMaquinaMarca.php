<?php
  $page_title = 'Marcas admintidas del proveedor';
  require_once('includes/load.php');
  page_require_level(2);
  //$proveedorSel = find_by_id_proveedor_maquina_marca_prov('maquina_marca_prov',$_GET['id']);
  $proveedor = find_by_id('proveedormaquina',$_GET['id']);  
  $proveedorSel = find_by_id_proveedor_maquina_marca_prov('maquina_marca_prov',$proveedor['id']);
  //$marcaSel = find_by_id_maquina_marca_prov('maquina_marca_prov',$proveedor{'id'});
  //$marca = find_by_id('maquina_marca',$marcaSel['id_maquina_marca']);
//Traer marcas no seleccionadas todavia
  $proveedorUsar = $_GET['id'];
  $proveedorLibre = find_by_sql("SELECT * FROM maquina_marca WHERE id NOT IN (SELECT id_maquina_marca FROM maquina_marca_prov WHERE id_proveedor = '{$proveedorUsar}')");
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
          $('#addMatricula').on('hidden.bs.modal', function(){
            location.reload();
          });
            //Agregar marca en la BD
          $('#aceptarmarca').click(function(){
            prove = <?php echo $_GET['id'];?>;
            marc = $('#marcaselect').val();
            b = 1;         
            if (prove && marc) {
              cargar_marca = "&prove=" + prove + "&marc=" + marc + "&b=" + b;

              $.ajax({
                type:"POST",
                url:"proveedorAbmMarca.php",
                data:cargar_marca,
                success:function(r){                    
                  if(r == 1){
                    alertify.success("Marca agregada correctamente.");
                    setInterval('location.reload()', 1000);
                  } else {
                    alertify.error("Error.");
                  }
                }
              });
            } else {
                alertify.error("Por favor, complete todos los campos.");
            }     
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
      });


      function blockSpecialChar(e) {
        var k = e.keyCode;
        return (!(k == 34 || k == 39));
      };

          //Borrar marca de la BD
      function eliminarMarcaFunc(marcaid){            
        prove = <?php echo $_GET['id'];?>;
        var marc = marcaid;
        b = 2;
        var eliminar = confirm("¿Está seguro que desea eliminar la marca para éste proveedor?");
        if(eliminar)         
          if (prove && marc) {
            eliminar_marca = "&prove=" + prove + "&marc=" + marc + "&b=" + b;

            $.ajax({
              type:"POST",
              url:"proveedorAbmMarca.php",
              data:eliminar_marca,
              success:function(r){                    
                if(r == 1){
                  alertify.success("Marca eliminada correctamente.");
                  setInterval('location.reload()', 1000);
                } else {
                  alertify.error("Error.");
                }
              }
            });
          } else {
            alertify.error("Por favor, complete todos los campos.");
          }                     
      }

  </script>

  <h2><b>Marcas de | <?php echo $proveedor['razon_social'] ;?> |</b></h2>
  <div class="row">
    <div class="col-md-6">
    <br>      
      <div class="pull-right">
        <button type="button" class="btn btn-success open-addMatricula" data-toggle="modal" data-id="" href="#addMarca">Agregar marca</button>
      </div>
    <div class="panel-heading clearfix">
    </div>
    </div>
  </div>

    <div class="modal fade bd-example-modal-lg" id="addMarca" tabindex="-1" role="dialog" aria-labelledby="modalMarca" data-backdrop="static" data-keyboard="false" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title" id="modalMarca">Agregar Marca</h4>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12">
                <label>Marcas:</label>
                  <select class="selectpicker" data-show-subtext="true" data-live-search="true" name="marcaselect" id="marcaselect" required="required" data-width="100%">
                    <option value="" selected>Seleccione una Marca</option>
                    <?php foreach ($proveedorLibre as $unProveedorLibre):
                    if(substr($unProveedorLibre['descripcion'], 0,3) != "---"){?>
                      <option value="<?php echo (int) $unProveedorLibre['id']?>">
                      <?php echo $unProveedorLibre['descripcion'];?>
                      </option>                  
                    <?php } endforeach; ?>
                  </select>
              </div>
            </div>
            <br>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-success" id="aceptarmarca" name="aceptarmarca">Aceptar</button>
          </div>
        </div>
      </div>
    </div>

    <script>
      
    </script>

    
<div class="row">
  <div class="col-md-6">
    <div class="table-responsive">
      <table id="marcas" class="table table-striped table-bordered" style="width:100%">
        <thead>
          <tr>
            <th style="width: 75%;text-align: center;">MARCA</th> 
            <th style="width: 25%;"></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($proveedorSel as $unProveedorSel):
            $unaMarca = find_by_id('maquina_marca',$unProveedorSel['id_maquina_marca']);
            ?>
            <tr>
              <td class="text-center"><?php echo $unaMarca['descripcion'];?></td>
              <td class="text-center">
                <a class="btn btn-xs btn-danger" title="Eliminar marca" id="eliminarmarca_<?php echo $unaMarca['id'];?>" name="eliminarmarca" data-toggle="tooltip" onclick="javascript:eliminarMarcaFunc('<?php echo $unaMarca['id'];?>')">
                <span class="glyphicon glyphicon-trash"></span>                                             
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
        <tfoot>
          <tr>
            <th style="width: 75%;text-align: center;">MARCA</th> 
            <th style="width: 25%;"></th>
          </tr>
        </tfoot>
      </table>

      <script>
        $('#matriculas').DataTable({ "order": [[ 0, "desc" ]] });
        $('.dataTables_length').addClass('bs-select');
      </script>
    </div>
  </div>
</div>

    <div class="row">
      <div class="col-md-6" style="text-align: right;">   
        <a class="btn btn-primary" href="proveedores.php" role=button>Volver a Proveedores</a>
      </div>
    </div>
                     

<?php include_once('layouts/footer.php'); ?>