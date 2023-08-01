<?php
  $page_title = 'Agregar Proveedor Máquina Eléctrica';
  require_once('includes/load.php');
  $all_paices = find_all('pais');
  // Checkin What level user has permission to view this page
  page_require_level(2);
  $argentina = find_prov_by_pais('1');
  $paraguay = find_prov_by_pais('2');
  $brasil = find_prov_by_pais('3');
  $usa = find_prov_by_pais('4');
  $all_proveedores = find_all_proveedores_me()
?>
<?php
 if(isset($_POST['abm_proveedormaquina'])){
   $razon_social = remove_junk($db->escape($_POST['razon_social']));
   $cuit = remove_junk($db->escape($_POST['cuit']));
   $cond_IVA = remove_junk($db->escape($_POST['condicion_iva']));
   $direccion = remove_junk($db->escape($_POST['direccion']));
   $localidad = remove_junk($db->escape($_POST['localidad']));
   $provincia = remove_junk($db->escape($_POST['provin']));
   $cp = remove_junk($db->escape($_POST['codigo_postal']));
   $tel1 = remove_junk($db->escape($_POST['telefono1']));
   $tel2 = remove_junk($db->escape($_POST['telefono2']));

   //$req_field = array('razon_social','direccion','localidad','pais','provin');
   //validate_fields($req_field);   

    if(empty($errors)){
        $query  = "INSERT INTO proveedormaquina (`cuit`, `razon_social`, `direccion`, `localidad`, `provincia`, `cp`, `telefono1`, `telefono2`, `iva`) VALUES ('{$cuit}', '{$razon_social}', '{$direccion}', '{$localidad}', '{$provincia}', '{$cp}', '{$tel1}', '{$tel2}', '{$cond_IVA}')";

        if($db->query($query)){
          $session->msg("s", "Proveedor agregado exitosamente.");
          redirect('proveedores.php',false);
        } else {
          $session->msg("d", "Lo siento, el registro falló");
          redirect('proveedores.php',false);
        }               
    }else{
      $session->msg("d", $errors);
      redirect('proveedores.php',false);
    }
 }
?>

