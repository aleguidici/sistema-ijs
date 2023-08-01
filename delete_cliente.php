<?php
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);
?>
<?php
  $banc = find_by_id_suc('cliente',(int)$_GET['id']);
  if(!$banc){
    $session->msg("d","ID vacío");
    redirect('clientes.php');
  }
?>
<?php
    $mediciones = find_mediciones_by_suc('datos_medicion', (int)$banc['id']);

    if($mediciones){
      $session->msg("d","No se puede eliminar un cliente con mediciones registradas.");
      redirect('clientes.php');
    } else {
      $delete_id = delete_by_id_suc('cliente',(int)$banc['id']);
    
      if($delete_id){
          $session->msg("s","Cliente eliminado");
          redirect('clientes.php');
      } else {
          $session->msg("d","Eliminación falló");
          redirect('clientes.php');
      }
    }
?>