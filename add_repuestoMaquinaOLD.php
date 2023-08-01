<?php
  require_once('includes/load.php');
  page_require_level(2);
  $idModelo = remove_junk($db->escape($_POST['idModelo']));
  $parte = remove_junk($db->escape($_POST['parte']));
  $codigo = remove_junk($db->escape($_POST['codigo']));
  $descripcion = remove_junk($db->escape($_POST['descripcion']));
  $repuestoExist = $db->fetch_assoc($db->query("SELECT `id` FROM `maquina_repuesto` WHERE `id_modelo` = '".$idModelo."' AND `parte` = '".$parte."' AND `codigo` = '".$codigo."'"));
  if (!$repuestoExist) {
  	$sql = "INSERT `maquina_repuesto`(`descripcion`, `codigo`, `parte`, `id_modelo`) VALUES ('".strtoupper($descripcion)."', '".strtoupper($codigo)."', '".strtoupper($parte)."', '".$idModelo."')";
    echo $result = $db->query($sql);
  } else {
  	echo $result = 2;
  }
?>