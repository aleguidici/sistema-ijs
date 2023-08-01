<?php
  $page_title = 'Cajas';
  require_once('includes/load.php');
  page_require_level(2);
  $user = current_user(); 
?>
<!DOCTYPE html>
  <html lang="en">
    <head>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php if (!empty($page_title))
          echo remove_junk($page_title);
          elseif(!empty($user))
          echo ucfirst($user['name']);
          else echo "Sistema IJS"; ?>
    </title>

<!-- LINKS -->

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" />

    <link rel="stylesheet" href="libs/css/main.css" /> 

    <!--<link rel="stylesheet" href="plugins/bootstrap/js/bootstrap.bundle.min.js">-->

    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bbootstrap 4 -->
    <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
    <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
    <!-- summernote -->
    <link rel="stylesheet" href="plugins/summernote/summernote-bs4.css">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">


      <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css">
      <link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.15.3/dist/bootstrap-table.min.css">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
      <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css">


<!-- SCRIPTS -->
      
      

    <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script> -->  
    
    <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script> -->

    



    


          
  
    <style type="text/css">
      body {
          color: #404E67;
          background: #F5F7FA;
          font-family: 'Open Sans', sans-serif;
          font-size: 12px;
      }
      .table-wrapper {
          width: fixed;
          margin: 30px auto;
          background: #fff;
          padding: 20px;  
          box-shadow: 0 1px 1px rgba(0,0,0,.05);
      }.table td.fit, 
        .table th.fit {
            white-space: nowrap;
            width: 1%;
        }
      .table-title {
          padding-bottom: 10px;
          margin: 0 0 10px;
      }
      .table-title h2 {
          margin: 6px 0 0;
          font-size: 22px;
      }
      .table-title .add-new {
          float: right;
          height: 30px;
          font-weight: bold;
          font-size: 12px;
          text-shadow: none;
          min-width: 100px;
          border-radius: 50px;
          line-height: 13px;
      }
      table.table {
          table-layout: fixed;
      }
      table.table tr th, table.table tr td {
          border-color: #e9e9e9;
      }
      table.table th i {
          font-size: 13px;
          margin: 0 5px;
          cursor: pointer;
      }
      table.table th:last-child {
          width: 100px;
      }
      table.table td a {
          cursor: pointer;
          display: inline-block;
          margin: 0 5px;
          min-width: 24px;
      }    
      table.table td a.add {
          color: #27C46B;
      }
      table.table td a.edit {
          color: #FFC107;
      }
      table.table td a.delete {
          color: #E34724;
      }
      table.table td i {
          font-size: 19px;
      }
      table.table td a.add i {
          font-size: 24px;
          margin-right: -1px;
          position: relative;
          top: 3px;
      }    
      table.table .form-control {
          height: 32px;
          line-height: 32px;
          box-shadow: none;
          border-radius: 2px;
      }
      table.table .form-control.error {
          border-color: #f50000;
      }
      table.table td .add {
          display: none;
      }
    </style>

