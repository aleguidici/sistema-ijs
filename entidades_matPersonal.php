<?php
  require_once('includes/load.php');
  page_require_level(2);

  $persoOK=$_POST['perso'];
  $numOK=$_POST['num'];
  $provOK=$_POST['prooo'];

  $sql = "INSERT INTO personal_matriculas ( `id_personal`, `id_provincia`, `num_matricula`) VALUES ('{$persoOK}','{$provOK}','{$numOK}')";
  $session->msg("s","Matrícula agregada.");
  echo $result=$db->query($sql);
?>