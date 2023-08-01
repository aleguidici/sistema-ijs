<?php
require_once('includes/load.php');
page_require_level(2); 
$idCliente = remove_junk($db->escape($_POST['idCliente']));
$idModelo = remove_junk($db->escape($_POST['idModelo']));
$numSerieBase = remove_junk($db->escape($_POST['numSerie']));
$numSerie = strtoupper($numSerieBase);
$descripcionFinalLower = remove_junk($db->escape($_POST['descripcion']));
$descripcionFinal = ucfirst($descripcionFinalLower);


if($db->query("INSERT INTO `maquina` (`id_cliente`, `modelo_id`, `num_serie`, `detalles`, `sin_reparacion`) VALUES ('{$idCliente}', '{$idModelo}', '{$numSerie}', '{$descripcionFinal}', 0)")){
  echo $res = 1;
  //$session->msg("s", "Máquina agregada exitosamente.");
} else {
  echo $res = 2;
  //$session->msg("d", "Lo siento, el registro falló");
}
echo $last_id = ".".$db->insert_id();  
?>
