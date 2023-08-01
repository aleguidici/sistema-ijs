<?php
  require_once('includes/load.php');
  page_require_level(2);
  $idRepuesto = remove_junk($db->escape($_POST['idRepuesto']));
  $parte = remove_junk($db->escape($_POST['parte']));
  $codigo = remove_junk($db->escape($_POST['codigo']));
  $descripcion = remove_junk($db->escape($_POST['descripcion']));
  $idModelo = remove_junk($db->escape($_POST['idModelo']));
  $repuestoExist = $db->fetch_assoc($db->query("SELECT `id` FROM `maquina_repuesto` WHERE (`id` <> '".$idRepuesto."' AND `id_modelo` = '".$idModelo."' AND `parte` = '".$parte."') OR (`id` <> '".$idRepuesto."' AND `id_modelo` = '".$idModelo."' AND `codigo` = '".$codigo."') LIMIT 1"));
  if (!$repuestoExist) {
  	$sql = "UPDATE `maquina_repuesto` SET `descripcion`='".strtoupper($descripcion)."',`codigo`='".strtoupper($codigo)."',`parte`='".strtoupper($parte)."',`id_modelo`='".$idModelo."' WHERE `id`='".$idRepuesto."'";
    echo $result = $db->query($sql);
  } else {
  	echo $result = 2;
  }
?>