<?php
  require_once('includes/load.php');
  $page_title = 'Informes de Caja';
  page_require_level(2);
  include_once('layouts/headerCaja.php');
?>
<?php
  $startInforme = remove_junk($db->escape($_POST['start_informe']));
  $endInforme = remove_junk($db->escape($_POST['end_informe']));

  $sql_rango_informe_ingresos = $db->query("SELECT * FROM caja_ingresos WHERE fecha >= '".$startInforme."' and fecha <= '".$endInforme."'");
  $informeIngresos = $db->while_loop($sql_rango_informe_ingresos);
  $rows_ingre = mysqli_num_rows($sql_rango_informe_ingresos);
  $sql_rango_informe_egresos = $db->query("SELECT * FROM caja_egresos WHERE fecha >= '".$startInforme."' and fecha <= '".$endInforme."'");
  $informeEgresos = $db->while_loop($sql_rango_informe_egresos);
  $rows_egre = mysqli_num_rows($sql_rango_informe_egresos);
  //echo nl2br("Ingresos: ".$unInformeIngresos['fecha']." - ".$unInformeIngresos['monto']."\n");
  //////////
  //SUMAS
  $sumaIngresos = 0;
  foreach ($informeIngresos as $unIngreso):
    $sumaIngresos = $sumaIngresos + $unIngreso['monto'];
  endforeach;
  //
  $sumaEgresos = 0;
  foreach ($informeEgresos as $unEgreso):
    $sumaEgresos = $sumaEgresos + $unEgreso['monto'];
  endforeach;
  //
  $sumaInicios = 0;
  $sumaCierres = 0;
  $sumaIniCie = 0;
  /*$sql_rango_informe_inicios = $db->query("SELECT fondo_inicio, fondo_cierre FROM caja WHERE fecha_cierre >= '".$startInforme."' and fecha_inicio <= '".$endInforme."'");
  $informeInicios = $db->while_loop($sql_rango_informe_inicios);*/

  $sql_rango_informe_inicios = $db->query("SELECT fondo_inicio FROM caja WHERE fecha_inicio >= '".$startInforme."' and fecha_inicio <= '".$endInforme."'");
  $informeInicios = $db->fetch_assoc($sql_rango_informe_inicios);

  $infInicios = $informeInicios['fondo_inicio'];
  $balanceCaja = ( $sumaIngresos + $infInicios ) - $sumaEgresos ;
  //echo '<script>console.log("'.$sumaInicios." - ".$sumaCierres.'");</script>';
  
  //ENDSUMAS
  //CONCEPTOS DISTINC
  $sql_tipo_conceptos_distinct_egresos = "SELECT DISTINCT caja_conceptos.id, caja_conceptos.descripcion FROM caja_egresos INNER JOIN caja_conceptos ON caja_egresos.concepto_id = caja_conceptos.id WHERE caja_egresos.fecha >= '".$startInforme."' and caja_egresos.fecha <= '".$endInforme."' ORDER by 2";
  $query_tipo_conceptos_egresos = $db->query($sql_tipo_conceptos_distinct_egresos);
  $result_tipo_conceptos_egresos = $db->while_loop($query_tipo_conceptos_egresos);

  $sql_tipo_ingreso_distinct = "SELECT DISTINCT caja_ingresos.tipo_ingreso FROM caja_ingresos WHERE caja_ingresos.fecha >= '".$startInforme."' and caja_ingresos.fecha <= '".$endInforme."' ORDER by 1";
  $query_tipo_ingreso = $db->query($sql_tipo_ingreso_distinct);
  $result_tipo_ingreso = $db->while_loop($query_tipo_ingreso);

  $montoConcepto_egresos = 0;
  $montoType_ingresos = 0 ;

  //END CONCEPT DISTINCT
  //////////
  echo '<script>console.log("'.$startInforme." / ".$endInforme.'");</script>';

  $dateInicioInforme = $startInforme;
  $partInicioInforme = array();
  $partInicioInforme = explode("-",$dateInicioInforme);
  if($partInicioInforme[1] == 1) { $partInicioInforme[1] = "Enero"; } if($partInicioInforme[1] == 2) { $partInicioInforme[1] = "Febrero"; }
  if($partInicioInforme[1] == 3) { $partInicioInforme[1] = "Marzo"; } if($partInicioInforme[1] == 4) { $partInicioInforme[1] = "Abril"; }
  if($partInicioInforme[1] == 5) { $partInicioInforme[1] = "Mayo"; } if($partInicioInforme[1] == 6) { $partInicioInforme[1] = "Junio"; }
  if($partInicioInforme[1] == 7) { $partInicioInforme[1] = "Julio";} if($partInicioInforme[1] == 8) { $partInicioInforme[1] = "Agosto"; }
  if($partInicioInforme[1] == 9) { $partInicioInforme[1] = "Septiembre"; } if($partInicioInforme[1] == 10) { $partInicioInforme[1] = "Octubre"; }
  if($partInicioInforme[1] == 11) { $partInicioInforme[1] = "Noviembre"; } if($partInicioInforme[1] == 12) { $partInicioInforme[1] = "Diciembre"; }

  $dateEndInforme = $endInforme;
  $partEndInforme = array();
  $partEndInforme = explode("-",$dateEndInforme);
  if($partEndInforme[1] == 1) { $partEndInforme[1] = "Enero"; } if($partEndInforme[1] == 2) { $partEndInforme[1] = "Febrero"; }
  if($partEndInforme[1] == 3) { $partEndInforme[1] = "Marzo"; } if($partEndInforme[1] == 4) { $partEndInforme[1] = "Abril"; }
  if($partEndInforme[1] == 5) { $partEndInforme[1] = "Mayo"; } if($partEndInforme[1] == 6) { $partEndInforme[1] = "Junio"; }
  if($partEndInforme[1] == 7) { $partEndInforme[1] = "Julio";} if($partEndInforme[1] == 8) { $partEndInforme[1] = "Agosto"; }
  if($partEndInforme[1] == 9) { $partEndInforme[1] = "Septiembre"; } if($partEndInforme[1] == 10) { $partEndInforme[1] = "Octubre"; }
  if($partEndInforme[1] == 11) { $partEndInforme[1] = "Noviembre"; } if($partEndInforme[1] == 12) { $partEndInforme[1] = "Diciembre"; }
