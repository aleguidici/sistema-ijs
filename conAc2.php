<?php
  $page_title = 'Proyectos';
  require_once('includes/load.php');
  page_require_level(2);
  $current_user_ok = current_user();
  $all_clientes = find_all('cliente');

?>

<?php include_once('layouts/newHeader.php'); ?>

<style type="text/css">
.loader2 {
  position: fixed;
  left: 0px;
  top: 0px;
  width: 100%;
  height: 100%;
  z-index: 9999;
  background: rgb(5, 5, 5);
  opacity: .5;
}
</style>
<script>
$(window).load(function() {
//    $(".loader").fadeOut(1750);
//    $('#cargando').html('<div class="loader"><br><p style="font-size: 24px;position: absolute;left: 40%;top: 57%;">Cargando, por favor espere...</p></div>');
});
  
</script>
<!--<div id="cargando" class="loader"></div>
<div id="loader2" class="loader2" hidden></div>-->
<!--<div class="loader"></div>-->

<h2><b>Trabajos</b></h2>

<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>

<ul class="nav nav-tabs" id="myTab">
  <li class="active">
    <a data-toggle="tab" role="tab" href="#menu1" onclick="javascript:reloadPage();">Proyectos</a>
  </li>
  <!-- <li><a data-toggle="tab" href="#menu2">Órdenes de servicio</a></li> -->
  <li>
    <a data-toggle="tab" role="tab" href="#menu3" onclick="javascript:reloadPage();">Mediciones de PaT</a>
  </li>
  <li>
    <a data-toggle="tab" role="tab" href="#menu4" onclick="javascript:reloadPage();">Máquinas Eléctricas</a>
  </li>
</ul>
 
