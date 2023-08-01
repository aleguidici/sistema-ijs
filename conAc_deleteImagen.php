<?php
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
?>
<?php
  $find_media = find_by_id('imagen',(int)$_GET['id']);
  $photo = new Media();
  
  $delete = delete_img_proyecto((int)$_GET['proyecto'], (int)$_GET['id']);

if($delete){
  if($photo->media_destroy($find_media['id'],$find_media['file_name'])){
      $session->msg("s","Se ha eliminado la imagen.");
      redirect("conAc_proyecto.php?id=". $_GET['proyecto']);
  } else {
      $session->msg("d","Se ha producido un error en la eliminación de la imagen.");
      redirect("conAc_proyecto.php?id=". $_GET['proyecto']);
  }
} else {
    $session->msg("d","Eliminación falló");
    redirect('clientes.php');
}


  
?>