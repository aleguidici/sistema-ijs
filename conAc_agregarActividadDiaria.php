<?php
	require_once('includes/load.php');
	page_require_level(2);

	$hora_iOK=$_POST['hora_i'];
	$hora_fOK=$_POST['hora_f'];
	$pers_afect_actOK= explode (",",$_POST['pers_afect_act']);
	$fecha_actOK=$_POST['fecha_act'];
	$act_estatOK=$_POST['act_estat'];
	$proyectIDOK=$_POST['proyectID'];
	$observacionesOK=$_POST['descrip_act'];


	$sql="INSERT INTO proy_actividades_diarias (`id_proyecto`, `id_actividad_estatica`, `fecha`, `hora_inicio`, `hora_fin`, `observaciones`, `visado`) VALUES ('{$proyectIDOK}', '{$act_estatOK}', '{$fecha_actOK}', '{$hora_iOK}', '{$hora_fOK}', '{$observacionesOK}', 0)";
	$db->query($sql);

	$ultima_actDiaria = find_last('proy_actividades_diarias');
	

	$queryOK = "INSERT INTO proy_actdiaria_persafect (`id_actDiaria`, `id_pers`) VALUES ";

	foreach($pers_afect_actOK as $un_pers){
	  $query_parts[] = " ('{$ultima_actDiaria["id"]}', '{$un_pers}') ";
	}

	$queryOK .= implode(',', $query_parts);

	$db->query($queryOK);
?>