<?php
  require_once('includes/load.php');
  page_require_level(2);

  $persOK=$_POST['personalTemp'];
  $id_proy=$_POST['id'];

  $sql="INSERT INTO proy_personalafectado (`id_proyecto`, `id_personal`) VALUES ('{$id_proy}','{$persOK}')";

  echo $result=$db->query($sql);
?>