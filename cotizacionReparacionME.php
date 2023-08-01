<?php
require_once('includes/load.php');
page_require_level(2);
$idReparacion=remove_junk($db->escape($_POST['idReparacion']));
$repuestos=remove_junk($db->escape($_POST['repuestos']));
$manoObra=remove_junk($db->escape($_POST['manoObra']));
$grasa=remove_junk($db->escape($_POST['grasa']));
$flete=remove_junk($db->escape($_POST['flete']));
$recargo=remove_junk($db->escape($_POST['recargo']));
$acumRecargo=remove_junk($db->escape($_POST['acumRecargo']));
$senia=remove_junk($db->escape($_POST['senia']));
$subTotal=remove_junk($db->escape($_POST['subTotal']));
$ivaFinal=remove_junk($db->escape($_POST['ivaFinal']));
$totalFinal=remove_junk($db->escape($_POST['totalFinal']));
$today = date('y-m-d');

$sqlCotizacionReparacion = $db->query("SELECT * FROM reparacion_cotizacion WHERE reparacion_id = ".$idReparacion."");
$cotizacionReparacion = $db->fetch_assoc($sqlCotizacionReparacion);

if (!$cotizacionReparacion) {
	$sqlInsert = "INSERT INTO `reparacion_cotizacion`(`reparacion_id`, `repuestos`, `mano_obra`, `grasa`, `flete`, `recargo`, `acum_recargo`, `senia`, `subtotal`, `iva`, `total`, `fecha_cotizacion`) VALUES ('{$idReparacion}','{$repuestos}','{$manoObra}','{$grasa}','{$flete}','{$recargo}','{$acumRecargo}','{$senia}','{$subTotal}', '{$ivaFinal}', '{$totalFinal}', '{$today}')";
	echo $result = $db->query($sqlInsert);
} else {
	$sqlUpdate = "UPDATE `reparacion_cotizacion` SET `repuestos`='{$repuestos}',`mano_obra`='{$manoObra}',`grasa`='{$grasa}',`flete`='{$flete}',`recargo`='{$recargo}',`acum_recargo`='{$acumRecargo}',`senia`='{$senia}',`subtotal`='{$subTotal}',`iva`='{$ivaFinal}', `total`='{$totalFinal}', `fecha_ultima_modificacion`='{$today}' WHERE `reparacion_id` = '{$idReparacion}'";
	echo $result = $db->query($sqlUpdate);
}
?>