<script>
  $( function() {
    $('sel_origen').selectpicker();
    'use strict'
    // Make the dashboard widgets sortable Using jquery UI
    $('.connectedSortable').sortable({
      placeholder         : 'sort-highlight',
      connectWith         : '.connectedSortable',
      handle              : '.card-header, .nav-tabs',
      forcePlaceholderSize: true,
      zIndex              : 999999
    })
    $('.connectedSortable .card-header, .connectedSortable .nav-tabs-custom').css('cursor', 'move')

    // The Calender
    $('#calendar').datetimepicker({
      format: 'L',
      inline: true
    });

    /* Chart.js Charts */
    // Sales chart
    var salesChartCanvas = document.getElementById('revenue-chart-canvas').getContext('2d');
    //$('#revenue-chart').get(0).getContext('2d');

    var salesChartData = {
      labels  : ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
      datasets: [
        {
          label               : 'Digital Goods',
          backgroundColor     : 'rgba(60,141,188,0.9)',
          borderColor         : 'rgba(60,141,188,0.8)',
          pointRadius          : false,
          pointColor          : '#3b8bba',
          pointStrokeColor    : 'rgba(60,141,188,1)',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(60,141,188,1)',
          data                : ['<?php echo 35000;?>',20000,25000,1000]
        },
        {
          label               : 'Electronics',
          backgroundColor     : 'rgba(210, 214, 222, 1)',
          borderColor         : 'rgba(210, 214, 222, 1)',
          pointRadius         : false,
          pointColor          : 'rgba(210, 214, 222, 1)',
          pointStrokeColor    : '#c1c7d1',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(220,220,220,1)',
          data                : [<?php for ($i = 0; $i <= 100000; $i += 25000) { echo $i.","; } ?>]
        },
      ]
    }

    var salesChartOptions = {
      maintainAspectRatio : false,
      responsive : true,
      legend: {
        display: false
      },
      scales: {
        xAxes: [{
          gridLines : {
            display : false,
          }
        }],
        yAxes: [{
          gridLines : {
            display : false,
          }
        }]
      }
    }

    // This will get the first returned node in the jQuery collection.
    var salesChart = new Chart(salesChartCanvas, { 
        type: 'line', 
        data: salesChartData, 
        options: salesChartOptions
      }
    )

    // Donut Chart
    var donutChartCanvas = $('#sales-chart-canvas').get(0).getContext('2d')
    var donutData        = {
      labels: [
          '<?php echo "hola"; ?>', 
          'Download Sales',
          'Mail-Order Sales', 
      ],
      datasets: [
        {
          data: [15,12,20],
          backgroundColor : ['#f56954', '#00a65a', '#f39c12'],
        }
      ]
    }
    var donutOptions = {
      legend: {
        display: false
      },
      maintainAspectRatio : false,
      responsive : true,
    }
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    var donutChart = new Chart(donutChartCanvas, {
      type: 'doughnut',
      data: donutData,
      options: donutOptions      
    });

      //-------------
      //- PIE CHART -
      //-------------
      // Get context with jQuery - using jQuery's .get() method.
      var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
      var pieData        = donutData;
      var pieOptions     = {
        maintainAspectRatio : false,
        responsive : true,
      }
      //Create pie or douhnut chart
      // You can switch between pie and douhnut using the method below.
      var pieChart = new Chart(pieChartCanvas, {
        type: 'pie',
        data: pieData,
        options: pieOptions      
      })

      //-------------


      var dateFormat = "dd/mm/yy",
        from = $( "#from" )
          .datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            numberOfMonths: 3
          })
          .on( "change", function() {
            to.datepicker( "option", "minDate", getDate( this ) );
          }),
        to = $( "#to" ).datepicker({
          defaultDate: "+1w",
          changeMonth: true,
          numberOfMonths: 3
        })
        .on( "change", function() {
          from.datepicker( "option", "maxDate", getDate( this ) );
        });
   
      function getDate( element ) {
        var date;
        try {
          date = $.datepicker.parseDate( dateFormat, element.value );
        } catch( error ) {
          date = null;
        }
     
          return date;
        }
  } );
</script>
  </head>
 
  <body>
    <?php if ($session->isUserLoggedIn(true)): ?>
      <header id="header">
        <div class="logo pull-left">IJS INGENIERÍA ELÉCTRICA</div>
        <div class="header-content">
        <div class="header-date pull-left">
          <strong><?php echo date("d/m/Y");?></strong>
        </div>
        <div class="pull-right clearfix">
          <ul class="info-menu list-inline list-unstyled">
            <li class="profile">
              <a href="#" data-toggle="dropdown" class="toggle" aria-expanded="false">
                <img src="uploads/users/<?php echo $user['image'];?>" alt="user-image" class="img-circle img-inline">
                <span><?php echo remove_junk(ucfirst($user['name'])); ?> <i class="caret"></i></span>
              </a>
              <ul class="dropdown-menu">
                <li>
                  <a href="profile.php?id=<?php echo (int)$user['id'];?>">
                    <i class="glyphicon glyphicon-user"></i>
                    Perfil
                  </a>
                </li>
                <li>
                  <a href="edit_account.php" title="edit account">
                    <i class="glyphicon glyphicon-cog"></i>
                    Configuración
                  </a>
                </li>
                <li class="last">
                  <a href="logout.php">
                    <i class="glyphicon glyphicon-off"></i>
                    Salir
                  </a>
               </li>
              </ul>
            </li>
          </ul>
        </div>
       </div>
      </header>
      <div class="sidebar" style="width: 251px;">
        <?php if($user['user_level'] === '1'): ?>
          <?php include_once('layouts/admin_menu.php');?>
        <?php elseif($user['user_level'] === '2'): ?>
          <?php include_once('layouts/special_menu.php');?>
        <?php elseif($user['user_level'] === '3'): ?>
          <?php include_once('layouts/user_menu.php');?>
        <?php endif;?>
      </div>
    <?php endif;?>
    <div class="page">
      <div class="container-fluid"> 
    

    
    

