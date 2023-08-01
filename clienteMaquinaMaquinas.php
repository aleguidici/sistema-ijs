<?php
  $page_title = 'Máquinas del Cliente';
  require_once('includes/load.php');
  page_require_level(2);
  $clienteMe = find_by_id('clientemaquina', $_GET['id']);
  $maquinasDelCliente = find_all_maquinas_cliente('maquina', $_GET['id']);
  $allMarcas = find_all('maquina_marca');
  $allMaquinaTipos = find_all('maquina_tipo');
  $allMaquinaModelos = find_all('maquina_modelo');
  $allMaquinaTamanios = find_all('maquina_tamanio');
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

<script type="text/javascript">

      $(document).ready(function() {
        $('#maquinas').DataTable(); 
        $('#buttom-crear-mod').prop("disabled",true);
        $('#buttom-crear-mod').attr("disabled",true);
        $('#marca').attr("disabled",true);
        $('#tipo').attr("disabled",true);
        $('#modelo').attr("disabled",true);
        $('#tamaniomodelo').attr("disabled",true);
        $('#inalambricomodelo').attr("disabled",true);
        $('#aniomodelo').attr("disabled",true);

        $('#guardar').click(function() {
          idMaquinaOk = $('#id_maquina').val();
          serieOk = $('#serie').val();
          serieOk = serieOk.toUpperCase();
          detalleOk = $('#detallemodelo').val();
          detalleOk = detalleOk.charAt(0).toUpperCase() + detalleOk.slice(1);         

          if (serieOk) {
            editar_m = "&idMaquinaOk=" + idMaquinaOk + "&serieOk=" + serieOk + "&detalleOk=" + detalleOk + "&bOk=" + 1;

            $.ajax({
              type:"POST",
              url:"clienteMaquinaMaquinasAbm.php",
              data:editar_m,
              success:function(r) {
                if (r == 1) {
                  alertify.success("Maquina editada.");
                  $("#modal_editar_maquina").modal("hide"); 
                  setInterval('location.reload()', 1000);
                } else {
                  alertify.error("Error.");
                }
              }
            });
          } else {
              alertify.error("Complete todos los campos.");
          }
        });


      });

      function editarMaquina(idMaquina,marcaDesc,idMarca,modelo,serie,tipoDesc,tamanioDesc,inalambrico,detalle) {
        $('#marca').find('option').remove();
        $('#tipo').find('option').remove();
        $('#tamaniomodelo').find('option').remove();
        $('#inalambricomodelo').find('option').remove();
        var selMarca = document.getElementById("marca");            
        var opt = document.createElement('option');            
        opt.appendChild( document.createTextNode(marcaDesc) );
        opt.value = parseInt(idMarca); 
        selMarca.appendChild(opt);
        document.getElementById("modelo").value = modelo;
        document.getElementById("serie").value = serie;
        var selTipo = document.getElementById("tipo");
        var optTipo = document.createElement('option');
        optTipo.appendChild( document.createTextNode(tipoDesc) );
        selTipo.appendChild(optTipo);
        var selTama = document.getElementById("tamaniomodelo");
        var optTama = document.createElement('option');
        optTama.appendChild( document.createTextNode(tamanioDesc) );
        selTama.appendChild(optTama);
        var selIna = document.getElementById("inalambricomodelo");
        var optIna = document.createElement('option');
        if (inalambrico == 1) {
          optIna.appendChild( document.createTextNode("SI") );
        } else {
          optIna.appendChild( document.createTextNode("NO") );
        }
        selIna.appendChild(optIna);
        document.getElementById('detallemodelo').value = detalle;
        document.getElementById('id_maquina').value = idMaquina;
      }

      function blockSpecialChar(e) {
        var k = e.keyCode;
        return (!(k == 34 || k == 39));
      };

      function eliminarMaquina(idMaquinaOk,serieOk){            
        var eliminar = confirm("¿Está seguro que desea eliminar ésta máquina?");
        if (eliminar)         
          if (idMaquinaOk) {
            eliminar_m = "&idMaquinaOk=" + idMaquinaOk + "&serieOk=" + serieOk + "&detalleOk=" + 0 + "&bOk=" + 2;

            $.ajax({
              type:"POST",
              url:"clienteMaquinaMaquinasAbm.php",
              data:eliminar_m,
              success:function(r){                    
                if(r == 1){
                  alertify.success("Máquina eliminada correctamente.");
                  setInterval('location.reload()', 1000);
                } else {
                  alertify.error("Error al eliminar, la máquina tiene reparaciones.");
                }
              }
            });
          } else {
            alertify.error("Error.");
          }                     
      }

