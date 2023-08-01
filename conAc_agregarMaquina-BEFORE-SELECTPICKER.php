<?php
  $page_title = 'Nueva Máquina';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
  $clienteOK = find_by_id('clientemaquina',$_GET['idCli']);
  $provinciaOK = find_by_id_prov('provincia',$clienteOK['provincia']);

  $sql_allMarcas = $db->query("SELECT * FROM maquina_marca ORDER BY descripcion");
  $all_marcas = $db->while_loop($sql_allMarcas);

  //$all_marcas = find_all('maquina_marca');
  $all_maquina_tipos = find_all_tipos('maquina_tipo');
  $all_maquina_tamanios = find_all('maquina_tamanio');
 
  // $all_matriculados = find_all_matriculados('personal_matriculas');
  // $mydata = Array();
  // $tabla = "";
  // $query_parts = Array();

  if(isset($_POST['abm_cliente'])){
   $idCliente = remove_junk($db->escape($_POST['idClienteFinal']));
   $idModelo = remove_junk($db->escape($_POST['modelo']));
   $numSerieBase = remove_junk($db->escape($_POST['numSerie']));
   $numSerie = strtoupper($numSerieBase);
   $descripcionFinal = remove_junk($db->escape($_POST['descripcionFinal']));
   // $forma_p = remove_junk($db->escape($_POST['forma_pago']));
 

	$query  = "INSERT INTO maquina (`id_cliente`, `modelo_id`, `num_serie`, `detalles`, `sin_reparacion`) VALUES ('{$idCliente}', '{$idModelo}', '{$numSerie}', '{$descripcionFinal}', 0)";

	if($db->query($query)){
    echo '<script>alertify.success("Máquina agregada con éxito.");</script>';
    $session->msg("s", "Cliente agregado exitosamente.");
    echo '<script>setInterval(location.replace("conAc.php"), 5000)</script>';
	  
	  //redirect('conAC.php',false);
	} else {
    echo '<script>alertify.warning("Error.");</script>';
	  $session->msg("d", "Lo siento, el registro falló");
	  //redirect('conAc.php',false);
	}  
 }
?>


