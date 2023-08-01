<?php
  require_once('includes/load.php');
  page_require_level(2);
  $accion = remove_junk($db->escape($_POST['accion']));
  $nameMarca = remove_junk($db->escape($_POST['nameMarca']));
  
  if ($accion == 1) {
  	$hayMarca = $db->fetch_assoc($db->query("SELECT `descripcion` FROM `maquina_marca` WHERE `descripcion` = '".$nameMarca."'"));
  	if (!$hayMarca) {
  		$sql = "INSERT `maquina_marca` (`descripcion`) VALUES ('".ucfirst($nameMarca)."');";
    echo $result = $db->query($sql);
  	} else {
  		echo $result = 2;
  	}
  }

?>