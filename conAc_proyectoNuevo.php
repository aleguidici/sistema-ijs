<?php
  require_once('includes/load.php');
  page_require_level(2);

  $nombreOK=$_POST['nombre_p'];
  $fechaOK=$_POST['fecha_p'];
  $clienteOK=$_POST['cliente_p'];
  $descripcionOK=$_POST['descripcion_p'];
  $linkPrivOk=$_POST['link_priv_p'];
  $linkPubOk=$_POST['link_publico_p'];

  $sql="INSERT INTO proyecto (`nombre_proyecto`, `fecha_inicio`, `id_cliente`, `descripcion`, `link_IJS`, `link_public`, `estado`) VALUES ('{$nombreOK}', '{$fechaOK}', '{$clienteOK}', '{$descripcionOK}', '{$linkPrivOk}', '{$linkPubOk}', 1)";
  echo $result=$db->query($sql);
?>