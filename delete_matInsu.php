<?php
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);

  $matInsu = find_by_id('inv_materiales_insumos',(int)$_GET['id']);
  if(!$matInsu){
    $session->msg("d","Material o insumo no encontrado.");
    redirect('inventario.php');
  }

  $delete_id = delete_by_id('inv_materiales_insumos',(int)$matInsu['id']);
    if($delete_id){
        $session->msg("s","Material o insumo eliminado.");
        redirect('inventario.php');
    } else {
        $session->msg("d","Eliminación falló.");
        redirect('inventario.php');
    }
?>

