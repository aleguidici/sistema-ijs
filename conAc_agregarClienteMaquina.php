<?php
  $page_title = 'Agregar Cliente - Máquina';
  require_once('includes/load.php');
?>
<script type="text/javascript">  

  function addNewClienteMaquina() {
  var cuit = document.getElementById('cuit').value;
  var cond_iva = document.getElementById('condicion_iva').value;
  var razon_social = document.getElementById('razon_social').value;
  var direccion = document.getElementById('direccion').value;
  var localidad = document.getElementById('localidad').value;
  var provincia = document.getElementById('provin').value;
  var cp = document.getElementById('codigo_postal').value;
  var tel = document.getElementById('telefono').value;
  var cel = document.getElementById('celular').value;
  var email = document.getElementById('email').value;

  if (cuit && cond_iva && razon_social && cp && provincia && localidad) {
    add_cliente = "&cuit=" + cuit + "&condicion_iva=" + cond_iva + "&razon_social=" + razon_social + "&direccion=" + direccion + "&localidad=" + localidad + "&provin=" + provincia + "&codigo_postal=" + cp + "&telefono=" + tel + "&celular=" + cel + "&email=" + email;
    $.ajax({
      type:"POST",
      url:"add_clienteMaquina.php",
      data:add_cliente,
      success:function(r) {
        var h = r.split(".");
        last_id_cliente = parseInt(h[1]);
        if (h[0] == 1) {
          $('#contenedor_select-clientes').load('conAc_loadClientesMaquinas.php');
          $('#contenedor_datos-cliente-maquina').load('conAc_agregarMaquina.php?idCli='+last_id_cliente);
          $('#contenedor_select-maquina').load('conAc_loadMaquinas.php?idCliente='+last_id_cliente);
          $('#contenedor_crear-nuevo-cliente').prop('hidden', true);
        }
        $('#div_msg').load('conAc_agregarMaquinaCli.php #div_msg-show');
      }
    });
  } else {
    alertify.error("Complete todos los campos requeridos, por favor.");
  }
}

function canCreate() {
  var rs = document.getElementById('razon_social').value;
  var cuit = document.getElementById('cuit').value;
  var iva = document.getElementById('condicion_iva').value;
  var prov = document.getElementById('provin').value;
  var loc = document.getElementById('localidad').value;
  var cp = document.getElementById('codigo_postal').value;
  var cel = document.getElementById('celular').value;
  if (rs && cuit && iva && prov && loc && cp) {
    $('#abm_cliente').removeClass("btn-danger").addClass("btn-warning");
    if (cel) {
      $('#abm_cliente').removeClass("btn-warning").addClass("btn-success");
    }
  }
}
</script>

<div class="row">
    <div class="col-md-12">
      <?php echo display_msg($msg); ?>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>NUEVO CLIENTE DE MAQUINARIAS</span>
         </strong>
        </div>

       <!-- <form method="post" action=""> -->
          <div class="panel-body">
            <div class="form-group col-xs-6">
              <label for="razon_social" class="control-label">Nombre / Razón Social: *</label>
              <input type="name" class="form-control" name="razon_social" id="razon_social" placeholder="Nombre / Razón Social" maxlength="100" required pattern="[a-zA-Z0-9ñÑÜüáéíóúÁÉÍÓÚº\-\.()\s/,\s]+" title="Sólo letras o números" onchange="javascript:canCreate();">
            </div>
            <div class="form-group col-xs-3">
              <label for="cuit" class="control-label">CUIT / DNI: *</label>
              <input type="name" name="cuit" id="cuit" class="form-control" placeholder="CUIT / DNI" required pattern="[0-9\-]+" title="Sólo números y guiones" onchange="javascript:canCreate();">
            </div>


            <div class="form-group col-xs-3">
              <label for="condicion_iva" class="control-label">Condición IVA: *</label>
              <select required="required" class="form-control" name="condicion_iva" id="condicion_iva" onchange="javascript:canCreate();">
                <option value="" disabled selected>Seleccione una condición</option>
                <?php
                $ivaCondiciones = find_all('iva_condiciones');
                foreach ($ivaCondiciones as $unaCondicionIva): ?>
                <option value="<?php echo $unaCondicionIva['id']; ?>"><?php echo $unaCondicionIva['descripcion']; ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="form-group col-xs-4">
              <label for="provincia" class="control-label">Provincia: *</label>
              <select required="required" class="form-control" name="provin" id="provin" onchange="javascript:canCreate();">
                <option value="" disabled selected>Seleccione una provincia</option>
                <?php
                $all_provincias = $db->while_loop($db->query("SELECT `id_provincia`, `nombre` FROM `provincia` WHERE `pais` = 1 ORDER BY `nombre`"));
                  foreach ($all_provincias as $prov): ?>
                  <option value="<?php echo (int) $prov['id_provincia']?>">
                <?php echo $prov['nombre'];?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="form-group col-xs-3">
              <label for="localidad" class="control-label">Localidad: *</label>
              <input type="name" name="localidad" id="localidad" class="form-control" placeholder="Localidad" required pattern="[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ.\s]+" title="Sólo letras" onchange="javascript:canCreate();">
            </div>
            <div class="form-group col-xs-1">
                <label for="codigo_postal" class="control-label">C.P.: *</label>
                <input type="name" name="codigo_postal" id="codigo_postal" class="form-control" placeholder="C.P." required pattern="[0-9\s]+" title="Sólo números" onchange="javascript:canCreate();">
            </div>
             

              
            <div class="form-group col-xs-4">
              <label for="direccion" class="control-label">Dirección: <sup>(Opcional)</sup></label>
              <input type="name" name="direccion" id="direccion" class="form-control" placeholder="Dirección" pattern="[a-zA-Z0-9/-,ñÑÜüáéíóúÁÉÍÓÚº\-\.()\s/,\s]+" title="Sólo letras o números">
            </div>
                  
            
            <div class="form-group col-xs-4">
              <label for="direccion" class="control-label">Correo Electrónico: <sup>(Opcional)</sup></label>
              <input type="name" name="email" id="email" class="form-control" placeholder="Correo Electrónico" pattern="[a-zA-Z0-9/-,ñÑÜüáéíóúÁÉÍÓÚº\-\.()\s/,\s]+" title="Sólo letras o números">
            </div>
            
            <div class="form-group col-xs-4">
                <label for="telefono" class="control-label">Teléfono: <sup>(Opcional)</sup></label>
                <input type="name" name="telefono" id="telefono" class="form-control" placeholder="Teléfono" pattern="[()0-9.+\-\s]+" title="Sólo letras o números">
            </div>
            <div class="form-group col-xs-4">
                <label for="celular" class="control-label">Celular: <sup>(Opcional)</sup></label>
                <input type="name" name="celular" id="celular" class="form-control" placeholder="Celular" pattern="[()0-9.+\-\s]+" title="Sólo letras o números" onchange="javascript:canCreate();">
            </div>
          </div>
          <div class="panel-body">
            <div class="form-group text-right">   
              <button type="button" class="btn btn-primary" onclick="javascript:rePage();">Cerrar</button>
              <button type="button" name="abm_cliente" id="abm_cliente" class="btn btn-danger" onclick="javascript:addNewClienteMaquina()">Crear Cliente - Máquina</button>
            </div>
          </div>
      <!--  </form> -->
      </div>
    </div>
  </div>