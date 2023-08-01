<?php
  $page_title = 'Editar instrumento';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
?>
<?php
  //Display all instrumentos.
  $instrument = find_by_num_ins('inv_instrumentos',(int)$_GET['id']);
  if(!$instrument){
    $session->msg("d","No se encuentra el instrumento.");
    redirect('instrumento.php');
  }
?>

<?php
if(isset($_POST['edit_cat'])){
  $req_field = array('marca','modelo','numeroser','fecha');
  validate_fields($req_field);
   //$num_name = remove_junk($db->escape($_POST['numero']));
   $marc_name = remove_junk($db->escape($_POST['marca']));
   $model_name = remove_junk($db->escape($_POST['modelo']));
   $numser_name = remove_junk($db->escape($_POST['numeroser']));
   $fech_name = remove_junk($db->escape($_POST['fecha']));
  if(empty($errors)){
      $sql = "UPDATE inv_instrumentos SET marca='{$marc_name}',modelo='{$model_name}', num_serie='{$numser_name}',fecha_calibracion='{$fech_name}'WHERE nro_instrumento = '{$instrument['nro_instrumento']}'";
     $result = $db->query($sql);
     if($result && $db->affected_rows() === 1) {
       $session->msg("s", "Instrumento actualizado con éxito.");
       redirect('instrumento.php',false);
     } else {
       $session->msg("d", "Lo siento, actualización falló.");
       redirect('instrumento.php',false);
     }
  } else {
    $session->msg("d", $errors);
    redirect('instrumento.php',false);
  }
}
?>
<?php include_once('layouts/header.php'); ?>

  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round|Open+Sans">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="stylesheet" href="http://weareoutman.github.io/clockpicker/dist/bootstrap-clockpicker.min.css">
   
  <script src="http://weareoutman.github.io/clockpicker/dist/bootstrap-clockpicker.min.js"></script>

  <div class="row">
    <div class="col-md-12">
      <?php echo display_msg($msg); ?>
    </div>
    <div class="col-md-5">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Editando Instrumento Nº <?php echo remove_junk(ucfirst($instrument['nro_instrumento']));?></span> 
          </strong>
        </div>
        <div class="panel-body">
          <form method="post" action="edit_instrumento.php?id=<?php echo (int)$instrument['nro_instrumento'];?>">
            <div class="form-group">
              <label for="marca" class="control-label">Marca:</label>
              <input type="text" class="form-control" name="marca" value="<?php echo remove_junk(ucfirst($instrument['marca']));?>">
            </div>
            <div class="form-group">
              <label for="modelo" class="control-label">Modelo:</label>
              <input type="text" class="form-control" name="modelo" value="<?php echo remove_junk(ucfirst($instrument['modelo']));?>">
            </div>
            <div class="form-group">
              <label for="numeroser" class="control-label">Número de serie:</label>
              <input type="text" class="form-control" name="numeroser" value="<?php echo remove_junk(ucfirst($instrument['num_serie']));?>">
            </div>
            <div class="form-group">
              <label for="fecha" class="control-label">Fecha de calibración</label>
              <input type="text" class="datepicker form-control" name="fecha" id="fecha" value="<?php echo remove_junk(ucfirst($instrument['fecha_calibracion']));?>" readonly>
            </div>
            <a href="instrumento.php" class="btn btn-success">Volver</a>
            <button type="submit" name="edit_cat" class="btn btn-primary" style="float: right;">Actualizar Instrumento</button>
          </form>
        </div>
      </div>
    </div>
  </div>

<?php include_once('layouts/footer.php'); ?>