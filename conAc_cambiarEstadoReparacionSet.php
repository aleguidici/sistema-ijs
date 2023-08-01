<?php
  require_once('includes/load.php');
  page_require_level(2);

  $idOK=$_GET['id'];
  $estadoOK=$_GET['estadoCambio'];

  if ($estadoOK != 8) {
	$sql="UPDATE reparacion_maquina SET id_estado = '{$estadoOK}' WHERE id = '{$idOK}'";
	echo $result=$db->query($sql);
	header("Location: conAc.php");
  } else {
    $fecha = date('Y-m-d');
    $hora = date('H:i:s');
    $sql="UPDATE reparacion_maquina SET id_estado = '{$estadoOK}', fecha_egreso = '{$fecha}', hora_egreso = '{$hora}' WHERE id = '{$idOK}'";
    echo $result=$db->query($sql);
    sleep(1);
    $sql_1="UPDATE reparacion_cotizacion SET fecha_entrega_maquina = '{$fecha}' WHERE reparacion_id = '{$idOK}'";
    $result2=$db->query($sql_1);
    header("Location: conAc.php");  	
  }


?>