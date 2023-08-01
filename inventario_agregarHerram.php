<?php
  require_once('includes/load.php');
  page_require_level(2);

  $marcaOK=$_POST['marca_h'];
  $tipoOK=$_POST['tipo_h'];
  $descripcionOK=$_POST['descripcion_h'];
  $cantidadOK=$_POST['cantidad_h'];
  $codOk=$_POST['cod_h'];

  $sql="INSERT INTO inv_herramientas (`marca`, `tipo`, `descripcion`, `cant`, `cod`) VALUES ('{$marcaOK}', '{$tipoOK}', '{$descripcionOK}', '{$cantidadOK}', '{$codOk}')";
  echo $result=$db->query($sql);
?>