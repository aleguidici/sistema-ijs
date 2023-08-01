<?php
  require_once('includes/load.php');
  page_require_level(2);

  $marcaOK=$_POST['marca_mqi'];
  $modeloOK=$_POST['modelo_mqi'];
  $descripcionOK=$_POST['descripcion_mqi'];
  $tipoOK=$_POST['tipo_mqi'];
  $estadoOK=$_POST['estado_mqi'];
  $anioOK=$_POST['yyyy_mqi'];
  $nSerieOK=$_POST['nSerie_mqi'];
  $detalleEstadoOK=$_POST['detalle_estado_mqi'];
  $hoy = date('Y-m-d');

  $sql="INSERT INTO inv_maquinarias (`marca`, `modelo`, `num_serie`, `descripcion`, `anio`, `tipo`) VALUES ('{$marcaOK}', '{$modeloOK}', '{$nSerieOK}', '{$descripcionOK}', '{$anioOK}', '{$tipoOK}')";
  $result=$db->query($sql);

  if($result == 1){
    $last_maquin = find_last_by_id('inv_maquinarias')['id'];
    $sql2="INSERT INTO inv_maq_historial (`id_maq`, `detalle`, `fecha`, `estado`) VALUES ('{$last_maquin}', '{$detalleEstadoOK}', '{$hoy}', '{$estadoOK}')";
    $db->query($sql2);
  }

  echo $result;

?>