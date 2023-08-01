<?php
  require_once('includes/load.php');
  page_require_level(2);
  $idCajaCambio = $_POST['idCajaCambio'];
  $idCajaActual = $_POST['idCajaActual'];

  $sqlUpdate_1 = "UPDATE caja SET `estado`= 0 WHERE `id`='{$idCajaActual}'";
  $result1 = $db->query($sqlUpdate_1); 
  
  if($result1 == 1) {
  	$sqlUpdate_2 = "UPDATE caja SET `estado`= 1 WHERE `id`='{$idCajaCambio}'";
  	echo $result2 = $db->query($sqlUpdate_2);
  }
?>