<?php
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);
?>
<?php
  $instrument = find_by_num_ins('inv_instrumentos',(int)$_GET['id']);
  if(!$instrument){
    $session->msg("d","Número de instrumento no encontrado.");
    redirect('inventario.php');
  }
?>
<?php
  
  $mediciones = find_mediciones_by_inst('datos_medicion', (int)$instrument['nro_instrumento']);  

  if($mediciones){
      $session->msg("d","No se puede eliminar un instrumento con mediciones registradas.");
      redirect('inventario.php');
  
  } else {

    $delete_id = delete_by_num_ins('inv_instrumentos',(int)$instrument['nro_instrumento']);

    if($delete_id){
        $session->msg("s","instrumento eliminado");
        redirect('inventario.php');
    } else {
        $session->msg("d","Eliminación falló");
        redirect('inventario.php');
    }
  }
?>