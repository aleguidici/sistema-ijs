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
          $('#matriculas').DataTable();
          $('#addMatricula').on('hidden.bs.modal', function(){
            location.reload();
          });
          $('#buttom-crear-mod').prop("disabled",true);
          $('#buttom-crear-mod').attr("disabled",true);            

          $('#addMatricula').on('hidden.bs.modal', function (e) {
            $(this)
              .find("input,textarea,select, name, text")
                 .val('')
                 .end()
              .find("input[type=checkbox], input[type=radio]")
                 .prop("checked", "")
                 .end();
          });

          $(document).on("click", ".open-modal_nuevoModelo", function () {
            document.getElementById("nombre_marca_modal2").innerHTML = $(this).data('marca'); //carga la marca en el modal
          });

          $('#modal_editar_maquina').on('shown.bs.modal', function(e) {
            var selMarca = document.getElementById("marca");
            $('#marca').find('option').remove().end().append('<option value="" disabled selected>Seleccione una marca</option>');
              <?php foreach ($allMarcas as $unaMarca): ?>
                <?php 
                  if(substr($unaMarca['descripcion'], 0,3) != "---") { ?>
                    var opt = document.createElement('option');
                    var marca = "<?php echo $unaMarca['descripcion']; ?>";
                    opt.appendChild( document.createTextNode(marca) );
                    opt.value = parseInt("<?php echo $unaMarca['id']; ?> "); 
                    selMarca.appendChild(opt);                 
              <?php } endforeach; ?>
          });

          $('#crearModelo').click(function(){
            nombreOK_m = $('#nombre_modelo').val();
            //nombreOK_m = nombreOK_m.charAt(0).toUpperCase() + nombreOK_m.slice(1);
            descripcionOK_m = $('#detalle_modelo').val();
            descripcionOK_m = descripcionOK_m.charAt(0).toUpperCase() + descripcionOK_m.slice(1);
            inalambricoOK_m = $('#inalambrico_modelo').val();
            anioOK_m = $('#anio_modelo').val();
            tipoIdOK_m = $('#tipo_modelo').val();
            tamanioIdOK_m = $('#tamanio_modelo').val();
            marcaIdOK_m = parseInt($('#marca').val());
            //window.alert(marcaIdOK_m);           

            if (nombreOK_m) {
              cadena_p = "&nombreOK_m=" + nombreOK_m + "&descripcionOK_m=" + descripcionOK_m + "&inalambricoOK_m=" + inalambricoOK_m + "&anioOK_m=" + anioOK_m + "&tipoIdOK_m=" + tipoIdOK_m + "&tamanioIdOK_m=" + tamanioIdOK_m + "&marcaIdOK_m=" + marcaIdOK_m;

              $.ajax({
                type:"POST",
                url:"conAc_agregarModeloMaquina.php",
                data:cadena_p,
                success:function(r) {
                  if(r == 1){
                    alertify.success("Modelo agregado.");
                    $("#modal_nuevoModelo").modal("hide");
                    //document.getElementById("btn-cancel-modal-loc").click();     
                    $('#modal_nuevoModelo').on('hidden.bs.modal', function (){ //recarga la pagina cuando se cierra el modal
                      location.reload();
                    });
                  }else{
                    alertify.error("Error.");
                  }
                }
              });
            } else {
              alertify.error("Complete todos los campos.");
            }
          });


      });

      function blockSpecialChar(e) {
        var k = e.keyCode;
        return (!(k == 34 || k == 39));
      };

      function marcaSelect() {                  
        document.getElementById("modelo").disabled = false;
        var marca = document.getElementById("marca").value;
        var marca_nombre = $("#marca option:selected").text();
        var selMarca = document.getElementById("modelo");
        $('#buttom-crear-mod').prop("disabled",false);
        $('#buttom-crear-mod').attr("disabled",false);
        $('#buttom-crear-mod').data("id", marca);
        $('#buttom-crear-mod').data("marca", marca_nombre);
        $('#modelo').find('option').remove().end().append('<option value="" disabled selected>Seleccione un modelo</option>');
        <?php 
        $allModelos = find_all('maquina_modelo');
        foreach ($allModelos as $unModelo): ?>
          if ("<?php echo $unModelo['marca_id'];?>" == marca){
            var opt = document.createElement('option');
            var nombre = "<?php echo find_by_id('maquina_tipo',$unModelo['tipo_id'])['descripcion']." '".$unModelo['codigo']."'";
              if ($unModelo['inalambrico']) {
                echo " [Inalámbrico]";
              }
              echo " (Tamaño: ".find_by_id('maquina_tamanio',$unModelo['tamanio_id'])['descripcion']."; Año: ".$unModelo['anio'].")";?>";
            opt.appendChild( document.createTextNode(nombre) );
            opt.value = parseInt("<?php echo $unModelo['id'];?> "); 
            selMarca.appendChild(opt); 
          }
        <?php endforeach; ?> 
      }

      function modeloSelect() {                  
        var marca2 = document.getElementById("marca").value;
        var marca_nombre2 = $("#marca option:selected").text();
        var modelo_nombre = $("#modelo option:selected").text();
        var selModelo = document.getElementById("modelo");
        $('#buttom-crear-mod').data("id", marca2);
        $('#buttom-crear-mod').data("marca", modelo_nombre);        
      }   


      function eliminarMaquina(idMaquina){            
        prove = <?php echo $_GET['id'];?>;
        var marc = marcaid;
        b = 2;
        var eliminar = confirm("¿Está seguro que desea eliminar la marca para éste proveedor?");
        if (eliminar)         
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

  <h1><b>Máquinas de: <?php echo $clienteMe['razon_social'] ;?></b></h1>
  <br>
  <div class="row">
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-12">
          <div class="panel panel-default">
            <div class="panel-heading">
              <div class="row">
                <div class="col-md-10">
                  <div style="vertical-align:middle; text-align: center;"><h3><strong>M Á Q U I N A S</strong></h3></div>
                </div>
                <div class="col-md-2">
                  <div style="vertical-align:middle; text-align: center;"><h3><strong>
                    <a href="conAc_agregarMaquina.php?idCli=<?php echo $clienteMe['id'] ;?>" class="btn btn-success btn-md" type="button" data-toggle="tooltip" title="Agregar máquina"><span class="glyphicon glyphicon-plus"></span></a></strong></h3>
                  </div>                  
                </div>
              </div>
            </div> 
            <div class="table-responsive">
              <table id="maquinas" class="table table-striped table-bordered" style="width:100%">
                <thead>          
                  <tr>
                    <th style="width: 21.25%; text-align: center;">Marca</th> 
                    <th style="width: 21.25%; text-align: center;">Modelo</th>
                    <th style="width: 21.25%; text-align: center;">N° Serie</th>
                    <th style="width: 15%; text-align: center;"></th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($maquinasDelCliente as $unaMaquinaDelCliente): 
                    $modelo = find_by_id('maquina_modelo', $unaMaquinaDelCliente['modelo_id']);
                    $marca = find_by_id('maquina_marca', $modelo['marca_id']);
                    ?>
                    <tr>
                      <td class="text-center"><?php echo $marca['descripcion']; ?></td>
                      <td class="text-center"><?php echo $modelo['codigo']; ?></td>
                      <td class="text-center"><?php echo $unaMaquinaDelCliente['num_serie']; ?></td>
                      <td class="text-center">
                        <a class="btn btn-xs btn-warning" id='btn-editar_maquina' data-toggle="modal" class='btn open-modal_editar_maquina' title='Editar máquina' data-target="#modal_editar_maquina" data-id_marca="<?php echo $marca['id']; ?>" data-marca="<?php echo $marca['descripcion']; ?>" data-modelo="<?php echo $modelo['codigo']; ?>"><span class="glyphicon glyphicon-pencil"></span></a>
                        <input type="hidden" name="id_maquina" id="id_maquina" value="$unaMaquinaDelCliente['id']; ?>">
                        <a class="btn btn-xs btn-danger" title="Eliminar máquina" id="eliminar_maquina_<?php echo $unaMaquinaDelCliente['id'];?>" name="eliminar_marca" data-toggle="tooltip" onclick="javascript:eliminarMaquina('<?php echo $unaMaquinaDelCliente['id'];?>')">
                        <span class="glyphicon glyphicon-trash"></span>                                             
                        </a>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
                <tfoot>
                  <tr>
                    <th style="width: 21.25%; text-align: center;">Marca</th> 
                    <th style="width: 21.25%; text-align: center;">Modelo</th>
                    <th style="width: 21.25%; text-align: center;">N° Serie</th>
                    <th style="width: 15%; text-align: center;"></th>
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
      </div>

           
    </div>
    <div class="col-md-6">       
    </div>
  </div>
  <div class="row">
    <div class="col-md-6" style="text-align: right;">   
      <a class="btn btn-primary" href="clientes.php" role=button>Volver a Clientes</a>
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
            <div class="col-md-4">
              <label>Marca:</label>
              <select class="form-control" name="marca" id="marca" required onchange="javascript:marcaSelect()">
                <option value=""></option>
              </select>
            </div>
            <div class="col-md-4">
              <label>Modelo:</label>
              <a id='buttom-crear-mod' data-toggle="modal" class='btn open-modal_nuevoModelo' title='Nuevo Modelo' style="padding-top: 0px; padding-bottom: 0px;" data-target="#modal_nuevoModelo" data-id="" data-marca=""><i class="fa fa-1 fa-plus-square" style="color:#008a55;" aria-hidden="true"></i></a>
              <select class="form-control" name="modelo" id="modelo" required disabled onchange="javascript:modeloSelect()">
                <option value=""></option>
              </select>
            </div>
            <div class="col-md-4">
              <label for="serie" class="control-label">N° Serie:</label>
                <input type="name" class="form-control" name="serie" id="serie" placeholder="N° serie" maxlength="100">
              </select>
            </div>          
          </div>
          <br>
          <div class="row">
            <div class="col-md-8">
              <div class="row">
                <div class="col-md-6">
                <label>Tipo:</label>
                  <select class="form-control" name="tipo" id="tipo">
                    <!--<option value=""></option>-->
                    <?php foreach ($allMaquinaTipos as $unTipo): ?>
                      <option value="<?php echo (int) $unTipo['id'];?>">                                  
                        <?php echo ($unTipo['descripcion']);?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-md-6">
                  <label>Nombre del Modelo:</label>
                  <input id="nombremodelo" class="form-control" placeholder="Nombre del Modelo" maxlength="100">
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-4">
                  <label>Tamaño:</label>
                  <select class="form-control" name="tamaniomodelo" id="tamaniomodelo">
                    <?php foreach ($allMaquinaTamanios as $unTamanio): ?>
                      <option value="<?php echo (int) $unTamanio['id'];?>">
                        <?php echo utf8_encode($unTamanio['descripcion']);?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-md-4">
                  <label>Inalámbrico:</label>
                  <select class="form-control" name="inalambricomodelo" id="inalambricomodelo">
                    <option value="1">SI</option>
                    <option value="0" selected>NO</option>
                  </select>
                </div>
                <div class="col-md-4">
                  <label>Año (Opcional):</label>
                  <input id="aniomodelo" class="form-control" placeholder="Año" maxlength="10">
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
          <hr>
          <div class="row">
            <div class="col-md-12">
              <div>
                <button type="button" class="btn btn-danger" id="btn-cancel-modal-loc" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success pull-right" id="guaraar">Guardar</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- MODAL NUEVO MODELO -->

  <div class="modal fade bd-example-modal-lg" id="modal_nuevoModelo" tabindex="-1" role="dialog" aria-labelledby="modalModelooo" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="modalModelooo">Nuevo Modelo</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <label>Marca:</label>
              <h4 id='nombre_marca_modal2'></h4>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-8">
              <div class="row">
                <div class="col-md-6">
                <label>Tipo:</label>
                  <select class="form-control" name="tipo_modelo" id="tipo_modelo">
                    <!--<option value=""></option>-->
                    <?php foreach ($allMaquinaTipos as $unTipo): ?>
                      <option value="<?php echo (int) $unTipo['id'];?>">                                  
                        <?php echo ($unTipo['descripcion']);?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-md-6">
                  <label>Nombre del Modelo:</label>
                  <input id="nombre_modelo" class="form-control" placeholder="Nombre del Modelo" maxlength="100">
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-4">
                  <label>Tamaño:</label>
                  <select class="form-control" name="tamanio_modelo" id="tamanio_modelo">
                    <?php foreach ($allMaquinaTamanios as $unTamanio): ?>
                      <option value="<?php echo (int) $unTamanio['id'];?>">
                        <?php echo utf8_encode($unTamanio['descripcion']);?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-md-4">
                  <label>Inalámbrico:</label>
                  <select class="form-control" name="inalambrico_modelo" id="inalambrico_modelo">
                    <option value="1">SI</option>
                    <option value="0" selected>NO</option>
                  </select>
                </div>
                <div class="col-md-4">
                  <label>Año (Opcional):</label>
                  <input id="anio_modelo" class="form-control" placeholder="Año" maxlength="10">
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="row">
                <div class="col-md-12">
                  <label>Descripción (Opcional):</label>
                  <textarea type="text" class="form-control" placeholder="Descripción" id="detalle_modelo" rows="5" maxlength="350" style="resize: none;"></textarea>
                </div>
              </div>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-md-12">
              <div>
                <button type="button" class="btn btn-danger" id="btn-cancel-modal-loc" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success pull-right" id="crearModelo">Guardar</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
                       

<?php include_once('layouts/footer.php'); ?>