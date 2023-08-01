<?php
  require_once('includes/load.php');
  page_require_level(2);

  $idOK = $_POST['id'];
  $nombreOK=$_POST['nombre_p'];
  $fechaOK=$_POST['fecha_p'];
  $clienteOK=$_POST['cliente_p'];
  $descripcionOK=$_POST['descripcion_p'];
  $linkPrivOk=$_POST['link_priv_p'];
  $linkPubOk=$_POST['link_publico_p'];

  $sql="UPDATE proyecto SET nombre_proyecto='{$nombreOK}', fecha_inicio='{$fechaOK}', id_cliente='{$clienteOK}', descripcion='{$descripcionOK}', link_IJS='{$linkPrivOk}', link_public='{$linkPubOk}' WHERE id = '{$idOK}'";
  echo $result=$db->query($sql);
?>