<?php
  $page_title = 'Movimientos';
  require_once('includes/load.php');
  page_require_level(2);
  $actEst = find_by_id('proy_actividades_estaticas',$_GET['id']);
?>

<?php include_once('layouts/header.php'); ?>
  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> 
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css">
  <link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.15.3/dist/bootstrap-table.min.css">
  
  <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>

  <!-- datatable -->
  <script type="text/javascript">
      $(document).ready(function() {
          $('#detallesActiv').DataTable();
      } );
  </script>
  <h2><b>Detalles de la actividad</b></h2>

  <hr>
  <h4><b>Información general del proyecto:</b></h4>
     
  <div class="row">           
    <div class="col-xs-1">
      <h5><b>Actividad: </b></h5>
    </div>
    <div class="col-xs-11">
      <h5><?php echo $actEst['nivel']." - ".$actEst['nombre'];?></h5>
    </div>
  </div>

  <?php
    $all_actividades = find_actividades_diarias_segun_actividad_estatica($_GET['id']);
    $horas = 0;
    $dias = 0;
    $minutos = 0;
    foreach ($all_actividades as $una_activ_diaria):
      list($H1, $m1, $s1) = explode(':', $una_activ_diaria['hora_inicio']);
      list($H2, $m2, $s2) = explode(':', $una_activ_diaria['hora_fin']);
      $minutos = $minutos + ($H2-$H1)*60 + ($m2-$m1);
    endforeach;
    $horas = ceil($minutos/60);
    $dias =  ceil($horas/8);
  ?>

  <div class="row">  
    <div class="col-xs-3">
      <h5><b>Horas totales trabajadas: </b></h5>
    </div>
    <div class="col-xs-9">
      <h5><?php echo $horas." hs.";?></h5>
    </div>
  </div>

  <div class="row">  
    <div class="col-xs-3">
      <h5><b>Jornadas laborales: </b></h5>
    </div>
    <div class="col-xs-9">
      <h5><?php echo $dias." días";?></h5>
    </div>
  </div>

  <hr>

  <div class="col-xs-12">
    <div id="tablaActDet"></div>   
  </div>  

  <script>
    $(document).ready(function(){
      $('#tablaActDet').load('conAc_tablaActividadDet.php?id=<?php echo (int)$_GET['id']?>');
    });
  </script> 
                      

<?php include_once('layouts/footer.php'); ?>
