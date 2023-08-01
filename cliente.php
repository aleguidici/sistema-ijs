<?php
  $page_title = 'Agregar Cliente';
  require_once('includes/load.php');
  $all_provincias = find_all('provincia');
  $all_croquis = find_all_imagenes('0');
  $all_logos = find_all_imagenes('1');
  // Checkin What level user has permission to view this page
  page_require_level(2);

  $all_cliente = find_all('cliente')
?>
<?php
 if(isset($_POST['abm_cliente'])){
   $num_sucu = remove_junk($db->escape($_POST['Numero_sucursal']));
   $nombre_sucu = remove_junk($db->escape($_POST['nombre_sucursal']));
   $cuit = remove_junk($db->escape($_POST['cuit']));
   $cond_IVA = remove_junk($db->escape($_POST['condicion_iva']));
   $razon_social = remove_junk($db->escape($_POST['razon_social']));
   $direccion = remove_junk($db->escape($_POST['direccion']));
   $localidad = remove_junk($db->escape($_POST['localidad']));
   $provincia = remove_junk($db->escape($_POST['provin']));
   $cp = remove_junk($db->escape($_POST['codigo_postal']));
   $tel = remove_junk($db->escape($_POST['telefono']));
   $croquis = remove_junk($db->escape($_POST['croquis']));
   $logo = remove_junk($db->escape($_POST['logo']));
   // $forma_p = remove_junk($db->escape($_POST['forma_pago']));

   $req_field = array('cuit','razon_social','direccion','localidad','provin','codigo_postal');
   validate_fields($req_field);   

    if(empty($errors)){
        $query  = "INSERT INTO cliente (`num_suc`, `nombre_suc`, `cuit`, `razon_social`, `direccion`, `localidad`, `provincia`, `cp`, `tel`, `croquis`, `logo`, `iva`) VALUES ('{$num_sucu}', '{$nombre_sucu}', '{$cuit}', '{$razon_social}', '{$direccion}', '{$localidad}', '{$provincia}', '{$cp}', '{$tel}', '{$croquis}', '{$logo}', '{$cond_IVA}')";

        if($db->query($query)){
          $session->msg("s", "Cliente agregado exitosamente.");
          redirect('clientes.php',false);
        } else {
          $session->msg("d", "Lo siento, el registro falló");
          redirect('cliente.php',false);
        }               
    }else{
      $session->msg("d", $errors);
      redirect('cliente.php',false);
    }
 }
?>

<?php include_once('layouts/header.php'); ?>

<div class="row">
  <div class="col-md-12">
    <div class="alert alert-d"><a href="#" class="close" data-dismiss="alert">&times;</a></div>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Nuevo Cliente</span>
       </strong>
      </div>

      <form method="post" action="cliente.php"> 
        <div class="panel-body">
          <div class="form-group col-xs-3 mr-2 ">
            <label for="Numero_sucursal" class="control-label">Nº de sucursal (opcional):</label>
            <input type="name" name= "Numero_sucursal" class="form-control" placeholder="Nº de sucursal" pattern="[A-Za-z0-9]+" title="Sólo letras o números">
          </div>  
          <div class="form-group col-xs-3 mr-2 ">
            <label for="nombre_sucursal" class="control-label">Nombre de sucursal (opcional):</label>
            <input type="name" name= "nombre_sucursal" class="form-control" placeholder="Nombre de sucursal" pattern="[a-zA-Z0-9ñÑÜüáéíóúÁÉÍÓÚº\-\.()\s/,\s]+" title="Sólo letras o números">
          </div>  
          <div class="form-group col-xs-6 mr-2">
            <label for="razon_social" class="control-label">Razón Social:</label>
            <input type="name" class="form-control" name="razon_social" placeholder="Razon social" maxlength="100" required pattern="[a-zA-Z0-9ñÑÜüáéíóúÁÉÍÓÚº\-\.()\s/,\s]+" title="Sólo letras o números">
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
            <input type="name" name= "cuit" class="form-control" placeholder="Cuit" required pattern="[0-9\-]+" title="Sólo números y guiones">
          </div>  
          <div class="form-group col-xs-6  mr-2">
            <label for="direccion" class="control-label">Dirección:</label>
            <input type="name" name= "direccion" class="form-control" placeholder="Dirección" required pattern="[a-zA-Z0-9/-,ñÑÜüáéíóúÁÉÍÓÚº\-\.()\s/,\s]+" title="Sólo letras o números">
          </div>       
          <div class="form-group col-xs-4 mr-2 ">
            <label for="localidad" class="control-label">Localidad:</label>
            <input type="name" name= "localidad" class="form-control" placeholder="Localidad" required pattern="[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ.\s]+" title="Sólo letras">
          </div>
          <div class="form-group col-xs-4  mr-2">
            <label for="provincia" class="control-label">Provincia / Estado:</label>
            <select required="required" class="form-control" name="provin" id="provin">
              <option value="" disabled selected>Seleccione una provincia</option>
              <?php  foreach ($all_provincias as $prov): ?>
                <option value="<?php echo (int) $prov['id_provincia']?>">
              <?php echo $prov['nombre'];?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group col-xs-4 mr-2 ">
              <label for="codigo_postal" class="control-label">Código Postal:</label>
              <input type="name" name= "codigo_postal" class="form-control" placeholder="Código Postal" required pattern="[0-9\s]+" title="Sólo números">
          </div>
          <div class="form-group col-xs-4 mr-2 ">
              <label for="telefono" class="control-label">Teléfono (Opcional):</label>
              <input type="name" name= "telefono" class="form-control" placeholder="Teléfono" pattern="[()0-9.+\-\s]+" title="Sólo letras o números">
          </div>
          <div class="form-group col-xs-4 mr-2">
              <label for="croquis" class="control-label">Croquis (Opcional):</label>
              <select required="required" class="form-control" name="croquis" id="croquis">
                <option value=0> (Sin imagen) </option>
                <?php  foreach ($all_croquis as $croquis): ?>
                  <?php if($croquis['id'] !== '0'){ ?> <option value="<?php echo (int) $croquis['id']?>"> <?php }?>
                  <?php echo remove_junk(utf8_encode($croquis['file_name']));?></option>
                <?php endforeach; ?>
              </select>
          </div>
          <div class="form-group col-xs-4 mr-2">
              <label for="logo" class="control-label">Logo (Opcional):</label>
              <select required="required" class="form-control" name="logo" id="logo">
                <option value=0> (Sin imagen) </option>
                <?php  foreach ($all_logos as $logo): ?>
                  <?php if($logo['id'] !== '0'){ ?> <option value="<?php echo (int) $logo['id']?>"> <?php }?>
                  <?php echo remove_junk(utf8_encode($logo['file_name']));?></option>
                <?php endforeach; ?>
              </select>
          </div>
          <!-- <div class="form-group col-xs-7 mr-2">
              <label for="forma_pago" class="control-label">Forma de pago (Opcional):</label>
              <textarea type="text" class="form-control" placeholder="Forma de pago" id="forma_pago" name="forma_pago" rows="3" required></textarea>
          </div> -->
        </div>
        <div class="panel-body">
          <div class="form-group text-right">   
            <a class="btn btn-primary" href="clientes.php" role=button>Volver a Clientes</a>
            <button type="submit" name="abm_cliente" class="btn btn-danger">Crear Cliente</button>
          </div>
        </div>
      </form> 
    </div>
  </div>
</div>
<?php include_once('layouts/footer.php'); ?>