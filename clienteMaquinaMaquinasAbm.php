<?php
  require_once('includes/load.php');
  page_require_level(2);
  $idMaquinaOk = remove_junk($db->escape($_POST['idMaquinaOk']));
  $serieOk = remove_junk($db->escape($_POST['serieOk']));
  $detalleOk = remove_junk($db->escape($_POST['detalleOk']));
  $bOk = remove_junk($db->escape($_POST['bOk']));


  //UPDATEAR
  if ($bOk == 1) { 
  	$sql1_0="UPDATE maquina SET num_serie = '{$serieOk}', detalles = '{$detalleOk}' WHERE id = '{$idMaquinaOk}'";
    echo $result=$db->query($sql1_0);
	}
  //ELIMINAR
  if ($bOk == 2) {
  	$sql2_0="DELETE FROM maquina WHERE id = '{$idMaquinaOk}' AND num_serie = '{$serieOk}'";
  	echo $result=$db->query($sql2_0);
  }

?>