<?php
  require_once('includes/load.php');
  page_require_level(1);
  $activDiaria = $_GET['id'];
  $visar = (int)$_GET['visar'];

  if ($visar == 0) { 
    $sql = "UPDATE proy_actividades_diarias SET visado = 0 WHERE id = '{$activDiaria}'";  
  } else {
    $sql = "UPDATE proy_actividades_diarias SET visado = 1 WHERE id = '{$activDiaria}'";  
  }

  $result = $db->query($sql);

  if($result && $db->affected_rows() === 1) {
    $session->msg("s","Listo!");
    redirect("conAc_actividadDetalles.php?id=". $_GET['id_act']);
  } else {
    $session->msg("d","No hubieron cambios");
    redirect("conAc_actividadDetalles.php?id=". $_GET['id_act']);
  }
?>