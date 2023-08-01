<?php
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);

  $maq = find_by_id('inv_maquinarias',(int)$_GET['id']);
  if(!$maq){
    $session->msg("d","Maquinaria o instrumento no encontrado.");
    redirect('inventario.php');
  }

  $sql = "DELETE FROM inv_maq_historial WHERE id_maq=". (int)$maq['id'];
  $db->query($sql);

  $delete_id = delete_by_id('inv_maquinarias',(int)$maq['id']);
  if($delete_id){
    $session->msg("s","Maquinaria o instrumento eliminado.");
    redirect('inventario.php');
  } else {
    $session->msg("d","Eliminación falló.");
    redirect('inventario.php');
  }
?>

