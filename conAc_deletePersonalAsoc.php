<?php
  require_once('includes/load.php');
  page_require_level(2);

  $sql="DELETE FROM proy_personalafectado WHERE id_proyecto = '{$_GET['id_proy']}' AND id_personal = '{$_GET['id_pers']}'";

  $result=$db->query($sql);

  if($result && $db->affected_rows() === 1) {
    $session->msg("s","Personal eliminado.");
      redirect("conAc_proyecto.php?id=". $_GET['id_proy']);
  } else {
      $session->msg("d","Eliminación falló.");
      redirect("conAc_proyecto.php?id=". $_GET['id_proy']);
  }
?>