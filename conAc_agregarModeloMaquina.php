<?php
  require_once('includes/load.php');
  page_require_level(2);

  $nombreOK=$_POST['nombreOK_m'];
  $descripcionOK=$_POST['descripcionOK_m'];
  $inalambricoOK=$_POST['inalambricoOK_m'];
  $anioOK=$_POST['anioOK_m'];
  $tipoIdOK=$_POST['tipoIdOK_m'];
  $tamanioIdOK=$_POST['tamanioIdOK_m'];
  $marcaIdOK=$_POST['marcaIdOK_m'];

  $hayMaquina = findModeloExiste('maquina_modelo', $marcaIdOK, $tipoIdOK, $nombreOK);

  if ($hayMaquina) {
    echo $result = 2;
  } else {
  $sql="INSERT INTO maquina_modelo (`codigo`, `descripcion`, `inalambrico`, `anio`, `tipo_id`, `tamanio_id`, `marca_id`) VALUES ('{$nombreOK}','{$descripcionOK}','{$inalambricoOK}','{$anioOK}','{$tipoIdOK}','{$tamanioIdOK}','{$marcaIdOK}')";
  echo $result=$db->query($sql);
  }

?>