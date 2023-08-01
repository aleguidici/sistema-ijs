<?php
  $page_title = 'Nueva Máquina';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
  $clienteOK = find_by_id('clientemaquina',$_GET['idCli']);
  $provinciaOK = find_by_id_prov('provincia',$clienteOK['provincia']);
  $all_marcas = $db->while_loop($db->query("SELECT * FROM `maquina_marca` ORDER BY `descripcion`"));
  $all_maquina_tipos = find_all_tipos('maquina_tipo');
  $all_maquina_tamanios = find_all('maquina_tamanio');
?>
<link rel="stylesheet" href="libs/font-awesome-4.7.0/css/font-awesome.min.css">



<style type="text/css">
  /*body { 
    padding-right: 0 !important;
  }*/
</style>
<script type="text/javascript">
var idClienteGlobal;

  
$(document).ready(function(){
  $('#buttom-crear-mod').prop("disabled",true);
  $('#buttom-crear-mod').attr("disabled",true);
  $('#modelo').selectpicker();
  $('#marca').selectpicker();

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
            //$('#div-modelos').fadeOut(500);
            //  setTimeout(function() {              
                $('#div-modelos').load('conAc_loadModelos.php?id_marca='+marcaIdOK_m+'&modelo='+nombreOK_m);
                //$('#div-modelos').fadeIn(1000);
              //},250);
            $('#modelo').selectpicker('refresh');
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



function resolveFaster() {
  return new Promise(resolve => {
    setTimeout(() => {
      resolve('resolved');
      $('#datos_crear-reparacion').load('conAc_agregarReparacionMaquina.php?id_maquina='+last_id_maquina);     
    }, 750);
  });
}

async function asyncCall() {
  $('#contenedor_datos-cliente-maquina').load('conAc_agregarMaquina.php?idCli='+idClienteGlobal);
  $('#contenedor_select-maquina').load('conAc_loadMaquinas.php?idCliente='+idClienteGlobal);
  console.log('calling');
  const result = await resolveFaster();
  console.log(result);
  // expected output: "resolved"
}

function addMaquina() {
  var idCliente = document.getElementById('idClienteFinal').value;
  idClienteGlobal = idCliente;
  var idModelo = document.getElementById('modelo').value;
  var numSerie = document.getElementById('numSerie').value;
  var descripcionFinal = document.getElementById('descripcionFinal').value;
  if (idCliente && idModelo) {
    add_maquina = "&idCliente=" + idCliente + "&idModelo=" + idModelo + "&numSerie=" + numSerie + "&descripcion=" + descripcionFinal;
    $.ajax({
      type:"POST",
      url:"add_maquina.php",
      data:add_maquina,
      success:function(r){
        var h = r.split(".");
        var lastIdMaquina = parseInt(h[1]);
        if (h[0] == 1) {
          alertify.success("Máquina agregada exitosamente.");
          asyncCall();
        } else if (r == 2) {
          alertify.error("Ésta máquina ya existe, por favor verifique.");
        } else {
          alertify.error("Error.");
        }
        last_id_maquina = lastIdMaquina; //set global variable -> id ultima maquina registrada
      }
    });
  } else {
    alertify.error("Complete todos los campos requeridos, por favor.");
  }
}


function blockSpecialChar(e) {
  var k = e.keyCode;
  return (!(k == 34 || k == 39));
};

/*function trackValues() {
  var valuea =document.getElementById('inp_marca-id').getAttribute("marca-id");
  console.log(valuea);
}*/
function noEnter(texto, e) {
  if (navigator.appName == "Netscape") tecla = e.which;
  else tecla = e.keyCode;
  if (tecla == 13) return false;
  else return true;
}
function noSpece(texto, e) {
  if (navigator.appName == "Netscape") tecla = e.which;
  else tecla = e.keyCode;
  if (tecla == 32) return false;
  else return true;
}

function reloadP() {
  //sessionStorage.setItem("reloading", "true");
  document.location.reload();
}       

function marcaSelect() {                  
document.getElementById("modelo").disabled = false;
var marca = document.getElementById("marca").value;
document.getElementById('inp_marca-id').setAttribute("marca-id", marca);
var marca_nombre = $("#marca option:selected").text();
var sel = document.getElementById("modelo");
$('#buttom-crear-mod').prop("disabled",false);
$('#buttom-crear-mod').attr("disabled",false);
$('#buttom-crear-mod').data("id", marca);
$('#buttom-crear-mod').data("marca", marca_nombre);
//$('#modelo').find('option').remove().end().append('<option value="" disabled selected>Seleccione un modelo</option>');  

$('#div-modelos').load('conAc_loadModelos.php?id_marca='+marca+'&modelo=the-first-time');

$('#modelo').selectpicker('refresh'); 
}                   

$(document).on("click", ".open-modal_nuevoModelo", function () {
//document.getElementById("nombre_marca_modal2").innerHTML = $(this).data('marca'); //carga la marca en el modal
document.getElementById("nombre_marca_modal2").innerHTML = $('#marca option:selected').text(); //carga la marca en el modal
});

</script>
    
      
<?php echo display_msg($msg); ?>
<div class="panel panel-default" id="panelcito">
  <div class="panel-heading">
    <strong>
      <span class="glyphicon glyphicon-th"></span>
      <span>Datos</span>
    </strong>
  </div>

  <div class="panel-body">
    <div class="row" style="margin-bottom: 4px; margin-top: 4px">
      <div class="col-md-10">
        <h4 style="margin-bottom: 2px;"><label class="control-label">Cliente:</label></h4>
      </div>
    </div>
    <div class="row" style="margin-bottom: 4px; margin-top: 4px">
      <div class="col-md-2">
        &emsp;&emsp;
        <b>Razón social: </b>
      </div>
      <div class="col-md-10">
        <label class="label label-info" style="font-size: 12px;"><?php echo $clienteOK['razon_social'];?></label>
      </div>
    </div>

    <div class="row" style="margin-bottom: 4px; margin-top: 4px">
      <div class="col-md-2">
        &emsp;&emsp;
        <b>Dirección: </b>
      </div>
      <div class="col-md-10">
        <?php echo $clienteOK['direccion']?> <em><?php echo ' - '.$clienteOK['localidad'].' ('.$clienteOK['cp'].'), '.$provinciaOK['nombre'];?></em>
      </div>
    </div>

    <div class="row" style="margin-bottom: 4px; margin-top: 4px">
      <div class="col-md-2">
        &emsp;&emsp;
        <b>DNI/CUIT: </b>
      </div>
      <div class="col-md-4">
        <?php echo $clienteOK['cuit'].' ('.find_by_id('iva_condiciones',$clienteOK['iva'])['descripcion'].')'  ;?>
      </div>
    </div>

    <div class="row" style="margin-bottom: 4px; margin-top: 4px">
      <div class="col-md-2">
        &emsp;&emsp;
        <b>Teléfono: </b>
      </div>
      <div class="col-md-4">
        <?php if ($clienteOK['tel'] != null) 
          echo $clienteOK['tel'];
        else
          echo "-- --"; ?>
      </div>
      <div class="col-md-2">
        &emsp;&emsp;
        <b>Celular: </b>
      </div>
      <div class="col-md-4">
        <?php if ($clienteOK['cel'] != null) 
          echo $clienteOK['cel'];
        else
          echo "-- --"; ?>
      </div>
    </div>

    <hr>
    <div id="div_datos-maquina" hidden>
      <h4 style="margin-bottom: 7px;"><label class="control-label">Crear nueva máquina:</label></h4>
      <div class="row">
        <div class="form-group col-md-4">
          <label for="marca" class="control-label">Marca: *</label><!-- <a type="button" id='btn_reload-datos' title="Recargar todos los datos" style="padding-top: 0px; padding-bottom: 0px;margin-bottom: -10px;margin-top: 1px; margin-right: 2px;float: right;" data-toggle="tooltip" onclick="javascript:reloadP();"><i class="fa fa-refresh" style="color:#D39E00;" aria-hidden="true"></i></a>-->
          <select required="required" class="selectpicker" data-show-subtext="true" data-live-search="true" data-width="100%" name="marca" id="marca" required onchange="javascript:marcaSelect()">
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
        <div class="form-group col-md-4">
          <div id="div-modelos">  
            <label for="modelo" class="control-label">Modelo: *</label><a id='buttom-crear-mod' data-toggle="modal" class='btn open-modal_nuevoModelo' title='Nuevo Modelo' style="padding-top: 0px; padding-bottom: 0px;margin-bottom: -10px;margin-top: -12px;" data-target="#modal_nuevoModelo" data-id="" data-marca=""><i class="fa fa-1 fa-plus-square" style="color:#008a55;" aria-hidden="true"></i></a>        
            <select class="selectpicker" data-show-subtext="true" data-live-search="true" data-width="100%" name="modelo" id="modelo" required disabled>
              <option value="" disabled selected>Seleccione un modelo</option>
            </select>
          </div>
        </div>
        <div class="form-group col-md-4">
          <label for="num_serie" class="control-label">Número de serie: <sup> (opcional)</sup></label>
          <input type="name" class="form-control" name="numSerie" id="numSerie" placeholder="N° serie" maxlength="100">
          <input type="hidden" class="form-control" id="idClienteFinal" name="idClienteFinal" value="<?php echo ($_GET['idCli'])?>">
        </div>
        <input type="text" name="inp_marca-id" id="inp_marca-id" hidden>
        <input type="text" name="inp_model-selecc" id="inp_model-selecc" hidden>
      </div>
      <div class="row">
        <div class="form-group col-md-4">
          <label for="telefono" class="control-label">Descripción de la máquina:<sup> (Opcional)</sup></label>
          <textarea type="text" class="form-control" placeholder="Descripción" id="descripcionFinal" name="descripcionFinal" rows="3" maxlength="250" style="resize: none;" onkeypress="return noEnter(this.value, event)"></textarea>
        </div>
        <div class="form-group col-md-6"></div>
        <div class="form-group col-xs-2">
          <br><br><br>
          <div class="form-group pull-right" style="margin-bottom: 0px; margin-top: 10px;">   
            <button class="btn btn-primary" onclick="javascript:rePage();" role=button>Cerrar</button>
            <button type="button" id="abm_maquina" name="abm_maquina" class="btn btn-success" onclick="javascript:addMaquina();">Crear Máquina</button>
          </div>
        </div>
      </div>
    </div>
    <div id="datos_crear-reparacion"></div> 
  </div>
</div>

<!-- MODAL ADD MODELO -->
<div class="modal fade bd-example-modal-lg" id="modal_nuevoModelo" tabindex="-1" role="dialog" aria-labelledby="modalModelooo" data-backdrop="static" data-keyboard="false" aria-hidden="true">
<div class="modal-dialog modal-lg" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <h4 class="modal-title" id="modalModelooo">Nuevo Modelo</h4>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
      
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
            <label>Tipo: *</label>
              <select class="form-control" name="tipo_modelo" id="tipo_modelo">
                <option value="" disabled selected>Seleccione un tipo</option>
                <?php foreach ($all_maquina_tipos as $un_tipo): ?>
                  <option value="<?php echo (int) $un_tipo['id'];?>">                                  
                    <?php echo ($un_tipo['descripcion']);?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-6">
              <label>Nombre del Modelo: *</label>
              <input id="nombre_modelo" class="form-control" placeholder="Nombre del Modelo" maxlength="100" onkeypress="return noSpece(this.value, event)">
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-4">
              <label>Tamaño: *</label>
              <select class="form-control" name="tamanio_modelo" id="tamanio_modelo">
                <?php foreach ($all_maquina_tamanios as $un_tamanio): ?>
                  <option value="<?php echo (int) $un_tamanio['id'];?>">
                    <?php echo utf8_encode($un_tamanio['descripcion']);?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-4">
              <label>Inalámbrico: *</label>
              <select class="form-control" name="inalambrico_modelo" id="inalambrico_modelo">
                <option value="1">SI</option>
                <option value="0" selected>NO</option>
              </select>
            </div>
            <div class="col-md-4">
              <label>Año:<sup> (Opcional)</sup></label>
              <input id="anio_modelo" class="form-control" placeholder="Año" maxlength="10">
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="row">
            <div class="col-md-12">
              <label>Descripción:<sup> (Opcional)</sup></label>
              <textarea type="text" class="form-control" placeholder="Descripción" id="detalle_modelo" rows="5" maxlength="350" style="resize: none;" onkeypress="return noEnter(this.value, event)"></textarea>
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
<script type="text/javascript">
$(function() { 
  $('#modal_nuevoModelo').on('hidden.bs.modal', function (e) {
    $(this)
    .find("input,textarea, name, text")
       .val('')
       .end()
    /*.find("select")
       .val('1')*/
    .find("input[type=checkbox], input[type=radio]")
       .prop("checked", "")
       .end();
    $('#tipo_modelo').val('');
    $('#tamanio_modelo').val('1');
    $('#inalambrico_modelo').val('0');     
  })
}); 
</script>
<!--END MODAL ADD MODELO-->


