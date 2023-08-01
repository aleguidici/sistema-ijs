<?php
  $page_title = 'Instrumentos';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
  
  $all_instrumentos = find_all('inv_instrumentos');
  $current_user_ok = current_user();
?>

<?php
 if(isset($_POST['add_cat'])){
   $req_field = array('fecha');
   validate_fields($req_field);
   //$num_name = remove_junk($db->escape($_POST['numero']));
   $marc_name = remove_junk($db->escape($_POST['marca']));
   $model_name = remove_junk($db->escape($_POST['modelo']));
   $numser_name = remove_junk($db->escape($_POST['numeroser']));
   $fech_name = remove_junk($db->escape($_POST['fecha']));
   if(empty($errors)){
      $sql  = "INSERT INTO inv_instrumentos ( `marca`, `modelo`, `num_serie`, `fecha_calibracion`) VALUES ( '{$marc_name}','{$model_name}','{$numser_name}','{$fech_name}')";
      if($db->query($sql)){
        $session->msg("s", "Instrumento agregado exitosamente.");
        redirect('instrumento.php',false);
      } else {
        $session->msg("d", "Lo siento, el registro falló");
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
  </div>
   <div class="row">
    <div class="col-md-5">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Agregar Instrumento</span>
         </strong>
        </div>
        <div class="panel-body">
          <form method="post" action="instrumento.php">
            <div class="form-group">
                <label for="marca" class="control-label">Marca:</label>
                <input type="text" class="form-control" name="marca" placeholder="Marca" maxlength="80" required pattern="[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s]+" title="Sólo letras o números">
            </div>
            <div class="form-group">
                <label for="modelo" class="control-label">Modelo:</label>
                <input type="text" class="form-control" name="modelo" placeholder="Modelo" maxlength="80" required pattern="[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ.\s-]+" title="Sólo letras o números">
            </div>
            <div class="form-group">
                <label for="numeroser" class="control-label">Número de serie:</label>
                <input type="text" class="form-control" name="numeroser" placeholder="Número de Serie" maxlength="20" required pattern="[a-zA-Z0-9.\-\s/]+" title="No es un formato aceptado">
            </div>
            <div class="form-group">
              <label for="fecha" class="control-label">Fecha de calibración</label>
              <input type="text" class="datepicker form-control" name="fecha" id="fecha" readonly required>
            </div>
            <button type="submit" name="add_cat" class="btn btn-primary" style="float: right;">Agregar Intrumento</button>
        </form>
        </div>
      </div>
    </div>
    <div class="col-md-7">
      <div class="panel panel-default">
        <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Lista de Instrumentos</span>
       </strong>
      </div>
        <div class="table-responsive">
          <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <!-- <th class="text-center" style="width: 50px;">#</th> -->
                    <th class="text-center">Número Instrumento</th>
                    <th class="text-center">Marca</th>
                    <th class="text-center">Modelo</th>
                    <th class="text-center">Número Serie</th>
                    <th class="text-center">Fecha Calibración</th>
                    <th class="text-center" style="width: 100px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
              <?php foreach ($all_instrumentos as $un_instrum):?>
                <tr>
                    <!-- <td class="text-center"><?php echo count_id();?></td> -->
                    <td class="text-center"><?php echo remove_junk(ucfirst($un_instrum['nro_instrumento'])); ?></td>
                    <td><?php echo remove_junk(ucfirst($un_instrum['marca'])); ?></td>
                    <td><?php echo remove_junk(ucfirst($un_instrum['modelo'])); ?></td>
                    <td><?php echo remove_junk(ucfirst($un_instrum['num_serie'])); ?></td>
                    <td><?php echo remove_junk(ucfirst($un_instrum['fecha_calibracion'])); ?></td>
                    <td class="text-center">
                      <div class="btn-group">
                        <a href="edit_instrumento.php?id=<?php echo (int)$un_instrum['nro_instrumento'];?>"  class="btn btn-xs btn-warning" data-toggle="tooltip" title="Editar">
                          <span class="glyphicon glyphicon-edit"></span>
                        </a>
                        <?php if ($current_user_ok['user_level'] <= 1) {?>
                          <a href="delete_instrumento.php?id=<?php echo (int)$un_instrum['nro_instrumento'];?>" class="btn btn-xs btn-danger"  title="Eliminar" data-toggle="tooltip" onclick="return confirm('¿Seguro que desea eliminar este instrumento?')">
                            <span class="glyphicon glyphicon-trash"></span>
                          </a>
                        <?php } ?>
                      </div>
                    </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
       </div>
    </div>
    </div>
   </div>

  <?php include_once('layouts/footer.php'); ?>
