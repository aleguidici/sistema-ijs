<?php
  require_once('includes/load.php');
  page_require_level(2);

  $marcaOK=$_POST['marca_mi'];
  $tipoOK=$_POST['tipo_mi'];
  $proveedorOK=NULL;
  $descripcionOK=$_POST['descripcion_mi'];
  $precioOK=$_POST['precio_mi'];
  $unidadOK=$_POST['unidad_mi'];
  $codOK=$_POST['cod_mi'];
  $cant_minOK=$_POST['cantidad_min_mi'];

  $sql="INSERT INTO inv_materiales_insumos (`marca`, `tipo`, `descripcion`, `cant`, `cant_disp`, `precio_lista`, `unidad`, `cod`, `cant_min`) VALUES ('{$marcaOK}', '{$tipoOK}', '{$descripcionOK}', 0, 0, '{$precioOK}', '{$unidadOK}', '{$codOK}', '{$cant_minOK}')";
  //echo $result = mysqli_query($db,$sql);
  echo $result=$db->query($sql);
?>