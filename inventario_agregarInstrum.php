<?php
  require_once('includes/load.php');
  page_require_level(2);

  $marcaOK=$_POST['marca_inst'];
  $modeloOK=$_POST['modelo_inst'];
  $fechaOK=$_POST['fecha_inst'];
  $nSerieOK=$_POST['nSerie_inst'];

  $sql="INSERT INTO inv_instrumentos ( `marca`, `modelo`, `num_serie`, `fecha_calibracion`) VALUES ( '{$marcaOK}','{$modeloOK}','{$nSerieOK}','{$fechaOK}')";
  echo $result=$db->query($sql);
?>