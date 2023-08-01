<?php
  $page_title = 'Reparaciones de Máquinas Eléctricas';
  require_once('includes/load.php');
  page_require_level(2);
  $current_user_ok = current_user();
?>


<!-- Solapa de Máquinas eléctricas -->
  
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

