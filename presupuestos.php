<?php
  $page_title = 'Presupuestos';
  require_once('includes/load.php');
  page_require_level(2);
  $all_presupuestos = find_all('presupuesto');
?>

<?php include_once('layouts/header.php'); ?>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css">

    <link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.15.3/dist/bootstrap-table.min.css">
    
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>    


  <!-- datatable -->
  <script type="text/javascript">
      $(document).ready(function() {
          $('#presupuestos').DataTable();
      } );
  </script>

  <h2><b>Presupuestos (WEG)</b></h2>
  <div class="panel-heading clearfix">
    <div class="pull-right">
      <a href="weg_cotizador.php" class="btn btn-primary">Agregar presupuesto</a>
    </div>
    <div class="pull-left">
      <a href="weg_lista_precios.php" class="btn btn-success">Ver lista de precios</a>
    </div>
  </div>

  <div class="table-responsive">
    <table id="presupuestos" class="table table-striped table-bordered" style="width:100%">
      <thead>
        <tr>
          <th style="width: 85px;">Nº de presupuesto</th> 
          <th style="width: 295px;">Cliente - [Nº de sucursal]</th>
          <th style="width: 155px;">Fecha de emisión</th>
          <th style="width: 145px;">Total</th>  
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($all_presupuestos as $un_presup):
          $un_cliente = find_by_id_suc('cliente', $un_presup['id_cliente']);?>
          <tr>
            <td class="text-center"><?php echo remove_junk($un_presup['id']);?></td>
            <td><?php 
            if (!empty($un_presup['id_cliente'])){
              echo remove_junk($un_cliente['razon_social']);
              if (!empty($un_cliente['num_suc']))
                echo remove_junk(' - '.$un_cliente['num_suc']);
            } else 
              echo "-";
            ?></td>
            <td class="text-center"><?php 
            list($año, $mes, $dia) = explode('-', $un_presup['fecha_emision']);
            echo remove_junk($dia."/".$mes."/".$año);?></td>
            <td class="text-center"><?php echo 'U$S ',str_replace('.', ',',remove_junk($un_presup['total']));?></td>
           
            <td class="text-center"><a target = '_blank' href="<?php echo 'pdf_presupuesto.php?id='.$un_presup['id'];?>"> Ver detalles </a></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
      <tfoot>
        <tr>
          <th style="width: 85px;">Nº de presupuesto</th> 
          <th style="width: 295px;">Cliente</th>
          <th style="width: 155px;">Fecha de emisión</th>
          <th style="width: 145px;">Total</th>  
          <th></th>
        </tr>
      </tfoot>
    </table>

    <script>
      $('#presupuestos').DataTable({ "order": [[ 0, "desc" ]] });
      $('.dataTables_length').addClass('bs-select');
    </script>
  </div>

                         

<?php include_once('layouts/footer.php'); ?>