?>

<script>
$(window).load(function() {
    //$(".loader").fadeOut(2000);
    //$('#generando_informe').html('<div class="loader"><br><p style="font-size: 24px;position: absolute;left: 31%;top: 57%;">Generando informe, por favor espere mientras se cargan los datos...</p></div>');
  });

$(document).ready(function() {
  'use strict' 
  // Pie Chart Ingresos
  var pieChartCanvasIngresos = $('#pieChart-ingresos').get(0).getContext('2d')
  var pieDataIngresos        = {
    labels: [
            <?php foreach ($result_tipo_ingreso as $unResult_tipo_ingreso) :
              if ($unResult_tipo_ingreso['tipo_ingreso'] == 1) {
                echo "'De personal',";
              }
              if ($unResult_tipo_ingreso['tipo_ingreso'] == 2) {
                echo "'De caja maquinarias',";
              }
              if ($unResult_tipo_ingreso['tipo_ingreso'] == 3) {
                echo "'De cliente regular',";
              }
              if ($unResult_tipo_ingreso['tipo_ingreso'] == 4) {
                echo "'De cliente maquinarias',";
              }
              if ($unResult_tipo_ingreso['tipo_ingreso'] == 5) {
                echo "'Otro'";
              }
            endforeach; ?>  
            ],
    datasets: [
      {
        data: [
        <?php 
        foreach ($result_tipo_ingreso as $unResult_tipo_ingreso) :
          $sql_type_ingresos = $db->query("SELECT monto FROM caja_ingresos WHERE fecha >='".$startInforme."'AND fecha <='".$endInforme."' AND tipo_ingreso = ".$unResult_tipo_ingreso['tipo_ingreso']."");
          $type_ingreso = $db->while_loop($sql_type_ingresos);
          foreach ($type_ingreso as $unType_ingreso) :
            $montoType_ingresos = $montoType_ingresos + $unType_ingreso['monto'];
          endforeach ;      
          echo $montoType_ingresos.",";
          $montoType_ingresos = 0 ; 
        endforeach ;
        ?>   
              ],
        backgroundColor : ['#48C9B0','#F4D03F','#5499C7','#EB984E','#CD6155'],
      }
    ]
  }
  var pieOptionsIngresos = {
    legend: {
      display: true
    },
    show: true,
    maintainAspectRatio : false,
    responsive : true,
  }
  var pieChartIngresos = new Chart(pieChartCanvasIngresos, {
    type: 'pie',
    data: pieDataIngresos,
    options: pieOptionsIngresos      
  }); 

  // Pie Chart Egresos
  var pieChartCanvasEgresos = $('#pieChart-egresos').get(0).getContext('2d')
  var pieDataEgresos        = {
    labels: [
              <?php foreach ($result_tipo_conceptos_egresos as $unResult_tipo_conceptos_egresos) :
                echo "'".$unResult_tipo_conceptos_egresos['descripcion']."',";
              endforeach; ?>
            ],
    datasets: [
      {
        data: [
              <?php 
              foreach ($result_tipo_conceptos_egresos as $unResult_tipo_conceptos_egresos) :
                $sql_concepto_egresos = $db->query("SELECT monto FROM caja_egresos WHERE fecha >= '".$startInforme."' AND fecha <= '".$endInforme."' AND concepto_id = ".$unResult_tipo_conceptos_egresos['id']."");
                $concepto_egresos = $db->while_loop($sql_concepto_egresos);
                foreach ($concepto_egresos as $unConcepto_egresos) :
                  $montoConcepto_egresos = $montoConcepto_egresos + $unConcepto_egresos['monto'];
                endforeach;
                echo $montoConcepto_egresos.",";
                $montoConcepto_egresos = 0;
              endforeach; 
              ?>
              ],
        backgroundColor : ['#CD6155','#AF7AC5', '#5499C7', '#48C9B0','#F4D03F','#52BE80','#EB984E','#EC7063','#A569BD','#5DADE2','#45B39D','#F5B041','#58D68D'],
      }
    ]
  }

  var pieOptionsEgresos = {
    legend: {
      display: true
    },
    show: true,
    maintainAspectRatio : false,
    responsive : true,
  }
  var pieChartEgresos = new Chart(pieChartCanvasEgresos, {
    type: 'pie',
    data: pieDataEgresos,
    options: pieOptionsEgresos      
  });

});

