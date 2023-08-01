<?php
  $page_title = 'Buscar mediciones';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(3);
  $all_mediciones = find_by_sql($_GET['consulta']);
?>

<?php include_once('layouts/header.php'); ?>

  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>

  <div class="panel-heading clearfix">
    <div class="pull-left">
      <a href="conAc.php" class="btn btn-success">Volver a mediciones</a>
    </div>
  </div>

  <div class="row">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Mediciones</span>
        </strong>
      </div>
      <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover" id="tablita">
          <thead>
            <tr>
              <th style="width: 45px;">Nº de sucursal</th> <!-- num_suc -->
              <th style="width: 95px;">Razón social</th> <!-- cp -->
              <th style="width: 200px;">Dirección</th> <!-- direccion -->
              <th style="width: 35px;">C.P.</th> <!-- cp -->
              <th style="width: 75px;">Localidad</th> <!-- localidad -->
              <th style="width: 55px;">Provincia / Estado</th> <!-- provincia.nombre -->
              <th style="width: 85px;">Fecha de medición</th>
              <th style="width: 85px;"></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($all_mediciones as $una_medic):
              $un_cliente = find_by_id_suc('cliente', $una_medic['num_suc']);
              $provincia = find_by_id_prov('provincia', $un_cliente['provincia']);
              $fecha = $una_medic['fecha_medicion'];
              list($año, $mes, $dia) = explode('-', $fecha);?>
              <tr>
                <td class="text-center"><?php echo remove_junk($un_cliente['num_suc']);?></td>
                <td ><?php echo remove_junk($un_cliente['razon_social']);?></td>
                <td ><?php echo remove_junk($un_cliente['direccion']);?></td>
                <td class="text-center"><?php echo remove_junk($un_cliente['cp']);?></td>
                <td ><?php echo remove_junk($un_cliente['localidad']);?></td>
                <td ><?php echo remove_junk(utf8_encode($provincia['nombre']));?></td>

                <td class="text-center"><?php echo remove_junk($dia."/".$mes."/".$año);?></td>
                
                <td class="text-center"><a target = '_blank' href="<?php echo 'pdf_protocolo.php?datos_medic='.$una_medic['id_medicion'];?>"> Ver detalles </a></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

<?php include_once('layouts/footer.php'); ?>
