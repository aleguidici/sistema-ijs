<?php
  require_once('includes/load.php');
  page_require_level(2);
  $sql_caja_activa = $db->query("SELECT * FROM caja WHERE estado <> 0 LIMIT 1");
  $cajaActiva = $db->fetch_assoc($sql_caja_activa);
  $idCajaCierre = $_POST['idCajaCierre'];
  $fondoCierre = $_POST['fondoCierre'];
  $arrayFechaActual = getDate();
  $dayActual = $arrayFechaActual['mday'];
  $monthActual = $arrayFechaActual['mon'];
  $yearActual = $arrayFechaActual['year'];
  $horaActual = $arrayFechaActual['hours'];
  $minActual = $arrayFechaActual['minutes'];
  $secActual = $arrayFechaActual['seconds'];
  $fechaActual = $yearActual."-".$monthActual."-".$dayActual;
  $horaComp = $horaActual.":".$minActual.":".$secActual;

  $fecha = $cajaActiva['fecha_inicio'];
    $partes = array();
    $partes = explode("-", $fecha);
  if ($partes[0] == $yearActual && $partes[1] == $monthActual) {
    echo $result = 2;
  } else {
    $sql_update_1 = "UPDATE caja SET `fecha_cierre`='{$fechaActual}', `hora_cierre`='{$horaComp}', `fondo_cierre`='{$fondoCierre}', `estado`=0  WHERE `id`='{$idCajaCierre}'";
    $result1 = $db->query($sql_update_1);

    if ($result1 == 1) {
      if ($cajaActiva) {
        $idCajaActiva = $cajaActiva['id'];
        $sql_update_2 = "UPDATE caja SET `estado`=0 WHERE `id`='{$idCajaActiva}'";
        sleep(1);
        $result2 = $db->query($sql_update_2);
      }
    }

    if ($result1 == 1) {
      $horaComp2 = $horaActual.":".$minActual.":".$secActual;
    	$sqlInsert = "INSERT INTO `caja`(`fecha_inicio`, `hora_inicio`, `fondo_inicio`, `estado`) VALUES ('{$fechaActual}','{$horaComp2}','{$fondoCierre}',1)";
    	echo $result3 = $db->query($sqlInsert);
    } 
    
  }
?>