<?php
  require_once('includes/load.php');
  page_require_level(2);
  $idEgreso = $_POST['idEgreso'];
  $idCaja = $_POST['idCaja']; 
  $idOrigen = $_POST['idOrigen'];
  $idPersonal = $_POST['idPersonal'];
  $fecha = $_POST['fecha'];
    $partes = array();
    $partes = explode("-", $fecha);
  $arreglo = array($partes[2], $partes[1], $partes[0]);
  $fechaOk = implode("-", $arreglo);
  $hora = $_POST['hora'];
  $monto = $_POST['montoEgreso'];
  $idMoneda = $_POST['idMoneda'];
  $numFactura = $_POST['numFactura'];
  $numRemito = $_POST['numRemito'];
  $tipoProveedor = $_POST['tipoProveedor'];
  $idProveedor = $_POST['proveedor'];
  $proveedorEspecificar = $_POST['proveedorEspecificar'];
  $idConcepto = $_POST['idConcepto'];
  $conceptoAdicional = $_POST['conceptoEspecificar'];
  $litrosCombustible = $_POST['litrosCombustible'];
  $aclaracion = $_POST['aclaracion'];
  $b = $_POST['b'];

if ($b == 1 || $b == 3) {
  if ($idPersonal != 0) {
    $persot = ",`personal_id`";
    $persov = ",'{$idPersonal}'";
    $persou = ",`personal_id`='{$idPersonal}'";
  } else {
    $persot = "";
    $persov = "";
    $persou = "";
  }
////////////////////////////////////////////////
  if ($idConcepto) {
    $conceptot = ", `aclaracion`";
    $conceptov = ",'{$aclaracion}'";
    $conceptou = ", `aclaracion`='{$aclaracion}'";
    if ($idConcepto == 1 || $idConcepto == 14) {
      $conceptot = ", `litros_combustible`";
      $conceptov = ",'{$litrosCombustible}'";
      $conceptou = ", `litros_combustible`='{$litrosCombustible}'";
    }
    if ($idConcepto == 12 ) {
      $conceptot = ", `concepto_adicional`";
      $conceptov = ",'{$conceptoAdicional}'";
      $conceptou = ", `concepto_adicional`='{$conceptoAdicional}'";
    }
  } else {
    echo $result = 2;
  }
////////////////////////////////////////////////
  if ($tipoProveedor != 0) {
    if ($tipoProveedor == 1 || $tipoProveedor == 2) {
      $t_proveet = ", `tipo_proveedor`";
      $t_proveev = ",'{$tipoProveedor}'";
      $t_proveeu = ", `tipo_proveedor`='{$tipoProveedor}'";
      $id_proveet = ", `proveedor_id`";
      $id_proveev = ",'{$idProveedor}'";
      $id_proveeu = ", `proveedor_id`='{$idProveedor}'";
      $esp_proveet = "";
      $esp_proveev = "";
      $esp_proveeu = "";
    }
    if ($tipoProveedor == 3) {
      $t_proveet = ", `tipo_proveedor`";
      $t_proveev = ",'{$tipoProveedor}'";
      $t_proveeu = ", `tipo_proveedor`='{$tipoProveedor}'";
      $id_proveet = "";
      $id_proveev= "";
      $id_proveeu = "";
      $esp_proveet = ", `proveedor_adicional`";
      $esp_proveev = ",'{$proveedorEspecificar}'";
      $esp_proveeu = ", `proveedor_adicional`='{$proveedorEspecificar}'";
    }
  } else {
    $t_proveet = "";
    $t_proveev = "";
    $t_proveeu = ", `tipo_proveedor`= null";
    $id_proveet = "";
    $id_proveev = "";
    $id_proveeu = ", `proveedor_id`= null";
    $esp_proveet = "";
    $esp_proveev = "";
    $esp_proveeu = ", `proveedor_adicional`= null";
  } 

/////////////////////////////////////////////////
  if ($numFactura == "") {
    $factut = "";
    $factuv = "";
    $factuu = "";
  } else {
    $factut = ", `num_factura`";
    $factuv = ",'{$numFactura}'";
    $factuu = ", `num_factura`='{$numFactura}'";
  }
  if ($numRemito == "") {
    $remit = "";
    $remiv = "";
    $remiu = "";
  } else {
    $remit = ", `num_remito`";
    $remiv = ",'{$numRemito}'";
    $remiu = ", `num_remito`='{$numRemito}'";
  }
}

  //INSERT
  if ($b == 1) { 
    $sql = "INSERT INTO caja_egresos (`caja_id`, `origen_id`".$persot.", `monto`, `fecha`, `hora`, `moneda_id`".$factut." ".$remit." ".$t_proveet." ".$id_proveet." ".$esp_proveet.", `concepto_id`".$conceptot.", `estado`) VALUES ('{$idCaja}','{$idOrigen}'".$persov.",'{$monto}','{$fechaOk}','{$hora}','{$idMoneda}'".$factuv." ".$remiv." ".$t_proveev." ".$id_proveev." ".$esp_proveev.",'{$idConcepto}'".$conceptov.",0)";
    echo $result = $db->query($sql); 

} 
  //DELETE
  if ($b == 2) {
    $sql_delete = "DELETE FROM caja_egresos WHERE id = '{$idEgreso}'";
    echo $result = $db->query($sql_delete);
  }

  //UPDATE
  if ($b == 3) {
    $sql_update = "UPDATE caja_egresos SET `origen_id`='{$idOrigen}'".$persou.",`fecha`='{$fechaOk}',`monto`='{$monto}',`moneda_id`='{$idMoneda}'".$factuu." ".$remiu." ".$t_proveeu." ".$id_proveeu." ".$esp_proveeu.",`concepto_id`='{$idConcepto}'".$conceptou." WHERE `id`='{$idEgreso}'";
    echo $result = $db->query($sql_update);
  }

?>