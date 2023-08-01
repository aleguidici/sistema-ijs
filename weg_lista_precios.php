<?php
  $page_title = 'Lista de precios - WEG';
  require_once('includes/load.php');
  page_require_level(2);
  $all_productos = find_all('weg_lista_productos');
?>

<?php include_once('layouts/header.php'); ?>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css">

    <link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.15.3/dist/bootstrap-table.min.css">
    
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>    


  <!-- datatable -->
  <script type="text/javascript">
      $(document).ready(function() {
          $('#listaprecios').DataTable();
      } );
  </script>

  <div class="panel-heading clearfix">
    <div class="pull-left">
      <a href="presupuestos.php" class="btn btn-success">Volver a presupuestos</a>
    </div>
  </div>

  <div class="table-responsive">
    <table id="listaprecios" class="table table-striped table-bordered" style="width:100%">
      <thead>
        <tr>
          <th style="width: 9%;">Código</th>
          <th style="width: 18%;">Referencia</th>
          <th style="width: 27%;">Descripción</th>
          <th style="width: 15%;">Sub familia</th>
          <th style="width: 9%;">Familia</th>
          <th style="width: 8%;">Embalaje (Pz)</th> 
          <th style="width: 10%;">Precio (U$S)</th> 
          <th style="width: 7%;">IVA (%)</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($all_productos as $un_producto):?>
          <tr>
            <td class="text-center"><?php echo $un_producto['codigo'];?></td>
            <td ><?php echo $un_producto['referencia'];?></td>
            <td ><?php echo $un_producto['descripcion'];?></td>
            <td ><?php echo $un_producto['sub_familia'];?></td>
            <td ><?php echo $un_producto['familia'];?></td>
            <td class="text-center"><?php echo $un_producto['embalaje'];?></td>
            <td class="text-center"><?php 
              if ($un_producto['precio'] == 0 || $un_producto['precio'] == "")
                echo str_replace('.', ',',"N/D");
              else
                echo str_replace('.', ',',$un_producto['precio']);?></td>
            <td class="text-center"><?php echo str_replace('.', ',',$un_producto['iva']*100);?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
      <tfoot>
        <tr>
          <th style="width: 9%;">Código</th>
          <th style="width: 18%;">Referencia</th>
          <th style="width: 27%;">Descripción</th>
          <th style="width: 15%;">Sub familia</th>
          <th style="width: 9%;">Familia</th>
          <th style="width: 8%;">Embalaje (Pz)</th> 
          <th style="width: 10%;">Precio (U$S)</th> 
          <th style="width: 7%;">IVA (%)</th>
      </tfoot>
    </table>

    <script>
      $('#listaprecios').DataTable({ "order": [[ 0, "asc" ]] });
      $('.dataTables_length').addClass('bs-select');
    </script>
  </div>

                         

<?php include_once('layouts/footer.php'); ?>