<?php
  //$caja = find_by_sql("SELECT * FROM caja WHERE estado <> 0");
  $sql_caja = $db->query("SELECT * FROM caja WHERE estado <> 0 LIMIT 1");
  $caja = $db->fetch_assoc($sql_caja);
  $idCaja = $caja['id'];


 /* 
  $sql_ingresos = $db->query("SELECT * FROM caja_ingresos WHERE caja_id = '".$idCaja."'");
  $allIngresos = $db->while_loop($sql_ingresos);

  $sql_egresos = $db->query("SELECT * FROM caja_egresos WHERE caja_id = '".$idCaja."'");
  $allEgresos = $db->while_loop($sql_egresos);

  $sql_gastos = $db->query("SELECT * FROM caja_gastos ");
  $allGastos = $db->while_loop($sql_gastos);
*/
  $allIngresos = find_all('caja_ingresos');
  $allEgresos = find_all('caja_egresos');
  $allGastos = find_all('caja_gastos');
  $allOrigenes = find_all('caja_origen');
  $allPersonal = find_all('personal');

  $sumaIngresos = 0;

  foreach ($allIngresos as $unIngreso):
    if ($unIngreso['caja_id'] == $idCaja) {
      $sumaIngresos = $sumaIngresos + $unIngreso['monto'];
    } 
  endforeach;
     
