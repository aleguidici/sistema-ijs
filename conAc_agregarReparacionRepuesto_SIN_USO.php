<?php
  require_once('includes/load.php');
  page_require_level(2);

  $idRepuestoSelOk=$_POST['idRepuestoSel'];
  $idReparacionSelOk=$_POST['idReparacionSel'];

  $sql="INSERT INTO reparacion_repuesto (`reparacion_id`, `repuesto_id`, `id_proveedor`) VALUES ('{$idReparacionSelOk}','{$idRepuestoSelOk}', 2)";
  echo $result=$db->query($sql);
?>