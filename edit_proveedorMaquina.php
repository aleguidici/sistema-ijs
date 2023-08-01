<?php
  $page_title = 'Editar Proveedor Máquina Eléctrica';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
   $all_provincias = find_all('provincia');
?>
  <link rel="stylesheet" type="text/css" href="libs/alertifyjs/css/alertify.css">
  <link rel="stylesheet" type="text/css" href="libs/alertifyjs/css/themes/default.css">

<?php
  $prov = find_by_id('proveedormaquina',(int)$_GET['id']);
  if(!$prov){
    $session->msg("d","Falta ID de proveedor.");
    redirect('proveedores.php');
  }
?>

<?php
 if(isset($_POST['abm_proveedormaquina'])){
   $razon_social = remove_junk($db->escape($_POST['razon_social']));
   $cuit = remove_junk($db->escape($_POST['cuit']));
   $cond_IVA = remove_junk($db->escape($_POST['condicion_iva']));
   $direccion = remove_junk($db->escape($_POST['direccion']));
   $localidad = remove_junk($db->escape($_POST['localidad']));
   $provincia = remove_junk($db->escape($_POST['provinciaa']));
   $cp = remove_junk($db->escape($_POST['codigo_postal']));
   $tel1 = remove_junk($db->escape($_POST['telefono1']));
   $tel2 = remove_junk($db->escape($_POST['telefono2']));

   //$req_field = array('cuit','razon_social','direccion','localidad','provinciaa');
   //validate_fields($req_field); 

      if(empty($errors)){ 
        if($tel1 === '0' || $tel1 === '')
          $tel1 = '';
        if($tel2 === '0' || $tel2 === '')
          $tel2 = '';
        $query = "UPDATE proveedormaquina SET  razon_social='{$razon_social}', cuit='{$cuit}', direccion='{$direccion}', localidad='{$localidad}', provincia='{$provincia}', cp='{$cp}', telefono1='{$tel1}', telefono2='{$tel2}', iva='{$cond_IVA}' WHERE id = '{$prov['id']}'";  
          $result = $db->query($query);
        if($result && $db->affected_rows() === 1) {
          $session->msg("s", "Proveedor actualizado con éxito.");
          redirect('proveedores.php',false);
        } else {
          $session->msg("d", "Lo siento, actualización falló.");
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
           <span>Editando Proveedor: Código interno Nº <?php echo remove_junk(ucfirst($prov['id']));?></span> 
        </strong>
       </div>

        <div class="panel-body">
          <form method="post" action="edit_proveedorMaquina.php?id=<?php echo (int)$prov['id'];?>">
              <div class="form-group  col-xs-6 mr-2">
                <label for="razon_social" class="control-label">Razón Social:</label>
                <input type="name" name="razon_social" class="form-control" value="<?php echo remove_junk(ucfirst($prov['razon_social']));?>">
              </div>
              <div class="form-group  col-xs-3 mr-2 ">
                <label for="cuit" class="control-label">Cuit:</label>
                <input type="name" name= "cuit" class="form-control" value="<?php echo remove_junk(ucfirst($prov['cuit']));?>">
              </div>
              <div class="form-group col-xs-3 mr-2 ">
              <label for="condicion_iva" class="control-label">Condición ante el IVA (opcional):</label>
              <select required="required" class="form-control" name="condicion_iva" id="condicion_iva">
                <option value="" disabled>Seleccione una condición</option>
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
              <script>
                var val = "<?php echo $prov['iva'] ?>";
                document.getElementById("condicion_iva").value = val;
              </script>
            </div>            
              <div class="form-group  col-xs-6 mr-2">
                  <label for="direccion" class="control-label">Dirección:</label>
                  <input type="name" name= "direccion" class="form-control" value="<?php echo remove_junk(ucfirst($prov['direccion']));?>">
              </div>       
              <div class="form-group  col-xs-6 mr-2">
                  <label for="localidad" class="control-label">Localidad:</label>
                  <input type="name" name= "localidad" class="form-control" value="<?php echo remove_junk(ucfirst($prov['localidad']));?>">
              </div>
              <div class="form-group  col-xs-3 mr-2">
                <label for="provincia" class="control-label">Provincia / Estado:</label>
                <select required="required" class="form-control" name="provinciaa" id="provinciaa">
                  <?php  foreach ($all_provincias as $provinc): ?>
                    <option value="<?php echo (int) $provinc['id_provincia']?>">
                  <?php echo remove_junk($provinc['nombre']);?></option>
                  <?php endforeach; ?>
                </select>
                <script>
                  var val = "<?php echo $prov['provincia'] ?>";
                  document.getElementById("provinciaa").selectedIndex = Number(val)-1;
                </script>
              </div>
              <div class="form-group  col-xs-3 mr-2 ">
                  <label for="codigo_postal" class="control-label">Código Postal:</label>
                  <input type="name" name= "codigo_postal" class="form-control" value="<?php echo remove_junk(ucfirst($prov['cp']));?>">
              </div>        
              <div class="form-group  col-xs-3 ml-5 ">
                  <label for="telefono1" class="control-label">Teléfono 1:</label>
                  <input type="name" name= "telefono1" class="form-control" value="<?php echo remove_junk(ucfirst($prov['telefono1']));?>">
              </div>
              <div class="form-group  col-xs-3 ml-5 ">
                  <label for="telefono2" class="control-label">Teléfono 2:</label>
                  <input type="name" name= "telefono2" class="form-control" value="<?php echo remove_junk(ucfirst($prov['telefono2']));?>">
              </div>
              <div class="form-group  col-xs-3  ">
                  <button type="submit" name="abm_proveedormaquina" class="btn btn-primary">Editar Proveedor</button>
              </div>
          </form>
        </div>
      </div>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>