?>

    <!-- Main content -->
    <section class="content" style="margin-left: -15px;margin-right: -15px;">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <form action="caja_actual.php" method="POST">
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3 style="font-size: calc(2.5rem + 0.6vw);margin-top: 0;margin-bottom: 0.5rem;font-weight: 600;line-height: 1.2;"><?php echo "$ ".$caja['fondo_inicio'];?></h3>
                <p style="margin-top: 0;margin-bottom: 1rem;font-size: 1.5rem;"><?php echo "Inicio de caja ".$caja['fecha_inicio'];?></p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a type="button" class="small-box-footer" data-toggle="modal" data-target="#modalAgregarRepuesto"><input hidden>Más información <i class="fas fa-arrow-circle-right" id="openModalAgregarRepuesto"></i></a>              
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3 style="font-size: calc(2.5rem + 0.6vw);margin-top: 0;margin-bottom: 0.5rem;font-weight: 600;line-height: 1.2;"><?php echo "$ ".$sumaIngresos;?> <!--<sup style="font-size: 20px">%</sup> --></h3>
                <p style="margin-top: 0;margin-bottom: 1rem;font-size: 1.5rem;">Ingresos</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <a type="button" class="small-box-footer" data-toggle="modal" data-target="#modal_ingresos"><input hidden>Ingresar movimiento <i class="fas fa-arrow-circle-right" id="open_modal_agregar_ingreso"></i></a> 
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3 style="font-size: calc(2.5rem + 0.6vw);margin-top: 0;margin-bottom: 0.5rem;font-weight: 600;line-height: 1.2;">44</h3>
                <p style="margin-top: 0;margin-bottom: 1rem;font-size: 1.5rem;">User Registrations</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3 style="font-size: calc(2.5rem + 0.6vw);margin-top: 0;margin-bottom: 0.5rem;font-weight: 600;line-height: 1.2;">65</h3>
                <p style="margin-top: 0;margin-bottom: 1rem;font-size: 1.5rem;">Unique Visitors</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>
        </form>
        <!-- /.row -->
      </div>
    </section>
    <!-- Main row -->
    <div class="row">

      <!-- LEFT col -->
      <section class="col-lg-7 connectedSortable">
        <div class="card card-success">
          <div class="card-header">
            <h3 class="card-title" style="font-size: 1.75rem; margin-top: 0.5rem;margin-bottom: 0.5rem;font-weight: 500;line-height: 1.2;">
              <i class="glyphicon glyphicon-transfer" style="margin-right: 3px;"></i>
              <p style="margin-left: 25px;margin-top: -23px;margin-bottom: 0px;">Movimientos</p>
            </h3>
          </div><!-- /.card-header -->
          <div class="card-body">
         
            <div class="table-responsive">
              <table id="caja_movimientos" name="caja_movimientos" class="table table-hover table-condensed table-bordered" style="width: 98.5%;font-size: 13px;">
                <thead>
                  <tr>
                    <th style="width: 12.5%;">Tipo</th>
                    <th style="width: 12.5%;">Origen</th>
                    <th style="width: 12.5%;">Entidad</th>
                    <th style="width: 12.5%;">Concepto</th>
                    <th style="width: 12.5%;">Divisa</th>
                    <th style="width: 12.5%;">Monto</th>
                    <th style="width: 12.5%;">Fecha</th>
                    <th style="width: 12.5%;">Estado</th>                    
                  </tr>
                </thead>
                <tbody>
                  <!--------------------- INGRESOS --------------------->
                  <?php foreach ($allIngresos as $unIngreso): 
                    $origen = find_by_id('caja_origen',$unIngreso['origen_id']);
                    $moneda = find_by_id('caja_moneda',$unIngreso['moneda_id']);                 
                  ?>
                        <tr class="success">

                          <td class="text-center"><span class="label label-success">Ingreso</span></td>

                          <td class="text-center"><?php echo $origen['descripcion'];?></td>

                          <?php if ($unIngreso['personal_id'] != null) { 
                          $personal = find_by_id('personal',$unIngreso['personal_id']);
                          ?>
                          <td class="text-center"><?php echo $personal['apellido'].", ".$personal['nombre'];?></td> 
                          <?php } elseif ($unIngreso['cliente_id'] != null) { 
                          $tipoCliente = $unIngreso['tipo_cliente'];
                          if ($tipoCliente == 1) { //cliente normal
                            $cliente = find_by_id('cliente',$unIngreso['cliente_id']); ?>
                            <td class="text-center"><?php echo $cliente['razon_social'];?></td>
                          <?php } elseif ($tipoCliente == 2) { 
                            $cliente = find_by_id('clientemaquina',$unIngreso['cliente_id']); ?>
                            <td class="text-center"><?php echo $cliente['razon_social'];?></td>
                          <?php } } else { ?>
                          <td class="text-center"><?php echo $unIngreso['cliente_adicional'];?></td>
                          <?php } ?>

                          <td class="text-center">-</td>

                          <td class="text-center"><?php echo $moneda['divisa'];?></td>                       

                          <td class="text-center"><?php echo $unIngreso['monto'];?></td>                          

                          <td class="text-center"><?php echo $unIngreso['fecha'];?></td>                            

                          <td class="text-center">-</td>

                        </tr>
                  <?php endforeach; ?>
                  <!--------------------- EGRESOS --------------------->
                  <?php foreach ($allEgresos as $unEgreso): 
                    $moneda = find_by_id('caja_moneda',$unEgreso['moneda_id']);
                  ?>
                    <tr class="danger">
                      <td class="text-center"><span class="label label-danger">Egreso</span></td>
                      <td class="text-center">-</td>
                      <td class="text-center">-</td>
                      <td class="text-center">-</td>
                      <td class="text-center"><?php echo $moneda['divisa'];?></td>
                      <td class="text-center"><?php echo $unEgreso['monto'];?></td>
                      <td class="text-center"><?php echo $unEgreso['fecha'];?></td>
                      <td class="text-center"></td>
                    </tr>
                    <?php  
                  endforeach;
                  ?>
                  <!--------------------- GASTOS --------------------->
                  <?php foreach ($allGastos as $unGasto): ?>
                    <tr class="info">
                      <td class="text-center"><span class="label label-info">Gasto</span></td>
                      <td class="text-center">-</td>
                      <td class="text-center">-</td>
                      <td class="text-center">-</td>
                      <td class="text-center">-</td>
                      <td class="text-center"><?php echo $unGasto['monto'];?></td>
                      <td class="text-center"><?php echo $unGasto['fecha'];?></td>
                      <td class="text-center">-</td>
                    </tr>
                    <?php  
                  endforeach;
                  ?>                  
                </tbody>
                <tfoot>
                  <tr>
                    <th style="width: 12.5%;">Tipo</th>
                    <th style="width: 12.5%;">Origen</th>
                    <th style="width: 12.5%;">Entidad</th>
                    <th style="width: 12.5%;">Concepto</th>
                    <th style="width: 12.5%;">Divisa</th>
                    <th style="width: 12.5%;">Monto</th>
                    <th style="width: 12.5%;">Fecha</th>
                    <th style="width: 12.5%;">Estado</th>
                  </tr>
                </tfoot>
              </table>
              <script>
                $('#caja_movimientos').DataTable({ "order": [[ 6, "desc" ]] });
                $('.dataTables_length').addClass('bs-select');
              </script>
            </div>   
       
          </div><!-- /.card-body -->
        </div>
      </section>










      <!-- RIGHT col -->
      <section class="col-lg-5 connectedSortable">
        <!-- Custom tabs (Charts with tabs)-->
        <div class="card card-info">
          <div class="card-header">
            <h3 class="card-title" style="font-size: 1.75rem; margin-top: 0.6rem;margin-bottom: 0.5rem;font-weight: 500;line-height: 1.2;">
              <i class="fas fa-chart-area mr-1"></i>
              Sales
            </h3>
      <!--      <div class="card-tools">
              <ul class="nav nav-pills ml-auto">
                <li class="nav-item">
                  <a class="nav-link active" href="#revenue-chart" data-toggle="tab">Area</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#sales-chart" data-toggle="tab">Donut</a>
                </li>
              </ul>
            </div>
      -->      
          </div><!-- /.card-header -->
          <div class="card-body">
            <div class="tab-content p-0">
              <!-- Morris chart - Sales -->
              <div class="chart tab-pane active" id="revenue-chart" style="position: relative; height: 300px;">
                  <canvas id="revenue-chart-canvas" height="300" style="height: 300px;"></canvas>                         
               </div>
              <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px;">
                <canvas id="sales-chart-canvas" height="300" style="height: 300px;"></canvas>                         
              </div>  
            </div>
          </div><!-- /.card-body -->
        </div>

        <!-- PIE CHART -->
        <div class="card card-danger">
          <div class="card-header">
            <h3 class="card-title" style="font-size: 1.75rem; margin-top: 0.6rem;margin-bottom: 0.5rem;font-weight: 500;line-height: 1.2;">
            <i class="fas fa-chart-pie mr-1"></i>
              Pie Chart</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
            </div>
          </div>
          <div class="card-body">
            <canvas id="pieChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </section>

      
    </div>


  </div>