</script>

  <h1><b><?php echo $clienteMe['razon_social'] ;?></b></h1>
  <br>
  <div class="row">
    <div class="col-md-1">       
    </div>
    <div class="col-md-10">
      <div class="row">
        <div class="col-md-12">
          <div class="panel panel-default" style="box-shadow: 2px 2px 10px 2px rgba(0, 0, 0, 0.2);">
            <div class="panel-heading">
              <div class="row" style="margin-top: 10px;">
                <div class="col-md-4">
                  <div><strong><p style="vertical-align:center;font-size: 30px;">LISTA DE MÁQUINAS</p></strong></div>
                </div>
                <div class="col-md-2 pull-right">
                  <div style="vertical-align:middle; margin-top: 2px;"><strong>
                    <a href="conAc_agregarMaquina.php?idCli=<?php echo $clienteMe['id'] ;?>" class="btn btn-success btn-md" type="button" data-toggle="tooltip" title="Agregar máquina"><span class="glyphicon glyphicon-plus"></span> AGREGAR MÁQUINA</a></strong>
                  </div>                  
                </div>
              </div>
            </div>
            <br> 
            <div class="table-responsive" style="margin-left: 15px;margin-right: 15px;margin-bottom: 5px;">
              <table id="maquinas" class="table table-striped table-bordered">
                <thead>          
                  <tr>
                    <th style="width: 7.5%; text-align: center;">IJS-ME</th>
                    <th style="width: 17.5%; text-align: center;">Tipo</th>
                    <th style="width: 17.5%; text-align: center;">Marca</th> 
                    <th style="width: 17.5%; text-align: center;">Modelo</th>
                    <th style="width: 17.5%; text-align: center;">N° Serie</th>
                    <th style="width: 17.5%; text-align: center;">Descripción</th>
                    <th style="width: 5%; text-align: center;"></th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($maquinasDelCliente as $unaMaquinaDelCliente): 
                    $modelo = find_by_id('maquina_modelo', $unaMaquinaDelCliente['modelo_id']);
                    $marca = find_by_id('maquina_marca', $modelo['marca_id']);
                    $tipo = find_by_id('maquina_tipo', $modelo['tipo_id']);
                    $tamanio = find_by_id('maquina_tamanio', $modelo['tamanio_id']);
                    ?>
                    <?php if ($unaMaquinaDelCliente['sin_reparacion'] == 1) { ?>
                    <tr class = "danger" data-toggle="tooltip" title="<?php echo "No tiene reparación, razón: ".$unaMaquinaDelCliente['razon_noreparacion']; ?>">
                    <?php } else { ?>
                    <tr>
                    <?php } ?>
                      <td class="text-center"><?php echo "IJS-ME: ".$unaMaquinaDelCliente['id'];?></td>
                      <td class="text-center"><?php echo $tipo['descripcion']; ?></td>
                      <td class="text-center"><?php echo $marca['descripcion']; ?></td>
                      <td class="text-center"><?php echo $modelo['codigo']; ?></td>
                      <td class="text-center"><?php echo $unaMaquinaDelCliente['num_serie']; ?></td>
                      <td class="text-center"><?php echo $unaMaquinaDelCliente['detalles']; ?></td>
                      <td class="text-center">
                        <button class="btn btn-xs btn-warning" id="btn-editar_maquina_<?php echo $unaMaquinaDelCliente['id'];?>" name="btn-editar_maquina" data-toggle="modal" class="btn open-modal_editar_maquina" title="Editar máquina" data-target="#modal_editar_maquina" onclick="javascript:editarMaquina('<?php echo $unaMaquinaDelCliente['id']; ?>','<?php echo $marca['descripcion']; ?>','<?php echo $marca['id']; ?>','<?php echo $modelo['codigo']; ?>','<?php echo $unaMaquinaDelCliente['num_serie']; ?>','<?php echo $tipo['descripcion']; ?>','<?php echo $tamanio['descripcion']; ?>','<?php echo $modelo['inalambrico']; ?>','<?php echo $unaMaquinaDelCliente['detalles']; ?>')"><span class="glyphicon glyphicon-pencil"></span></button>
                        <input type="hidden" name="id_maquina" id="id_maquina" value="">
                        <a class="btn btn-xs btn-danger" title="Eliminar máquina" id="btn-eliminar_maquina_<?php echo $unaMaquinaDelCliente['id'];?>" name="btn-eliminar_maquina" data-toggle="tooltip" onclick="javascript:eliminarMaquina('<?php echo $unaMaquinaDelCliente['id'];?>','<?php echo $unaMaquinaDelCliente['num_serie'];?>')"><span class="glyphicon glyphicon-trash"></span>                                      
                        </a>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
                <tfoot>
                  <tr>
                    <th style="width: 7.5%; text-align: center;">IJS-ME</th>
                    <th style="width: 17.5%; text-align: center;">Tipo</th>
                    <th style="width: 17.5%; text-align: center;">Marca</th> 
                    <th style="width: 17.5%; text-align: center;">Modelo</th>
                    <th style="width: 17.5%; text-align: center;">N° Serie</th>
                    <th style="width: 17.5%; text-align: center;">Descripción</th>
                    <th style="width: 5%; text-align: center;"></th>
                  </tr>
                </tfoot>
              </table>

              <script>
                $('#maquinas').DataTable({ "order": [[ 0, "asc" ],[ 1, 'asc' ],[ 2, 'asc' ]]});
                $('.dataTables_length').addClass('bs-select');
              </script>

            </div>
          </div>
        </div>
      </div>           
    </div>
    <div class="col-md-1">       
    </div>
  </div>
  <div class="row">
    <div class="col-md-1"></div>
    <div class="col-md-10" style="text-align: right;">   
      <a class="btn btn-primary" href="clientes.php?tabclieme=2" role=button>Volver a Clientes</a>
    </div>
  </div>

  <!-- MODAL EDITAR DATOS MAQUINA-->
  <div class="modal fade bd-example-modal-lg" id="modal_editar_maquina" tabindex="-1" role="dialog" aria-labelledby="modal_editar_maquina-label" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="modal_editar_maquina-label">Editar Máquina</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-8">
              <div class="row">
                <div class="col-md-6">
                  <label>Marca:</label>
                  <select class="form-control" name="marca" id="marca" required>
                    <option value=""></option>
                  </select>
                </div>
                <div class="col-md-6">
                    <label>Tipo:</label>
                    <select class="form-control" name="tipo" id="tipo">
                      <option value="">
                        
                      </option>
                    </select>
                </div>                           
              </div>
          <br>
              <div class="row">
                <div class="col-md-6">
                  <label for="modelo" class="control-label">Modelo:</label>
                    <input type="name" class="form-control" name="modelo" id="modelo" placeholder="Modelo" maxlength="100">                  
                </div>
                <div class="col-md-6">
                  <label for="serie" class="control-label">N° Serie:</label>
                    <input type="name" class="form-control" name="serie" id="serie" placeholder="N° serie" maxlength="100" required>
                  
                </div>
              </div>
            </div>        
            <div class="col-md-4">
              <div class="row">
                <div class="col-md-12">
                  <label>Descripción (Opcional):</label>
                  <textarea type="text" class="form-control" placeholder="Descripción" id="detallemodelo" rows="5" maxlength="350" style="resize: none;"></textarea>
                </div>
              </div>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-4">
              <label>Tamaño:</label>
              <select class="form-control" name="tamaniomodelo" id="tamaniomodelo">                
                  <option value="">
                    
                  </option>               
              </select>
            </div>
            <div class="col-md-4">
              <label>Inalámbrico:</label>
              <select class="form-control" name="inalambricomodelo" id="inalambricomodelo">
                <option value="">
                  
                </option>
              </select>
            </div>
            <div class="col-md-4">
              <label>Año (Opcional):</label>
              <input id="aniomodelo" class="form-control" placeholder="Año" maxlength="10">
            </div>
          </div>     
          <hr>
          <div class="row">
            <div class="col-md-12">
              <div>
                <button type="button" class="btn btn-danger" id="btn-cancel-modal-loc" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success pull-right" id="guardar">Guardar</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>  
                       

<?php include_once('layouts/footer.php'); ?>