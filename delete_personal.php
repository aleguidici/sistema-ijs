<?php
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);
  $personal = find_by_id('personal',(int)$_GET['id']);
  if(!$personal){
    $session->msg("d","Personal no encontrado.");
    redirect('entidades_personal.php');
  }
?>
<?php
  $sql = "UPDATE personal SET baja='1' WHERE id = '{$personal['id']}'";

  $result = $db->query($sql);
  if($result && $db->affected_rows() === 1) {
    $session->msg("s","Personal eliminado.");
    redirect('entidades_personal.php', false);
  } else {
    $session->msg("d","Eliminación falló.");
    redirect('entidades_personal.php', false);
  }

?>