function goInforme() {
  document.getElementById('submit_generar_informe').click();
}

</script>
<!--<div id="generando_informe" class="loader"></div>-->
<form id="form_generar_reporte" method="post" action="cajaInformes.php">
  <input class="form-control" type="text" id="start_informe" name="start_informe" hidden>
  <input class="form-control" type="text" id="end_informe" name="end_informe" hidden>
  <button type="submit" id="submit_generar_informe" class="btn btn-outline-light" style="border: 1px solid white;" hidden></button>
</form>

<section class="content" style="margin-left: -15px;margin-right: -15px;">
  <div class="container-fluid">
    <!-- Small boxes (Stat box) -->
    <form action="" method="POST">
    <div class="row">
      <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-warning"><!-- style="box-shadow: 4px 4px 4px 1px rgba(0, 0, 0, 0.2);" -->
          <div class="inner">
            <!--<h3 style="font-size: calc(2.5rem + 0.6vw);margin-top: 0;margin-bottom: 0.5rem;font-weight: 600;line-height: 1.2;"><?php// echo "$ ".$caja['fondo_inicio'];?></h3> -->
            <p style="margin-top: 0;margin-bottom: 3px;font-size: 1.2rem;font-weight: bold;"><?php echo "Éste informe abarca desde:";?></p>
            <p style="margin-top: 0;margin-bottom: 0px;font-size: 1.5rem;font-weight: bold;font-style: italic;"><?php echo $partInicioInforme[2]." de ".$partInicioInforme[1]." del ".$partInicioInforme[0];?></p>
            <p style="margin-top: 0;margin-bottom: 0px;font-size: 1.2rem;font-weight: bold;"><?php echo "Hasta:";?></p>
            <p style="margin-top: 0;margin-bottom: 0px;font-size: 1.5rem;font-weight: bold;font-style: italic;"><?php echo $partEndInforme[2]." de ".$partEndInforme[1]." del ".$partEndInforme[0];?></p>
          </div>
          <div class="icon">
            <i class="fas fa-cash-register"></i>
          </div>
          <a type="button" id="a_new_informe" class="small-box-footer" data-toggle="modal" data-target="#modal-new_informe"><input hidden>Re-seleccionar rango de informe <i class="fas fa-arrow-circle-right" id="open_modal-new_informe"></i></a>
          <!--<a type="button" id="" class="small-box-footer"><input hidden><i class="fas fa-arrow-circle-right" id=""></i></a>-->              
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-success">
          <div class="inner">
            <h3 style="font-size: calc(2.5rem + 0.6vw);margin-top: 0;margin-bottom: 0.5rem;font-weight: 600;line-height: 1.2;"><?php echo "$ ".$sumaIngresos;?><!--<sup style="font-size: 20px">%</sup>--></h3> 
            <p style="margin-top: 0;margin-bottom: 1rem;font-size: 1.5rem;font-weight: bold;"><?php echo $rows_ingre; ?> Ingresos en total</p>
          </div>
          <div class="icon">
            <i class="ion ion-arrow-graph-down-left"></i>
          </div>
          <a type="button" id="" class="small-box-footer"><i class="fas fa-arrow-circle-right" id=""></i></a> 
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-danger">
          <div class="inner">
            <h3 style="font-size: calc(2.5rem + 0.6vw);margin-top: 0;margin-bottom: 0.5rem;font-weight: 600;line-height: 1.2;"><?php echo "$ ".$sumaEgresos;?></h3>
            <p style="margin-top: 0;margin-bottom: 1rem;font-size: 1.5rem;font-weight: bold;"><?php echo $rows_egre; ?> Egresos en total</p>
          </div>
          <div class="icon">
            <i class="ion ion-arrow-graph-up-right"></i>
          </div>
          <a type="button" id="" class="small-box-footer" ><i class="fas fa-arrow-circle-right" id=""></i></a>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-info">
          <div class="inner">
            <h3 style="font-size: calc(2.5rem + 0.6vw);margin-top: 0;margin-bottom: 0.5rem;font-weight: 600;line-height: 1.2;"><?php echo "$ ".round($balanceCaja,2) ; ?></h3>
            <p style="font-size: 13px;margin-top: 0;margin-bottom: 0.5rem;font-weight: 600;line-height: 1.2;"><?php echo $infInicios." + ".$sumaIngresos." - ".$sumaEgresos.""?></p>
            <p style="margin-top: 0;margin-bottom: 0px;font-size: 13px;font-weight: 600;line-height: 0.9;">(F. Inicial + Ingresos) - Egresos </p>
          </div>
          <div class="icon">
            <i class="ion ion-pie-graph"></i>
          </div>

          <a type="button" id="" class="small-box-footer"> <i class="fas fa-arrow-circle-right" id=""></i></a>
        </div>
      </div>
      <!-- ./col -->
    </div>
    </form>
    <!-- /.row -->
  </div>
