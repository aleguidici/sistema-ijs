<?php
  require_once('includes/load.php');
  page_require_level(2);
  $proveOK=$_POST['prove'];
  $marcOK=$_POST['marc'];
  $bOK=$_POST['b'];
  //GUARDAR
  if($bOK == 1){ 
  	$sql="INSERT INTO maquina_marca_prov (`id_maquina_marca`, `id_proveedor`) VALUES ('{$marcOK}','{$proveOK}')";
  	echo $result=$db->query($sql);
	}
  //ELIMINAR
  if($bOK == 2){
  	$sql="DELETE FROM maquina_marca_prov WHERE id_maquina_marca = '{$marcOK}' AND id_proveedor = '{$proveOK}'";
  	echo $result=$db->query($sql);
  }

?>