<div class="tab-content">
<!-- Solapa de Proyectos -->
  <div id="menu1" class="tab-pane fade in active">
    <br>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalNuevoProyecto">
      <span class="glyphicon glyphicon-plus-sign"></span> Nuevo Proyecto
    </button>

    <br><br>
    <div id="tablaProyectos"></div>

    <!-- Modal nuevo Proyecto -->
    <div class="modal fade bd-example-modal-lg" id="modalNuevoProyecto" tabindex="-1" role="dialog" aria-labelledby="modalProyectooo" data-backdrop="static" data-keyboard="false" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="modalProyectooo">Nuevo Proyecto</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            
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
                <label>Cliente:</label>
                <select class="selectpicker" data-show-subtext="true" data-live-search="true" name="clien" id="clien" required="required" data-width="100%">
                  <option value="" disabled selected>Seleccione un Cliente</option>
                  <?php  foreach ($all_clientes as $un_cli): ?>
                    <option value="<?php echo (int) $un_cli['id']?>">
                      <?php if(!empty($un_cli['num_suc']))
                        echo 'Sucursal Nº ',$un_cli['num_suc'] , ' - ';
                      echo $un_cli['razon_social'], ' - ',  $un_cli['direccion'] , ' - ',  $un_cli['localidad'], ' - ', utf8_encode(find_by_id_prov('provincia', $un_cli['provincia'])['nombre'])?>
                    </option>
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
            <button type="button" class="btn btn-primary" data-dismiss="modal" id="guardarnuevo_p">Agregar</button>
          </div>
        </div>
      </div>
    </div>

    <script>
      $(document).ready(function(){
        $('#tablaProyectos').load('conAc_tablaProyectos.php');
      });

      $(document).ready(function(){
        $('#guardarnuevo_p').click(function(){
          nombre_p = $('#nombre_temp_p').val();
          nombre_p = nombre_p.charAt(0).toUpperCase() + nombre_p.slice(1);
          fecha_p = $('#fecha_proy').val();
          cliente_p = $('#clien').val();
          descripcion_p=$('#descrip_temp_p').val();
          descripcion_p = descripcion_p.charAt(0).toUpperCase() + descripcion_p.slice(1);

          link_priv_p = $('#link_priv_p').val();
          link_publico_p=$('#link_publico_p').val();

          if (nombre_p && fecha_p && cliente_p && descripcion_p) {
            cadena_p = "nombre_p=" + nombre_p + "&fecha_p=" + fecha_p + "&cliente_p=" + cliente_p + "&descripcion_p=" + descripcion_p + "&link_priv_p=" + link_priv_p + "&link_publico_p=" + link_publico_p;

            $.ajax({
              type:"POST",
              url:"conAc_proyectoNuevo.php",
              data:cadena_p,
              success:function(r){
                if(r==1){
                  $('#tablaProyectos').load('conAc_tablaProyectos.php');
                  alertify.success("Proyecto agregado.");
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

      $('#modalNuevoProyecto').on('hidden.bs.modal', function (e) {
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

      function irClientesMe(){
        location.href = "clientes.php?tabclieme=2";
      }

      function reloadPage() {
        location.reload();
      }

      function goGestion() {
        var password = document.getElementById('inp_pw-access').value;
        if (password) {
          if (password == "2353") {
            location.replace('reparacion_informes.php');
          } else {
            alertify.error("Lo siento, clave incorrecta. Intente nuevamente.");
            document.getElementById('inp_pw-access').value = "";
          }
        } else {
          alertify.warning("Por favor, ingrese una clave.");
        }
      }
      
    </script>
  </div>

<!-- Solapa de Mediciones de PaT -->
  <div id="menu3" class="tab-pane fade">
    <br>
    <?php if ($current_user_ok['user_level'] <= 2) {?>
      <a href="instrumento.php" class="btn btn-primary"><span class="glyphicon glyphicon-plus-sign"></span> Nuevo Instrumento</a>
      <a href="conAc_clienteMedicion.php" class="btn btn-primary"><span class="glyphicon glyphicon-plus-sign"></span> Nueva Medición</a>
    <?php } ?>
    
    <div class="pull-right">
      <a href="conAc_medicionesBusqueda.php" class="btn btn-warning">Búsqueda avanzada</a>
    </div>

    <br><br>
    <div id="tablaMedicionesPaT"></div>       
    <script>
      $(document).ready(function(){
        $('#tablaMedicionesPaT').load('conAc_tablaMediciones.php');
      });
    </script> 
  </div>

<!-- Solapa de Máquinas eléctricas -->
  <div id="menu4" class="tab-pane fade">
    <br>
    <a href="conAc_agregarMaquinaCli.php" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span> Nuevo Ingreso</a>
    <!--<a href="conAc_agregarMaquinaCli.php" class="btn btn-primary"><span class="glyphicon glyphicon-plus-sign"></span> Nueva Máquina Eléctrica</a>
    <a href="conAc_agregarReparacionMaquinaSelect.php" class="btn btn-primary"><span class="glyphicon glyphicon-plus-sign"></span> <span class="glyphicon glyphicon glyphicon-wrench"></span> Nueva Reparación</a>-->
    
    <div class="pull-right">
      <div class="btn-group">
        <!--<button class="btn btn-info" onclick="javascript:goGestion();"><span class="glyphicon glyphicon-new-window"> </span> Gestión </button></div>&emsp;-->
        <a href="#" type="button" class="btn btn-info" id="a_modal-control-access" data-toggle="modal" data-target="#modal-control-access"><span class="glyphicon glyphicon-new-window"></span> Gestión </a>&emsp;
      </div>
      <div class="btn-group btn-group-toggle" data-toggle="buttons">
        <label class="btn btn-warning active">
          <input type="radio" name="options" id="option1" autocomplete="off" onchange="javascript:minTablaMaquinas();" checked>Vista normal <span class="glyphicon glyphicon-resize-small"> </span>
        </label>
        <label class="btn btn-warning">
          <input type="radio" name="options" id="option2" autocomplete="off" onchange="javascript:fullTablaMaquinas();"> <span class="glyphicon glyphicon-resize-full"> </span> Vista completa 
        </label>
      </div>
     <!-- <a href="" class="btn btn-warning">Mostrar todas las reparaciones</a> -->
    </div>

    <br><br>

    <div id="tablaArreglosMaquinasElec"></div>       
    <script>
      $(document).ready(function() {
        $('#tablaArreglosMaquinasElec').load('conAc_tablaArreglosMaquinasElec.php?full=0');
      });
      function minTablaMaquinas() {
        $('.loader2').show();
        alertify.success("Cargando vista normal, por favor espere.");
        $('.loader2').fadeOut(2150);
        $('#tablaArreglosMaquinasElec').fadeOut(1000);
        $('#tablaArreglosMaquinasElec').fadeIn(1000);
        $('#tablaArreglosMaquinasElec').load('conAc_tablaArreglosMaquinasElec.php?full=0');
      }

      function fullTablaMaquinas() {
        $('.loader2').show();
        alertify.success("Cargando vista completa, por favor espere.");
        $('.loader2').fadeOut(2150);
        $('#tablaArreglosMaquinasElec').fadeOut(1000);
        $('#tablaArreglosMaquinasElec').fadeIn(1000);
        $('#tablaArreglosMaquinasElec').load('conAc_tablaArreglosMaquinasElec.php?full=1');
      }     
    </script> 
  </div>
</div>

<!--------------- MODAL ------------------>
<div class="modal" id="modal-control-access" tabindex="-1" role="dialog" aria-labelledby="modal-control-access-label" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-xs" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title">Acceso:</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="row">
          
          <div class="col-md-12">
            <label class="control-label" for="inp_pw-access">Clave para el ingreso a Gestión:</label>
            <input type="password" name="inp_pw-access" id="inp_pw-access" class="form-control" placeholder="Ingrese la clave">
            <script type="text/javascript">
              $('#inp_pw-access').on('keyup', function (e) {
                if (e.key === 'Enter' || e.keyCode === 13) {
                  $('#btn_perform-access').click();
                }
              });
            </script>
          </div>

        </div>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" id="btn_cerrar_modal-control-access" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
        <button type="button" id="btn_perform-access" class="btn btn-success" onclick="javascript:goGestion();">Ingresar</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<!--------------- END MODAL -------------->



<script>
  //Script que permite setear como activo el tab que estaba antes de recargar la pagina
    $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
        localStorage.setItem('activeTab', $(e.target).attr('href'));
    });

    var activeTab = localStorage.getItem('activeTab');

    if(activeTab){
        $('#myTab a[href="' + activeTab + '"]').tab('show');
    }

  //Permite la correcta carga de las columnas de los dataTable a traves de los TABS
    $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
        $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
    } );
</script> 

<?php include_once('layouts/newFooter.php'); ?>