</section>
<!------------------------------------------------------------------------------------->
<div class="row">
  <div class="col-md-2">
  </div>
  <div class="col-md-8">
    <div class="small-box bg-warning" style="text-align: center;">
      <a type="button" href="cajaChica.php" id="" class="small-box-footer" style="font-weight: 600;"><i class="fas fa-arrow-circle-left" id=""></i> VOLVER A LA VENTANA PRINCIPAL DE CAJAS </a>
    </div>
  </div>
  <div class="col-md-2">
  </div>
</div>
<!------------------------------------------------------------------------------------->
<div class="row"><!-- Main row -->

  <section class="col-lg-3 connectedSortable">
    <!-- PIE CHART INGRESOS-->
    <div class="card card-success">
      <div class="card-header">
        <h3 class="card-title" style="font-size: 1.75rem; margin-top: 0.6rem;margin-bottom: 0.5rem;font-weight: 500;line-height: 1.2;">
        <i class="fas fa-chart-pie mr-1"></i>
          Gr&aacutefico de Ingresos</h3>
        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
          </button>
          <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
        </div>
      </div>
      <div class="card-body">
        <canvas id="pieChart-ingresos" style="min-height: 250px; height: 275px; max-height: 350px; max-width: 100%;"></canvas>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </section><!-- /. end section -->
