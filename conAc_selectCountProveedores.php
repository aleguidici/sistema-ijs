<?php
	require_once('includes/load.php');
	page_require_level(2);

	$IdReparacionOk=$_POST['idReparacion'];
	$IdRepuestoOk=$_POST['idRepuesto'];


$sql = "SELECT * FROM reparacion_repuesto WHERE reparacion_id = '{$IdReparacionOk}' AND repuesto_id = '{$IdRepuestoOk}'";
$result = $db->query($sql);
echo $rows = mysqli_num_rows($result); 

?>