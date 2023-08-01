<?php
  require_once('includes/load.php');
  page_require_level(2);

  $modeloOK=$_POST['modeloOK'];
  $codigoOK=$_POST['codigoOK'];
  $parteOK=$_POST['parteOK'];
  $descripcionOK=$_POST['descripcionOK'];
  
  $sql="INSERT INTO maquina_repuesto (`codigo`, `parte`, `descripcion`, `id_modelo`) VALUES ('{$codigoOK}','{$parteOK}','{$descripcionOK}','{$modeloOK}')";
  echo $result=$db->query($sql);
?>