<!------------------------------------------------------------------------------------------------------------------------------------>  
  <section class="col-lg-3 connectedSortable">
    <!-- /.card Lista de egresos -->
    <div class="card card-info">
      <div class="card-header" style="height: 48px;">
        <h3 class="card-title" style="font-size: 1.75rem; margin-top: 0.5rem;margin-bottom: 0.5rem;font-weight: 500;line-height: 1.2;">
          <i class="glyphicon glyphicon-transfer" style="margin-right: 4px;"></i>
          <p style="margin-left: 25px;margin-top: -23px;margin-bottom: 0px;">Listado de Ingresos</p>
        </h3>
      </div><!-- /.card-header -->
      <div class="card-body">
        <div class="table-responsive" style="width: 100%;margin-top: 2px;">
          <table id="tabla_egresos" name="tabla_egresos" class="table table-bordered table-striped table-hover" style="font-size: 12px;">
            <thead style="width: 80%; border: 1px solid lightgrey;">
              <tr>
                <th style="width: 65%;">CONCEPTO</th>
                <th style="width: 35%;">MONTO</th>
              </tr>
            </thead>
            <tbody>
              <?php 
              $montoType_ingresos = 0;
              $colorcito = 0;
              foreach ($result_tipo_ingreso as $unResult_tipo_ingreso) :
                ?><tr class="info"><?php
                $sql_type_ingresos = $db->query("SELECT monto FROM caja_ingresos WHERE fecha >='".$startInforme."'AND fecha <='".$endInforme."' AND tipo_ingreso = ".$unResult_tipo_ingreso['tipo_ingreso']."");
                $type_ingreso = $db->while_loop($sql_type_ingresos);
                if ($unResult_tipo_ingreso['tipo_ingreso'] == 1) { ?>
                  <td>Ingreso de personal</td>
                <?php } 
                if ($unResult_tipo_ingreso['tipo_ingreso'] == 2) { ?>
                  <td>Ingreso de caja maquinarias</td>
                <?php }
                if ($unResult_tipo_ingreso['tipo_ingreso'] == 3) { ?>
                  <td>Ingreso de cliente regular</td>
                <?php }
                if ($unResult_tipo_ingreso['tipo_ingreso'] == 4) { ?>
                  <td>Ingreso de cliente maquinarias</td>
                <?php }
                if ($unResult_tipo_ingreso['tipo_ingreso'] == 5) { ?>
                  <td>Otro ingreso</td>
                <?php }
                foreach ($type_ingreso as $unType_ingreso) :
                  $montoType_ingresos = $montoType_ingresos + $unType_ingreso['monto'];
                endforeach; ?>
                <td class="warning"><?php echo $montoType_ingresos;?></td>
                <?php $montoType_ingresos = 0 ; ?> 
              </tr>
              <?php $colorcito = $colorcito + 1;
              endforeach; ?>
            </tbody>
            <tfoot style="border: 1px solid lightgrey;">
              <tr>
                <th class="danger" style="width: 65%;font-size: 14px;">TOTALES</th>
                <th class="danger" style="width: 35%;font-size: 14px;"><?php echo "$ ".$sumaIngresos;?></th>
              </tr>
            </tfoot>
          </table>
          <script>
           // $('#tabla_egresos').DataTable(/*{ "order": [[ 6, "desc" ],[ 7, "desc" ]] }*/); 
           // $('.dataTables_length').addClass('bs-select');
          </script>
        </div><!-- .table end -->   
      </div><!-- /.card-body -->
    </div><!-- /. card-end -->
  </section><!-- /. end section -->
<!-------------------------------------------------------------------------------------------------------------------------------------->  
  <section class="col-lg-3 connectedSortable">
    <!-- PIE CHART EGRESOS-->
    <div class="card card-danger">
      <div class="card-header">
        <h3 class="card-title" style="font-size: 1.75rem; margin-top: 0.6rem;margin-bottom: 0.5rem;font-weight: 500;line-height: 1.2;">
        <i class="fas fa-chart-pie mr-1"></i>
          Gr&aacutefico de Egresos</h3>
        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
          </button>
          <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
        </div>
      </div>
      <div class="card-body">
        <canvas id="pieChart-egresos" style="min-height: 250px; height: 275px; max-height: 350px; max-width: 100%;"></canvas>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
    
  </section><!-- /. end section -->
