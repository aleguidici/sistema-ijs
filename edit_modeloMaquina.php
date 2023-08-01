<?php
  require_once('includes/load.php');
  page_require_level(2);
  $idModelo = remove_junk($db->escape($_POST['modelo']));
  $idMarca = remove_junk($db->escape($_POST['marca']));
  $tipo = remove_junk($db->escape($_POST['tipo']));
  $codigo = remove_junk($db->escape($_POST['codigo']));
  $tamanio = remove_junk($db->escape($_POST['tamanio']));
  $inalambrico = remove_junk($db->escape($_POST['inalambrico']));
  $anio = remove_junk($db->escape($_POST['anio']));
  $detalle = remove_junk($db->escape($_POST['detalle'])); 
  
  $thisModelo = $db->fetch_assoc($db->query("SELECT `id`, `codigo`, `marca_id` FROM `maquina_modelo` WHERE `id` = '".$idModelo."'"));
  $codigoEnEstaMarca = $db->fetch_assoc($db->query("SELECT `id`, `codigo`, `marca_id` FROM `maquina_modelo` WHERE `id` <> '".$idModelo."' AND `codigo` = '".$codigo."' AND `marca_id` = '".$idMarca."'"));
  if ($thisModelo) {
    if (!$codigoEnEstaMarca) {
  	$sql = "UPDATE `maquina_modelo` SET `codigo`= '{$codigo}', `descripcion`='{$detalle}', `inalambrico`='{$inalambrico}', `anio`='{$anio}', `tipo_id`='{$tipo}', `tamanio_id`='{$tamanio}', `marca_id`='{$idMarca}' WHERE `id` = '".$idModelo."'";
    echo $result = $db->query($sql); /// OK
    } else {
  	echo $result = 2; // ya existe ese codigo de modelo para esa marca
    }
  } else {
    echo $result = 3; // No se encontró un modelo con ese id
  }
  
?>