<?php
  require_once('includes/load.php');
  page_require_level(2);
  $idMarca = remove_junk($db->escape($_POST['idMarca']));
  $codigo = remove_junk($db->escape($_POST['codigo']));
  $descripcion = remove_junk($db->escape($_POST['descripcion']));
  $inalambrico = remove_junk($db->escape($_POST['inalambrico']));
  $anio = remove_junk($db->escape($_POST['anio']));
  $tipo = remove_junk($db->escape($_POST['tipo']));
  $tamanio = remove_junk($db->escape($_POST['tamanio']));
  $hayModelo = $db->fetch_assoc($db->query("SELECT `codigo` FROM `maquina_modelo` WHERE `codigo` = '".$codigo."' AND `marca_id` = '".$idMarca."'"));
  if (!$hayModelo) {
  	$sql = "INSERT INTO `maquina_modelo` (`codigo`, `descripcion`, `inalambrico`, `anio`, `tipo_id`, `tamanio_id`, `marca_id`, `despiece_id`) VALUES ('".strtoupper($codigo)."','".$descripcion."','".$inalambrico."','".$anio."','".$tipo."','".$tamanio."','".$idMarca."','0')";
    echo $result = $db->query($sql);
  } else {
  	echo $result = 2;
  }
  

?>