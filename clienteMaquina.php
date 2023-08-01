<?php
  $page_title = 'Agregar Cliente - Máquina';
  require_once('includes/load.php');
  $all_provincias = find_all('provincia');
  // Checkin What level user has permission to view this page
  page_require_level(2);

  $all_clienteMaq = find_all('clienteMaquina');
?>
<?php include_once('layouts/header.php'); ?>
  <link rel="stylesheet" type="text/css" href="libs/alertifyjs/css/alertify.css">
  <link rel="stylesheet" type="text/css" href="libs/alertifyjs/css/themes/default.css">
  <script src="libs/alertifyjs/alertify.js"></script>
  <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.rtl.min.css"/>
  <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.min.css"/>


<?php
 if(isset($_POST['abm_cliente'])){
   $cuit = remove_junk($db->escape($_POST['cuit']));
   $cond_IVA = remove_junk($db->escape($_POST['condicion_iva']));
   $razon_social = remove_junk($db->escape($_POST['razon_social']));
   $razon_social = strtoupper($razon_social);
   $direccion = remove_junk($db->escape($_POST['direccion']));
   $localidad = remove_junk($db->escape($_POST['localidad']));
   $localidad = ucfirst(strtolower($localidad));
   $provincia = remove_junk($db->escape($_POST['provin']));
   $cp = remove_junk($db->escape($_POST['codigo_postal']));
   $tel = remove_junk($db->escape($_POST['telefono']));
   $cel = remove_junk($db->escape($_POST['celular']));
   $email = remove_junk($db->escape($_POST['email']));

   $allCuitExiste = find_all('clientemaquina');
   $cuitExiste = find_by_cuit('clientemaquina', $cuit);

   if ($cuitExiste) {
    //echo '<script>window.alert("SI");</script>' ;
    //$session->msg("d", "Lo siento, al parecer ése cliente ya está registrado.");
    //redirect('clienteMaquina.php',false);
    echo '<script>alertify.error("Lo siento, al parecer ése cliente ya está registrado.");</script>';
   } else {
    $query  = "INSERT INTO clientemaquina (`cuit`, `razon_social`, `direccion`, `localidad`, `provincia`, `cp`, `email`, `tel` , `cel`, `iva`) VALUES ('{$cuit}', '{$razon_social}', '{$direccion}', '{$localidad}', '{$provincia}', '{$cp}', '{$email}', '{$tel}', '{$cel}', '{$cond_IVA}')";
      if ($db->query($query) == 1) {        
        //header( "refresh:3;url=clientes.php" );
        echo '<script>alertify.success("Cliente agregado exitosamente.");</script>';
        echo '<script>setInterval(location.replace("clientes.php?tabclieme=2"), 3000)</script>';
        //$session->msg("s", "Cliente agregado exitosamente.");
        //redirect('clientes.php',false);
      } else {
        echo '<script>alertify.warning("Lo siento, el registro falló.");</script>';
        //echo '<script>setInterval("location.reload()", 3000);</script>';
        //$session->msg("d", "Lo siento, el registro falló.");
        //redirect('clienteMaquina.php',false);
      }    
   }
 }


   //$req_field = array('cuit','razon_social','direccion','localidad','provin','codigo_postal');
  //validate_fields($req_field);
?>



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
            <span>Nuevo Cliente - Máquina</span>
         </strong>
        </div>

        <form method="post" action="clienteMaquina.php"> 
          <div class="panel-body">
            <div class="form-group col-xs-6 mr-2">
              <label for="razon_social" class="control-label">Nombre / Razón Social:</label>
              <input type="name" class="form-control" name="razon_social" placeholder="Nombre / Razón Social" maxlength="100" required pattern="[a-zA-Z0-9ñÑÜüáéíóúÁÉÍÓÚº\-\.()\s/,\s]+" title="Sólo letras o números">
            </div>
            <div class="form-group col-xs-3 mr-2 ">
              <label for="condicion_iva" class="control-label">Condición IVA:</label>
              <select required="required" class="form-control" name="condicion_iva" id="condicion_iva">
                <option value="" disabled selected>Seleccione una condición</option>
                <?php
                $ivaCondiciones = find_all('iva_condiciones');
                foreach ($ivaCondiciones as $unaCondicionIva): ?>
                <option value="<?php echo $unaCondicionIva['id']; ?>"><?php echo $unaCondicionIva['descripcion']; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group col-xs-3 mr-2">
              <label for="cuit" class="control-label">Cuit / DNI:</label>
              <input type="name" name= "cuit" class="form-control" placeholder="Cuit / DNI" required pattern="[0-9\-]+" title="Sólo números y guiones">
            </div>  
            <div class="form-group col-xs-6  mr-2">
              <label for="direccion" class="control-label">Dirección:</label>
              <input type="name" name= "direccion" class="form-control" placeholder="Dirección" pattern="[a-zA-Z0-9/-,ñÑÜüáéíóúÁÉÍÓÚº\-\.()\s/,\s]+" title="Sólo letras o números">
            </div>
            <div class="form-group col-xs-3  mr-2">
              <label for="provincia" class="control-label">Provincia / Estado:</label>
              <select required="required" class="form-control" name="provin" id="provin">
                <option value="" disabled selected>Seleccione una provincia</option>
                <?php  foreach ($all_provincias as $prov): ?>
                  <option value="<?php echo (int) $prov['id_provincia']?>">
                <?php echo $prov['nombre'];?></option>
                <?php endforeach; ?>
              </select>
            </div>       
            <div class="form-group col-xs-3 mr-2 ">
              <label for="localidad" class="control-label">Localidad:</label>
              <input type="name" name= "localidad" class="form-control" placeholder="Localidad" required pattern="[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ.\s]+" title="Sólo letras">
            </div>
                        <div class="form-group col-xs-6  mr-2">
              <label for="direccion" class="control-label">Correo Electrónico:</label>
              <input type="name" name= "email" class="form-control" placeholder="Correo Electrónico" pattern="[a-zA-Z0-9/-,ñÑÜüáéíóúÁÉÍÓÚº\-\.()\s/,\s]+" title="Sólo letras o números">
            </div>
            <div class="form-group col-xs-2 mr-2 ">
                <label for="codigo_postal" class="control-label">C.P.:</label>
                <input type="name" name= "codigo_postal" class="form-control" placeholder="C.P." required pattern="[0-9\s]+" title="Sólo números">
            </div>
            <div class="form-group col-xs-5 mr-2 ">
                <label for="telefono" class="control-label">Teléfono (Opcional):</label>
                <input type="name" name= "telefono" class="form-control" placeholder="Teléfono" pattern="[()0-9.+\-\s]+" title="Sólo letras o números">
            </div>
            <div class="form-group col-xs-5 mr-2 ">
                <label for="celular" class="control-label">Celular (Opcional):</label>
                <input type="name" name= "celular" class="form-control" placeholder="Celular" pattern="[()0-9.+\-\s]+" title="Sólo letras o números">
            </div>
          </div>
          <div class="panel-body">
            <div class="form-group text-right">   
              <a class="btn btn-primary" href="clientes.php?tabclieme=2" role=button>Volver a Clientes</a>
              <button type="submit" name="abm_cliente" class="btn btn-danger">Crear Cliente - Máquina</button>
            </div>
          </div>
        </form> 
      </div>
    </div>
  </div>
<?php include_once('layouts/footer.php'); ?>