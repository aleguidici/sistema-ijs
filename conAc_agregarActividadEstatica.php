<?php
  require_once('includes/load.php');
  page_require_level(2);

  $nivelOK=$_POST['nivel_act'];
  $descripcionOK=$_POST['descrip_act'];
  $id_proyecto=$_POST['cod_proyecto'];

  $sql="INSERT INTO proy_actividades_estaticas (`id_proy`, `nivel`, `nombre`) VALUES ('{$id_proyecto}', '{$nivelOK}', '{$descripcionOK}')";
  echo $result=$db->query($sql);
?>