<!--------------------------------------------------------------------------------------------------------------------------------->  
  <section class="col-lg-3 connectedSortable">
    <!-- /.card Lista de egresos -->
    <div class="card card-info">
      <div class="card-header" style="height: 48px;">
        <h3 class="card-title" style="font-size: 1.75rem; margin-top: 0.5rem;margin-bottom: 0.5rem;font-weight: 500;line-height: 1.2;">
          <i class="glyphicon glyphicon-transfer" style="margin-right: 4px;"></i>
          <p style="margin-left: 25px;margin-top: -23px;margin-bottom: 0px;">Listado de Egresos</p>
        </h3>
      </div><!-- /.card-header -->
      <div class="card-body">
        <div class="table-responsive" style="width: 100%;margin-top: 2px;">
          <table id="tabla_egresos" name="tabla_egresos" class="table table-bordered table-striped table-hover" style="font-size: 12px;">
            <thead style="width: 80%; border: 1px solid lightgrey;">
              <tr>
                <th style="width: 65%;">CONCEPTO</th>
                <th style="width: 35%;">MONTO</th>
              </tr>
            </thead>
            <tbody>
              <?php 
              $montoConcept_egresos = 0;
              $colorcito = 0;
              foreach ($result_tipo_conceptos_egresos as $unResult_tipo_conceptos_egresos) :
                ?><tr class="info"><?php
                $sql_concept_egresos = $db->query("SELECT monto FROM caja_egresos WHERE fecha >='".$startInforme."'AND fecha <='".$endInforme."' AND concepto_id = ".$unResult_tipo_conceptos_egresos['id']."");
                $concept_egresos = $db->while_loop($sql_concept_egresos); ?>
                <td> <?php echo $unResult_tipo_conceptos_egresos['descripcion'];?> </td>
                <?php
                foreach ($concept_egresos as $unConcept_egresos) :
                  $montoConcept_egresos = $montoConcept_egresos + $unConcept_egresos['monto'];
                endforeach; ?>
                <td class="warning"><?php echo $montoConcept_egresos;?></td>
                <?php $montoConcept_egresos = 0 ; ?> 
              </tr>
              <?php $colorcito = $colorcito + 1;
              endforeach; ?>
            </tbody>
            <tfoot style="border: 1px solid lightgrey;">
              <tr>
                <th class="danger" style="width: 65%;font-size: 14px;">TOTALES</th>
                <th class="danger" style="width: 35%;font-size: 14px;"><?php echo "$ ".$sumaEgresos;?></th>
              </tr>
            </tfoot>
          </table>
          <script>
           // $('#tabla_egresos').DataTable(/*{ "order": [[ 6, "desc" ],[ 7, "desc" ]] }*/); 
           // $('.dataTables_length').addClass('bs-select');
          </script>
        </div><!-- .table end -->   
      </div><!-- /.card-body -->
    </div><!-- /. card-end -->
  </section><!-- /. end section -->