<?php include_once('layouts/header.php'); ?>

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
            <span>Nuevo Proveedor máquina eléctrica</span>
         </strong>
        </div>

        <form method="post" action="proveedorMaquina.php"> 
          <div class="panel-body">
            <div class="form-group col-xs-6 mr-2">
              <label for="razon_social" class="control-label">Razón Social:</label>
              <input type="name" class="form-control" name="razon_social" placeholder="Razon social" maxlength="60" required pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚº\-\.,()\s/]+" title="Sólo letras">
            </div>
            <div class="form-group col-xs-6  mr-2">
              <label for="direccion" class="control-label">Dirección:</label>
              <input type="name" name= "direccion" class="form-control" placeholder="Dirección" required pattern="[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚº\-\.,()\s/]+" title="Sólo letras o números">
            </div>   
            <div class="form-group col-xs-3 mr-2 ">
              <label for="condicion_iva" class="control-label">Condición ante el IVA (opcional):</label>
              <select required="required" class="form-control" name="condicion_iva" id="condicion_iva">
                <option value="" disabled selected>Seleccione una condición</option>
                <option value="N/D">N/D</option>
                <option value="IVA Responsable Inscripto">IVA Responsable Inscripto</option>
                <option value="IVA Responsable no Inscripto">IVA Responsable no Inscripto</option>
                <option value="IVA no Responsable">IVA no Responsable</option>
                <option value="IVA Sujeto Exento">IVA Sujeto Exento</option>
                <option value="Consumidor Final">Consumidor Final</option>
                <option value="Monotributista">Monotributista</option>
                <option value="Sujeto no Categorizado">Sujeto no Categorizado</option>
                <option value="Proveedor del Exterior">Proveedor del Exterior</option>
                <option value="Cliente del Exterior">Cliente del Exterior</option>
              </select>
            </div>
            <div class="form-group col-xs-3 mr-2">
              <label for="cuit" class="control-label">Cuit:</label>
              <input type="name" name= "cuit" class="form-control" required placeholder="Cuit" pattern="[0-9\-]+" title="Sólo números y guiones">
            </div> 
            <div class="form-group col-xs-3 mr-2 ">
                <label for="telefono" class="control-label">Teléfono 1 (Opcional):</label>
                <input type="name" name= "telefono1" class="form-control" placeholder="Teléfono" pattern="[0-9.+\-()/\s]+" title="Sólo letras o números">
            </div>
            <div class="form-group col-xs-3 mr-2 ">
                <label for="telefono" class="control-label">Teléfono 2 (Opcional):</label>
                <input type="name" name= "telefono2" class="form-control" placeholder="Teléfono" pattern="[0-9.+\-()/\s]+" title="Sólo letras o números">
            </div>     
            <div class="form-group col-xs-6  mr-2">
              <label for="pais" class="control-label">País:</label>
              <select required="required" class="form-control" name="pais" id="pais" onchange="javascript:paisss()">
                <option value="" disabled selected>Seleccione un país</option>
                <?php  foreach ($all_paices as $pa): ?>
                  <option value="<?php echo (int) $pa['id']?>">
                <?php echo remove_junk(utf8_encode($pa['nombre']));?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <script>
              function paisss() {
                document.getElementById("provin").disabled = false;
                document.getElementById("localidad").disabled = false;
                document.getElementById("localidad").value = "";
                document.getElementById("codigo_postal").disabled = false;
                document.getElementById("codigo_postal").value = "";
                var sel = document.getElementById("provin");
                var pais_ok = document.getElementById("pais").selectedIndex;
                var cont = 0;

                $('#provin')
                  .find('option')
                  .remove()
                  .end()
                  .append('<option value="" disabled selected>Seleccione una provincia / estado</option>')
                ;

                switch (pais_ok){
                  case 1:
                    <?php  foreach ($argentina as $arg): ?>
                      cont += 1;
                      var opt = document.createElement('option');
                      var nombre = "<?php echo ($arg['nombre'])?> ";
                      opt.appendChild( document.createTextNode(nombre) );
                      opt.value = parseInt("<?php echo $arg['id_provincia']?> "); 
                      sel.appendChild(opt); 
                    <?php endforeach; ?>
                    break;
                  case 2:
                    <?php  foreach ($paraguay as $par): ?>
                      cont += 1;
                      var opt = document.createElement('option');
                      var nombre = "<?php echo ($par['nombre'])?> ";
                      opt.appendChild( document.createTextNode(nombre) );
                      opt.value = parseInt("<?php echo $par['id_provincia']?> ");
                      sel.appendChild(opt); 
                    <?php endforeach; ?>
                    break;
                  case 3:
                    <?php  foreach ($brasil as $bra): ?>
                      cont += 1;
                      var opt = document.createElement('option');
                      var nombre = "<?php echo ($bra['nombre'])?> ";
                      opt.appendChild( document.createTextNode(nombre) );
                      opt.value = parseInt("<?php echo $bra['id_provincia']?> ");; 
                      sel.appendChild(opt); 
                    <?php endforeach; ?>
                    break;
                  case 4:
                    <?php  foreach ($usa as $us): ?>
                      cont += 1;
                      var opt = document.createElement('option');
                      var nombre = "<?php echo ($us['nombre'])?> ";
                      opt.appendChild( document.createTextNode(nombre) );
                      opt.value = parseInt("<?php echo $us['id_provincia']?> ");; 
                      sel.appendChild(opt); 
                    <?php endforeach; ?>
                    break;
                }
              }
            </script>
            <div class="form-group col-xs-6  mr-2">
              <label for="provin" class="control-label">Provincia / estado:</label>
              <select required="required" class="form-control" name="provin" id="provin" disabled="true">
                <option value="" disabled selected>Seleccione una provincia / estado</option>
              </select>
            </div>
            <div class="form-group col-xs-6 mr-2 ">
              <label for="localidad" class="control-label">Localidad:</label>
              <input type="name" name= "localidad" id= "localidad" class="form-control" placeholder="Localidad" required pattern="[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ.\s]+" title="Sólo letras" disabled>
            </div>
            <div class="form-group col-xs-6 mr-2 ">
                <label for="codigo_postal" class="control-label">Código Postal:</label>
                <input type="name" name= "codigo_postal" id= "codigo_postal" class="form-control" placeholder="Código Postal" required pattern="[a-zA-Z0-9\-/\s]+" title="Sólo números" disabled>
            </div>
          </div>
          <div class="panel-body">
            <div class="form-group text-right">
              <a class="btn btn-primary" href="proveedores.php" role=button>Volver a Proveedores</a>
              <button type="submit" name="abm_proveedormaquina" class="btn btn-danger">Crear Proveedor</button>
            </div>
          </div>
        </form> 
      </div>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>