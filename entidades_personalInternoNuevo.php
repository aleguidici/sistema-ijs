<?php
  require_once('includes/load.php');
  page_require_level(2);

  $nombreOK=$_POST['nombre_p'];
  $apellidoOK=$_POST['apellido_p'];
  $dniOK=$_POST['dni_p'];
  $cargoOK=$_POST['cargo_p'];
  $tel1OK=$_POST['tel1_p'];
  $esrespOK=$_POST['esResp_p'];

  $sql = "INSERT INTO personal ( `dni`, `apellido`, `nombre`, `cargo`, `tel1`, `responsable_servicios`, `baja`, `tercero`) VALUES ('{$dniOK}','{$apellidoOK}','{$nombreOK}','{$cargoOK}', '{$tel1OK}','{$esrespOK}',0,0)";
  $session->msg("s","Personal creado.");
  echo $result=$db->query($sql);
?>