<?php
  $page_title = 'Listado de croquis';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
?>
<?php $media_files = find_all_imagenes('0');?>
<?php
  if(isset($_POST['submit'])) {
    $photo = new Media();
    $photo->upload($_FILES['file_upload']);
    if($photo->process_media('0')){
      $session->msg('s','Imagen subida');
      redirect('croquis.php');
    } else{
      $session->msg('d',join($photo->errors));
      redirect('croquis.php');
    }
  }

?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
  </div>

  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <span class="glyphicon glyphicon-camera"></span>
        <span>Listado de croquis</span>
        <div class="pull-right">
          <form class="form-inline" action="croquis.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
              <div class="input-group">
                <input type="file" name="file_upload" multiple="multiple" class="btn btn-primary btn-file"/>
              </div>
              <div class="input-group">
                <button type="submit" name="submit" class="btn btn-default">Subir</button>
              </div>
            </div>
         </form>
        </div>
      </div>
      <div class="panel-body">
        <table class="table">
          <thead>
            <tr>
              <th class="text-center" style="width: 20%;">Imagen</th>
              <th class="text-center" style="width: 40%;">Descripción</th>
              <th class="text-center" style="width: 20%;">Tipo</th>
              <th class="text-center" style="width: 10%;">Acciones</th>
            </tr>
          </thead>
            <tbody>
            <?php foreach ($media_files as $media_file): ?>
              <?php if($media_file['id'] !== '0'){ ?> 
              <tr class="list-inline">
                <td class="text-center">
                  <a target="_blank" href="uploads/imagenes/<?php echo $media_file['file_name'];?>">
                    <img src="uploads/imagenes/<?php echo $media_file['file_name'];?>" class="img-thumbnail" />
                  </a>
                  
                </td>
                <td class="text-center">
                  <?php echo $media_file['file_name'];?>
                </td>
                <td class="text-center">
                  <?php echo $media_file['file_type'];?>
                </td>
                <td class="text-center">
                  <a href="delete_croquis.php?id=<?php echo (int) $media_file['id'];?>" class="btn btn-xs btn-danger"  title="Eliminar" data-toggle="tooltip" onclick="return confirm('¿Seguro que desea eliminar este croquis?')">
                    <span class="glyphicon glyphicon-trash"></span>
                  </a>
                </td>
              </tr>
              <?php }?>
            <?php endforeach;?>

        </tbody>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>
