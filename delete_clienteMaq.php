<?php
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);
?>
<?php
  $clieMaq = find_by_id('clientemaquina',(int)$_GET['id']);
  if(!$clieMaq){
    $session->msg("d","ID vacío");
    redirect('clientes.php');
  }
?>
<?php
    $maquinas = find_maquinas_by_cliente((int)$clieMaq['id']);

    if($maquinas){
      $session->msg("d","No se puede eliminar un cliente con maquinas registradas.");
      redirect('clientes.php');
    } else {
      $delete_id = delete_by_id('clientemaquina',(int)$clieMaq['id']);
    
      if($delete_id){
          $session->msg("s","Cliente eliminado");
          redirect('clientes.php');
      } else {
          $session->msg("d","Eliminación falló");
          redirect('clientes.php');
      }
    }
?>