<!------------------------------------------------------------------------------------------------------------------------------------>
  <section class="col-lg-12 connectedSortable"><!-- LEFT col -->
    <div class="card card-success">
      <div class="card-header" style="height: 48px;">
        <h3 class="card-title" style="font-size: 1.75rem; margin-top: 0.5rem;margin-bottom: 0.5rem;font-weight: 500;line-height: 1.2;">
          <i class="glyphicon glyphicon-transfer" style="margin-right: 4px;"></i>
          <p style="margin-left: 25px;margin-top: -23px;margin-bottom: 0px;">Movimientos</p>
        </h3>
      </div><!-- /.card-header -->
      <div class="card-body">
        <div class="table-responsive" style="width: 100%;padding: 13px;margin-left: -6px;">
          <table id="caja_movimientos" name="caja_movimientos" class="table table-bordered table-striped table-hover" style="width: 100%;font-size: 12px;">
            <thead>
              <tr>
                <th style="width: 5%;">N° Caja</th>
                <th style="width: 7%;">Tipo</th>
                <th style="width: 12%;">Origen</th>
                <th style="width: 25%;">Entidad</th>
                <th style="width: 15%;">Concepto</th>
                <th style="width: 20%;">Descripción</th>
                <th style="width: 10%;">Monto</th>
                <th style="width: 8%;">Fecha</th>
                <th style="width: 8%;">Hora</th>
                <!--<th style="width: 8%;">Estado</th>-->                    
              </tr>
            </thead>
            <tbody>
              <!--------------------- INGRESOS --------------------->
              <?php foreach ($informeIngresos as $unInformeIngresos): 
                $origen = find_by_id('caja_origen',$unInformeIngresos['origen_id']);
                $moneda = find_by_id('caja_moneda',$unInformeIngresos['moneda_id']);                 
              ?>
                    <tr class="success">
                      <td class="text-center"><?php echo $unInformeIngresos['caja_id'];?></td>

                      <td class="text-center"><span class="label label-success">Ingreso</span></td>

                      <td class="text-center"><?php echo $origen['descripcion'];?></td>

                        <?php if ($unInformeIngresos['tipo_ingreso'] == 1) { 
                        $personal = find_by_id('personal',$unInformeIngresos['personal_id']); ?>
                      <td class="text-center"><?php echo $personal['apellido'].", ".$personal['nombre'];?></td> 
                        <?php } ?>
                        <?php if ($unInformeIngresos['tipo_ingreso'] == 2) { ?>
                      <td class="text-center"><?php echo "Caja de maquinarias";?></td>
                        <?php } ?>  
                        <?php if ($unInformeIngresos['tipo_ingreso'] == 3) { //cliente normal
                        $esteCliente = find_by_id('cliente',$unInformeIngresos['cliente_id']); ?>
                      <td class="text-center"><?php echo $esteCliente['razon_social'];?></td>
                        <?php } ?>
                        <?php if ($unInformeIngresos['tipo_ingreso'] == 4) {  
                        $esteCliente = find_by_id('clientemaquina',$unInformeIngresos['cliente_id']); ?>
                      <td class="text-center"><?php echo $esteCliente['razon_social'];?></td>
                        <?php } ?>
                        <?php if ($unInformeIngresos['tipo_ingreso'] == 5) { ?>
                      <td class="text-center"><?php echo $unInformeIngresos['cliente_adicional'];?></td>
                      <?php } ?>

                      <td class="text-center">-</td>

                      <td class="text-center">-</td>

                      <!--<td class="text-center"><?php //echo $moneda['codigo'];?></td>   -->                    

                      <td class="text-center"><?php echo "$ ".$unInformeIngresos['monto'];?></td>                          

                      <td class="text-center"><?php echo $unInformeIngresos['fecha'];?></td>

                      <td class="text-center"><?php echo $unInformeIngresos['hora'];?></td>                             

                      <!--<td class="text-center">-</td>-->
                    </tr>
              <?php endforeach; ?>
              <!--------------------- EGRESOS --------------------->
              <?php foreach ($informeEgresos as $unInformeEgresos):
                $origen = find_by_id('caja_origen',$unInformeEgresos['origen_id']); 
                $moneda = find_by_id('caja_moneda',$unInformeEgresos['moneda_id']);
              ?>
                <tr class="danger">
                  <td class="text-center"><?php echo $unInformeEgresos['caja_id'];?></td>

                  <td class="text-center"><span class="label label-danger">Egreso</span></td>

                  <td class="text-center"><?php echo $origen['descripcion']; ?></td>

                    <?php if ($unInformeEgresos['personal_id'] != null) { 
                      $personal = find_by_id('personal',$unInformeEgresos['personal_id']);
                    ?>
                  <td class="text-center"><?php echo $personal['apellido'].", ".$personal['nombre'];?></td>
                    <?php } else { ?>
                  <td class="text-center">-</td>
                    <?php } ?>
                    <?php $esteConcepto = find_by_id('caja_conceptos',$unInformeEgresos['concepto_id']); 
                      if ($esteConcepto['id'] == 1 || $esteConcepto['id'] == 14) {
                        $tdTitle = $unInformeEgresos['litros_combustible']." Litros";
                      } else {
                        $tdTitle = $unInformeEgresos['aclaracion'];
                      }
                      if($esteConcepto['id'] == 12) {
                        $tdTitle = $unInformeEgresos['concepto_adicional'];
                      }                          
                    ?>
                  <td class="text-center" title="<?php echo $tdTitle; ?>"><?php echo $esteConcepto['descripcion']; ?></td>
                  <!--<td class="text-center"><?php// echo $moneda['codigo'];?></td>-->
                  <td class="text-center"><?php echo $tdTitle;?></td>
                  <td class="text-center"><?php echo "$ ".$unInformeEgresos['monto'];?></td>
                  <td class="text-center"><?php echo $unInformeEgresos['fecha'];?></td>
                  <td class="text-center"><?php echo $unInformeEgresos['hora'];?></td> 
                  <!--<td class="text-center">-</td>-->
                </tr>
                <?php  
              endforeach;
              ?>         
            </tbody>
            <tfoot>
              <tr>
                <th style="width: 5%;">N° Caja</th>
                <th style="width: 7%;">Tipo</th>
                <th style="width: 12%;">Origen</th>
                <th style="width: 15%;">Entidad</th>
                <th style="width: 15%;">Concepto</th>
                <th style="width: 20%;">Descripción</th>
                <th style="width: 10%;">Monto</th>
                <th style="width: 8%;">Fecha</th>
                <th style="width: 8%;">Hora</th>
              </tr>
            </tfoot>
          </table>
          <script>
            $('#caja_movimientos').DataTable({ "order": [[ 7, "desc" ],[ 8, "desc" ]]}); 
            $('.dataTables_length').addClass('bs-select');
          </script>
        </div>   
      </div><!-- /card body -->
    </div><!-- /card header -->
  </section>

</div><!-- end main row -->


