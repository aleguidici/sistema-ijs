<?php
  $page_title = 'Cajas';
  require_once('includes/load.php');
   page_require_level(2);
?>
 
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
    <script src="plugins/chart.js/Chart.min.js"></script>
    <?php include_once('layouts/header.php');?>    




<?php
  //$caja = find_by_sql("SELECT * FROM caja WHERE estado <> 0");
  $sql_caja = $db->query("SELECT * FROM caja WHERE estado <> 0 LIMIT 1");
  $caja = $db->fetch_assoc($sql_caja);
?>
 
<script>
  $( function() {

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
});
   
</script>    

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
              <a href="caja_actual.php?caja_id=<?php echo $caja['id'];?>" class="small-box-footer"><input hidden>Más información <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3 style="font-size: calc(2.5rem + 0.6vw);margin-top: 0;margin-bottom: 0.5rem;font-weight: 600;line-height: 1.2;">53<sup style="font-size: 20px">%</sup></h3>
                <p style="margin-top: 0;margin-bottom: 1rem;font-size: 1.5rem;">Bounce Rate</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
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
      <!-- Left col -->
      <section class="col-lg-7 connectedSortable">
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

      <section class="col-lg-5 connectedSortable">
        <!-- Calendar -->
        <div class="card bg-gradient-success">
          <div class="card-header border-0">

            <h3 class="card-title" style="font-size: 1.75rem; margin-top: 0;margin-bottom: 0.5rem;font-weight: 500;line-height: 1.2;">
              <i class="far fa-calendar-alt"></i>
              Calendario
            </h3>
            <!-- tools card -->
            <div class="card-tools">
              <button type="button" class="btn btn-success btn-sm" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-success btn-sm" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button>
            </div>
            <!-- /. tools -->
          </div>
          <!-- /.card-header -->
          <div class="card-body pt-0">
            <!--The calendar -->
            <div id="calendar" style="width: 100%;"></div></div>
        </div>
          <!-- /.card-body -->
        <!-- /.card -->
      </section>
    </div>


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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
</html>

<?php if(isset($db)) { $db->db_disconnect(); } ?>

