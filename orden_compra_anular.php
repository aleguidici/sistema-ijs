<?php
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);

  $orden_compra = (int)$_GET['id'];
  $valor = (int)$_GET['val'];

  if(empty($errors)){
    $sql = "UPDATE orden_compra SET anulado='{$valor}' WHERE id = '{$orden_compra}'";
    $result = $db->query($sql);

    if($result && $db->affected_rows() === 1) {
      $session->msg("s", "Orden de compra actualizada con éxito.");
      redirect('ordenes_compra.php');
    } else {
      $session->msg("d", "No se realizaron cambios.");
      redirect('ordenes_compra.php');
    }
  } else {
    $session->msg("d", $errors);
    redirect('ordenes_compra.php');
  }
?>