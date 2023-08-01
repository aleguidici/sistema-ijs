<?php
  require_once('includes/load.php');
  page_require_level(2);
  $idCajaActual = $_POST['idCajaActual'];

  $sql_1 = "DELETE FROM `caja` WHERE `id`='{$idCajaActual}'";
  $result1 = $db->query($sql_1);
  
  if($result1 == 1) {
    $sql_2 = $db->query("SELECT * FROM `caja` WHERE `fecha_inicio` = (SELECT MAX(`fecha_inicio`) FROM `caja`)");
    $cajaNull = $db->fetch_assoc($sql_2);
    if ($cajaNull['fecha_cierre'] == null || $cajaNull['hora_cierre'] == null) {
      echo $result1 = 1;
    } else {
      $sql_3 = "UPDATE `caja` SET `fecha_cierre`= null, `hora_cierre`= null, `estado`= 1 WHERE `id` = ".$cajaNull['id'];
      $result2 = $db->query($sql_3);
      echo $result = 1;
    }
  }
 /* if($result1 == 1) {
  	$sqlUpdate_2 = "UPDATE caja SET `estado`= 1 WHERE `id`='{$idCajaCambio}'";
  	sleep(1);
  	echo $result2 = $db->query($sqlUpdate_2);
  }*/

?>