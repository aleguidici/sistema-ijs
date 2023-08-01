<?php
  $page_title = 'Editar Personal';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
  $all_provincias = find_prov_by_pais('1');
?>
<?php
  //Display all instrumentos.
  $personal = find_by_id('personal',(int)$_GET['id']);

  if(!$personal){
    $session->msg("d","Falta dni personal.");
    redirect('entidades_personal.php');
  }
?>

<?php
if(isset($_POST['edit_op'])){
  $req_field = array('Nombre','Apellido','Cargo');
  validate_fields($req_field);

  $dni = remove_junk($db->escape($_POST['Documento']));
  $name = remove_junk($db->escape($_POST['Nombre']));
  $subname = remove_junk($db->escape($_POST['Apellido']));
  $cargo = remove_junk($db->escape($_POST['Cargo']));

  $telOK = remove_junk($db->escape($_POST['teeel1']));
  $respOK = remove_junk($db->escape($_POST['respServ']));
  // $num_mat = remove_junk($db->escape($_POST['Matricula']));
  // $provincia = remove_junk($db->escape($_POST['provin']));

  if(empty($errors)){
    $sql = "UPDATE personal SET dni='{$dni}', apellido='{$subname}', nombre='{$name}',cargo='{$cargo}',baja=0, tel1='{$telOK}', responsable_servicios='{$respOK}' WHERE id = '{$personal['id']}'";
    $result = $db->query($sql);
    if($result && $db->affected_rows() === 1) {
      $session->msg("s", "Personal actualizado con éxito.");
      redirect('entidades_personal.php',false);
    } else {
      $session->msg("d", "No se realizaron cambios.");
      redirect('entidades_personal.php',false);
    }
  } else {
    $session->msg("d", $errors);
    redirect('entidades_personal.php',false);
  }
}
?>
<?php include_once('layouts/header.php'); ?>

<div class="row">
   <div class="col-md-12">
     <?php echo display_msg($msg); ?>
   </div>
   <div class="col-md-10">
     <div class="panel panel-default">
       <div class="panel-heading">
         <strong>
           <span class="glyphicon glyphicon-th"></span>
           <span>Editar Personal</span> 
        </strong>
       </div>
       <div class="panel-body">
         <form method="post" action="edit_personal.php?id=<?php echo (int)$personal['id'];?>">
            <h4>Campos obligatorios:</h4>
            <div class="row">
              <div class="col-md-6">
                <label for="Nombre" class="control-label">Nombre:</label>
                <input type="text" style="border:1px solid #000000" required class="form-control" name="Nombre" value="<?php echo $personal['nombre'];?>" onkeypress="return blockSpecialChar(event)">
              </div>
              <div class="col-md-6">
                <label for="Apellido" class="control-label">Apellido:</label>
                <input type="text" style="border:1px solid #000000" required class="form-control" name="Apellido" value="<?php echo $personal['apellido'];?>" onkeypress="return blockSpecialChar(event)">
              </div>
           </div>

           <br>
           <div class="row">
              <div class="col-md-3">
                <label for="Documento" class="control-label">DNI:</label>
                <input type="text" style="border:1px solid #000000" required class="form-control" name="Documento" value="<?php echo $personal['dni'];?>" onkeypress="return blockSpecialChar(event)">
              </div>
              <div class="col-md-3">
                <label>Es responsable de servicios:</label>
                <select required style="border:1px solid #000000" class="form-control" name="respServ" id="respServ">
                  <option value="0" selected> NO </option>
                  <option value="1"> SI </option>
                </select>
              </div>
              
              <script>
                document.getElementById("respServ").selectedIndex = Number("<?php echo $personal['responsable_servicios'];?>");
              </script>

              <div class="col-md-6">
              <label for="Cargo" class="control-label">Cargo:</label>
              <input type="text" style="border:1px solid #000000" required class="form-control" name="Cargo" value="<?php echo $personal['cargo'];?>"  onkeypress="return blockSpecialChar(event)">
              </div>
            </div>
            <hr style="border-color:black;">

            <h4>Otros datos (Opcionales):</h4>
            <div class="row">
              <div class="col-md-4">
                <label>Teléfono de contacto:</label>
                <input type="name" class="form-control" id="teeel1" name="teeel1" placeholder="Teléfono" maxlength="50" onkeypress="return blockSpecialChar(event)" value="<?php echo $personal['tel1'];?>">
              </div>
            </div>  
            
            <div class="form-group text-right">
              <a class="btn btn-info" href="entidades_personal.php" role=button>Volver</a>
              <button type="submit" name="edit_op" class="btn btn-success">Actualizar</button>
            </div>
          </form>
       </div>

        <script>
          function blockSpecialChar(e) {
            var k = e.keyCode;
            return (!(k == 34 || k == 39));
          }
        </script>
     </div>
   </div>
</div>

<?php include_once('layouts/footer.php'); ?>