<!--------------- MODAL INFORMES ------------------>
<div class="modal bd-example-modal-lg" id="modal-new_informe" tabindex="-1" role="dialog" aria-labelledby="modal-new_informe-label" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content bg-info">
      <div class="modal-header">
        <h3 class="modal-title">Seleccionar nuevo rango</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-5">
          <!--  <input type="text" id="start_informe" hidden>
            <input type="text" id="end_informe" hidden>  -->
            <label for="fecha_informe" class="control-label">Rango de informes:</label>
            <input type="text" class="form-control" name="fecha_informe" id="fecha_informe">
            <script>
              $('#fecha_informe').daterangepicker({
                "locale": {
                      "format": "DD/MM/YYYY",
                      "separator": " - ",
                      "applyLabel": "Aceptar",
                      "cancelLabel": "Cancelar",
                      "fromLabel": "De",
                      "toLabel": "Hasta",
                      "customRangeLabel": "Rango personalizado",
                      "weekLabel": "W",
                      "daysOfWeek": [
                          "Do",
                          "Lu",
                          "Ma",
                          "Mi",
                          "Ju",
                          "Vi",
                          "Sa"
                      ],
                      "monthNames": [
                          "Enero",
                          "Febrero",
                          "Marzo",
                          "Abril",
                          "Mayo",
                          "Junio",
                          "Julio",
                          "Agosto",
                          "Septiembre",
                          "Octubre",
                          "Noviembre",
                          "Dicienmbre"
                      ],
                      "firstDay": 1
                  },
                ranges   : {
                  'Hoy'       : [moment(), moment()],
                  'Ayer'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                  'Últimos 7 días' : [moment().subtract(6, 'days'), moment()],
                  'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
                  'Éste mes'  : [moment().startOf('month'), moment().endOf('month')],
                  'Mes anterior'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                startDate: moment(),
                endDate  : moment()
              }, function (start, end) {
                document.getElementById('start_informe').value = start.format('YYYY-MM-DD');
                document.getElementById('end_informe').value = end.format('YYYY-MM-DD');
                document.getElementById('btn_generar_informe').disabled = false;
              //window.alert('Su selección: ' + start.format('D MMMM, YYYY') + ' Hasta ' + end.format('D MMMM, YYYY'))
              });  
            </script>
          </div>
          <div class="col-md-2">
            <label disabled for="btn_generar_informe" class="control-label">Acción:</label>
            <button disabled type="button" id="btn_generar_informe" class="btn btn-outline-light" onclick="javascript:goInforme();" style="border: 1px solid white;">Generar</button>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
          </div>
        </div>
      </div>
       <div class="modal-footer">
        <br>
        <div class="row">          
          <div class="col-md-6">            
            <button type="button" class="btn btn-outline-light" style="border: 1px solid white;"  data-dismiss="modal">Cancelar</button>
          </div>
       <!--   <div class="col-md-6">                                        
            <button type="button" class="btn btn-outline-light" style="border: 1px solid white;" id="btn_agregar_egreso">Aceptar</button>
          </div>   -->
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<!--------------- END MODAL INFORMES -------------->



    </div>
  </div>
</body>
  <!-- jQuery -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <!-- jQuery UI 1.11.4 -->
  <script src="plugins/jquery-ui/jquery-ui.min.js"></script>
  <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
  <script>
    $.widget.bridge('uibutton', $.ui.button)
  </script>
  <!-- Bootstrap 4 -->
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- ChartJS -->
  <script src="plugins/chart.js/Chart.min.js"></script>
  <!-- Sparkline -->
  <script src="plugins/sparklines/sparkline.js"></script>
  <!-- JQVMap -->
  <script src="plugins/jqvmap/jquery.vmap.min.js"></script>
  <script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
  <!-- jQuery Knob Chart -->
  <script src="plugins/jquery-knob/jquery.knob.min.js"></script>
  <!-- daterangepicker -->
  <!--<script src="plugins/moment/moment.min.js"></script>-->
  <!--<script src="plugins/daterangepicker/daterangepicker.js"></script>-->
  <!-- Tempusdominus Bootstrap 4 -->
  <script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
  <!-- Summernote -->
  <script src="plugins/summernote/summernote-bs4.min.js"></script>
  <!-- overlayScrollbars -->
  <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.js"></script>
  <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
  <!--<script src="dist/js/pages/dashboard.js"></script>-->
  <!-- AdminLTE for demo purposes -->
  <!--<script src="dist/js/demo.js"></script>-->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
</html>

<?php if(isset($db)) { $db->db_disconnect(); } ?>