</div>


<!----- MODAL AGREGAR INGRESO ----->
<div class="modal bd-example-modal-lg" id="modal_ingresos" tabindex="-1" role="dialog" aria-labelledby="modal_ingreso" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">        
        <h3 class="modal-title">Nuevo ingreso</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-6">
            <label>Origen:</label>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <select class="selectpicker" data-show-subtext="true" data-live-search="true" name="sel_origen" id="sel_origen" required="required" data-width="100%">
              <option value="" disabled selected >Seleccione un origen:</option>
              <?php  foreach ($allOrigenes as $unOrigen): ?>                                    
                <option value="<?php echo $unOrigen['id']; ?>">
                 <?php echo $unOrigen['descripcion'];?>
                </option>                    
              <?php endforeach; ?>                   
            </select>
          </div>
          <div class="col-md-6">
            <select class="form-control" name="sel_personal" id="sel_personal" data-width="100%">
              <option value="" disabled selected >Seleccione un personal:</option>
              <?php  foreach ($allPersonal as $unPersonal): ?>                                    
                <option value="<?php echo $unPersonal['id'];?>">
                 <?php echo $unPersonal['apellido'].", ".$unPersonal['nombre'];?>
                </option>                    
              <?php endforeach; ?>                   
            </select>
          </div>
          <!--
          <div class="col-md-4" style="text-align: center-left;">                              
            <a class="btn btn-primary" data-dismiss="modal" data-toggle="modal" title="Crear un repuesto" href="#modalCrearRepuesto"><span class="glyphicon glyphicon-plus"></span> Crear un repuesto</a>
          </div>
          -->
        </div>
      </div>
      <div class="modal-footer">
      <!--
        <div class="row">
          <div class="col-md-12" style="text-align: right;">
            <label vertical-align="middle" id="stockDisponible" style="display: none">(Stock Disponible)</label>
          </div>
        </div>
      -->
        <div class="row">
          <br>
          <div class="col-md-4" style="text-align: left">            
            <button type="button" class="btn btn-danger"  data-dismiss="modal">Cancelar</button>
          </div>
          <div class="col-md-8" style="text-align: right">                                        
            <button type="button" class="btn btn-success" id="btn_agregar_ingreso">Aceptar</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!----- FIN MODAL INGRESO ----->



  
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
 <!-- <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>-->
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
  <script src="plugins/moment/moment.min.js"></script>
  <script src="plugins/daterangepicker/daterangepicker.js"></script>
  <!-- Tempusdominus Bootstrap 4 -->
  <script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
  <!-- Summernote -->
  <script src="plugins/summernote/summernote-bs4.min.js"></script>
  <!-- overlayScrollbars -->
  <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.js"></script>
  <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
  <script src="dist/js/pages/dashboard.js"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="dist/js/demo.js"></script>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>


  <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>

    <script type="text/javascript" src="libs/js/functions.js"></script> 

    <script src="plugins/chart.js/Chart.min.js"></script>  

      <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
      <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script> 
      <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
      <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>

  

  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
</html>

<?php if(isset($db)) { $db->db_disconnect(); } ?>