<?php include_once('layouts/header.php'); ?>
    <style type="text/css">
      body { 
        padding-right: 0 !important;
      }
    </style>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round|Open+Sans">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
    <link rel="stylesheet" type="text/css" href="libs/alertifyjs/css/alertify.css">
    <link rel="stylesheet" type="text/css" href="libs/alertifyjs/css/themes/default.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
    <script src="libs/alertifyjs/alertify.js"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> 
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
      
      <?php echo display_msg($msg); ?>
      <div class="panel panel-default" id="panelcito">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Nueva máquina</span>
          </strong>
        </div>

        <div class="panel-body">
          <div class="row">
            <div class="col-md-10">
              <h5><label class="control-label">Cliente:</label></h5>
            </div>
          </div>
          <div class="row">
            <div class="col-md-2">
              &emsp;&emsp;
              <b>Razón social: </b>
            </div>
            <div class="col-md-10">
              <?php echo $clienteOK['razon_social'];?>
            </div>
          </div>

          <div class="row">
            <div class="col-md-2">
              &emsp;&emsp;
              <b>Dirección: </b>
            </div>
            <div class="col-md-10">
              <?php echo $clienteOK['direccion']?> <em><?php echo ' - '.$clienteOK['localidad'].' ('.$clienteOK['cp'].'), '.$provinciaOK['nombre'];?></em>
            </div>
          </div>

          <div class="row">
            <div class="col-md-2">
              &emsp;&emsp;
              <b>DNI/CUIT: </b>
            </div>
            <div class="col-md-4">
              <?php echo $clienteOK['cuit'].' ('.$clienteOK['iva'].')'  ;?>
            </div>
          </div>

          <div class="row">
            <div class="col-md-2">
              &emsp;&emsp;
              <b>Teléfono: </b>
            </div>
            <div class="col-md-4">
              <?php if ($clienteOK['tel'] != null) 
                echo $clienteOK['tel'];
              else
                echo "-"; ?>
            </div>
            <div class="col-md-2">
              &emsp;&emsp;
              <b>Celular: </b>
            </div>
            <div class="col-md-4">
              <?php if ($clienteOK['cel'] != null) 
                echo $clienteOK['cel'];
              else
                echo "-"; ?>
            </div>
          </div>

          <hr>

          <form method="post" action="conAc_agregarMaquina.php"> 
            <h5><label class="control-label">Datos máquina:</label></h5>
            <div class="row">
              
              <div class="form-group col-xs-4 mr-2">
                <label for="marca" class="control-label">Marca:</label>
                <select required="required" class="form-control" name="marca" id="marca" required onchange="javascript:marcaSelect()">
                  <option value="" disabled selected>Seleccione una marca</option>
                  <?php foreach ($all_marcas as $una_marca): 
                    if(substr($una_marca['descripcion'], 0,3) != "---"){?>
                    <option value="<?php echo (int) $una_marca['id']?>">
                      <?php 
                      echo $una_marca['descripcion'];?>                      
                    </option>
                  <?php } endforeach; ?>
                </select>
              </div>
              

              <div class="form-group col-xs-4  mr-2">
                <label for="modelo" class="control-label">Modelo:</label>
                
                <a id='buttom-crear-mod' data-toggle="modal" class='btn open-modal_nuevoModelo' title='Nuevo Modelo' style="padding-top: 0px; padding-bottom: 0px;" data-target="#modal_nuevoModelo" data-id="" data-marca=""><i class="fa fa-1 fa-plus-square" style="color:#008a55;" aria-hidden="true"></i></a>

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
                                  <?php foreach ($all_maquina_tipos as $un_tipo): ?>
                                    <option value="<?php echo (int) $un_tipo['id'];?>">                                  
                                      <?php echo ($un_tipo['descripcion']);?>
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
                                  <?php foreach ($all_maquina_tamanios as $un_tamanio): ?>
                                    <option value="<?php echo (int) $un_tamanio['id'];?>">
                                      <?php echo utf8_encode($un_tamanio['descripcion']);?>
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

                <script>

                  $(function() { 
                    $('#modal_nuevoModelo').on('hidden.bs.modal', function (e) {
                      $(this)
                      .find("input,textarea, name, text")
                         .val('')
                         .end()
                      .find("select")
                         .val('1')
                      .find("input[type=checkbox], input[type=radio]")
                         .prop("checked", "")
                         .end();
                    })
                  }); 

                  $(document).ready(function(){
                    $('#crearModelo').click(function(){

                      nombreOK_m = $('#nombre_modelo').val();
                      nombreOK_m = nombreOK_m.toUpperCase();
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
                          success:function(r){
                            if (r == 1) {
                              alertify.success("Modelo agregado.");
                              $("#modal_nuevoModelo").modal("hide");
                              //document.getElementById("btn-cancel-modal-loc").click();     
                              $('#modal_nuevoModelo').on('hidden.bs.modal', function (){ //recarga la pagina cuando se cierra el modal
                                location.reload();
                              });
                            } else if (r == 2) {
                              alertify.error("Ése modelo ya existe, por favor verifique.");
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

                  function blockSpecialChar(e) {
                    var k = e.keyCode;
                    return (!(k == 34 || k == 39));
                  };
                </script>
                     
                <script>
                    function marcaSelect() {                  
                    document.getElementById("modelo").disabled = false;
                    var marca = document.getElementById("marca").value;
                    var marca_nombre = $("#marca option:selected").text();
                    var sel = document.getElementById("modelo");
                    $('#buttom-crear-mod').prop("disabled",false);
                    $('#buttom-crear-mod').attr("disabled",false);
                    $('#buttom-crear-mod').data("id", marca);
                    $('#buttom-crear-mod').data("marca", marca_nombre);
                    $('#modelo').find('option').remove().end().append('<option value="" disabled selected>Seleccione un modelo</option>');
                    <?php 
                    //$all_modelos = find_all('maquina_modelo'); OJOOOOOOOOOOOOOOOOOO
                    $all_modelos = find_all_modelo_order('maquina_modelo');
                    foreach ($all_modelos as $un_modelo): ?>
                      if ("<?php echo $un_modelo['marca_id'];?>" == marca){
                        var opt = document.createElement('option');
                        var nombre = "<?php echo find_by_id('maquina_tipo',$un_modelo['tipo_id'])['descripcion']." '".$un_modelo['codigo']."'";
                          if ($un_modelo['inalambrico']) {
                            echo " [Inalámbrico]";
                          }
                          echo " (Tamaño: ".find_by_id('maquina_tamanio',$un_modelo['tamanio_id'])['descripcion']."; Año: ".$un_modelo['anio'].")";?>";
                        opt.appendChild( document.createTextNode(nombre) );
                        opt.value = parseInt("<?php echo $un_modelo['id'];?> "); 
                        sel.appendChild(opt); 
                      }
                    <?php endforeach; ?> 
                  }                   

                  $('#modal_nuevoModelo').on('hidden.bs.modal', function () { //recarga la pagina cuando se cierra el modal
                    //location.reload();
                  });

                  $(document).on("click", ".open-modal_nuevoModelo", function () {
                    document.getElementById("nombre_marca_modal2").innerHTML = $(this).data('marca'); //carga la marca en el modal
                  });

                  </script>

                <select required="required" class="form-control" name="modelo" id="modelo" required disabled>
                  <option value="" disabled selected>Seleccione un modelo</option>
                </select>
              </div>
              <div class="form-group col-xs-4 mr-2">
                <label for="num_serie" class="control-label">Modelo: N° serie</label>
                <input type="name" class="form-control" name="numSerie" id="numSerie" placeholder="N° serie" maxlength="100">
                <input type="hidden" class="form-control" name="idClienteFinal" value="<?php echo ($_GET['idCli'])?>">
              </div>
            </div>

            <!--<div class="row">
              <div class="form-group col-xs-2 mr-2">
                <label for="telefono" class="control-label">Año (Opcional):</label>
                <input type="name" class="form-control" name="anio" placeholder="Año" maxlength="10">
              </div>
            </div>-->

            <div class="row">
              <div class="form-group col-xs-6 mr-2">
                <label for="telefono" class="control-label">Descripción (Opcional):</label>
                <textarea type="text" class="form-control" placeholder="Descripción" id="descripcionFinal" name="descripcionFinal" rows="3"></textarea>
              </div>
            </div>
            

            <div class="row">
              <div class="form-group col-xs-12 mr-2">
                <div class="form-group text-right">   
                  <a class="btn btn-primary" href="conAc.php" role=button>Volver a ConAc</a>
                  <button type="submit" name="abm_cliente" class="btn btn-success">Crear Máquina</button>
                </div>
              </div>
            </div>
          </form> 
        </div>
      </div>

<script>
  /*$('#modal_nuevoModelo').on('hidden.bs.modal', function (e) {
    $(this)
      .find("input,textarea,select, name, text")
         .val('')
         .end()
      .find("input[type=checkbox], input[type=radio]")
         .prop("checked", "")
         .end();
  });

*/
  $(document).ready(function(){
    $('#buttom-crear-mod').prop("disabled",true);
    $('#buttom-crear-mod').attr("disabled",true);
  });
</script>


<?php include_once('layouts/footer.php'); ?>