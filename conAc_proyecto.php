<?php
  $page_title = 'Detalles proyecto';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
  $proyecto = find_by_id('proyecto',$_GET['id']);
  $all_clientes = find_all('cliente');
  $cliente = find_by_id('cliente', $proyecto['id_cliente']);
  $actividades = find_by_id('proy_actividades', $proyecto['id']);
  $photo = new Media();
  $all_personal_noAfectado = find_personal_noAfectado($_GET['id']);
  $all_personal_afectado = find_personal_proyecto($_GET['id']);

  $all_activ_estaticas = find_actEstat_by_proy($_GET['id']);
?>

<?php include_once('layouts/header.php'); ?>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> 
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css">
  <link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.15.3/dist/bootstrap-table.min.css">
  
  <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="libs/alertifyjs/css/alertify.css">
  <link rel="stylesheet" type="text/css" href="libs/alertifyjs/css/themes/default.css">

  <script src="libs/alertifyjs/alertify.js"></script>     
  <link rel="stylesheet" href="http://weareoutman.github.io/clockpicker/dist/bootstrap-clockpicker.min.css">
  <script src="http://weareoutman.github.io/clockpicker/dist/bootstrap-clockpicker.min.js"></script>

  <?php echo display_msg($msg); ?>
  
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Detalles del proyecto</span>
        </strong>
      </div>

      <div class="panel-body">
        <ul class="nav nav-tabs" id="myTab">
          <li class="active">
            <a data-toggle="tab" role="tab" href="#menu1">Datos</a>
          </li>
          <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">Lista de materiales / insumos <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="#menu2" data-toggle="tab" role="tab" >Propio</a></li>
              <li><a href="#menu5" data-toggle="tab" role="tab" >Del cliente</a></li>
            </ul>
          </li>
          <li>
            <a data-toggle="tab" role="tab" href="#menu4">Personal</a>
          </li>
          <li>
            <a data-toggle="tab" role="tab" href="#menu3">Planificación</a>
          </li>
          <li>
            <a data-toggle="tab" role="tab" href="#menu6">Actividades</a>
          </li>
          <li>
            <a data-toggle="tab" role="tab" href="#menu7">Avances</a>
          </li>
        </ul>
         
        <div class="tab-content">
        <!-- Solapa de Inicio -->
          <div id="menu1" class="tab-pane fade in active">
            <br>
            <div class="col-xs-12" />
              <div class="row">
                <div class="pull-right">
                  <button type="button" class="btn btn-primary open-editProyecto" data-toggle="modal" data-id="<?php echo $proyecto['id'];?>" href="#editProyecto"> Editar información </button>
                </div>

                <div class="modal fade bd-example-modal-lg" id="editProyecto" tabindex="-1" role="dialog" aria-labelledby="modalInfooo" data-backdrop="static" data-keyboard="false" aria-hidden="true">
                  <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="modalInfooo">Editar Proyecto</h4>
                      </div>
                      <div class="modal-body">
                        <h4>Campos obligatorios:</h4>
                        <div class="row">
                          <div class="col-md-9">
                            <label>Nombre del Proyecto:</label>
                            <input style="border:1px solid #000000" type="name" id="nombre_temp_p" class="form-control" placeholder="Nombre de Proyecto" required maxlength="255" onkeypress="return blockSpecialChar(event)">
                          </div>
                          <div class="col-md-3">
                            <label>Fecha de inicio</label>
                            <input style="border:1px solid #000000" type="date" class="form-control" name="fecha_proy" id="fecha_proy" required onkeydown="return false">
                          </div>
                        </div>
                        <br>
                        <div class="row">
                          <div class="col-md-12">
                            <label id="labelCli" name="labelCli">Cliente: </label>
                            <select class="selectpicker" data-show-subtext="true" data-live-search="true" name="clien" id="clien" required="required" data-width="100%">
                            <option value="" selected>Seleccione un Cliente</option>
                            <?php  foreach ($all_clientes as $un_cli): ?>
                              <option value="<?php echo (int) $un_cli['id']?>">
                              <?php if(!empty($un_cli['num_suc']))
                                echo 'Sucursal Nº ',$un_cli['num_suc'] , ' - ';
                              echo $un_cli['razon_social'], ' - ',  $un_cli['direccion'] , ' - ',  $un_cli['localidad'], ' - ', utf8_encode(find_by_id_prov('provincia', $un_cli['provincia'])['nombre'])?></option>
                            <?php endforeach; ?>
                          </select>
                          </div>
                        </div>
                        <br>
                        <div class="row">
                          <div class="col-md-12">
                            <label>Descripción:</label>
                            <textarea style="resize: none; border:1px solid #000000" type="name" class="form-control" placeholder="Descripción" id="descrip_temp_p" required maxlength="255" onkeypress="return blockSpecialChar(event)"></textarea>
                          </div>
                        </div>
                        <hr style="border-color:black;">
                        <h4>Otros datos (Opcionales):</h4>
                        <div class="row">
                          <div class="col-md-6">
                            <label>Link IJS (Privado):</label>
                            <input type="name" class="form-control" id="link_priv_p" placeholder="Link privado" maxlength="255" onkeypress="return blockSpecialChar(event)">
                          </div>
                          <div class="col-md-6">
                            <label>Link Público:</label>
                            <input type="name" class="form-control" id="link_publico_p" placeholder="Link público" maxlength="255" onkeypress="return blockSpecialChar(event)">
                          </div>
                        </div>
                        <br>
                      </div>

                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal" id="editar_p">Editar</button>
                      </div>
                    </div>
                  </div>
                </div>

                <script>
                  $('#editProyecto').on('hidden.bs.modal', function () {
                    location.reload();
                  });

                  $(document).on("click", ".open-editProyecto", function () {
                    $(".modal-body #nombre_temp_p").val("<?php echo find_by_id('proyecto', $_GET['id'])['nombre_proyecto']?>");
                    $(".modal-body #fecha_proy").val("<?php echo find_by_id('proyecto', $_GET['id'])['fecha_inicio']?>");
                    $(".modal-body #labelCli").text("Cliente: (Anterior: <?php echo $cliente['razon_social']?>)");
                    $(".modal-body #clien").val("<?php echo find_by_id('proyecto', $_GET['id'])['id_cliente']?>");
                    $(".modal-body #clien").change();
                    $(".modal-body #descrip_temp_p").val("<?php echo find_by_id('proyecto', $_GET['id'])['descripcion']?>");
                    $(".modal-body #link_priv_p").val("<?php echo find_by_id('proyecto', $_GET['id'])['link_IJS']?>");
                    $(".modal-body #link_publico_p").val("<?php echo find_by_id('proyecto', $_GET['id'])['link_public']?>");
                  });

                  $(document).ready(function(){
                    $('#editar_p').click(function(){
                      id = <?php echo $_GET['id'];?>;
                      nombre_p = $('#nombre_temp_p').val();
                      nombre_p = nombre_p.charAt(0).toUpperCase() + nombre_p.slice(1);
                      fecha_p = $('#fecha_proy').val();
                      cliente_p = $('#clien').val();
                      descripcion_p=$('#descrip_temp_p').val();
                      descripcion_p = descripcion_p.charAt(0).toUpperCase() + descripcion_p.slice(1);

                      link_priv_p = $('#link_priv_p').val();
                      link_publico_p=$('#link_publico_p').val();

                      if (nombre_p && fecha_p && cliente_p && descripcion_p) {
                        cadena_p = "nombre_p=" + nombre_p + "&fecha_p=" + fecha_p + "&cliente_p=" + cliente_p + "&descripcion_p=" + descripcion_p + "&link_priv_p=" + link_priv_p + "&link_publico_p=" + link_publico_p + "&id=" + id;

                        $.ajax({
                          type:"POST",
                          url:"conAc_proyectoEditar.php",
                          data:cadena_p,
                          success:function(r){
                            if(r==1){
                              alertify.success("Proyecto editado.");
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

                  $('#editProyecto').on('hidden.bs.modal', function (e) {
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

                <h4><b>Información general del proyecto:</b></h4>
                
                <div class="col-xs-2">
                  <h5><b>Cliente: </b></h5>
                </div>
                <div class="col-xs-10">
                  <h5><?php echo $cliente['razon_social'];?></h5>
                </div>
                <div class="col-xs-2">
                  <h5><b>Proyecto: </b></h5>
                </div>
                <div class="col-xs-10">
                  <h5><?php echo $proyecto['nombre_proyecto'];?></h5>
                </div>
                <div class="col-xs-2">
                  <h5><b>Descripción: </b></h5>
                </div>
                <div class="col-xs-10">
                  <h5><?php echo $proyecto['descripcion'];?></h5>
                </div>
              </div>
              <hr>
              <div class="row">
                <h4><b>Links:</b></h4>
                <div class="col-xs-2">
                  <h5><b>Link IJS (privado): </b></h5>
                </div>
                <div class="col-xs-10">
                  <h5><a target = '_blank' href="<?php echo $proyecto['link_IJS'];?>"> <?php echo $proyecto['link_IJS'];?> </a></h5>
                </div>
                <div class="col-xs-2">
                  <h5><b>Link público: </b></h5>
                </div>
                <div class="col-xs-10">
                  <h5><a target = '_blank' href="<?php echo $proyecto['link_public'];?>"> <?php echo $proyecto['link_public'];?> </a></h5>
                </div>
              </div>
            </div>
            
            <?php if (current_user()['user_level'] <= 1) {?>
              <div class="col-xs-12" style="vertical-align:middle; text-align: center;">
                <br><hr>
                <svg class="bi bi-dot" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd" d="M8 9.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/>
                </svg>
                <br>
                <svg class="bi bi-dot" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd" d="M8 9.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/>
                </svg>
                <br>
                <svg class="bi bi-dot" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd" d="M8 9.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/>
                </svg>
                <br>
                <svg class="bi bi-dot" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd" d="M8 9.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/>
                </svg>
                <br>
                <svg class="bi bi-dot" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd" d="M8 9.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/>
                </svg>
                <br>
                <svg class="bi bi-dot" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd" d="M8 9.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/>
                </svg>
                <br><br>

                <a href="conAc_proyectoCierre.php?id=<?php echo $_GET['id'];?>" class="btn btn-danger btn-lg btn-block" title="Cierre del proyecto" data-toggle="tooltip"> Cierre del proyecto </a>
              </div>
            <?php } ?>
          </div>

        <!-- Solapa de Lista de materiales / insumos PROPIOS -->
          <div id="menu2" class="tab-pane fade">
            <br>
            <div class="col-xs-12">
              <div class="row">
                <div id="tablaMatInsuInt"></div>       
                <script>
                  $(document).ready(function(){
                    $('#tablaMatInsuInt').load('conAc_tablaMatInsu.php?id=<?php echo (int)$_GET['id']?>&int=1');
                  });
                </script>
              </div>
            </div>  
          </div>

        <!-- Solapa de Lista de materiales / insumos AJENOS -->
          <div id="menu5" class="tab-pane fade">
            <br>
            <div class="col-xs-12">
              <div class="row">
                <div id="tablaMatInsuExt"></div>       
                <script>
                  $(document).ready(function(){
                    $('#tablaMatInsuExt').load('conAc_tablaMatInsu.php?id=<?php echo (int)$_GET['id']?>&int=0');
                  });
                </script>
              </div>
            </div>  
          </div>

        <!-- Solapa de Personal -->
          <div id="menu4" class="tab-pane fade">
            <br>
            <div class="col-xs-12">
              <div class="row">
                <h4><b>Personal afectado</b></h4>
                <div>
                  &emsp;&emsp;<label>Asociar personal al proyecto:&emsp;&emsp;</label>
                </div>
                <div>
                  <div class="col-xs-3">
                    <select class="selectpicker" multiple title="Seleccione una o varias" id="cmb_pers" name="cmb_pers">
                      <?php foreach ($all_personal_noAfectado as $pers_noAfect):?>
                        <option value="<?php echo (int) $pers_noAfect['id'];?>">
                        <?php 
                          echo $pers_noAfect['apellido']." ".$pers_noAfect['nombre'];?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  &emsp;&emsp;<a class="btn btn-primary" id="btn_asociar" name="btn_asociar">Asociar</a>
                  <script>
                    $('#btn_asociar').click(function(){
                      var personal = new Array();
                      personal = Object.values($('#cmb_pers').val());

                      if (!(personal=="")){
                        id = <?php echo $_GET['id'];?>;

                        if (window.confirm("¿Seguro que desea agregar esta selección?")) {
                          var control = 1;
                          Object.entries(personal).forEach(([key, value]) => {
                            cadena_per = "personalTemp=" + value + "&id=" + id;

                            $.ajax({
                              type:"POST",
                              url:"conAc_proyectoAsociarPers.php",
                              data:cadena_per,
                              success:function(r){
                                if(r!=1){
                                  control = 0;
                                }
                              }
                            });
                          });
                          
                          if (control){
                            window.alert("Personal asociado correctamente.");
                            location.reload();
                          }
                          else
                            alertify.error("Error inesperado.");
                        } 
                      } else {
                        window.alert("Seleccione al menos un personal.");
                      }
                    });
                  </script>
                </div>

                <br><br>
                <div id="tablaPersonal"></div>   
                <script>
                  $(document).ready(function(){
                    $('#tablaPersonal').load('conAc_tablaPersonal.php?id=<?php echo (int)$_GET['id']?>');
                  });
                </script>
              </div>
            </div>  
          </div>

        <!-- Solapa de Planificación -->
          <div id="menu3" class="tab-pane fade">
            <br>
            <div class="row">
              <div class="col-xs-6">
                <h4><b>Planificación (Project):</b></h4>
              </div>
            </div>
            <div class="row">
              <div class="col-xs-12">
                <div class="pull-right">
                  <?php if (current_user()['user_level'] <= 1) {?>
                    <div class="pull-right">
                      <a href="conAc_agregarImagen.php?id=<?php echo $proyecto['id'];?>" class="btn btn-primary" data-toggle="tooltip"><span class="glyphicon glyphicon-download"></span> Agregar imagen Project </a>
                    </div>
                    <br><br><br>
                  <?php } ?>
                </div>
              </div>
            </div>

            <div id="galeriaProject"></div>       
            <script>
              $(document).ready(function(){
                $('#galeriaProject').load('conAc_tablaImagenes.php?id=<?php echo (int)$_GET['id']?>');
              });
            </script>

          </div>

        <!-- Solapa de Actividades -->
          <div id="menu6" class="tab-pane fade">
            <br>
            <div class="col-xs-12" >
              <div class="row">
                <h4><b>Actividades disponibles:</b></h4>
                <?php if (current_user()['user_level'] <= 1) {?>
                  <div>
                    <div class="col-md-5" >
                      <select class="form-control">
                        <?php foreach ($all_activ_estaticas as $activ): ?>
                          <option value="<?php echo (int) $activ['id'];?>">
                        <?php echo $activ['nivel']." - ".$activ['nombre'];?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                    <div class="col-md-3" >
                      <button type="button" class="btn btn-primary" onclick="javascript:openModalAgregAct()">
                        <span class="glyphicon glyphicon-plus-sign"></span>
                      </button>
                    </div>
                  </div>
                <?php } ?>

                <!-- Modal Agregar Actividad -->
                <div class="modal fade" id="modalAgregarActiv" tabindex="-1" role="dialog" aria-labelledby="modalAgregActtt" data-backdrop="static" data-keyboard="false" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                          <h4 class="modal-title" id="modalAgregActtt">Nueva Actividad</h4>
                      </div>
                      <div class="modal-body">
                        <div class="row">
                          <div class="col-md-3">
                            <label>Nivel:</label>
                            <input style="border:1px solid #000000" id="nivelAct_temp" class="form-control" placeholder="Nivel" required maxlength="50" onkeypress="return blockSpecialChar(event)">
                          </div>
                          <div class="col-md-7">
                            <label>Descripción:</label>
                            <input style="border:1px solid #000000" id="nombreAct_temp" class="form-control" placeholder="Descripcion" required maxlength="100" onkeypress="return blockSpecialChar(event)">
                          </div>
                          <div class="col-md-2">
                            <div class="pull-right">
                              <label>&emsp;</label><br>
                              <button type="button" class="btn btn-success" id="guardarNueva_act"><span class="glyphicon glyphicon-plus-sign"></span></button>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal" id="hechoAct">Hecho</button>
                      </div>
                    </div>
                  </div>
                </div>

                <script>
                  // CODIGO PARA ARREGLAR MODAL QUE MUEVE PANTALLA    VVVVV $$$
                  function openModalAgregAct() {
                    $("#modalAgregarActiv").modal("show");
                  }

                  $(function() { 
                    $('#modalAgregarActiv').on('hidden.bs.modal', function (e) {
                      $('nav.navbar').attr('style', 'background-color: #ff5733;padding:0;');
                      $(this)
                      .find("input,textarea,select, name, text")
                         .val('')
                         .end()
                      .find("input[type=checkbox], input[type=radio]")
                         .prop("checked", "")
                         .end();
                    })
                  }); 
                  // CODIGO PARA ARREGLAR MODAL QUE MUEVE PANTALLA   ^^^^^^ $$$

                  $(document).ready(function(){
                    $('#guardarNueva_act').click(function(){
                      nivel_act=$('#nivelAct_temp').val();
                      descrip_act = $('#nombreAct_temp').val();
                      descrip_act = descrip_act.charAt(0).toUpperCase() + descrip_act.slice(1);

                      if (nivel_act && descrip_act) {
                        cadena = "nivel_act=" + nivel_act + "&descrip_act=" + descrip_act + "&cod_proyecto=<?php echo $proyecto['id'];?>";
                        
                        $.ajax({
                          type:"POST",
                          url:"conAc_agregarActividadEstatica.php",
                          data:cadena,
                          success:function(r){
                            if(r==1){
                              alertify.success("Actividad agregada.");
                            }else{
                              alertify.error("Error inesperado.");
                            }
                          }
                        });
                      } else {
                        alertify.error("Complete todos los campos.");
                      }
                      document.getElementById("nivelAct_temp").value = "";
                      document.getElementById("nombreAct_temp").value = "";
                    });
                  });
                </script>
              </div> 
              <hr>
            </div>

            <div class="col-xs-12" >
              <div class="row">
                <h4><b>Libro diario:</b></h4>

                <div class="col-xs-12" >
                  <!-- <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalNuevaActivDelDia"> -->
                  <button type="button" class="btn btn-success" onclick="javascript:openModalActDia()">
                    <span class="glyphicon glyphicon-plus-sign"></span> Nueva Actividad del Día
                  </button>
                  <br><br>
                </div>
              </div>

              <div class="modal fade" id="modalNuevaActivDelDia" tabindex="-1" role="dialog" aria-labelledby="modalActDelDiaaa" data-backdrop="static" data-keyboard="false" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                      <h4 class="modal-title" id="modalActDelDiaaa">Nueva Actividad del Día</h4>
                    </div>
                    <div class="modal-body">
                      <h4>Campos obligatorios:</h4>
                      <div class="row">
                        <div class="col-md-12" >
                          <label>Actividad:</label><br>
                          <select class="form-control" id="actEstat" style="border:1px solid #000000" >
                            <?php foreach ($all_activ_estaticas as $activ): ?>
                              <option value="<?php echo (int) $activ['id'];?>">
                            <?php echo $activ['nivel']." - ".$activ['nombre'];?></option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                      </div>
                      <br>
                      <div class="row">
                        <div class="col-md-1">
                        </div>
                        <div class="col-md-5">
                          <label>Personal afectado:</label><br>
                          <select style="border:1px solid #000000" required class="selectpicker" multiple id="multiPers">
                            <?php  foreach ($all_personal_afectado as $un_afectado): 
                              $un_personal_afec = find_by_id('personal', $un_afectado['id_personal']);;?>
                              <option value="<?php echo (int) $un_afectado['id_personal']?>">
                                <?php echo $un_personal_afec['apellido'],' ',$un_personal_afec['nombre'];?>
                              </option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                      </div>
                      <br>
                      <div class="row">
                        <div class="col-md-1">
                        </div>
                        <div class="col-md-4">
                          <label>Fecha</label>
                          <input style="border:1px solid #000000" type="date" class="form-control" name="fecha_actdia" id="fecha_actdia" required onkeydown="return false">
                          <script>
                            var today = new Date();
                            var dd = today.getDate();
                            var mm = today.getMonth()+1; //January is 0!
                            var yyyy = today.getFullYear();
                             if(dd<10){
                                    dd='0'+dd
                                } 
                                if(mm<10){
                                    mm='0'+mm
                                } 

                            today = yyyy+'-'+mm+'-'+dd;
                            document.getElementById("fecha_actdia").setAttribute("max", today);
                          </script>
                        </div>
                        <div class="col-md-3">
                          <label>Hora inicio:</label>
                          <div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true" required>
                            <input type="text" class="form-control" name="hora_ini" id="hora_ini" readonly>
                            <span class="input-group-addon">
                              <span class="glyphicon glyphicon-time"></span>
                            </span>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <label>Hora fin:</label>
                          <div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true" required>
                            <input type="text" class="form-control" name="hora_fin" id="hora_fin" readonly>
                            <span class="input-group-addon">
                              <span class="glyphicon glyphicon-time"></span>
                            </span>
                          </div>
                          <script type="text/javascript">
                            $('.clockpicker').clockpicker();
                          </script>
                        </div>
                        
                      </div>

                      <hr style="border-color:black;">
                      <h4>Otros datos (Opcionales):</h4>
                      <div class="row">
                        <div class="col-md-12">
                          <label>Descripción:</label>
                          <textarea type="name" style="resize: none;" class="form-control" placeholder="Descripción" id="descrip_actDia" required maxlength="350" onkeypress="return blockSpecialChar(event)" onpaste="return false"></textarea>
                        </div>
                      </div>
                      <br>
                    </div>

                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                      <button type="button" class="btn btn-primary" id="nueva_actDiaria">Agregar</button>
                    </div>
                  </div>
                </div>
              </div>

              <script>
                function openModalActDia() {
                  $("#modalNuevaActivDelDia").modal("show");
                }

                $(function() { 
                  $('#modalNuevaActivDelDia').on('hidden.bs.modal', function (e) {
                    $('nav.navbar').attr('style', 'background-color: #ff5733;padding:0;');
                    $(this)
                    .find("input,textarea,select, name, text")
                       .val('')
                       .end()
                    .find("input[type=checkbox], input[type=radio]")
                       .prop("checked", "")
                       .end();
                  })
                }); 

                $(document).ready(function(){
                  $('#nueva_actDiaria').click(function(){
                    id = <?php echo $_GET['id'];?>;
                    fecha_act = $('#fecha_actdia').val();
                    descrip_act=$('#descrip_actDia').val();
                    descrip_act = descrip_act.charAt(0).toUpperCase() + descrip_act.slice(1);

                    var hora_i = document.getElementById("hora_ini").value.split(":");
                    var hora_f = document.getElementById("hora_fin").value.split(":");
                    var horaOK = true;

                    if(Number(hora_f[0]) < Number(hora_i[0])){
                      horaOK = false;
                    } else {
                      if(Number(hora_f[0]) == Number(hora_i[0])) {
                        if(Number(hora_f[1]) <= Number(hora_i[1])){
                          horaOK = false;
                        }
                      }
                    }

                    pers_afect_act = $('#multiPers').val();
                    act_estat = $('#actEstat').val();
                    hora_inicioOK = $('#hora_ini').val();
                    hora_finOK = $('#hora_fin').val();

                    if (hora_i != '' && hora_f != '' && pers_afect_act != '' && act_estat && fecha_act) {
                      if (horaOK) {
                        cadena_actDia = "proyectID=" + id + "&hora_i=" + hora_inicioOK + "&hora_f=" + hora_finOK + "&pers_afect_act=" + pers_afect_act + "&act_estat=" + act_estat + "&fecha_act=" + fecha_act + "&descrip_act=" + descrip_act;

                        $.ajax({
                          type:"POST",
                          url:"conAc_agregarActividadDiaria.php",
                          data:cadena_actDia,
                          success:function(r){
                            if(r!=1){
                              $('#tablaDiario').load('conAc_tablaDiario.php?id=<?php echo (int)$_GET['id']?>');
                              alertify.success("Actividad agregada.");
                              redirect('conAc_proyecto.php',false);
                            }else{
                              alertify.error("Errorrr.");
                            }
                          }
                        });
                      } else {
                        alertify.warning("La hora de fin tiene que ser superior a la de inicio.");
                      }
                    } else {
                      alertify.error("Complete todos los campos.");
                    }
                  });
                });

                function blockSpecialChar(e) {
                  var k = e.keyCode;
                  return (!(k == 34 || k == 39 || k == 13));
                };
              </script>
              <!-- ------------------- -->

              <div id="tablaDiario"></div>   
              <script>
                $(document).ready(function(){
                  $('#tablaDiario').load('conAc_tablaDiario.php?id=<?php echo (int)$_GET['id']?>');
                });
              </script> 
            </div>
          </div>

        <!-- Solapa de Avances -->
          <div id="menu7" class="tab-pane fade">
            <br>
            <div class="col-xs-12" >
              <div class="row">
                <h4><b>Avances por Actividad:</b></h4>
              </div>

              <div id="tablaAvances"></div>   
              <script>
                $(document).ready(function(){
                  $('#tablaAvances').load('conAc_tablaAvances.php?id=<?php echo (int)$_GET['id']?>');
                });
              </script> 
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
  
  <script>
    $(document).ready(function(){
      $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
        localStorage.setItem('activeTab', $(e.target).attr('href'));
      });

      var activeTab = localStorage.getItem('activeTab');

      if(activeTab){
        $('#myTab a[href="' + activeTab + '"]').tab('show');
      }
    });
  </script>
<?php include_once('layouts/footer.php'); ?>