<?php
  require_once('includes/load.php');
  page_require_level(2);
  $idIngreso = $_POST['idIngreso'];
  $idCaja = $_POST['idCaja'];
  $idOrigen = $_POST['idOrigen'];
  $idPersonal = $_POST['idPersonal'];
  $tipoCliente = $_POST['tipoCliente'];
  $idCliente = $_POST['idCliente'];
  $otroIngreso = $_POST['otroIngreso'];
  $monto = $_POST['monto'];
  $fecha = $_POST['fecha'];
    $partes = array();
    $partes = explode("-", $fecha);
  $arreglo = array($partes[2], $partes[1], $partes[0]);
  $fechaOk = implode("-", $arreglo);
  $hora = $_POST['hora'];
  $tipoIngreso = $_POST['tipoIngreso'];
  $idMoneda = $_POST['idMoneda'];
  $b = $_POST['b'];

  //INSERT
  if($b == 1) { 
    if ($tipoIngreso == 1) {
  	 $sql = "INSERT INTO caja_ingresos (`caja_id`, `origen_id`, `personal_id`, `monto`, `fecha`, `hora`, `tipo_ingreso`, `moneda_id`) VALUES ('{$idCaja}','{$idOrigen}','{$idPersonal}','{$monto}','{$fechaOk}','{$hora}','{$tipoIngreso}','{$idMoneda}')";
  	 echo $result = $db->query($sql);
    }
    if ($tipoIngreso == 2) {
     $sql = "INSERT INTO caja_ingresos (`caja_id`, `origen_id`, `monto`, `fecha`, `hora`, `tipo_ingreso`, `moneda_id`) VALUES ('{$idCaja}','{$idOrigen}','{$monto}','{$fechaOk}','{$hora}','{$tipoIngreso}','{$idMoneda}')";
     echo $result = $db->query($sql);
    }
    if ($tipoIngreso == 3) {
     $sql = "INSERT INTO caja_ingresos (`caja_id`, `origen_id`, `tipo_cliente`, `cliente_id`, `monto`, `fecha`, `hora`, `tipo_ingreso`, `moneda_id`) VALUES ('{$idCaja}','{$idOrigen}','{$tipoCliente}','{$idCliente}','{$monto}','{$fechaOk}','{$hora}','{$tipoIngreso}','{$idMoneda}')";
     echo $result = $db->query($sql);
    }
    if ($tipoIngreso == 4) {
     $sql = "INSERT INTO caja_ingresos (`caja_id`, `origen_id`, `tipo_cliente`, `cliente_id`, `monto`, `fecha`, `hora`, `tipo_ingreso`, `moneda_id`) VALUES ('{$idCaja}','{$idOrigen}','{$tipoCliente}','{$idCliente}','{$monto}','{$fechaOk}','{$hora}','{$tipoIngreso}','{$idMoneda}')";
     echo $result = $db->query($sql);
    }
    if ($tipoIngreso == 5) {
     $sql = "INSERT INTO caja_ingresos (`caja_id`, `origen_id`, `cliente_adicional`, `monto`, `fecha`, `hora`, `tipo_ingreso`, `moneda_id`) VALUES ('{$idCaja}','{$idOrigen}','{$otroIngreso}','{$monto}','{$fechaOk}','{$hora}','{$tipoIngreso}','{$idMoneda}')";
     echo $result = $db->query($sql);
    }
	}
  //DELETE
  if ($b == 2) {
    $sql_delete = "DELETE FROM caja_ingresos WHERE id = '{$idIngreso}'";
    echo $result = $db->query($sql_delete);
  }
  //UPDATE
  if ($b == 3) {
    if ($tipoIngreso == 1) {
      $sql_update = "UPDATE `caja_ingresos` SET `caja_id`='{$idCaja}',`origen_id`='{$idOrigen}',`personal_id`='{$idPersonal}',`tipo_cliente`=null,`cliente_id`=null,`cliente_adicional`=null,`monto`='{$monto}',`fecha`='{$fechaOk}',`hora`='{$hora}',`tipo_ingreso`='{$tipoIngreso}',`moneda_id`='{$idMoneda}' WHERE `id`='{$idIngreso}'";
      echo $result = $db->query($sql_update);
    }
    if ($tipoIngreso == 2) {
      $sql_update = "UPDATE `caja_ingresos` SET `caja_id`='{$idCaja}',`origen_id`='{$idOrigen}',`personal_id`=null,`tipo_cliente`=null,`cliente_id`=null,`cliente_adicional`=null,`monto`='{$monto}',`fecha`='{$fechaOk}',`hora`='{$hora}',`tipo_ingreso`='{$tipoIngreso}',`moneda_id`='{$idMoneda}' WHERE `id`='{$idIngreso}'";
      echo $result = $db->query($sql_update);
    }
    if ($tipoIngreso == 3) {
      $sql_update = "UPDATE `caja_ingresos` SET `caja_id`='{$idCaja}',`origen_id`='{$idOrigen}',`personal_id`=null,`tipo_cliente`=1,`cliente_id`='{$idCliente}',`cliente_adicional`=null,`monto`='{$monto}',`fecha`='{$fechaOk}',`hora`='{$hora}',`tipo_ingreso`='{$tipoIngreso}',`moneda_id`='{$idMoneda}' WHERE `id`='{$idIngreso}'";
      echo $result = $db->query($sql_update);
    }
    if ($tipoIngreso == 4) {
      $sql_update = "UPDATE `caja_ingresos` SET `caja_id`='{$idCaja}',`origen_id`='{$idOrigen}',`personal_id`=null,`tipo_cliente`=2,`cliente_id`='{$idCliente}',`cliente_adicional`=null,`monto`='{$monto}',`fecha`='{$fechaOk}',`hora`='{$hora}',`tipo_ingreso`='{$tipoIngreso}',`moneda_id`='{$idMoneda}' WHERE `id`='{$idIngreso}'";
      echo $result = $db->query($sql_update);
    }
    if ($tipoIngreso == 5) {
      $sql_update = "UPDATE `caja_ingresos` SET `caja_id`='{$idCaja}',`origen_id`='{$idOrigen}',`personal_id`=null,`tipo_cliente`=null,`cliente_id`=null,`cliente_adicional`='{$otroIngreso}',`monto`='{$monto}',`fecha`='{$fechaOk}',`hora`='{$hora}',`tipo_ingreso`='{$tipoIngreso}',`moneda_id`='{$idMoneda}' WHERE `id`='{$idIngreso}'";
      echo $result = $db->query($sql_update);
    }
  }

?>