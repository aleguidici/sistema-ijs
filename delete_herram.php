<?php
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);

  $herram = find_by_id('inv_herramientas',(int)$_GET['id']);
  if(!$herram){
    $session->msg("d","Herramienta no encontrada.");
    redirect('inventario.php');
  }

  $delete_id = delete_by_id('inv_herramientas',(int)$herram['id']);
    if($delete_id){
        $session->msg("s","Herramienta eliminada.");
        redirect('inventario.php');
    } else {
      $session->msg("d","Eliminación falló.");
      redirect('inventario.php');
    }
?>

