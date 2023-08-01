<?php
  $page_title = 'Nueva imagen para Proyecto';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
  $proyecto = find_by_id('proyecto',$_GET['id']);
?>

<?php
  if(isset($_POST['submit'])) {
    $photo = new Media();
    $photo->upload($_FILES['file_upload']);
    $descrip = $_POST["descrip_img"];

    if($photo->process_media('2')) {
      $img = find_last('imagen');
      $fecha = date('Y-m-d');

      $sql  = "INSERT INTO proy_imagen ( `id_proyecto`, `id_imagen`, `descripcion`,`fecha_actualizacion`) VALUES ('{$proyecto['id']}','{$img['id']}','{$descrip}','{$fecha}')";

      if($db->query($sql)){

        $session->msg('s','Imagen subida');
        redirect("conAc_proyecto.php?id=".$proyecto['id']);

      } else {
        $session->msg("d", "Lo siento, el registro falló");
        redirect("conAc_proyecto.php?id=".$proyecto['id']);
      }
    } else {
      $session->msg('d',join($photo->errors));
      redirect("conAc_proyecto.php?id=".$proyecto['id']);
    }
  }

?>
<?php include_once('layouts/header.php'); ?>


<div class="col-md-10">
<form class="form-inline" action="conAc_agregarImagen.php?id=<?php echo (int)$_GET['id']?>" method="POST" enctype="multipart/form-data">
  <div class="panel panel-default">
    <div class="panel-heading">
      <strong>
        <span class="glyphicon glyphicon-th"></span>
        <span>Nueva imagen de project para: <?php echo $proyecto['nombre_proyecto']; ?> [ID. IJS: <?php echo $proyecto['id'];?>]</span>
      </strong>
    </div>

    <div class="panel-body">
      <div class="row">
        <div class="col-md-5">
          <h5><b>Seleccione la imagen: </b></h5>
        </div>
        <div class="col-md-7">
          <h5><b>Descripción: </b></h5>
        </div>
      </div>

      <div class="row">
        <div class="col-md-5">
          <div class="input-group">
            <input type="file" name="file_upload" multiple="multiple" class="btn btn-primary btn-file" required/>
          </div>
        </div>
        <div class="col-md-7">
          <textarea style="resize: none; border:1px solid #000000" type="name" class="form-control" placeholder="Descripción" id="descrip_img" name="descrip_img" required maxlength="255" cols="55" onkeypress="return blockSpecialChar(event)"></textarea>
        </div>
      </div>
      <hr />
      <div class="pull-right">
        <a href="conAc_proyecto.php?id=<?php echo (int)$_GET['id']?>" class="btn btn-primary">Volver</a>
        <button type="submit" name="submit" class="btn btn-success" role="button">Aceptar</button>
      </div>
    </div>

    <script>
      function blockSpecialChar(e) {
        var k = e.keyCode;
        return (!(k == 34 || k == 39));
      };
    </script>

  </div>
</form>
</div>


<?php include_once('layouts/footer.php'); ?>
