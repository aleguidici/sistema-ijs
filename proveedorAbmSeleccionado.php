<?php
  require_once('includes/load.php');
  page_require_level(2);
  $proveOK=remove_junk($db->escape($_POST['idProveedor']));
  $repueOK=remove_junk($db->escape($_POST['idRepuesto']));
  $repaOK=remove_junk($db->escape($_POST['idReparacion']));
  $bOK=remove_junk($db->escape($_POST['b']));
  $precioOK=remove_junk($db->escape($_POST['precio']));
  $cantidadOK=remove_junk($db->escape($_POST['cantidad']));
  $date=remove_junk($db->escape($_POST['date']));
  $partes = array();
  $partes = explode("-",$date);
  $arreglo = array($partes[2], $partes[1], $partes[0]);
  $dateOK = implode("-", $arreglo);
  $elegidoOK = remove_junk($db->escape($_POST['elegido']));
  $estadoOK = remove_junk($db->escape($_POST['idEstado']));
  $ivaOK = remove_junk($db->escape($_POST['iva']));


  $proveMe = find_all_proveedores_me();
  $prove2 = findProveedor2($repaOK,$repueOK);
  $repaExist = findRepaExist($repaOK,$repueOK);
  $maquinaRepuestoFile = find_by_id('maquina_repuesto',$repueOK);
  $maquinaRepuestoStock = $maquinaRepuestoFile['stock'];

  //AGREGAR REPUESTO PARA BUSCAR PROVEEDOR

  //$sql="INSERT INTO reparacion_repuesto (`reparacion_id`, `repuesto_id`, `id_proveedor`) VALUES ('{$idReparacionSelOk}','{$idRepuestoSelOk}', 2)";
  //echo $result=$db->query($sql);


  //AGREGAR REPUESTO + PROVEEDOR VACIO, IJS U OTRO.
  if ($bOK == 1) {
    if ($proveOK == 1) {
      if ($repaExist) {
      } else {
        if ($maquinaRepuestoStock > 0) {
          $maquinaRepuestoStock = $maquinaRepuestoStock - 1 ;
          $sqlprueba = "UPDATE maquina_repuesto SET stock = '{$maquinaRepuestoStock}' WHERE id = '{$repueOK}'";
          if ($db->query($sqlprueba)) {
            $sql1="INSERT INTO reparacion_repuesto (`reparacion_id`, `repuesto_id`, `id_proveedor`, `cantidad`, `precio`, `fecha`, `elegido`) VALUES ('{$repaOK}','{$repueOK}','{$proveOK}','{$cantidadOK}','{$precioOK}','{$dateOK}',1)";
            echo $result=$db->query($sql1);
          }
        } else {
        }
      }
    } else {
      if ($proveOK != 2) {
        if ($prove2) {      
          $sql1_1="UPDATE reparacion_repuesto SET cantidad = '{$cantidadOK}', id_proveedor = '{$proveOK}', precio = '{$precioOK}', fecha = '{$dateOK}', iva = '{$ivaOK}' WHERE reparacion_id = '{$repaOK}' AND repuesto_id = '{$repueOK}' AND id_proveedor = 2";
          echo $result=$db->query($sql1_1);
        } else {
          $sql1_2="INSERT INTO reparacion_repuesto (`reparacion_id`, `repuesto_id`, `id_proveedor`, `cantidad`, `precio`, `iva`, `fecha`) VALUES ('{$repaOK}','{$repueOK}','{$proveOK}','{$cantidadOK}','{$precioOK}','{$ivaOK}','{$dateOK}')";
          echo $result=$db->query($sql1_2);
        }
      } else {
        if ($repaExist) {
        } else {
          $sql1_3="INSERT INTO reparacion_repuesto (`reparacion_id`, `repuesto_id`, `id_proveedor`, `cantidad`, `precio`, `fecha`) VALUES ('{$repaOK}','{$repueOK}','{$proveOK}','{$cantidadOK}','{$precioOK}','{$dateOK}')";
            echo $result=$db->query($sql1_3);
        }
      }
    }    
  }
  //ELIMINAR PROVEEDOR
  if($bOK == 2){
    if ($proveOK == 1) {
      $stockFinalOK = $maquinaRepuestoStock + $cantidadOK ;
      $sql2 = "UPDATE maquina_repuesto SET stock = '{$stockFinalOK}' WHERE id = '{$repueOK}'";
      if ($db->query($sql2)) {
        $sql2_1="DELETE FROM reparacion_repuesto WHERE reparacion_id = '{$repaOK}' AND repuesto_id = '{$repueOK}' AND id_proveedor = '{$proveOK}'";
      echo $result=$db->query($sql2_1);
      }
    } else {
    	$sql2_2="DELETE FROM reparacion_repuesto WHERE reparacion_id = '{$repaOK}' AND repuesto_id = '{$repueOK}' AND id_proveedor = '{$proveOK}'";
    	echo $result=$db->query($sql2_2);
    }
  }
  //EDITAR PROVEEDOR
  if($bOK == 3){
    $sql3="UPDATE FROM reparacion_repuesto WHERE reparacion_id = '{$repaOK}' AND repuesto_id = '{$repueOK}' AND id_proveedor = '{$proveOK}'";
    echo $result=$db->query($sql3);
  }
  //SUMAR CANTIDAD
   if ($bOK == 4) {
    if ($proveOK == 1) {
      if ($maquinaRepuestoStock > 0) {
        $maquinaRepuestoStock = $maquinaRepuestoStock - 1 ;
        $sqlprueba = "UPDATE maquina_repuesto SET stock = '{$maquinaRepuestoStock}' WHERE id = '{$repueOK}'";
        //$db->query($sqlprueba);
        if ($db->query($sqlprueba)) {
          $sql4="UPDATE reparacion_repuesto SET cantidad = '{$cantidadOK}' WHERE reparacion_id = '{$repaOK}' AND repuesto_id = '{$repueOK}' AND id_proveedor = '{$proveOK}'";
          echo $result=$db->query($sql4);
        }
      }
    } else {
      $sql4_1="UPDATE reparacion_repuesto SET cantidad = '{$cantidadOK}' WHERE reparacion_id = '{$repaOK}' AND repuesto_id = '{$repueOK}' AND id_proveedor = '{$proveOK}'";
      echo $result=$db->query($sql4_1);

    }
    //RESTAR CANTIDAD
  } 
  if ($bOK == 5) {
    if ($proveOK == 1) {
      //if ($maquinaRepuestoStock) {
        $maquinaRepuestoStock = $maquinaRepuestoStock + 1 ;
        $sqlprueba2 = "UPDATE maquina_repuesto SET stock = '{$maquinaRepuestoStock}' WHERE id = '{$repueOK}'";
        //$db->query($sqlprueba);
        if ($db->query($sqlprueba2)) {
          $sql5="UPDATE reparacion_repuesto SET cantidad = '{$cantidadOK}' WHERE reparacion_id = '{$repaOK}' AND repuesto_id = '{$repueOK}' AND id_proveedor = '{$proveOK}'";
          echo $result=$db->query($sql5);
        }
      //}
    } else {
      $sql5_1="UPDATE reparacion_repuesto SET cantidad = '{$cantidadOK}' WHERE reparacion_id = '{$repaOK}' AND repuesto_id = '{$repueOK}' AND id_proveedor = '{$proveOK}'";
      echo $result=$db->query($sql5_1);
    }
  }

  //ELEGIR Y CANCELAR PROVEEDOR

  if ($bOK == 6 && $elegidoOK == 1) {
    $sql6="UPDATE reparacion_repuesto SET elegido = '{$elegidoOK}' WHERE reparacion_id = '{$repaOK}' AND repuesto_id = '{$repueOK}' AND id_proveedor = '{$proveOK}'";
    echo $result=$db->query($sql6);
  } elseif ($bOK == 6 && $elegidoOK == 0){
    $sql6_1="UPDATE reparacion_repuesto SET elegido = '{$elegidoOK}' WHERE reparacion_id = '{$repaOK}' AND repuesto_id = '{$repueOK}' AND id_proveedor = '{$proveOK}'";
    echo $result=$db->query($sql6_1);
  }

  //SET ESTADO 3
  if ($bOK == 7 && $estadoOK == 3) {
    $sql="UPDATE reparacion_maquina SET id_estado = '{$estadoOK}' WHERE id = '{$repaOK}'";
    echo $result=$db->query($sql);
  } elseif ($bOK == 7 && $estadoOK == 4) {
    $sql="UPDATE reparacion_maquina SET id_estado = '{$estadoOK}' WHERE id = '{$repaOK}'";
    echo $result=$db->query($sql);
  } elseif ($bOK == 7 && $estadoOK == 5) {
    $sql="UPDATE reparacion_maquina SET id_estado = '{$estadoOK}' WHERE id = '{$repaOK}'";
    echo $result=$db->query($sql);
  } elseif ($bOK == 7 && $estadoOK == 6) {
    $sql="UPDATE reparacion_maquina SET id_estado = '{$estadoOK}' WHERE id = '{$repaOK}'";
    echo $result=$db->query($sql);
  } elseif ($bOK == 7 && $estadoOK == 7) {
    $sql="UPDATE reparacion_maquina SET id_estado = '{$estadoOK}' WHERE id = '{$repaOK}'";
    echo $result=$db->query($sql);
  } elseif ($bOK == 7 && $estadoOK == 8) {
    $hour = date('H:i:s');
    $sql="UPDATE reparacion_maquina SET fecha_egreso = '{$dateOK}', hora_egreso = '{$hour}', id_estado = '{$estadoOK}' WHERE id = '{$repaOK}'";
    echo $result=$db->query($sql);
    sleep(1);
    $sqlCotizacion="UPDATE reparacion_cotizacion SET fecha_entrega_maquina = '{$dateOK}' WHERE reparacion_id = '{$repaOK}'";
    $resultCotizacion=$db->query($sqlCotizacion);
  } 

  // </. update precio e iva del proveedor seleccionado
  if ($bOK == 8) {
    $sql="UPDATE `reparacion_repuesto` SET `precio`='{$precioOK}',`iva`='{$ivaOK}',`fecha`='{$dateOK}' WHERE `reparacion_id`='{$repaOK}' AND `repuesto_id`='{$repueOK}' AND `id_proveedor`='{$proveOK}'";
    echo $result = $db->query($sql);
  }


?>