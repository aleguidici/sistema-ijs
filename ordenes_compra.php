<?php
  $page_title = 'Órdenes de compra';
  require_once('includes/load.php');
  page_require_level(2);
  $all_ordenes = find_all('orden_compra');
?>

<?php include_once('layouts/header.php'); ?>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css">

    <link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.15.3/dist/bootstrap-table.min.css">
    
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>    


  <!-- datatable -->
  <script type="text/javascript">
      $(document).ready(function() {
          $('#ordenes').DataTable();
      } );
  </script>
  <h2><b>Órdenes de compra</b></h2>
  <div class="panel-heading clearfix">
    <div class="pull-right">
      <a href="orden_compra.php" class="btn btn-primary">Agregar orden de compra</a>
    </div>
  </div>

  <div class="table-responsive">
    <table id="ordenes" class="table table-striped table-bordered" style="width:100%">
      <thead>
        <tr>
          <th style="width: 35px;">Nº de orden de compra</th>
          <th style="width: 90px;">Proveedor</th>
          <th style="width: 90px;">Localidad y provincia / estado proveedor</th>
          <th style="width: 35px;">Fecha emisión</th>
          <th style="width: 35px;">Fecha vencim.</th>
          <th style="width: 65px;">Forma de pago</th> 
          <th style="width: 100px;">Forma de envío</th> 
          <th style="width: 25px;">Estado</th> 
          <th style="width: 20px;"></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($all_ordenes as $una_orden):
          $un_proveedor = find_by_id('proveedor', $una_orden['id_proveedor']);
          $provincia = find_by_id_prov('provincia', $un_proveedor['provincia']);?>
          <tr>
            <td class="text-center"><?php 
              for ($i = 1; $i <= (7-strlen($una_orden['id'])); $i++) {
                  echo 0;
                  if ($i == '3')
                    echo '-';
              }
              echo $una_orden['id'];
            ?></td>
            <td ><?php echo $un_proveedor['razon_social'];?></td>
            <td ><?php echo $un_proveedor['localidad'].' - '.$provincia['nombre'];?></td>
            <td class="text-center"><?php 
                list($año, $mes, $dia) = explode('-', $una_orden['fecha_emision']);
                echo remove_junk($dia."/".$mes."/".$año);?></td>
            <td class="text-center"><?php 
                list($año2, $mes2, $dia2) = explode('-', $una_orden['fecha_validez']);
                echo remove_junk($dia2."/".$mes2."/".$año2);?></td>
            <td class="text-center"><?php echo $una_orden['forma_pago'];?></td>
            <td class="text-center"><?php echo $una_orden['forma_envio'];?></td>
            <td class="text-center">
              <?php if($una_orden['anulado'] == '0') {
                echo '<span style="color:green;text-align:center;">Vigente</span>'; ?>
                <a href="<?php echo 'orden_compra_anular.php?val=1&id='.$una_orden['id'];?>" onclick="return confirm('¿Seguro que desea anular esta orden de compra?')"> (Anular) </a>
              <?php } else {
                echo '<span style="color:red;text-align:center;">Anulado</span>'; ?>
                <!-- <a href="<?php // echo 'orden_compra_anular.php?val=0&id='.$una_orden['id'];?>"> (Reactivar) </a> -->
              <?php } ?>
            </td>
            <td class="text-center">
              <?php //if($una_orden['anulado'] == '0'){?>
                <a target = '_blank' href="<?php echo 'pdf_orden.php?orden_comp='.$una_orden['id'];?>"> Ver detalles </a>
              <?php //}?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
      <tfoot>
        <tr>
          <th style="width: 35px;">Nº de orden de compra</th>
          <th style="width: 90px;">Proveedor</th>
          <th style="width: 90px;">Localidad y provincia / estado proveedor</th>
          <th style="width: 35px;">Fecha emisión</th>
          <th style="width: 35px;">Fecha vencim.</th>
          <th style="width: 65px;">Forma de pago</th> 
          <th style="width: 100px;">Forma de envío</th> 
          <th style="width: 25px;">Estado</th> 
          <th style="width: 20px;"></th>
        </tr>
      </tfoot>
    </table>

    <script>
      $('#ordenes').DataTable({ "order": [[ 0, "desc" ]] });
      $('.dataTables_length').addClass('bs-select');
    </script>
  </div>

                         

<?php include_once('layouts/footer.php'); ?>
