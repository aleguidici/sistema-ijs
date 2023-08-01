<?php
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);
?>
<?php
  $prov = find_by_id('proveedormaquina',(int)$_GET['id']);
  if(!$prov){
    $session->msg("d","Proveedor no encontrado.");
    redirect('proveedores.php');
  }
?>
<?php
  $ordenes_compra = find_ordenes_compra_by_prov('orden_compra', (int)$prov['id']);

  if($ordenes_compra){
    ?>
    <script>
      window.alert("ERROR\nNo se puede eliminar un proveedor con ordenes de compra asociadas.");
      window.location.href='proveedores.php';
    </script>
    <?php
  } else {
    $delete_id = delete_by_id('proveedormaquina',(int)$prov['id']);
    if($delete_id){
      ?>
      <script>
        window.alert("Proveedor eliminado.");
        window.location.href='proveedores.php';
      </script>
      <?php
    } else {
      ?>
      <script>
        window.alert("Eliminación falló.");
        window.location.href='proveedores.php';
      </script>
      <?php
    }
  }
?>