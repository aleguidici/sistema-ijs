<?php
  require_once('includes/load.php');
  page_require_level(2);

  $nombreOK=$_POST['nombre_e'];
  $apellidoOK=$_POST['apellido_e'];
  $dniOK=$_POST['dni_e'];
  $cargoOK=$_POST['cargo_e'];
  $tel1OK=$_POST['tel1_e'];
  $esrespOK=$_POST['esResp_e'];

  $sql = "INSERT INTO personal ( `dni`, `apellido`, `nombre`, `cargo`, `tel1`, `responsable_servicios`, `baja`, `tercero`) VALUES ('{$dniOK}','{$apellidoOK}','{$nombreOK}','{$cargoOK}', '{$tel1OK}','{$esrespOK}',0,1)";
  $session->msg("s","Personal creado.");
  echo $result=$db->query($sql);
?>