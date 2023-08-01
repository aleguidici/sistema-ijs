<?php
  require_once('includes/load.php');
  page_require_level(2);

  $id_maqOK = $_POST['id_maquin'];
  $estadoOK=$_POST['estado_nuevo'];
  $detalleOK=$_POST['detalle_nuevo'];
  $hoy = date('Y-m-d');

  
  $sql2="INSERT INTO inv_maq_historial (`id_maq`, `detalle`, `fecha`, `estado`) VALUES ('{$id_maqOK}', '{$detalleOK}', '{$hoy}', '{$estadoOK}')";
  echo $result=$db->query($sql2);
?>