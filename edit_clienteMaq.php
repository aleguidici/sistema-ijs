<?php
  $page_title = 'Editar Cliente - Máquina';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
  $all_provincias = find_all('provincia');

  $clienteMaqui = find_by_id('clientemaquina',(int)$_GET['id']);
  if(!$clienteMaqui){
    $session->msg("d","Missing cliente id.");
    redirect('clientes.php');
  }
?>

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

    if(empty($errors)){ 
      if($tel === '0' || $tel === '')
        $tel = '';
      if($cel === '0' || $cel === '')
        $cel = '';
      $query = "UPDATE clientemaquina SET cuit='{$cuit}', email='{$email}', razon_social='{$razon_social}', direccion='{$direccion}', localidad='{$localidad}', provincia='{$provincia}', cp='{$cp}', tel='{$tel}', cel='{$cel}', iva='{$cond_IVA}' WHERE id = '{$clienteMaqui['id']}'";  
      
      $result = $db->query($query);
      if($result && $db->affected_rows() === 1) {
        $session->msg("s", "Cliente actualizado con éxito.");
        redirect('clientes.php?tabclieme=2',false);
      } else {
        $session->msg("d", "Lo siento, actualización falló.");
        redirect('clientes.php?tabclieme=2',false);
      }               
    }else{
      $session->msg("d", $errors);
      redirect('clientes.php?tabclieme=2',false);
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
          <span>Editando Cliente - Máquina: Código interno Nº <?php echo $clienteMaqui['id'];?></span> 
        </strong>
      </div>

      <form method="post" action="edit_clienteMaq.php?id=<?php echo (int)$clienteMaqui['id'];?>">
        <div class="panel-body">
          <div class="form-group col-xs-6 mr-2">
              <label for="razon_social" class="control-label">Razón Social:</label>
              <input type="name" class="form-control" name="razon_social" placeholder="Razon social" maxlength="100" required pattern="[a-zA-Z0-9ñÑÜüáéíóúÁÉÍÓÚº\-\.()\s/,\s]+" title="Sólo letras o números" value="<?php echo $clienteMaqui['razon_social'];?>">
          </div>
          <div class="form-group col-xs-3 mr-2 ">
              <label for="condicion_iva" class="control-label">Condición ante el IVA (opcional):</label>
              <select required="required" class="form-control" name="condicion_iva" id="condicion_iva">
                  <option value="" disabled>Seleccione una condición</option>
                <?php
                $ivaCondiciones = find_all('iva_condiciones');
                foreach ($ivaCondiciones as $unaCondicionIva): ?>
                <option value="<?php echo $unaCondicionIva['id']; ?>"><?php echo $unaCondicionIva['descripcion']; ?></option>
                <?php endforeach; ?>
              </select>
              <script>
                  var val = "<?php echo $clienteMaqui['iva'] ?>";
                  document.getElementById("condicion_iva").value = val;
              </script>
          </div>
          <div class="form-group col-xs-3 mr-2">
              <label for="cuit" class="control-label">Cuit:</label>
              <input type="name" name= "cuit" class="form-control" placeholder="Cuit" required pattern="[0-9\-]+" title="Sólo números y guiones" value="<?php echo $clienteMaqui['cuit'];?>">
          </div>  
          <div class="form-group col-xs-6  mr-2">
              <label for="direccion" class="control-label">Dirección:</label>
              <input type="name" name= "direccion" class="form-control" placeholder="Dirección" pattern="[a-zA-Z0-9/-,ñÑÜüáéíóúÁÉÍÓÚº\-\.()\s/,\s]+" title="Sólo letras o números" value="<?php echo $clienteMaqui['direccion'];?>">
          </div>    
          <div class="form-group col-xs-3 mr-2 ">
              <label for="localidad" class="control-label">Localidad:</label>
              <input type="name" name= "localidad" class="form-control" placeholder="Localidad" pattern="[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ.\s]+" title="Sólo letras" value="<?php echo $clienteMaqui['localidad'];?>">
          </div>
          <div class="form-group col-xs-3 mr-2">
              <label for="provincia" class="control-label">Provincia / Estado:</label>
              <select required="required" class="form-control" name="provin" id="provin">
                  <option value="" disabled selected>Seleccione una provincia</option>
                  <?php  foreach ($all_provincias as $prov): ?>
                      <option value="<?php echo (int) $prov['id_provincia']?>">
                      <?php echo $prov['nombre'];?></option>
                  <?php endforeach; ?>
              </select>
              <script>
                  var val = "<?php echo $clienteMaqui['provincia'] ?>";
                  document.getElementById("provin").selectedIndex = Number(val);
              </script>
          </div>
          <div class="form-group col-xs-6  mr-2">
              <label for="direccion" class="control-label">Correo Electrónico:</label>
              <input type="name" name= "email" class="form-control" placeholder="Correo Electrónico" pattern="[a-zA-Z0-9/-,ñÑÜüáéíóúÁÉÍÓÚº\-\.()\s/,\s]+" title="Sólo letras o números" value="<?php echo $clienteMaqui['email'];?>">
          </div> 
          <div class="form-group col-xs-2 mr-2 ">
              <label for="codigo_postal" class="control-label">C.P.:</label>
              <input type="name" name= "codigo_postal" class="form-control" placeholder="C.P." pattern="[0-9\s]+" title="Sólo números" value="<?php echo $clienteMaqui['cp'];?>">
          </div>
          <div class="form-group col-xs-5 mr-2 ">
              <label for="telefono" class="control-label">Teléfono (Opcional):</label>
              <input type="name" name= "telefono" class="form-control" placeholder="Teléfono" pattern="[()0-9.+\-\s]+" title="Sólo letras o números" value="<?php echo $clienteMaqui['tel'];?>">
          </div>
          <div class="form-group col-xs-5 mr-2 ">
              <label for="celular" class="control-label">Celular (Opcional):</label>
              <input type="name" name= "celular" class="form-control" placeholder="Celular" pattern="[()0-9.+\-\s]+" title="Sólo letras o números" value="<?php echo $clienteMaqui['cel'];?>">
          </div>
        </div>
        <div class="panel-body">
          <div class="form-group text-right">
              <a class="btn btn-primary" href="clientes.php?tabclieme=2" role=button>Volver a Clientes</a>
              <button type="submit" name="abm_cliente" class="btn btn-danger">Editar Cliente - Máquina</button>     
          </div>     
        </div>
      </form>
    </div>
  </div>
</div>
<?php include_once('layouts/footer.php'); ?>
