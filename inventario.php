<?php
  $page_title = 'Inventario / Stock';
  require_once('includes/load.php');
  page_require_level(2);
  $current_user_ok = current_user();

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

<h2><b>Inventario</b></h2>


<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>

<ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#menu1">Materiales / Insumos</a></li>
  <li><a data-toggle="tab" href="#menu2">Herramientas</a></li>
  <li><a data-toggle="tab" href="#menu3">Maquinarias</a></li>
  <li><a data-toggle="tab" href="#menu4">Instrumentos</a></li>
</ul>

<div class="tab-content">
<!-- Solapa de Materiales -->
  <div id="menu1" class="tab-pane fade in active">
    <br>
    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modalNuevoMatInsu">
      <span class="glyphicon glyphicon-plus-sign"></span> Nuevo Material / Insumo
    </button>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalFaltanteMatInsu">
      <span class="glyphicon glyphicon-info-sign"></span> Ver faltante
    </button>
    <a href="inventario_movimMatInsu.php" class="btn btn-primary" title="Movimientos de Materiales / Insumos" data-toggle="tooltip">
      <span class="glyphicon glyphicon-info-sign"></span> Ver movimientos </a>

    <!-- Modal Nuevo Material / Insumo -->
    <div class="modal fade bd-example-modal-lg" id="modalNuevoMatInsu" tabindex="-1" role="dialog" aria-labelledby="modalMatInsuu" data-backdrop="static" data-keyboard="false" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
              <h4 class="modal-title" id="modalMatInsuu">Nuevo Material / Insumo</h4>
          </div>
          <div class="modal-body">
            <h4>Campos obligatorios:</h4>
            <div class="row">
              <div class="col-md-6">
                <label>Marca:</label>
                <input style="border:1px solid #000000" type="name" id="marca_temp_mi" class="form-control" placeholder="Marca" required maxlength="100" onkeypress="return blockSpecialChar(event)">
              </div>
              <div class="col-md-6">
                <label>Unidad:</label>
                <select style="border:1px solid #000000" required class="form-control" name="unidad_temp_mi" id="unidad_temp_mi">
                  <option value="" disabled selected>Seleccione una unidad</option>
                  <option value="1"> Unidades </option>
                  <option value="2"> Kilos </option>
                  <option value="3"> Metros </option>
                  <option value="4"> Litros </option>
                  <option value="5"> Cajas / Paquetes </option>
                </select>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-12">
                <label>Descripción:</label>
                <textarea style="resize: none; border:1px solid #000000" type="name" class="form-control" placeholder="Descripción" id="descrip_temp_mi" required maxlength="350" onkeypress="return blockSpecialChar(event)" onpaste="return false"></textarea>
              </div>
            </div>
            <hr style="border-color:black;">
            <h4>Otros datos (Opcionales):</h4>
            <div class="row">
              <div class="col-md-4">
                <label>Tipo o Modelo:</label>
                <input type="name" class="form-control" id="tipo_temp_mi" placeholder="Tipo" maxlength="90" onkeypress="return blockSpecialChar(event)">
              </div>
              <div class="col-md-3">
                <label>Cod.:</label>
                <input type="name" class="form-control" id="cod_temp_mi" placeholder="Cod." maxlength="50" onkeypress="return blockSpecialChar(event)">
              </div>
              <div class="col-md-3">
                <label>Precio de lista en dólares:</label>
                <input type="name" class="form-control" placeholder="Precio de lista" id="precio_temp_mi" value="0,0" title="Sólo números" onchange="javascript:precio_mi_change()">
              </div>
              <script>
                function precio_mi_change(){
                  var precio_reemp_mi = document.getElementById("precio_temp_mi").value;
                  precio_reemp_mi = precio_reemp_mi.replace(",", ".");
                  precio_reemp_mi = parseFloat(precio_reemp_mi).toFixed(3);
                  if (isNaN(precio_reemp_mi)){
                    precio_temp_mi.value = "";
                    alertify.error("El monto ingresado no es válido.");
                  }
                  else 
                    precio_temp_mi.value = precio_reemp_mi.replace(".", ",");
                };
              </script>
              <div class="col-md-2">
                <label>Cant. mínima:</label>
                <input class="form-control" name="cant_min_temp_mi" id="cant_min_temp_mi" type="number" min="0" value="0" required onchange="javascript:cantidad_min_mi_change()">
              </div>
              <script>
                function cantidad_min_mi_change(){
                  var cant_min_mi = parseInt(document.getElementById("cant_min_temp_mi").value);
                  if (Number.isInteger(cant_min_mi))
                    cant_min_temp_mi.value = cant_min_mi;
                  else
                    cant_min_temp_mi.value = "";
                };
              </script>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-primary" data-dismiss="modal" id="guardarnuevo_mi">Agregar</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Faltante -->
    <div class="modal fade bd-example-modal-lg" id="modalFaltanteMatInsu" tabindex="-1" role="dialog" aria-labelledby="modalFaltaMatIns" data-backdrop="static" data-keyboard="false" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
              <h4 class="modal-title" id="modalFaltaMatIns">Faltante según stock mínimo</h4>
          </div>
          <div class="modal-body">
            <div id="tablaFaltaMatInsu"></div>
          </div>
          <script>
            $(document).ready(function(){
              $('#tablaFaltaMatInsu').load('inventario_tablaFaltaMatInsu.php');
            });
          </script>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>

    <script>
      $('#modalFaltanteMatInsu').on('hidden.bs.modal', function () {
        location.reload();
      });

      $(document).ready(function(){
        $('#todosss2').hide();
        $('#tablaMatsInsu').load('inventario_tablaMatInsu.php?todos=1');//lo inverti para lucas, valor original 0
      });

      $(document).ready(function(){
        $('#guardarnuevo_mi').click(function(){
          marca_mi=$('#marca_temp_mi').val();
          marca_mi = marca_mi.charAt(0).toUpperCase() + marca_mi.slice(1);
          tipo_mi=$('#tipo_temp_mi').val();
          tipo_mi = tipo_mi.charAt(0).toUpperCase() + tipo_mi.slice(1);
          cantidad_min_mi = $('#cant_min_temp_mi').val();
          
          descripcion_mi=$('#descrip_temp_mi').val();
          descripcion_mi = descripcion_mi.charAt(0).toUpperCase() + descripcion_mi.slice(1);
          precio_mi=$('#precio_temp_mi').val().replace(",", ".");
          unidad_mi=null;
          if (!($('#unidad_temp_mi').val() == '0')) {
            switch ($('#unidad_temp_mi').val()){
              case "1":
                unidad_mi="Uds.";
                break;
              case "2":
                unidad_mi="Kgs.";
                break;
              case "3":
                unidad_mi="Mts.";
                break;
              case "4":
                unidad_mi="Lts.";
                break;
              case "5":
                unidad_mi="Caj. / Paq.";
                break;
            }
          }
          cod_mi=$('#cod_temp_mi').val();

          if (marca_mi && descripcion_mi && unidad_mi) {
            cadena= "marca_mi=" + marca_mi + "&tipo_mi=" + tipo_mi + "&descripcion_mi=" + descripcion_mi + "&precio_mi=" + precio_mi + "&unidad_mi=" + unidad_mi+ "&cod_mi=" + cod_mi + "&cantidad_min_mi=" + cantidad_min_mi;
            
            $.ajax({
              type:"POST",
              url:"inventario_agregarMatInsu.php",
              data:cadena,
              success:function(r){
                if(r==1){
                  location.reload();
                  //$('#tablaMatsInsu').load('inventario_tablaMatInsu.php?todos=0');
                  alertify.success("Material / Insumo agregado.");
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

      $('#modalNuevoMatInsu').on('hidden.bs.modal', function (e) {
        $(this)
          .find("input,textarea,select, name, text")
             .val('')
             .end()
          .find("input[type=checkbox], input[type=radio]")
             .prop("checked", "")
             .end();
      })
    </script>

    <div class="pull-right">
      <?php if ($current_user_ok['user_level'] <= 1 || $current_user_ok['id'] == 15) {?>
        <a href="inventario_ingresoMatInsu.php" class="btn btn-success"  title="Nuevo Ingreso de Materiales / Insumos" data-toggle="tooltip">
          <span class="glyphicon glyphicon-log-in"></span>&emsp;Ingreso</a>
        <a href="inventario_egresoMatInsu.php" class="btn btn-danger" title="Nuevo Egreso de Materiales / Insumos" data-toggle="tooltip">
          <span class="glyphicon glyphicon-log-out"></span>&emsp;Egreso</a>
      <?php } ?>
    </div>
    
    <br><br>
    <h5 id="todosss"><input type="checkbox" id="chkbox_todos" value="" onClick="verTodos()"> <label>Ver todos los items</label></h5>
    <h5 id="todosss2"><label>Mostrando todos los materiales e insumos</label></h5>

    

    <script>
      function verTodos() {
        if (document.getElementById("chkbox_todos").checked) {
          $('#todosss').hide();
          $('#tablaMatsInsu').load('inventario_tablaMatInsu.php?todos=0');//lo inverti para lucas, valor original 1
          $('#todosss2').show();
        }
        else {
          $('#tablaMatsInsu').load('inventario_tablaMatInsu.php?todos=1');//lo inverti para lucas, valor original 0
        }
      }
    </script>

    <div id="tablaMatsInsu"></div>

  </div>

<!-- Solapa de Herramientas -->
  <div id="menu2" class="tab-pane fade">
    <br>
    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modalNuevaHerram">
      <span class="glyphicon glyphicon-plus-sign"></span> Nueva Herramienta
    </button>

    <br><br>
    <div id="tablaHerram"></div>

    <!-- Modal nueva Herramienta -->
    <div class="modal fade bd-example-modal-lg" id="modalNuevaHerram" tabindex="-1" role="dialog" aria-labelledby="modalHerramm" data-backdrop="static" data-keyboard="false" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
              <h4 class="modal-title" id="modalHerramm">Nueva Herramienta</h4>
          </div>
          <div class="modal-body">
            <h4>Campos obligatorios:</h4>
            <div class="row">
              <div class="col-md-8">
                <label>Marca:</label>
                <input style="border:1px solid #000000" type="name" id="marca_temp_h" class="form-control" placeholder="Marca" required maxlength="100" onkeypress="return blockSpecialChar(event)">
              </div>
              <div class="col-md-4">
                <label>Cantidad:</label>
                <input style="border:1px solid #000000" class="form-control" name="cantNueva_h" id="cantNueva_h" type="number" min="1" value="1" required onchange="javascript:cantidad_h_change()">
              </div>
              <script>
                function cantidad_h_change(){
                  var cant_h = parseInt(document.getElementById("cantNueva_h").value);
                  if (Number.isInteger(cant_h))
                    cantNueva_h.value = cant_h;
                  else
                    cantNueva_h.value = "";
                };
              </script>
            </div>
            <br>
            <div class="row">
              <div class="col-md-12">
                <label>Descripción:</label>
                <textarea style="resize: none; border:1px solid #000000" type="name" class="form-control" placeholder="Descripción" id="descrip_temp_h" required maxlength="350" onkeypress="return blockSpecialChar(event)" onpaste="return false"></textarea>
              </div>
            </div>
            <hr style="border-color:black;">
            <h4>Otros datos (Opcionales):</h4>
            <div class="row">
              <div class="col-md-8">
                <label>Tipo o Modelo:</label>
                <input type="name" class="form-control" id="tipo_temp_h" placeholder="Tipo" maxlength="255" onkeypress="return blockSpecialChar(event)">
              </div>
              <div class="col-md-4">
                <label>Cod.:</label>
                <input type="name" class="form-control" id="cod_temp_h" placeholder="Cod." maxlength="50" onkeypress="return blockSpecialChar(event)">
              </div>
            </div>
            <br>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-primary" data-dismiss="modal" id="guardarnuevo_h">Agregar</button>
          </div>
        </div>
      </div>
    </div>

    <script>
      $(document).ready(function(){
        $('#tablaHerram').load('inventario_tablaHerram.php');
      });

      $(document).ready(function(){
        $('#guardarnuevo_h').click(function(){
          marca_h = $('#marca_temp_h').val();
          marca_h = marca_h.charAt(0).toUpperCase() + marca_h.slice(1);
          cantidad_h = $('#cantNueva_h').val();
          tipo_h = $('#tipo_temp_h').val();
          tipo_h = tipo_h.charAt(0).toUpperCase() + tipo_h.slice(1);
          cod_h=$('#cod_temp_h').val();
          descripcion_h = $('#descrip_temp_h').val();
          descripcion_h = descripcion_h.charAt(0).toUpperCase() + descripcion_h.slice(1);

          if (marca_h && descripcion_h && cantidad_h) {
            cadena_h = "marca_h=" + marca_h + "&tipo_h=" + tipo_h + "&cantidad_h=" + cantidad_h + "&cod_h=" + cod_h + "&descripcion_h=" + descripcion_h;

            $.ajax({
              type:"POST",
              url:"inventario_agregarHerram.php",
              data:cadena_h,
              success:function(r){
                if(r==1){
                  $('#tablaHerram').load('inventario_tablaHerram.php');
                  alertify.success("Herramienta agregada.");
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

      $('#modalNuevaHerram').on('hidden.bs.modal', function (e) {
        $(this)
          .find("input,textarea,select, name, text")
             .val('')
             .end()
          .find("input[type=checkbox], input[type=radio]")
             .prop("checked", "")
             .end();
      })
    </script>
  </div>

<!-- Solapa de Maquinarias -->
  <div id="menu3" class="tab-pane fade">
    <br>
    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modalNuevaMaquin">
      <span class="glyphicon glyphicon-plus-sign"></span> Nueva Maquinaria
    </button>

    <br><br>
    <div id="tablaMaquin"></div>

    <!-- Modal -->
    <div class="modal fade bd-example-modal-lg" id="modalNuevaMaquin" tabindex="-1" role="dialog" aria-labelledby="modalMaquinnn" data-backdrop="static" data-keyboard="false" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
              <h4 class="modal-title" id="modalMaquinnn">Nueva Maquinaria</h4>
          </div>
          <div class="modal-body">
            <h4>Campos obligatorios:</h4>
            <div class="row">
              <div class="col-md-5">
                <label>Marca:</label>
                <input style="border:1px solid #000000" type="name" id="marca_temp_mqi" class="form-control" placeholder="Marca" required maxlength="100" onkeypress="return blockSpecialChar(event)">
              </div>
              <div class="col-md-4">
                <label>Modelo:</label>
                <input style="border:1px solid #000000" type="name" class="form-control" id="modelo_temp_mqi" placeholder="Modelo" maxlength="255" onkeypress="return blockSpecialChar(event)">
              </div>
              <div class="col-md-3">
                <label>Tipo:</label>
                <select style="border:1px solid #000000" required class="form-control" name="tipo_temp_mqi" id="tipo_temp_mqi">
                  <option value="" disabled selected>Seleccione un tipo</option>
                  <option value="1"> Eléctrico/a </option>
                  <option value="2"> Hidráulico/a </option>
                  <option value="3"> Electro-Hidráulico/a </option>
                  <option value="4"> Mecánico/a </option>
                  <option value="5"> Neumático/a </option>
                </select>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-12">
                <label>Descripción:</label>
                <textarea style="resize: none; border:1px solid #000000" type="name" class="form-control" placeholder="Descripción" id="descrip_temp_mqi" required maxlength="350" onkeypress="return blockSpecialChar(event)" onpaste="return false"></textarea>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-3">
                <label>Año:</label>
                <input style="border:1px solid #000000" type="number" id="anio_temp_mqi" name="anio_temp_mqi" class="form-control" placeholder="Año" min="1980" value="2020" onkeydown="return false">
                <script>
                  document.querySelector("input[name=anio_temp_mqi]")
                  .oninput = e => console.log(new Date(e.target.valueAsNumber, 0, 1))

                  var today = new Date();
                  var yyyy = today.getFullYear();
                  document.getElementById("anio_temp_mqi").setAttribute("max", yyyy);

                  $("[id='anio_temp_mqi']").keypress(function (evt){ evt.preventDefault(); });
                </script>
              </div>
              <div class="col-md-3">
                <label>Estado actual:</label>
                <select style="border:1px solid #000000" required class="form-control" name="estado_temp_mqi" id="estado_temp_mqi">
                  <option value="" disabled selected>Seleccione un estado</option>
                  <option value="1"> Muy malo </option>
                  <option value="2"> Malo </option>
                  <option value="3"> Regular </option>
                  <option value="4"> Bueno </option>
                  <option value="5"> Muy bueno </option>
                </select>
              </div>
              <div class="col-md-6">
                <label>Detalles del estado actual:</label>
                <input style="border:1px solid #000000" type="name" id="detalle_estado_temp_mqi" class="form-control" placeholder="Detalles del estado actual" required maxlength="350" onkeypress="return blockSpecialChar(event)">
              </div>
            </div>
            <hr style="border-color:black;">
            <h4>Otros datos (Opcionales):</h4>
            <div class="row">
              <div class="col-md-4">
                <label>Nº serie:</label>
                <input type="name" class="form-control" id="nSerie_temp_mqi" placeholder="Nº serie" maxlength="100" onkeypress="return blockSpecialChar(event)">
              </div>
            </div>
            <br>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-primary" data-dismiss="modal" id="guardarnuevo_mqi">Agregar</button>
          </div>
        </div>
      </div>
    </div>

    <script>
      $(document).ready(function(){
        $('#tablaMaquin').load('inventario_tablaMaquin.php');
      });

      $(document).ready(function(){
        $('#guardarnuevo_mqi').click(function(){
          marca_mqi = $('#marca_temp_mqi').val();
          marca_mqi = marca_mqi.charAt(0).toUpperCase() + marca_mqi.slice(1);
          modelo_mqi = $('#modelo_temp_mqi').val();
          modelo_mqi = modelo_mqi.charAt(0).toUpperCase() + modelo_mqi.slice(1);
          descripcion_mqi = $('#descrip_temp_mqi').val();
          descripcion_mqi = descripcion_mqi.charAt(0).toUpperCase() + descripcion_mqi.slice(1);
          tipo_mqi=null;
          if (!($('#tipo_temp_mqi').val() == '0')) {
            switch ($('#tipo_temp_mqi').val()){
              case "1":
                tipo_mqi="Eléctrico/a";
                break;
              case "2":
                tipo_mqi="Hidráulico/a";
                break;
              case "3":
                tipo_mqi="Electro-Hidráulico/a";
                break;
              case "4":
                tipo_mqi="Mecánico/a";
                break;
              case "5":
                tipo_mqi="Neumático/a";
                break;
            }
          }
          estado_mqi=null;
          if (!($('#estado_temp_mqi').val() == '0')) {
            switch ($('#estado_temp_mqi').val()){
              case "1":
                estado_mqi="Muy malo";
                break;
              case "2":
                estado_mqi="Malo";
                break;
              case "3":
                estado_mqi="Regular";
                break;
              case "4":
                estado_mqi="Bueno";
                break;
              case "5":
                estado_mqi="Muy bueno";
                break;
            }
          }
          yyyy_mqi = $('#anio_temp_mqi').val();
          nSerie_mqi=$('#nSerie_temp_mqi').val();
          detalle_estado_mqi = $('#detalle_estado_temp_mqi').val();
          detalle_estado_mqi = detalle_estado_mqi.charAt(0).toUpperCase() + detalle_estado_mqi.slice(1);

          if (marca_mqi && modelo_mqi && descripcion_mqi && tipo_mqi && estado_mqi && detalle_estado_mqi && yyyy_mqi) {
            cadena_mqi = "marca_mqi=" + marca_mqi + "&modelo_mqi=" + modelo_mqi + "&descripcion_mqi=" + descripcion_mqi + "&tipo_mqi=" + tipo_mqi + "&estado_mqi=" + estado_mqi + "&yyyy_mqi=" + yyyy_mqi + "&nSerie_mqi=" + nSerie_mqi + "&detalle_estado_mqi=" + detalle_estado_mqi;

            $.ajax({
              type:"POST",
              url:"inventario_agregarMaquin.php",
              data:cadena_mqi,
              success:function(r){
                if(r==1){
                  $('#tablaMaquin').load('inventario_tablaMaquin.php');
                  alertify.success("Maquinaria agregada.");
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

      $('#modalNuevaMaquin').on('hidden.bs.modal', function (e) {
        $(this)
          .find("input,textarea,select, name, text")
            .val('')
            .end()
          .find("input[type=checkbox], input[type=radio]")
            .prop("checked", "")
            .end();
      })
    </script>
  </div>

<!-- Solapa de Instrumentos de medición -->
  <div id="menu4" class="tab-pane fade">
    <br>
    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modalNuevoInstrum">
      <span class="glyphicon glyphicon-plus-sign"></span> Nuevo Instrumento
    </button>

    <br><br>
    <div id="tablaInstrum"></div>

    <!-- Modal nuevo Instrumento -->
    <div class="modal fade bd-example-modal-lg" id="modalNuevoInstrum" tabindex="-1" role="dialog" aria-labelledby="modalInstrummm" data-backdrop="static" data-keyboard="false" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
              <h4 class="modal-title" id="modalInstrummm">Nuevo Instrumento</h4>
          </div>
          <div class="modal-body">
            <h4>Campos obligatorios:</h4>
            <div class="row">
              <div class="col-md-5">
                <label>Marca:</label>
                <input style="border:1px solid #000000" type="name" id="marca_temp_inst" class="form-control" required maxlength="100" title="Sólo letras o números" onkeypress="return blockSpecialChar(event)">
              </div>
              <div class="col-md-4">
                <label>Modelo:</label>
                <input style="border:1px solid #000000" type="name" required class="form-control" id="modelo_temp_inst" placeholder="Modelo" maxlength="100" title="Sólo letras o números" onkeypress="return blockSpecialChar(event)">
              </div>
              <div class="col-md-3">
                <label>Fecha de calibración</label>
                <input style="border:1px solid #000000" type="date" class="form-control" name="fecha_inst" id="fecha_inst" required onkeydown="return false">
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
                  document.getElementById("fecha_inst").setAttribute("max", today);
                </script>
              </div>
            </div>
            <hr style="border-color:black;">
            <h4>Otros datos (Opcionales):</h4>
            <div class="row">
              <div class="col-md-4">
                <label>Nº serie:</label>
                <input type="name" class="form-control" id="nSerie_temp_inst" placeholder="Nº serie" maxlength="50" title="No es un formato aceptado" onkeypress="return blockSpecialChar(event)">
              </div>
            </div>
            <br>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-primary" data-dismiss="modal" id="guardarnuevo_inst">Agregar</button>
          </div>
        </div>
      </div>
    </div>

    <script>
      $(document).ready(function(){
        $('#tablaInstrum').load('inventario_tablaInstrum.php');
      });

      $(document).ready(function(){
        $('#guardarnuevo_inst').click(function(){
          marca_inst = $('#marca_temp_inst').val();
          marca_inst = marca_inst.charAt(0).toUpperCase() + marca_inst.slice(1);
          modelo_inst = $('#modelo_temp_inst').val();
          modelo_inst = modelo_inst.charAt(0).toUpperCase() + modelo_inst.slice(1);
          fecha_inst = $('#fecha_inst').val();

          nSerie_inst=$('#nSerie_temp_inst').val();

          if (marca_inst && modelo_inst && fecha_inst) {
            cadena_inst = "marca_inst=" + marca_inst + "&modelo_inst=" + modelo_inst + "&fecha_inst=" + fecha_inst + "&nSerie_inst=" + nSerie_inst;

            $.ajax({
              type:"POST",
              url:"inventario_agregarInstrum.php",
              data:cadena_inst,
              success:function(r){
                if(r==1){
                  $('#tablaInstrum').load('inventario_tablaInstrum.php');
                  alertify.success("Instrumento agregado.");
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

      $('#modalNuevoInstrum').on('hidden.bs.modal', function (e) {
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
        return (!(k == 34 || k == 39 || k == 13));
      };
    </script>
  </div>
</div>
  
<?php include_once('layouts/footer.php'); ?>