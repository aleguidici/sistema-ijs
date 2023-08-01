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
    <!--<link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">-->
    <!-- summernote -->
    <link rel="stylesheet" href="plugins/summernote/summernote-bs4.css">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <script src="plugins/chart.js/Chart.min.js"></script>   


    <?php include_once('layouts/header.php');?>


    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>


  <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> -->
  
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css">
  <link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.15.3/dist/bootstrap-table.min.css">
  <link rel="stylesheet" type="text/css" href="libs/alertifyjs/css/alertify.css">
  <link rel="stylesheet" type="text/css" href="libs/alertifyjs/css/themes/default.css">
  <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>
  <script src="libs/alertifyjs/alertify.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>  
  <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>

<?php
  $sql_caja = $db->query("SELECT * FROM caja WHERE estado <> 0 LIMIT 1");
  $caja = $db->fetch_assoc($sql_caja);
  $idCaja = $caja['id'];

  $dateInicioCajaActiva = $caja['fecha_inicio'];
  $partInicioCajaActiva = array();
  $partInicioCajaActiva = explode("-",$dateInicioCajaActiva);
  if($partInicioCajaActiva[1] == 1) {
    $partInicioCajaActiva[1] = "Enero";
  }
  if($partInicioCajaActiva[1] == 2) {
    $partInicioCajaActiva[1] = "Febrero";
  }
  if($partInicioCajaActiva[1] == 3) {
    $partInicioCajaActiva[1] = "Marzo";
  }
  if($partInicioCajaActiva[1] == 4) {
    $partInicioCajaActiva[1] = "Abril";
  }
  if($partInicioCajaActiva[1] == 5) {
    $partInicioCajaActiva[1] = "Mayo";
  }
  if($partInicioCajaActiva[1] == 6) {
    $partInicioCajaActiva[1] = "Junio";
  }
  if($partInicioCajaActiva[1] == 7) {
    $partInicioCajaActiva[1] = "Julio";
  }
  if($partInicioCajaActiva[1] == 8) {
    $partInicioCajaActiva[1] = "Agosto";
  }
  if($partInicioCajaActiva[1] == 9) {
    $partInicioCajaActiva[1] = "Septiembre";
  }
  if($partInicioCajaActiva[1] == 10) {
    $partInicioCajaActiva[1] = "Octubre";
  }
  if($partInicioCajaActiva[1] == 11) {
    $partInicioCajaActiva[1] = "Noviembre";
  }
  if($partInicioCajaActiva[1] == 12) {
    $partInicioCajaActiva[1] = "Diciembre";
  }

  $arrayInicioCajaActiva = array($partInicioCajaActiva[2], $partInicioCajaActiva[1], $partInicioCajaActiva[0]);
  $dateInicioCajaActivaOk = implode(" de ", $arrayInicioCajaActiva); 

  $sql_personal = $db->query("SELECT * FROM personal ORDER BY apellido");
  $allPersonal = $db->while_loop($sql_personal);

  $sql_tipo_moneda = $db->query("SELECT * FROM caja_moneda ORDER BY codigo");
  $allTipoMoneda = $db->while_loop($sql_tipo_moneda);

  $sql_cliente = $db->query("SELECT * FROM cliente ORDER BY razon_social, num_suc");
  $allCLientes = $db->while_loop($sql_cliente);

  $sql_clienteMaquina = $db->query("SELECT * FROM clientemaquina ORDER BY razon_social");
  $allClienteMaquina = $db->while_loop($sql_clienteMaquina);

  $sql_conceptos = $db->query("SELECT * FROM caja_conceptos ORDER BY descripcion");
  $allConceptos = $db->while_loop($sql_conceptos);

  $sql_proveedores = $db->query("SELECT * FROM proveedor ORDER BY razon_social");
  $allProveedores = $db->while_loop($sql_proveedores);

  $sql_proveedoresMaquinas = $db->query("SELECT * FROM proveedormaquina ORDER BY razon_social");
  $allProveedoresMaquinas = $db->while_loop($sql_proveedoresMaquinas);

  
  $sql_ingresos = $db->query("SELECT * FROM caja_ingresos WHERE caja_id = '".$idCaja."'");
  $allIngresos = $db->while_loop($sql_ingresos);

  $sql_egresos = $db->query("SELECT * FROM caja_egresos WHERE caja_id = '".$idCaja."'");
  $allEgresos = $db->while_loop($sql_egresos);

  $sql_gastos = $db->query("SELECT * FROM caja_gastos ");
  $allGastos = $db->while_loop($sql_gastos);

  //$allIngresos = find_all('caja_ingresos');
  //$allEgresos = find_all('caja_egresos');
  //$allGastos = find_all('caja_gastos');
  $allOrigenes = find_all('caja_origen');

  //$allConceptos = find_all('caja_conceptos');
  //$allPersonal = find_all('personal');
  //$allTipoMoneda = find_all('caja_moneda');

  //////////////////////BUSCA TODAS LAS CAJAS QUE NO ESTAN ACTIVAS
  $sql_cajas = $db->query("SELECT * FROM caja WHERE estado <> 1 ORDER BY 1 DESC");
  $allCajas = $db->while_loop($sql_cajas);

  //////////////////////BUSCAR ENTRE TODAS LAS CAJAS, LA QUE NO TIENE FECHA NI HORA DE CIERRE Y REUNE DATOS PARA SUS OPERACIONES
  $sql_caja_sin_cierre = $db->query("SELECT * FROM caja WHERE fecha_cierre is NULL AND hora_cierre is NULL");
  $cajaSinCierre = $db->fetch_assoc($sql_caja_sin_cierre);
  $sumaIngresosSinCierre = 0;
  foreach ($allIngresos as $unIngresoSinCierre):
    if($unIngresoSinCierre['caja_id'] == $cajaSinCierre['id']) {
      $sumaIngresosSinCierre = $sumaIngresosSinCierre + $unIngresoSinCierre['monto'];
    }
  endforeach;
  $sumaEgresosSinCierre = 0;
  foreach ($allEgresos as $unEgresoSinCierre):
    if($unEgresoSinCierre['caja_id'] == $cajaSinCierre['id']) {
      $sumaEgresosSinCierre = $sumaEgresosSinCierre + $unEgresoSinCierre['monto'];
    }
  endforeach;
  $balanceSinCierre = ($cajaSinCierre['fondo_inicio'] + $sumaIngresosSinCierre) - $sumaEgresosSinCierre;

////////////////////////DATOS DE INGRESOS CAJA ACTIVA
  $sumaIngresos = 0;
  foreach ($allIngresos as $unIngreso):
    if ($unIngreso['caja_id'] == $idCaja) {
      $sumaIngresos = $sumaIngresos + $unIngreso['monto'];
    } 
  endforeach;
  $sql_count_ingre = "SELECT * FROM caja_ingresos WHERE caja_id = '{$idCaja}'";
  $result_count_ingre = $db->query($sql_count_ingre);
  $rows_ingre = mysqli_num_rows($result_count_ingre);

  ////////////////////////DATOS DE EGRESOS 
  $sumaEgresos = 0;
  foreach ($allEgresos as $unEgreso):
    if ($unEgreso['caja_id'] == $idCaja) {
      $sumaEgresos = $sumaEgresos + $unEgreso['monto'];
    } 
  endforeach;
  $sql_count_egre = "SELECT * FROM caja_egresos WHERE caja_id = '{$idCaja}'";
  $result_count_egre = $db->query($sql_count_egre);
  $rows_egre = mysqli_num_rows($result_count_egre);

  ///////////////////////CALCULOS VARIOS
  $fondoInicio = $caja['fondo_inicio'];
  $balanceCaja = ($fondoInicio + $sumaIngresos) - $sumaEgresos ;

  //////////////////////CALCULOS PARA LOS GRAFICOS
  //CAKE
/*  $sql_count_tipo_egresos = "SELECT DISTINCT concepto_id FROM caja_egresos WHERE caja_id = '{$idCaja}'";
  $result_count_tipo_egresos = $db->query($sql_count_tipo_egresos);
  $val_count_tipo_egresos = $db->while_loop($result_count_tipo_egresos); */
$sql_tipo_conceptos_distinct = "SELECT DISTINCT caja_conceptos.id, caja_conceptos.descripcion FROM caja_egresos INNER JOIN caja_conceptos ON caja_egresos.concepto_id = caja_conceptos.id WHERE caja_egresos.caja_id = '{$idCaja}' ORDER by 2";
$query_tipo_conceptos = $db->query($sql_tipo_conceptos_distinct);
$result_tipo_conceptos = $db->while_loop($query_tipo_conceptos);

$montoConcept = 0;
//go to linea 260
///////////////////////// GRAPH DE LINEAS ING/EGRE
$arrayFechaActual = getDate();
$dayActual = $arrayFechaActual['mday'];
$monthActual = $arrayFechaActual['mon'];
$yearActual = $arrayFechaActual['year'];
echo '<script>console.log('.$dayActual.' + "-" +'.$monthActual.' + "-" +'.$yearActual.');</script>';
//go to linea 226
?>
<style type="text/css">
  .hoverdanger:hover {
    color: white;
    background-color: rgba(0,0,0,0.8);
  }

  .bgroundwhite {
    background-color: #FFFFFF;
  }

  .bg-warning-light {
    background-color: rgba(255,210,70,1);
  }
</style>
 
<script>
  $(window).load(function() {
    $(".loader").fadeOut(1500);
    $('#cargando').html('<div class="loader"><br><p style="font-size: 24px;position: absolute;left: 40%;top: 57%;">Cargando, un momento por favor...</p></div>');
});


  $(document).ready(function() {
    $('#modal_egresos').on('hidden.bs.modal', function (e) {
      $("#modal_egresos").find("input,textarea,select").val("");
      document.getElementById("sel_tipo_proveedor").disabled = true;
      document.getElementById("sel_proveedor").disabled = true;
      document.getElementById("inp_num_factura").disabled = true;
      document.getElementById("inp_num_remito").disabled = true;
      document.getElementById("sel_tipo_moneda_egre").disabled = true;
      document.getElementById("inp_monto_egre").disabled = true;
      document.getElementById("concepto_especificar").disabled = true;
      document.getElementById("lbl_egre_combustible_lt").hidden = true;
      document.getElementById("inp_egre_combustible_lt").hidden = true;
      document.getElementById("lbl_concepto_aclaracion_egre").hidden = true;
      document.getElementById("inp_concepto_aclaracion_egre").hidden = true;
      document.getElementById("lbl_proveedor_especificar").hidden = true;
      document.getElementById("proveedor_especificar").hidden = true;
      document.getElementById("lbl_sel_proveedor").hidden = false;
      document.getElementById("sel_proveedor").hidden = false;
    })

    $('#modal_ingresos').on('hidden.bs.modal', function (e) {
      //location.reload();
      $(this)
      .find("input,textarea,select, name, text")
         .val('')
         .end()
      .find("input[type=checkbox], input[type=radio]")
         .prop("checked", "")
         .end();
      document.getElementById("sel_tipo_ingreso").disabled = true;
      document.getElementById("sel_personal").disabled = true;
      document.getElementById("sel_cliente").disabled = true;
      document.getElementById("sel_tipo_ingreso").disabled = true;
      document.getElementById("sel_tipo_moneda").disabled = true;
      document.getElementById("otro").disabled = true;
      document.getElementById("inp_monto").disabled = true;
    });

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
  /*$('#calendar').datetimepicker({
    format: 'L',
    inline: true
  });*/

  /* Chart.js Charts */
  // Sales chart
  var salesChartCanvas = document.getElementById('revenue-chart-canvas').getContext('2d');
  //$('#revenue-chart').get(0).getContext('2d');

  var salesChartData = {
    labels  : ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
    datasets: [
      {
        label               : 'Ingresos',
        backgroundColor     : /*'rgba(60,141,188,0.9)'*/'rgba(82,190,128,0.9)',
        borderColor         : 'rgba(82,190,128,0.8)',
        pointRadius         : true,
        pointColor          : '#3b8bba',
        pointStrokeColor    : 'rgba(82,190,128,1)',
        pointHighlightFill  : '#fff',
        pointHighlightStroke: 'rgba(82,190,128,1)',
        data                : [
                                <?php
                                  $sumaIngresosEnero = 0;
                                  $sumaIngresosFebrero = 0;
                                  $sumaIngresosMarzo = 0;
                                  $sumaIngresosAbril = 0;
                                  $sumaIngresosMayo = 0;
                                  $sumaIngresosJunio = 0;
                                  $sumaIngresosJulio = 0;
                                  $sumaIngresosAgosto = 0;
                                  $sumaIngresosSeptiembre = 0;
                                  $sumaIngresosOctubre = 0;
                                  $sumaIngresosNoviembre = 0;
                                  $sumaIngresosDiciembre = 0;
                                  $allIngresosEver = find_all('caja_ingresos'); 
                                  foreach ($allIngresosEver as $unIngresoEver) :
                                    list($yearControl, $monthControl, $dayControl) = explode('-', $unIngresoEver['fecha']);
                                    if ($yearControl == $yearActual) {
                                      if ($monthControl == "01") {
                                        $sumaIngresosEnero = $sumaIngresosEnero + $unIngresoEver['monto'];
                                      }
                                      if ($monthControl == "02") {
                                        $sumaIngresosFebrero = $sumaIngresosFebrero + $unIngresoEver['monto'];
                                      }
                                      if ($monthControl == "03") {
                                        $sumaIngresosMarzo = $sumaIngresosMarzo + $unIngresoEver['monto'];
                                      }
                                      if ($monthControl == "04") {
                                        $sumaIngresosAbril = $sumaIngresosAbril + $unIngresoEver['monto'];
                                      }
                                      if ($monthControl == "05") {
                                        $sumaIngresosMayo = $sumaIngresosMayo + $unIngresoEver['monto'];
                                      }
                                      if ($monthControl == "06") {
                                        $sumaIngresosJunio = $sumaIngresosJunio + $unIngresoEver['monto'];
                                      }
                                      if ($monthControl == "07") {
                                        $sumaIngresosJulio = $sumaIngresosJulio + $unIngresoEver['monto'];
                                      }
                                      if ($monthControl == "08") {
                                        $sumaIngresosAgosto = $sumaIngresosAgosto + $unIngresoEver['monto'];
                                      }
                                      if ($monthControl == "09") {
                                        $sumaIngresosSeptiembre = $sumaIngresosSeptiembre + $unIngresoEver['monto'];
                                      }
                                      if ($monthControl == "10") {
                                        $sumaIngresosOctubre = $sumaIngresosOctubre + $unIngresoEver['monto'];
                                      }
                                      if ($monthControl == "11") {
                                        $sumaIngresosNoviembre = $sumaIngresosNoviembre + $unIngresoEver['monto'];
                                      }
                                      if ($monthControl == "12") {
                                        $sumaIngresosDiciembre = $sumaIngresosDiciembre + $unIngresoEver['monto'];
                                      }
                                    }
                                  endforeach;
                                  echo $sumaIngresosEnero.",".$sumaIngresosFebrero.",".$sumaIngresosMarzo.",".$sumaIngresosAbril.",".$sumaIngresosMayo.",".$sumaIngresosJunio.",".$sumaIngresosJulio.",".$sumaIngresosAgosto.",".$sumaIngresosSeptiembre.",".$sumaIngresosOctubre.",".$sumaIngresosNoviembre.",".$sumaIngresosDiciembre ;
                                ?>      
                              ]
      },
      {
        label               : 'Egresos',
        backgroundColor     : 'rgba(205,97,85, 0.9)',
        borderColor         : 'rgba(205,97,85, 0.8)',
        pointRadius         : true,
        pointColor          : 'rgba(205,97,85, 1)',
        pointStrokeColor    : '#c1c7d1',
        pointHighlightFill  : '#fff',
        pointHighlightStroke: 'rgba(205,97,85,1)',
        data                : [
                              <?php
                                  $sumaEgresosEnero = 0;
                                  $sumaEgresosFebrero = 0;
                                  $sumaEgresosMarzo = 0;
                                  $sumaEgresosAbril = 0;
                                  $sumaEgresosMayo = 0;
                                  $sumaEgresosJunio = 0;
                                  $sumaEgresosJulio = 0;
                                  $sumaEgresosAgosto = 0;
                                  $sumaEgresosSeptiembre = 0;
                                  $sumaEgresosOctubre = 0;
                                  $sumaEgresosNoviembre = 0;
                                  $sumaEgresosDiciembre = 0;
                                  $allEgresosEver = find_all('caja_egresos'); 
                                  foreach ($allEgresosEver as $unEgresoEver) :
                                    list($yearControl, $monthControl, $dayControl) = explode('-', $unEgresoEver['fecha']);
                                    if ($yearControl == $yearActual) {
                                      if ($monthControl == "01") {
                                        $sumaEgresosEnero = $sumaEgresosEnero + $unEgresoEver['monto'];
                                      }
                                      if ($monthControl == "02") {
                                        $sumaEgresosFebrero = $sumaEgresosFebrero + $unEgresoEver['monto'];
                                      }
                                      if ($monthControl == "03") {
                                        $sumaEgresosMarzo = $sumaEgresosMarzo + $unEgresoEver['monto'];
                                      }
                                      if ($monthControl == "04") {
                                        $sumaEgresosAbril = $sumaEgresosAbril + $unEgresoEver['monto'];
                                      }
                                      if ($monthControl == "05") {
                                        $sumaEgresosMayo = $sumaEgresosMayo + $unEgresoEver['monto'];
                                      }
                                      if ($monthControl == "06") {
                                        $sumaEgresosJunio = $sumaEgresosJunio + $unEgresoEver['monto'];
                                      }
                                      if ($monthControl == "07") {
                                        $sumaEgresosJulio = $sumaEgresosJulio + $unEgresoEver['monto'];
                                      }
                                      if ($monthControl == "08") {
                                        $sumaEgresosAgosto = $sumaEgresosAgosto + $unEgresoEver['monto'];
                                      }
                                      if ($monthControl == "09") {
                                        $sumaEgresosSeptiembre = $sumaEgresosSeptiembre + $unEgresoEver['monto'];
                                      }
                                      if ($monthControl == "10") {
                                        $sumaEgresosOctubre = $sumaEgresosOctubre + $unEgresoEver['monto'];
                                      }
                                      if ($monthControl == "11") {
                                        $sumaEgresosNoviembre = $sumaEgresosNoviembre + $unEgresoEver['monto'];
                                      }
                                      if ($monthControl == "12") {
                                        $sumaEgresosDiciembre = $sumaEgresosDiciembre + $unEgresoEver['monto'];
                                      }
                                    }
                                  endforeach;
                                  echo $sumaEgresosEnero.",".$sumaEgresosFebrero.",".$sumaEgresosMarzo.",".$sumaEgresosAbril.",".$sumaEgresosMayo.",".$sumaEgresosJunio.",".$sumaEgresosJulio.",".$sumaEgresosAgosto.",".$sumaEgresosSeptiembre.",".$sumaEgresosOctubre.",".$sumaEgresosNoviembre.",".$sumaEgresosDiciembre ;
                                ?>
                              ]
      },
      /*{
        label               : 'Balance',
        backgroundColor     : 'rgba(60,141,188,0.9)',
        borderColor         : 'rgba(60,141,188,0.8)',
        pointRadius          : false,
        pointColor          : '#3b8bba',
        pointStrokeColor    : 'rgba(60,141,188,1)',
        pointHighlightFill  : '#fff',
        pointHighlightStroke: 'rgba(60,141,188,1)',
        data                : [20000,25000,25000,25000,25000,26000]
      },*/
    ]
  }

  var salesChartOptions = {
    maintainAspectRatio : false,
    responsive : true,
    legend: {
      display: true
    },
    scales: {
      xAxes: [{
        gridLines : {
          display : true,
        }
      }],
      yAxes: [{
        gridLines : {
          display : true,
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

  ////////////////////CHART COMBUSTIBLE////////////////////////////////////////////////
  var combustibleChartCanvas = document.getElementById('chart-combustible-canvas').getContext('2d');

  var combustibleChartData = {
    labels  : ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
    datasets: [
      {
        label               : 'Litros de Combustible',
        backgroundColor     : /*'rgba(60,141,188,0.9)'*/'rgba(23,162,184,0.9)',
        borderColor         : 'rgba(23,162,184,0.8)',
        pointRadius         : true,
        pointColor          : '#3b8bba',
        pointStrokeColor    : 'rgba(23,162,184,1)',
        pointHighlightFill  : '#fff',
        pointHighlightStroke: 'rgba(23,162,184,1)',
        data                : [
                                <?php
                                  $sumaCombustibleEnero = 0;
                                  $sumaCombustibleFebrero = 0;
                                  $sumaCombustibleMarzo = 0;
                                  $sumaCombustibleAbril = 0;
                                  $sumaCombustibleMayo = 0;
                                  $sumaCombustibleJunio = 0;
                                  $sumaCombustibleJulio = 0;
                                  $sumaCombustibleAgosto = 0;
                                  $sumaCombustibleSeptiembre = 0;
                                  $sumaCombustibleOctubre = 0;
                                  $sumaCombustibleNoviembre = 0;
                                  $sumaCombustibleDiciembre = 0;
                                  $sumaGLPAEnero = 0;
                                  $sumaGLPAFebrero = 0;
                                  $sumaGLPAMarzo = 0;
                                  $sumaGLPAAbril = 0;
                                  $sumaGLPAMayo = 0;
                                  $sumaGLPAJunio = 0;
                                  $sumaGLPAJulio = 0;
                                  $sumaGLPAAgosto = 0;
                                  $sumaGLPASeptiembre = 0;
                                  $sumaGLPAOctubre = 0;
                                  $sumaGLPANoviembre = 0;
                                  $sumaGLPADiciembre = 0;
                                  $allCombustibleEver = find_all('caja_egresos'); 
                                  foreach ($allCombustibleEver as $unCombustibleEver) :
                                    list($yearControl, $monthControl, $dayControl) = explode('-', $unCombustibleEver['fecha']);
                                    if ($yearControl == $yearActual) {
                                      if ($monthControl == "01") {
                                        if ($unCombustibleEver['concepto_id'] == 1) {
                                          $sumaCombustibleEnero = $sumaCombustibleEnero + $unCombustibleEver['litros_combustible'];
                                        }
                                        if ($unCombustibleEver['concepto_id'] == 14) {
                                          $sumaGLPAEnero = $sumaGLPAEnero + $unCombustibleEver['litros_combustible'];
                                        }                                        
                                      }
                                      if ($monthControl == "02") {
                                        if ($unCombustibleEver['concepto_id'] == 1) {
                                          $sumaCombustibleFebrero = $sumaCombustibleFebrero + $unCombustibleEver['litros_combustible'];
                                        }
                                        if ($unCombustibleEver['concepto_id'] == 14) {
                                          $sumaGLPAFebrero = $sumaGLPAFebrero + $unCombustibleEver['litros_combustible'];
                                        }
                                      }
                                      if ($monthControl == "03") {
                                        if ($unCombustibleEver['concepto_id'] == 1) {
                                          $sumaCombustibleMarzo = $sumaCombustibleMarzo + $unCombustibleEver['litros_combustible'];
                                        }
                                        if ($unCombustibleEver['concepto_id'] == 14) {
                                          $sumaGLPAMarzo = $sumaGLPAMarzo + $unCombustibleEver['litros_combustible'];
                                        }
                                      }
                                      if ($monthControl == "04") {
                                        if ($unCombustibleEver['concepto_id'] == 1) {
                                          $sumaCombustibleAbril = $sumaCombustibleAbril + $unCombustibleEver['litros_combustible'];
                                        }
                                        if ($unCombustibleEver['concepto_id'] == 14) {
                                          $sumaGLPAAbril = $sumaGLPAAbril + $unCombustibleEver['litros_combustible'];
                                        }
                                      }
                                      if ($monthControl == "05") {
                                        if ($unCombustibleEver['concepto_id'] == 1) {
                                          $sumaCombustibleMayo = $sumaCombustibleMayo + $unCombustibleEver['litros_combustible'];
                                        }
                                        if ($unCombustibleEver['concepto_id'] == 14) {
                                          $sumaGLPAMayo = $sumaGLPAMayo + $unCombustibleEver['litros_combustible'];
                                        }
                                      }
                                      if ($monthControl == "06") {
                                        if ($unCombustibleEver['concepto_id'] == 1) {
                                          $sumaCombustibleJunio = $sumaCombustibleJunio + $unCombustibleEver['litros_combustible'];
                                        }
                                        if ($unCombustibleEver['concepto_id'] == 14) {
                                          $sumaGLPAJunio = $sumaGLPAJunio + $unCombustibleEver['litros_combustible'];
                                        }
                                      }
                                      if ($monthControl == "07") {
                                        if ($unCombustibleEver['concepto_id'] == 1) {
                                          $sumaCombustibleJulio = $sumaCombustibleJulio + $unCombustibleEver['litros_combustible'];
                                        }
                                        if ($unCombustibleEver['concepto_id'] == 14) {
                                          $sumaGLPAJulio = $sumaGLPAJulio + $unCombustibleEver['litros_combustible'];
                                        }
                                      }
                                      if ($monthControl == "08") {
                                        if ($unCombustibleEver['concepto_id'] == 1) {
                                          $sumaCombustibleAgosto = $sumaCombustibleAgosto + $unCombustibleEver['litros_combustible'];
                                        }
                                        if ($unCombustibleEver['concepto_id'] == 14) {
                                          $sumaGLPAAgosto = $sumaGLPAAgosto + $unCombustibleEver['litros_combustible'];
                                        }
                                      }
                                      if ($monthControl == "09") {
                                        if ($unCombustibleEver['concepto_id'] == 1) {
                                          $sumaCombustibleSeptiembre = $sumaCombustibleSeptiembre + $unCombustibleEver['litros_combustible'];
                                        }
                                        if ($unCombustibleEver['concepto_id'] == 14) {
                                          $sumaGLPASeptiembre = $sumaGLPASeptiembre + $unCombustibleEver['litros_combustible'];
                                        }
                                      }
                                      if ($monthControl == "10") {
                                        if ($unCombustibleEver['concepto_id'] == 1) {
                                          $sumaCombustibleOctubre = $sumaCombustibleOctubre + $unCombustibleEver['litros_combustible'];
                                        }
                                        if ($unCombustibleEver['concepto_id'] == 14) {
                                          $sumaGLPAOctubre = $sumaGLPAOctubre + $unCombustibleEver['litros_combustible'];
                                        }
                                      }
                                      if ($monthControl == "11") {
                                        if ($unCombustibleEver['concepto_id'] == 1) {
                                          $sumaCombustibleNoviembre = $sumaCombustibleNoviembre + $unCombustibleEver['litros_combustible'];
                                        }
                                        if ($unCombustibleEver['concepto_id'] == 14) {
                                          $sumaGLPANoviembre = $sumaGLPANoviembre + $unCombustibleEver['litros_combustible'];
                                        }
                                      }
                                      if ($monthControl == "12") {
                                        if ($unCombustibleEver['concepto_id'] == 1) {
                                          $sumaCombustibleDiciembre = $sumaCombustibleDiciembre + $unCombustibleEver['litros_combustible'];
                                        }
                                        if ($unCombustibleEver['concepto_id'] == 14) {
                                          $sumaGLPADiciembre = $sumaGLPADiciembre + $unCombustibleEver['litros_combustible'];
                                        }
                                      }
                                    }
                                  endforeach;
                                  echo $sumaCombustibleEnero.",".$sumaCombustibleFebrero.",".$sumaCombustibleMarzo.",".$sumaCombustibleAbril.",".$sumaCombustibleMayo.",".$sumaCombustibleJunio.",".$sumaCombustibleJulio.",".$sumaCombustibleAgosto.",".$sumaCombustibleSeptiembre.",".$sumaCombustibleOctubre.",".$sumaCombustibleNoviembre.",".$sumaCombustibleDiciembre ;
                                ?>      
                              ]
      },
      {
        label               : 'Litros de GLPA',
        backgroundColor     : 'rgba(255,193,7,0.9)',
        borderColor         : 'rgba(255,193,7,0.8)',
        pointRadius         : true,
        pointColor          : '#3b8bba',
        pointStrokeColor    : 'rgba(255,193,7,1)',
        pointHighlightFill  : '#fff',
        pointHighlightStroke: 'rgba(255,193,7,1)',
        data                : [
                              <?php echo $sumaGLPAEnero.",".$sumaGLPAFebrero.",".$sumaGLPAMarzo.",".$sumaGLPAAbril.",".$sumaGLPAMayo.",".$sumaGLPAJunio.",".$sumaGLPAJulio.",".$sumaGLPAAgosto.",".$sumaGLPASeptiembre.",".$sumaGLPAOctubre.",".$sumaGLPANoviembre.",".$sumaGLPADiciembre ;
                                ?>
                              ]
      },
    ]
  }

  var combustibleChartOptions = {
    maintainAspectRatio : false,
    responsive : true,
    legend: {
      display: true
    },
    scales: {
      xAxes: [{
        gridLines : {
          display : true,
        }
      }],
      yAxes: [{
        gridLines : {
          display : true,
        }
      }]
    }
  }

  var combustibleChart = new Chart(combustibleChartCanvas, { 
      type: 'line', 
      data: combustibleChartData, 
      options: combustibleChartOptions
    })
  /////////////////////////END CHART COMBUSTIBLE///////////////////////////////////////
  // Donut Chart
  var donutChartCanvas = $('#sales-chart-canvas').get(0).getContext('2d')
  var donutData        = {
    labels: [
              <?php foreach ($result_tipo_conceptos as $unResult_tipo_conceptos) :
                echo "'".$unResult_tipo_conceptos['descripcion']."',";
              endforeach; ?>
            ],
    datasets: [
      {
        data: [
              <?php 
              foreach ($result_tipo_conceptos as $unResult_tipo_conceptos) :
                $sql_concept = $db->query("SELECT monto FROM caja_egresos WHERE caja_id = '{$idCaja}' AND concepto_id = ".$unResult_tipo_conceptos['id']."");
                $concept = $db->while_loop($sql_concept);
                foreach ($concept as $unConcept) :
                  $montoConcept = $montoConcept + $unConcept['monto'];
                endforeach;
                echo $montoConcept.",";
                $montoConcept = 0;
              endforeach; 
              ?>
              ],
        backgroundColor : ['#CD6155','#AF7AC5', '#5499C7', '#48C9B0','#F4D03F','#52BE80','#EB984E','#EC7063','#A569BD','#5DADE2','#45B39D','#F5B041','#58D68D'],
      }
    ]
  }
  var donutOptions = {
    legend: {
      display: false
    },
    show: true,
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
    //- BAR CHART -
    //-------------
    var barChartCanvas = $('#barChart').get(0).getContext('2d')
    var barChartData = jQuery.extend(true, {}, salesChartData)
    var temp0 = salesChartData.datasets[0]
    var temp1 = salesChartData.datasets[1]
    barChartData.datasets[0] = temp0
    barChartData.datasets[1] = temp1

    var barChartOptions = {
      responsive              : true,
      maintainAspectRatio     : false,
      datasetFill             : false
    }

    var barChart = new Chart(barChartCanvas, {
      type: 'bar', 
      data: barChartData,
      options: barChartOptions
    })

    //---------------------



//-----------------------------------------------------------------------------------------------------
//AGREGAR UN INGRESO
    $('#btn_agregar_ingreso').click(function() {
        //trae datos del data del boton
        var idCaja = $.parseJSON($('#a_modal_ingresos').attr('data-id_caja'));
        var selectOrigen = $('#sel_origen').val();
        var selectTipoIngreso = $('#sel_tipo_ingreso').val();
        var selectPersonal = $('#sel_personal').val();
        var selectCliente = $('#sel_cliente').val();
        var otroIngreso = $('#otro').val();
        var selectTipoMoneda = $('#sel_tipo_moneda').val();
        var monto = $('#inp_monto').val();
        var dateGet = $("#fecha_ing").datepicker({ dateFormat: 'dd,mm,yyyy' }).val();
        
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
        var yyyy = today.getFullYear();
        var hora = today.getHours() + ':' + today.getMinutes() + ':' + today.getSeconds();
        today = dd + '-' + mm + '-' + yyyy;

        if (dateGet != "") {
          date = dateGet;        
        } else {
          date = today;        
        }

        if (selectTipoIngreso == 1) {
          selectCliente = 0;
          otroIngreso = 0;
          var tipoCliente = 0;
        }
        if (selectTipoIngreso == 2) {
          selectPersonal = 0;
          otroIngreso = 0;
          var tipoCliente = 0;
        }
        if (selectTipoIngreso == 3) {
          selectPersonal = 0;
          otroIngreso = 0;
          var tipoCliente = 1;
        }
        if (selectTipoIngreso == 4) {
          selectPersonal = 0;
          otroIngreso = 0;
          var tipoCliente = 2;
        }
        if (selectTipoIngreso == 5) {
          selectCliente = 0;
          selectPersonal = 0;
          var tipoCliente = 0;
        }

          if (idCaja && monto) {
            if (monto >= 0) {
              agregar_ing = "&idIngreso=" + 0 + "&idCaja=" + idCaja + "&idOrigen=" + selectOrigen + "&idPersonal=" + selectPersonal + "&tipoCliente=" + tipoCliente + "&idCliente=" + selectCliente + "&otroIngreso=" + otroIngreso + "&monto=" + monto + "&fecha=" + date + "&hora=" + hora + "&tipoIngreso=" + selectTipoIngreso + "&idMoneda=" + selectTipoMoneda + "&b=" + 1;
                $('#btn_agregar_ingreso').prop('disabled', true);
                $.ajax({
                  type:"POST",
                  url:"cajaMovIngreso.php",
                  data:agregar_ing,
                  success:function(r) {
                    if (r == 1) {
                      alertify.success("Ingreso agregado correctamente.");
                      setInterval('location.reload()', 1000);
                      //$("#modalAgregarRepuesto").modal("hide");                    
                      //location.reload();                    
                    } else {
                      alertify.error("Error.");
                    }
                  }
                });
              } else {
                alertify.error("El monto debe de ser mayor a 0.")
              }
          } else {
            alertify.error("Por favor, complete todos los campos.");
          }
    });

//AGREGAR UN INGRESO
    $('#btn_editar_ingreso').click(function() {
        //trae datos del data del boton
        //var idCaja = $.parseJSON($('#a_modal_ingresos').attr('data-id_caja'));
        var idCaja = $('#inp_edit_ingreso_caja_id').val();
        var idIngreso = $('#inp_edit_ingreso_id').val();
        var selectOrigen = $('#sel_edit_origen_ingre').val();
        var selectTipoIngreso = $('#sel_edit_tipo_ingreso_ingre').val();
        var selectPersonal = $('#sel_edit_personal_ingre').val();
        var selectCliente = $('#sel_edit_cliente_ingre').val();
        var otroIngreso = $('#otro_edit_ingre').val();
        var selectTipoMoneda = $('#sel_edit_tipo_moneda_ingre').val();
        var monto = $('#inp_edit_monto_ingre').val();
        var dateGet = $("#edit_fecha_ing").datepicker({ dateFormat: 'dd,mm,yyyy' }).val();
        
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
        var yyyy = today.getFullYear();
        var hora = today.getHours() + ':' + today.getMinutes() + ':' + today.getSeconds();
        today = dd + '-' + mm + '-' + yyyy;

        if (dateGet != "") {
          date = dateGet;        
        } else {
          date = today;        
        }

        var a = 0;

        if (selectTipoIngreso == 1) {
          if (selectPersonal) {
            a = 1;
          } else {
            a = 0;
            alertify.error("Error, debe seleccionar un personal.");
          }
          selectCliente = 0;
          otroIngreso = 0;
          var tipoCliente = 0;
        }
        if (selectTipoIngreso == 2) {
          a = 1;
          selectPersonal = 0;
          otroIngreso = 0;
          var tipoCliente = 0;
        }
        if (selectTipoIngreso == 3) {
          if (selectCliente) {
            a = 1;
          } else {
            a = 0;
            alertify.error("Error, debe seleccionar un cliente regular.");
          }
          selectPersonal = 0;
          otroIngreso = 0;
          var tipoCliente = 1;
        }
        if (selectTipoIngreso == 4) {
          if (selectCliente) {
            a = 1;
          } else {
            a = 0;
            alertify.error("Error, debe seleccionar un cliente de maquinarias.");
          }
          selectPersonal = 0;
          otroIngreso = 0;
          var tipoCliente = 2;
        }
        if (selectTipoIngreso == 5) {
          selectCliente = 0;
          selectPersonal = 0;
          var tipoCliente = 0;
          a = 1;
        }

        if (a == 1) {
          if (idCaja && monto) {
            if (monto >= 0) {
              editar_ing = "&idIngreso=" + idIngreso + "&idCaja=" + idCaja + "&idOrigen=" + selectOrigen + "&idPersonal=" + selectPersonal + "&tipoCliente=" + tipoCliente + "&idCliente=" + selectCliente + "&otroIngreso=" + otroIngreso + "&monto=" + monto + "&fecha=" + date + "&hora=" + hora + "&tipoIngreso=" + selectTipoIngreso + "&idMoneda=" + selectTipoMoneda + "&b=" + 3;
                $('#btn_editar_ingreso').prop('disabled', true);
                $('#procesando').html('<div class="loader"><br><p style="font-size: 24px;position: absolute;left: 40%;top: 57%;">Procesando, un momento por favor...</p></div>');
                $.ajax({
                  type:"POST",
                  url:"cajaMovIngreso.php",
                  data:editar_ing,
                  success:function(r) {
                    if (r == 1) {
                      alertify.success("Ingreso actualizado correctamente.");
                      setInterval('location.reload()', 1000);
                      //$("#modalAgregarRepuesto").modal("hide");                    
                      //location.reload();                    
                    } else {
                      alertify.error("Error.");
                    }
                  }
                });
              } else {
                alertify.error("El monto debe de ser mayor a 0.")
              }
          } else {
            alertify.error("Por favor, complete todos los campos.");
          }
        } else {
          //No sigue porque no completaron los campos necesarios
        }
    });

//AGREGAR UN EGRESO
$('#btn_agregar_egreso').click(function() {
  var idCaja = $.parseJSON($('#a_modal_egresos').attr('data-id_caja'));
  var selectOrigenEgreso = $('#sel_origen_egre').val();
  var selectPersonalEgreso = $('#sel_personal_egre').val();
  var selectConceptoEgreso = $('#sel_concepto_egre').val();
  var litrosCombustibleEgreso = $('#inp_egre_combustible_lt').val();
  var conceptoEspecificar = $('#concepto_especificar').val();
  var aclaracion = $('#inp_concepto_aclaracion_egre').val();
  var tipoMonedaEgreso = $('#sel_tipo_moneda_egre').val();
  var montoEgreso = $('#inp_monto_egre').val();
  var dateGet = $("#fecha_egre").datepicker({ dateFormat: 'dd,mm,yyyy' }).val();        
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = today.getFullYear();
    var hora = today.getHours() + ':' + today.getMinutes() + ':' + today.getSeconds();
    today = dd + '-' + mm + '-' + yyyy;
    if (dateGet != "") {
      date = dateGet;        
    } else {
      date = today;        
    }
  var tipoProveedor = $('#sel_tipo_proveedor').val();
  var proveedor = $('#sel_proveedor').val();
  var proveedorEspecificar = $('#proveedor_especificar').val();
  var numFactura = $('#inp_num_factura').val();
  var numRemito = $('#inp_num_remito').val();
  var o = 0;
  var p = 0;

  if (idCaja && montoEgreso && selectOrigenEgreso && selectConceptoEgreso) {
    if (tipoProveedor == 1 && proveedor == null) {
      o = 1;
      alertify.error("Por favor, elija un proveedor.");
    }
    if (tipoProveedor == 2 && proveedor == null) {
      o = 1;
      alertify.error("Por favor, elija un proveedor.");
    } 
    if (tipoProveedor == 3 && proveedorEspecificar == "") {
      o = 1;
      alertify.error("Por favor, especifique el proveedor.");
    } 
///////////////////////////////////////////////////////////////////////////////  
    if (selectConceptoEgreso == 1 && litrosCombustibleEgreso == 0) {
      p = 1;
      alertify.error("Por favor, complete los litros de combustible.");
    }
    if (selectConceptoEgreso == 14 && litrosCombustibleEgreso == 0) {
      p = 1;
      alertify.error("Por favor, complete los litros de GLP.");
    }
    if (selectConceptoEgreso == 12 && conceptoEspecificar == 0) {
      p = 1;
      alertify.error("Por favor, especifique el concepto.");
    }
    if (selectConceptoEgreso != 1 && selectConceptoEgreso != 12 && selectConceptoEgreso != 13 && selectConceptoEgreso != 14 && aclaracion == 0) {
      p = 1;
      alertify.error("Por favor, aclare el concepto.");
    }
///////////////////////////////////////////////////////////////////////////////
    if ((selectConceptoEgreso == 13 && selectPersonalEgreso == null) || (selectConceptoEgreso == 11 && selectPersonalEgreso == null)){
      p = 1;
      alertify.error("Por favor, seleccione un personal.");
    }
///////////////////////////////////////////////////////////////////////////////      
    if (o != 1 && p != 1) {
      if (montoEgreso > 0) {
        agregar_egre = "&idEgreso=" + 0 + "&idCaja=" + idCaja + "&idOrigen=" + selectOrigenEgreso + "&idPersonal=" + selectPersonalEgreso + "&fecha=" + date + "&hora=" + hora + "&montoEgreso=" + montoEgreso + "&idMoneda=" + tipoMonedaEgreso + "&numFactura=" + numFactura + "&numRemito=" + numRemito + "&tipoProveedor=" + tipoProveedor + "&proveedor=" + proveedor + "&proveedorEspecificar=" + proveedorEspecificar + "&idConcepto=" + selectConceptoEgreso + "&conceptoEspecificar=" + conceptoEspecificar + "&litrosCombustible=" + litrosCombustibleEgreso + "&aclaracion=" + aclaracion + "&b=" + 1;
        $('#btn_agregar_egreso').prop('disabled', true);
        $('#procesando').html('<div class="loader"><br><p style="font-size: 24px;position: absolute;left: 40%;top: 57%;">Procesando, un momento por favor...</p></div>');
        $.ajax({
          type:"POST",
          url:"cajaMovEgreso.php",
          data:agregar_egre,
          success:function(r) {
            if (r == 1) {
              alertify.success("Egreso agregado correctamente.");
              setInterval('location.reload()', 1000);                  
            } else {
              alertify.error("Error.");
            }
          }
        });
      } else {
        alertify.error("El monto debe de ser mayor a 0.")
      }
    } 
  } else {
    alertify.error("Por favor, complete todos los campos.");
  }
});
////////////////FIN AGREGAR EGRESO/////////////////////////////////////////////////// 
///////////////INCIO EDITAR EGRESO///////////////////////////////////////////////////
$('#btn_editar_egreso').click(function() {
  var idEgreso = document.getElementById("inp_edit_egreso_id").value;
  var idCaja = document.getElementById("inp_edit_egreso_caja_id").value;
  var selectOrigenEgreso = $('#sel_edit_origen_egre').val();
  var selectPersonalEgreso = $('#sel_edit_personal_egre').val();
  var selectConceptoEgreso = $('#sel_edit_concepto_egre').val();
  var litrosCombustibleEgreso = $('#inp_edit_egre_combustible_lt').val();
  var conceptoEspecificar = $('#edit_concepto_especificar').val();
  var aclaracion = $('#inp_edit_concepto_aclaracion_egre').val();
  var tipoMonedaEgreso = $('#sel_edit_tipo_moneda_egre').val();
  var montoEgreso = $('#inp_edit_monto_egre').val();
  var dateGet = $("#fecha_edit_egre").datepicker({ dateFormat: 'dd,mm,yyyy' }).val();        
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = today.getFullYear();
    var hora = today.getHours() + ':' + today.getMinutes() + ':' + today.getSeconds();
    today = dd + '-' + mm + '-' + yyyy;
    if (dateGet != "") {
      date = dateGet;        
    } else {
      //date = today;        
    }   
  var tipoProveedor = $('#sel_edit_tipo_proveedor').val();
  var proveedor = $('#sel_edit_proveedor').val();
  var proveedorEspecificar = $('#edit_proveedor_especificar').val();
  var numFactura = $('#inp_edit_num_factura').val();
  var numRemito = $('#inp_edit_num_remito').val();
  var o = 0;
  var p = 0;

  if (idCaja && montoEgreso && selectOrigenEgreso && selectConceptoEgreso) {
    if (tipoProveedor == 1 && proveedor == null) {
      o = 1;
      alertify.error("Por favor, elija un proveedor.");
    }
    if (tipoProveedor == 2 && proveedor == null) {
      o = 1;
      alertify.error("Por favor, elija un proveedor.");
    } 
    if (tipoProveedor == 3 && proveedorEspecificar == "") {
      o = 1;
      alertify.error("Por favor, especifique el proveedor.");
    } 
///////////////////////////////////////////////////////////////////////////////  
    if (selectConceptoEgreso == 1 && litrosCombustibleEgreso == 0) {
      p = 1;
      alertify.error("Por favor, complete los litros de combustible.");
    }
    if (selectConceptoEgreso == 14 && litrosCombustibleEgreso == 0) {
      p = 1;
      alertify.error("Por favor, complete los litros de GLP.");
    }
    if (selectConceptoEgreso == 12 && conceptoEspecificar == 0) {
      p = 1;
      alertify.error("Por favor, especifique el concepto.");
    }
    if (selectConceptoEgreso != 1 && selectConceptoEgreso != 12 && selectConceptoEgreso != 13 && selectConceptoEgreso != 14 && aclaracion == 0) {
      p = 1;
      alertify.error("Por favor, aclare el concepto.");
    }
///////////////////////////////////////////////////////////////////////////////
    if ((selectConceptoEgreso == 13 && selectPersonalEgreso == null) || (selectConceptoEgreso == 11 && selectPersonalEgreso == null)){
      p = 1;
      alertify.error("Por favor, seleccione un personal.");
    }
///////////////////////////////////////////////////////////////////////////////      
    if (o != 1 && p != 1) {
      if (montoEgreso > 0) {
        editar_egre = "&idEgreso=" + idEgreso + "&idCaja=" + idCaja + "&idOrigen=" + selectOrigenEgreso + "&idPersonal=" + selectPersonalEgreso + "&fecha=" + date + "&hora=" + hora + "&montoEgreso=" + montoEgreso + "&idMoneda=" + tipoMonedaEgreso + "&numFactura=" + numFactura + "&numRemito=" + numRemito + "&tipoProveedor=" + tipoProveedor + "&proveedor=" + proveedor + "&proveedorEspecificar=" + proveedorEspecificar + "&idConcepto=" + selectConceptoEgreso + "&conceptoEspecificar=" + conceptoEspecificar + "&litrosCombustible=" + litrosCombustibleEgreso + "&aclaracion=" + aclaracion + "&b=" + 3;
        $('#btn_agregar_egreso').prop('disabled', true);
        //$('#procesando').html('<div class="loader"></div>');
        //$(".loader").fadeIn(1500);
        $('#procesando').html('<div class="loader"><br><p style="font-size: 24px;position: absolute;left: 40%;top: 57%;">Procesando, un momento por favor...</p></div>');
        $.ajax({
          type:"POST",
          url:"cajaMovEgreso.php",
          data:editar_egre,
          success:function(r) {
            //$('#procesando').fadeIn(1000).html(r);
            if (r == 1) {
              //$('#procesando').html('<div class="loader"><br><p style="font-size: 24px;position: absolute;left: 40%;top: 57%;">Procesando, un momento por favor...</p></div>');
              alertify.success("Egreso actualizado correctamente.");
              setInterval('location.reload()', 1000);                  
            } else {
              alertify.error("Error.");
            }
          }
        });
      } else {
        alertify.error("El monto debe de ser mayor a 0.")
      }
    } 
  } else {
    alertify.error("Por favor, complete todos los campos.");
  }
});
///////////////FIN EDITAR EGRESO/////////////////////////////////////////////////////
});
/////////////////////////////////////////////////////////////////////////////////////
function tipoIngresoSeleccionado() {                  
  var tipo = document.getElementById("sel_tipo_ingreso").value;
  var perso = document.getElementById("sel_personal").value;
  if (tipo == 1) {
    document.getElementById("sel_tipo_moneda").disabled = true;
    document.getElementById("sel_cliente").disabled = true;
    document.getElementById("sel_personal").disabled = false;
    document.getElementById("otro").disabled = true;
    document.getElementById("otro").value = "";
    $('#sel_cliente').find('option').remove().end().append('<option value="" disabled selected>Seleccione un cliente:</option>');
    $('#otro').find('input').remove().end();
    var selPersonal = document.getElementById("sel_personal");
    <?php foreach ($allPersonal as $unPersonal) : ?>
      var optPersonal = document.createElement('option');
      var textPersonal = "<?php echo $unPersonal['apellido'].", ".$unPersonal['nombre'];?>";
      optPersonal.appendChild(document.createTextNode(textPersonal));
      optPersonal.value = parseInt("<?php echo $unPersonal['id']; ?>");
      selPersonal.appendChild(optPersonal);
    <?php endforeach; ?>  
  }
  if (tipo == 2) {
    document.getElementById("sel_tipo_moneda").disabled = false;
    document.getElementById("sel_cliente").disabled = true;
    document.getElementById("sel_personal").disabled = true;
    document.getElementById("otro").disabled = true;
    document.getElementById("otro").value = "";
    $('#sel_cliente').find('option').remove().end().append('<option value="" disabled selected>Seleccione un cliente:</option>');
    $('#sel_personal').find('option').remove().end().append('<option value="" disabled selected>Seleccione un personal:</option>');
    $('#otro').find('input').remove().end(); 
  }
  if (tipo == 3) {
    document.getElementById("sel_tipo_moneda").disabled = true;
    document.getElementById("sel_personal").disabled = true;
    document.getElementById("sel_cliente").disabled = false;
    document.getElementById("otro").disabled = true;
    document.getElementById("otro").value = "";
    $('#sel_personal').find('option').remove().end().append('<option value="" disabled selected>Seleccione un personal:</option>');
    $('#sel_cliente').find('option').remove().end().append('<option value="" disabled selected>Seleccione un cliente:</option>');
    $('#otro').find('value').remove().end();
    var selCliente = document.getElementById("sel_cliente");
    <?php foreach ($allCLientes as $unCliente) : 
      $sql_prov = $db->query("SELECT * FROM provincia WHERE id_provincia = '".$unCliente['provincia']."' LIMIT 1");
      $prov = $db->fetch_assoc($sql_prov);
    ?>
      var optCliente = document.createElement('option');
      var textCliente = "<?php echo $unCliente['razon_social']." - Suc.: ".$unCliente['num_suc']." (".$unCliente['localidad']." - ".$prov['nombre'].") Dir.: ".$unCliente['direccion']; ?>";
      optCliente.appendChild(document.createTextNode(textCliente));
      optCliente.value = parseInt("<?php echo $unCliente['id']; ?>");
      selCliente.appendChild(optCliente);
    <?php endforeach; ?>
  }
  if (tipo == 4) {
    document.getElementById("sel_tipo_moneda").disabled = true;
    document.getElementById("sel_personal").disabled = true;
    document.getElementById("sel_cliente").disabled = false;
    document.getElementById("otro").disabled = true;
    $('#sel_personal').find('option').remove().end().append('<option value="" disabled selected>Seleccione un personal:</option>');
    $('#sel_cliente').find('option').remove().end().append('<option value="" disabled selected>Seleccione un cliente:</option>');
    document.getElementById("otro").value = "";
    var selClienteMaquina = document.getElementById("sel_cliente");
    <?php foreach ($allClienteMaquina as $unClienteMaquina) : 
      $sql_prov = $db->query("SELECT * FROM provincia WHERE id_provincia = '".$unClienteMaquina['provincia']."' LIMIT 1");
      $prov = $db->fetch_assoc($sql_prov);
    ?>
      var optClienteMaquina = document.createElement('option');
      var textClienteMaquina = "<?php echo $unClienteMaquina['razon_social']." (".$unClienteMaquina['localidad']." - ".$prov['nombre'].") Dir.: ".$unClienteMaquina['direccion']; ?>";
      optClienteMaquina.appendChild(document.createTextNode(textClienteMaquina));
      optClienteMaquina.value = parseInt("<?php echo $unClienteMaquina['id']; ?>");
      selClienteMaquina.appendChild(optClienteMaquina);
    <?php endforeach; ?>
  }
  if (tipo == 5) {
    document.getElementById("sel_personal").disabled = true;
    document.getElementById("sel_cliente").disabled = true;
    document.getElementById("otro").disabled = false;
    document.getElementById("sel_tipo_moneda").disabled = false;
    $('#sel_personal').find('option').remove().end().append('<option value="" disabled selected>Seleccione un personal:</option>');
    $('#sel_cliente').find('option').remove().end().append('<option value="" disabled selected>Seleccione un cliente:</option>');
  }
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function tipoIngresoSeleccionadoEdit() {                  
  var tipo = document.getElementById("sel_edit_tipo_ingreso_ingre").value;
  var perso = document.getElementById("sel_edit_personal_ingre").value;
  if (tipo == 1) {
    document.getElementById("sel_edit_tipo_moneda_ingre").disabled = true;
    document.getElementById("sel_edit_cliente_ingre").disabled = true;
    document.getElementById("sel_edit_personal_ingre").disabled = false;
    document.getElementById("otro_edit_ingre").disabled = true;
    document.getElementById("otro_edit_ingre").value = "";
    $('#sel_edit_cliente_ingre').find('option').remove().end().append('<option value="" disabled selected>Seleccione un cliente:</option>');
    $('#otro_edit_ingre').find('input').remove().end();
    var selPersonal = document.getElementById("sel_edit_personal_ingre");
    <?php foreach ($allPersonal as $unPersonal) : ?>
      var optPersonal = document.createElement('option');
      var textPersonal = "<?php echo $unPersonal['apellido'].", ".$unPersonal['nombre'];?>";
      optPersonal.appendChild(document.createTextNode(textPersonal));
      optPersonal.value = parseInt("<?php echo $unPersonal['id']; ?>");
      selPersonal.appendChild(optPersonal);
    <?php endforeach; ?>  
  }
  if (tipo == 2) {
    document.getElementById("sel_edit_tipo_moneda_ingre").disabled = false;
    document.getElementById("sel_edit_cliente_ingre").disabled = true;
    document.getElementById("sel_edit_personal_ingre").disabled = true;
    document.getElementById("otro_edit_ingre").disabled = true;
    document.getElementById("otro_edit_ingre").value = "";
    $('#sel_edit_cliente_ingre').find('option').remove().end().append('<option value="" disabled selected>Seleccione un cliente:</option>');
    $('#sel_edit_personal_ingre').find('option').remove().end().append('<option value="" disabled selected>Seleccione un personal:</option>');
    $('#otro').find('input').remove().end(); 
  }
  if (tipo == 3) {
    document.getElementById("sel_edit_tipo_moneda_ingre").disabled = true;
    document.getElementById("sel_edit_personal_ingre").disabled = true;
    document.getElementById("sel_edit_cliente_ingre").disabled = false;
    document.getElementById("otro_edit_ingre").disabled = true;
    document.getElementById("otro_edit_ingre").value = "";
    $('#sel_edit_personal_ingre').find('option').remove().end().append('<option value="" disabled selected>Seleccione un personal:</option>');
    $('#sel_edit_cliente_ingre').find('option').remove().end().append('<option value="" disabled selected>Seleccione un cliente:</option>');
    $('#otro').find('value').remove().end();
    var selCliente = document.getElementById("sel_edit_cliente_ingre");
    <?php foreach ($allCLientes as $unCliente) : 
      $sql_prov = $db->query("SELECT * FROM provincia WHERE id_provincia = '".$unCliente['provincia']."' LIMIT 1");
      $prov = $db->fetch_assoc($sql_prov);
    ?>
      var optCliente = document.createElement('option');
      var textCliente = "<?php echo $unCliente['razon_social']." - Suc.: ".$unCliente['num_suc']." (".$unCliente['localidad']." - ".$prov['nombre'].") Dir.: ".$unCliente['direccion']; ?>";
      optCliente.appendChild(document.createTextNode(textCliente));
      optCliente.value = parseInt("<?php echo $unCliente['id']; ?>");
      selCliente.appendChild(optCliente);
    <?php endforeach; ?>
  }
  if (tipo == 4) {
    document.getElementById("sel_edit_tipo_moneda_ingre").disabled = true;
    document.getElementById("sel_edit_personal_ingre").disabled = true;
    document.getElementById("sel_edit_cliente_ingre").disabled = false;
    document.getElementById("otro_edit_ingre").disabled = true;
    $('#sel_edit_personal_ingre').find('option').remove().end().append('<option value="" disabled selected>Seleccione un personal:</option>');
    $('#sel_edit_cliente_ingre').find('option').remove().end().append('<option value="" disabled selected>Seleccione un cliente:</option>');
    document.getElementById("otro_edit_ingre").value = "";
    var selClienteMaquina = document.getElementById("sel_edit_cliente_ingre");
    <?php foreach ($allClienteMaquina as $unClienteMaquina) : 
      $sql_prov = $db->query("SELECT * FROM provincia WHERE id_provincia = '".$unClienteMaquina['provincia']."' LIMIT 1");
      $prov = $db->fetch_assoc($sql_prov);
    ?>
      var optClienteMaquina = document.createElement('option');
      var textClienteMaquina = "<?php echo $unClienteMaquina['razon_social']." (".$unClienteMaquina['localidad']." - ".$prov['nombre'].") Dir.: ".$unClienteMaquina['direccion']; ?>";
      optClienteMaquina.appendChild(document.createTextNode(textClienteMaquina));
      optClienteMaquina.value = parseInt("<?php echo $unClienteMaquina['id']; ?>");
      selClienteMaquina.appendChild(optClienteMaquina);
    <?php endforeach; ?>
  }
  if (tipo == 5) {
    document.getElementById("sel_edit_personal_ingre").disabled = true;
    document.getElementById("sel_edit_cliente_ingre").disabled = true;
    document.getElementById("otro_edit_ingre").disabled = false;
    document.getElementById("sel_edit_tipo_moneda_ingre").disabled = false;
    $('#sel_edit_personal_ingre').find('option').remove().end().append('<option value="" disabled selected>Seleccione un personal:</option>');
    $('#sel_edit_cliente_ingre').find('option').remove().end().append('<option value="" disabled selected>Seleccione un cliente:</option>');
  }
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//------------------------------ FUNCTION BORRAR ---------------------------
//BORRAR INGRESO
function borrarIngreso(id) {
  var today = new Date();
  var dd = String(today.getDate()).padStart(2, '0');
  var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
  var yyyy = today.getFullYear();
  var hora = today.getHours() + ':' + today.getMinutes() + ':' + today.getSeconds();
  today = dd + '-' + mm + '-' + yyyy;
  var result = confirm("¿Está seguro que desea eliminar éste ingreso?");
  if (result) {
    if (id) {
      eliminar_ing = "&idCaja=" + id + "&idOrigen=" + 0 + "&idPersonal=" + 0 + "&tipoCliente=" + 0 + "&idCliente=" + 0 + "&otroIngreso=" + 0 + "&monto=" + 0 + "&fecha=" + today + "&hora=" + hora + "&tipoIngreso=" + 0 + "&idMoneda=" + 0 + "&b=" + 2;
      $('#procesando').html('<div class="loader"><br><p style="font-size: 24px;position: absolute;left: 40%;top: 57%;">Procesando, un momento por favor...</p></div>');
      $.ajax({
        type:"POST",
        url:"cajaMovIngreso.php",
        data:eliminar_ing,
        success:function(r) {
          if (r == 1) {
            alertify.success("Ingreso eliminado correctamente.");
            setInterval('location.reload()', 1000);
            //$("#modalAgregarRepuesto").modal("hide");                    
            //location.reload();                    
          } else {
            alertify.error("Error.");
          }
        }
      });
    }
  }
}
//BORRAR EGRESO
function borrarEgreso(id) {
  var today = new Date();
  var dd = String(today.getDate()).padStart(2, '0');
  var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
  var yyyy = today.getFullYear();
  var hora = today.getHours() + ':' + today.getMinutes() + ':' + today.getSeconds();
  today = dd + '-' + mm + '-' + yyyy;
  var result = confirm("¿Está seguro que desea eliminar éste egreso?");
  if (result) {
    if (id) {
      eliminar_egre = "&idEgreso=" + id + "&idCaja=" + 0 + "&idOrigen=" + 0 + "&idPersonal=" + 0 + "&fecha=" + today + "&hora=" + hora + "&montoEgreso=" + 0 + "&idMoneda=" + 0 + "&numFactura=" + 0 + "&numRemito=" + 0 + "&tipoProveedor=" + 0 + "&proveedor=" + 0 + "&proveedorEspecificar=" + 0 + "&idConcepto=" + 0 + "&conceptoEspecificar=" + 0 + "&litrosCombustible=" + 0 + "&aclaracion=" + 0 + "&b=" + 2;
      $('#procesando').html('<div class="loader"><br><p style="font-size: 24px;position: absolute;left: 40%;top: 57%;">Procesando, un momento por favor...</p></div>');
      $.ajax({
        type:"POST",
        url:"cajaMovEgreso.php",
        data:eliminar_egre,
        success:function(r) {
          if (r == 1) {
            alertify.success("Egreso eliminado correctamente.");
            setInterval('location.reload()', 1000);
            //$("#modalAgregarRepuesto").modal("hide");                    
            //location.reload();                    
          } else {
            alertify.error("Error.");
          }
        }
      });
    }
  }
}
//------------------------------ORIGEN DE EGRESO---------------------------
function origenEgreSelec() {
  document.getElementById("sel_tipo_proveedor").value = "";
  document.getElementById("sel_tipo_proveedor").disabled = false;
  document.getElementById("inp_num_factura").disabled = false;
  document.getElementById("inp_num_remito").disabled = false;
  document.getElementById("sel_proveedor").value = "";
  document.getElementById("sel_proveedor").disabled = true;
  document.getElementById("proveedor_especificar").value = "";
  document.getElementById("proveedor_especificar").hidden = true;
  document.getElementById("lbl_proveedor_especificar").hidden = true;
  document.getElementById("sel_proveedor").hidden = false;
  document.getElementById("lbl_sel_proveedor").hidden = false;
  document.getElementById("sel_tipo_moneda_egre").disabled = false;  
}

//------------------------------PERSONAL DE EGRESO---------------------------
function personalEgreSelec() {
  document.getElementById("inp_concepto_aclaracion_egre").value = "";
  document.getElementById("inp_concepto_aclaracion_egre").disabled = true;
  document.getElementById("inp_concepto_aclaracion_egre").hidden = true;
  document.getElementById("lbl_concepto_aclaracion_egre").disabled = true;
  document.getElementById("lbl_concepto_aclaracion_egre").hidden = true;  

  document.getElementById("inp_egre_combustible_lt").value = "";
  document.getElementById("inp_egre_combustible_lt").disabled = true;
  document.getElementById("inp_egre_combustible_lt").hidden = true;
  document.getElementById("lbl_egre_combustible_lt").disabled = true;
  document.getElementById("lbl_egre_combustible_lt").hidden = true;
  
  document.getElementById("concepto_especificar").value = "";
  document.getElementById("concepto_especificar").disabled = true;

  document.getElementById("sel_concepto_egre").disabled = false;
    $('#sel_concepto_egre').find('option').remove().end().append('<option value="" disabled selected>Seleccione un concepto:</option>');
    $('#concepto_especificar').find('input').remove().end();
    var selConceptoEgre = document.getElementById("sel_concepto_egre");
    <?php foreach ($allConceptos as $unConcepto) : 
      if ($unConcepto['id'] == 12) { ?>
        var optConceptoEgre = document.createElement('option');
        var textConceptoEgre = "<?php echo "---".$unConcepto['descripcion']."---";?>";
        optConceptoEgre.appendChild(document.createTextNode(textConceptoEgre));
        optConceptoEgre.value = parseInt("<?php echo $unConcepto['id']; ?>");
        selConceptoEgre.appendChild(optConceptoEgre);
    <?php  } else { ?>
        var optConceptoEgre = document.createElement('option');
        var textConceptoEgre = "<?php echo $unConcepto['descripcion'];?>";
        optConceptoEgre.appendChild(document.createTextNode(textConceptoEgre));
        optConceptoEgre.value = parseInt("<?php echo $unConcepto['id']; ?>");
        selConceptoEgre.appendChild(optConceptoEgre);
    <?php  } ?>
    <?php endforeach; ?>  
}

//------------------------------CONCEPTO DE EGRESO---------------------------
function conceptoEgreSelec() {
  document.getElementById("sel_tipo_moneda_egre").disabled = false;
  var valueConcepto = document.getElementById("sel_concepto_egre").value
  if (valueConcepto != 1 && valueConcepto != 12 && valueConcepto != 14) {
    document.getElementById("inp_concepto_aclaracion_egre").disabled = false;
    document.getElementById("lbl_concepto_aclaracion_egre").disabled = false;
    document.getElementById("inp_concepto_aclaracion_egre").hidden = false;
    document.getElementById("lbl_concepto_aclaracion_egre").hidden = false;    
  } else {
    document.getElementById("inp_concepto_aclaracion_egre").value = "";
    document.getElementById("inp_concepto_aclaracion_egre").disabled = true;
    document.getElementById("lbl_concepto_aclaracion_egre").disabled = true;
    document.getElementById("inp_concepto_aclaracion_egre").hidden = true;
    document.getElementById("lbl_concepto_aclaracion_egre").hidden = true;    
  }

  if (valueConcepto == 12) {
    document.getElementById("concepto_especificar").disabled = false;
  } else {
    document.getElementById("concepto_especificar").value = "";
    document.getElementById("concepto_especificar").disabled = true;
  }
  if(valueConcepto == 1 || valueConcepto == 14) {
    document.getElementById("lbl_egre_combustible_lt").disabled = false;
    document.getElementById("inp_egre_combustible_lt").disabled = false;
    document.getElementById("lbl_egre_combustible_lt").hidden = false;
    document.getElementById("inp_egre_combustible_lt").hidden = false;    
  } else {
    document.getElementById("inp_egre_combustible_lt").value = "";
    document.getElementById("lbl_egre_combustible_lt").disabled = true;
    document.getElementById("inp_egre_combustible_lt").disabled = true;
    document.getElementById("lbl_egre_combustible_lt").hidden = true;
    document.getElementById("inp_egre_combustible_lt").hidden = true;
  }
}
//----------------------------- CONCEPTO EDIT EGRESO ------------------------
function conceptoEditEgreSelec() {
  var valueConcepto = document.getElementById("sel_edit_concepto_egre").value
  if (valueConcepto != 1 && valueConcepto != 12 && valueConcepto != 14) {
    document.getElementById("inp_edit_concepto_aclaracion_egre").disabled = false;
    document.getElementById("lbl_edit_concepto_aclaracion_egre").disabled = false;
    document.getElementById("inp_edit_concepto_aclaracion_egre").hidden = false;
    document.getElementById("lbl_edit_concepto_aclaracion_egre").hidden = false;    
  } else {
    document.getElementById("inp_edit_concepto_aclaracion_egre").value = "";
    document.getElementById("inp_edit_concepto_aclaracion_egre").disabled = true;
    document.getElementById("lbl_edit_concepto_aclaracion_egre").disabled = true;
    document.getElementById("inp_edit_concepto_aclaracion_egre").hidden = true;
    document.getElementById("lbl_edit_concepto_aclaracion_egre").hidden = true;    
  }

  if (valueConcepto == 12) {
    document.getElementById("edit_concepto_especificar").disabled = false;
  } else {
    document.getElementById("edit_concepto_especificar").value = "";
    document.getElementById("edit_concepto_especificar").disabled = true;
  }
  if(valueConcepto == 1 || valueConcepto == 14) {
    document.getElementById("lbl_edit_egre_combustible_lt").disabled = false;
    document.getElementById("inp_edit_egre_combustible_lt").disabled = false;
    document.getElementById("lbl_edit_egre_combustible_lt").hidden = false;
    document.getElementById("inp_edit_egre_combustible_lt").hidden = false;    
  } else {
    document.getElementById("inp_edit_egre_combustible_lt").value = "";
    document.getElementById("lbl_edit_egre_combustible_lt").disabled = true;
    document.getElementById("inp_edit_egre_combustible_lt").disabled = true;
    document.getElementById("lbl_edit_egre_combustible_lt").hidden = true;
    document.getElementById("inp_edit_egre_combustible_lt").hidden = true;
  }
}

//------------------------------ TIPO PROVEEDOR SELECCIONADO-----------------
function tipoProveeSelec(){
  var tipoProv = document.getElementById("sel_tipo_proveedor").value;
  if (tipoProv == 1) {
    document.getElementById("sel_proveedor").value = "";
    document.getElementById("sel_proveedor").hidden = false;
    document.getElementById("lbl_sel_proveedor").hidden = false;
    document.getElementById("proveedor_especificar").value = "";
    document.getElementById("proveedor_especificar").hidden = true;
    document.getElementById("lbl_proveedor_especificar").hidden = true;
    document.getElementById("proveedor_especificar").disabled = false;

    document.getElementById("sel_proveedor").disabled = false;
    $('#sel_proveedor').find('option').remove().end().append('<option value="" disabled selected>Seleccione un proveedor:</option>');
    $('#proveedor_especificar').find('input').remove().end();
    var selProveedor = document.getElementById("sel_proveedor");
    <?php foreach ($allProveedores as $unProveedor) : 
      $sql_prov = $db->query("SELECT * FROM provincia WHERE id_provincia = '".$unProveedor['provincia']."' LIMIT 1");
      $prov = $db->fetch_assoc($sql_prov);
    ?>
      var optProveedor = document.createElement('option');
      var textProveedor = "<?php echo $unProveedor['razon_social'].", ( ".$unProveedor['localidad']." - ".$prov['nombre']." )";?>";
      optProveedor.appendChild(document.createTextNode(textProveedor));
      optProveedor.value = parseInt("<?php echo $unProveedor['id']; ?>");
      selProveedor.appendChild(optProveedor);
    <?php endforeach; ?>  
  }
  if (tipoProv == 2) {
    document.getElementById("sel_proveedor").value = "";
    document.getElementById("sel_proveedor").hidden = false;
    document.getElementById("lbl_sel_proveedor").hidden = false;
    document.getElementById("proveedor_especificar").value = "";
    document.getElementById("proveedor_especificar").hidden = true;
    document.getElementById("lbl_proveedor_especificar").hidden = true;
    document.getElementById("proveedor_especificar").disabled = false;

    document.getElementById("sel_proveedor").disabled = false;
    $('#sel_proveedor').find('option').remove().end().append('<option value="" disabled selected>Seleccione un proveedor:</option>');
    $('#proveedor_especificar').find('input').remove().end();
    var selProveedor = document.getElementById("sel_proveedor");
    <?php foreach ($allProveedoresMaquinas as $unProveedorMaquina) : 
      $sql_prov = $db->query("SELECT * FROM provincia WHERE id_provincia = '".$unProveedorMaquina['provincia']."' LIMIT 1");
      $prov = $db->fetch_assoc($sql_prov);
      if ($unProveedorMaquina['id'] == 1 || $unProveedorMaquina['id'] == 2){
      } else {
    ?>
      var optProveedor = document.createElement('option');
      var textProveedor = "<?php echo $unProveedorMaquina['razon_social'].", ( ".$unProveedorMaquina['localidad']." - ".$prov['nombre']." )";?>";
      optProveedor.appendChild(document.createTextNode(textProveedor));
      optProveedor.value = parseInt("<?php echo $unProveedorMaquina['id']; ?>");
      selProveedor.appendChild(optProveedor);
    <?php } endforeach; ?>  
  }
  if (tipoProv == 3) {
    document.getElementById("sel_proveedor").value = "";
    document.getElementById("sel_proveedor").hidden = true;
    document.getElementById("lbl_sel_proveedor").hidden = true;
    document.getElementById("proveedor_especificar").value = "";
    document.getElementById("proveedor_especificar").hidden = false;
    document.getElementById("proveedor_especificar").disabled = false;
    document.getElementById("lbl_proveedor_especificar").hidden = false;
  }
}
//---------------------------------------------------------------------------
function tipoProveeSelecEgre(){
  var tipoProv = document.getElementById("sel_edit_tipo_proveedor").value;

  if (tipoProv == "") {
    document.getElementById("sel_edit_proveedor").value = "";
    document.getElementById("sel_edit_proveedor").hidden = false;
    document.getElementById("sel_edit_proveedor").disabled = true;
    document.getElementById("lbl_edit_sel_proveedor").hidden = false;
    document.getElementById("edit_proveedor_especificar").value = "";
    document.getElementById("edit_proveedor_especificar").hidden = true;
    document.getElementById("lbl_edit_proveedor_especificar").hidden = true;
    document.getElementById("edit_proveedor_especificar").disabled = false;
  }

  if (tipoProv == 1) {
    document.getElementById("sel_edit_proveedor").value = "";
    document.getElementById("sel_edit_proveedor").hidden = false;
    document.getElementById("lbl_edit_sel_proveedor").hidden = false;
    document.getElementById("edit_proveedor_especificar").value = "";
    document.getElementById("edit_proveedor_especificar").hidden = true;
    document.getElementById("lbl_edit_proveedor_especificar").hidden = true;
    document.getElementById("edit_proveedor_especificar").disabled = false;

    document.getElementById("sel_edit_proveedor").disabled = false;
    $('#sel_edit_proveedor').find('option').remove().end().append('<option value="" disabled selected>Seleccione un proveedor:</option>');
    $('#edit_proveedor_especificar').find('input').remove().end();
    var selProveedor = document.getElementById("sel_edit_proveedor");
    <?php foreach ($allProveedores as $unProveedor) : 
      $sql_prov = $db->query("SELECT * FROM provincia WHERE id_provincia = '".$unProveedor['provincia']."' LIMIT 1");
      $prov = $db->fetch_assoc($sql_prov);
    ?>
      var optProveedor = document.createElement('option');
      var textProveedor = "<?php echo $unProveedor['razon_social'].", ( ".$unProveedor['localidad']." - ".$prov['nombre']." )";?>";
      optProveedor.appendChild(document.createTextNode(textProveedor));
      optProveedor.value = parseInt("<?php echo $unProveedor['id']; ?>");
      selProveedor.appendChild(optProveedor);
    <?php endforeach; ?>  
  }
  if (tipoProv == 2) {
    document.getElementById("sel_edit_proveedor").value = "";
    document.getElementById("sel_edit_proveedor").hidden = false;
    document.getElementById("lbl_edit_sel_proveedor").hidden = false;
    document.getElementById("edit_proveedor_especificar").value = "";
    document.getElementById("edit_proveedor_especificar").hidden = true;
    document.getElementById("lbl_edit_proveedor_especificar").hidden = true;
    document.getElementById("edit_proveedor_especificar").disabled = false;

    document.getElementById("sel_edit_proveedor").disabled = false;
    $('#sel_edit_proveedor').find('option').remove().end().append('<option value="" disabled selected>Seleccione un proveedor:</option>');
    $('#edit_proveedor_especificar').find('input').remove().end();
    var selProveedor = document.getElementById("sel_edit_proveedor");
    <?php foreach ($allProveedoresMaquinas as $unProveedorMaquina) : 
      $sql_prov = $db->query("SELECT * FROM provincia WHERE id_provincia = '".$unProveedorMaquina['provincia']."' LIMIT 1");
      $prov = $db->fetch_assoc($sql_prov);
      if ($unProveedorMaquina['id'] == 1 || $unProveedorMaquina['id'] == 2){
      } else {
    ?>
      var optProveedor = document.createElement('option');
      var textProveedor = "<?php echo $unProveedorMaquina['razon_social'].", ( ".$unProveedorMaquina['localidad']." - ".$prov['nombre']." )";?>";
      optProveedor.appendChild(document.createTextNode(textProveedor));
      optProveedor.value = parseInt("<?php echo $unProveedorMaquina['id']; ?>");
      selProveedor.appendChild(optProveedor);
    <?php } endforeach; ?>  
  }
  if (tipoProv == 3) {
    document.getElementById("sel_edit_proveedor").value = "";
    document.getElementById("sel_edit_proveedor").hidden = true;
    document.getElementById("lbl_edit_sel_proveedor").hidden = true;
    document.getElementById("edit_proveedor_especificar").value = "";
    document.getElementById("edit_proveedor_especificar").hidden = false;
    document.getElementById("edit_proveedor_especificar").disabled = false;
    document.getElementById("lbl_edit_proveedor_especificar").hidden = false;
  }
}
//---------------------------------------------------------------------------
function editarEgreso(idEgreso,idCaja,idOrigen,idPersonal,fecha,monto,idMoneda,numFactura,numRemito,tipoProveedor,idProveedor,proveedorAdicional,idConcepto,conceptoAdicional,aclaracion,litrosCombustible) {
  document.getElementById("inp_edit_egreso_id").value = idEgreso;
  document.getElementById("inp_edit_egreso_caja_id").value = idCaja;
  document.getElementById("sel_edit_origen_egre").value = idOrigen;
  var today = fecha;
    var elem = today.split('-');
    dd = elem[2];
    mm = elem[1];
    yyyy = elem[0];
  today = dd + '-' + mm + '-' + yyyy;
  document.getElementById("fecha_edit_egre").value = today;
  document.getElementById("sel_edit_personal_egre").value = idPersonal;
  document.getElementById("sel_edit_concepto_egre").value = idConcepto;
  document.getElementById("inp_edit_egre_combustible_lt").value = litrosCombustible;
  document.getElementById("inp_edit_concepto_aclaracion_egre").value = aclaracion;
  document.getElementById("edit_concepto_especificar").value = conceptoAdicional;
  document.getElementById("sel_edit_tipo_proveedor").value = tipoProveedor;
  document.getElementById("inp_edit_num_factura").value = numFactura;
  document.getElementById("inp_edit_num_remito").value = numRemito;
  document.getElementById("sel_edit_proveedor").value = idProveedor;
  document.getElementById("edit_proveedor_especificar").value = proveedorAdicional;
  document.getElementById("sel_edit_tipo_moneda_egre").value = idMoneda;
  document.getElementById("inp_edit_monto_egre").value = monto;

          if (idConcepto != 1 && idConcepto != 12 && idConcepto != 14) {
            document.getElementById("inp_edit_concepto_aclaracion_egre").disabled = false;
            document.getElementById("lbl_edit_concepto_aclaracion_egre").disabled = false;
            document.getElementById("inp_edit_concepto_aclaracion_egre").hidden = false;
            document.getElementById("lbl_edit_concepto_aclaracion_egre").hidden = false; 
            document.getElementById("inp_edit_concepto_aclaracion_egre").value = aclaracion;   
          } else {
            document.getElementById("inp_edit_concepto_aclaracion_egre").value = "";
            document.getElementById("inp_edit_concepto_aclaracion_egre").disabled = true;
            document.getElementById("lbl_edit_concepto_aclaracion_egre").disabled = true;
            document.getElementById("inp_edit_concepto_aclaracion_egre").hidden = true;
            document.getElementById("lbl_edit_concepto_aclaracion_egre").hidden = true;    
          }

          if (idConcepto == 12) {
            document.getElementById("edit_concepto_especificar").disabled = false;
            document.getElementById("edit_concepto_especificar").value = conceptoAdicional;
          } else {
            document.getElementById("edit_concepto_especificar").value = "";
            document.getElementById("edit_concepto_especificar").disabled = true;
          }
          if(idConcepto == 1 || idConcepto == 14) {
            document.getElementById("lbl_edit_egre_combustible_lt").disabled = false;
            document.getElementById("inp_edit_egre_combustible_lt").disabled = false;
            document.getElementById("lbl_edit_egre_combustible_lt").hidden = false;
            document.getElementById("inp_edit_egre_combustible_lt").hidden = false;
            document.getElementById("inp_edit_egre_combustible_lt").value = litrosCombustible;
          } else {
            document.getElementById("inp_edit_egre_combustible_lt").value = "";
            document.getElementById("lbl_edit_egre_combustible_lt").disabled = true;
            document.getElementById("inp_edit_egre_combustible_lt").disabled = true;
            document.getElementById("lbl_edit_egre_combustible_lt").hidden = true;
            document.getElementById("inp_edit_egre_combustible_lt").hidden = true;
          }

          if (tipoProveedor == 1) {
            //document.getElementById("sel_edit_proveedor").value = idProveedor;
            document.getElementById("sel_edit_proveedor").hidden = false;
            document.getElementById("lbl_edit_sel_proveedor").hidden = false;
            document.getElementById("edit_proveedor_especificar").value = "";
            document.getElementById("edit_proveedor_especificar").hidden = true;
            document.getElementById("lbl_edit_proveedor_especificar").hidden = true;
            document.getElementById("edit_proveedor_especificar").disabled = false;

            document.getElementById("sel_edit_proveedor").disabled = false;
            $('#sel_edit_proveedor').find('option').remove().end().append('<option value="" disabled selected>Seleccione un proveedor:</option>');
            $('#edit_proveedor_especificar').find('input').remove().end();
            var selProveedor = document.getElementById("sel_edit_proveedor");
            <?php foreach ($allProveedores as $unProveedor) : 
              $sql_prov = $db->query("SELECT * FROM provincia WHERE id_provincia = '".$unProveedor['provincia']."' LIMIT 1");
              $prov = $db->fetch_assoc($sql_prov);
            ?>
              var optProveedor = document.createElement('option');
              var textProveedor = "<?php echo $unProveedor['razon_social'].", ( ".$unProveedor['localidad']." - ".$prov['nombre']." )";?>";
              optProveedor.appendChild(document.createTextNode(textProveedor));
              optProveedor.value = parseInt("<?php echo $unProveedor['id']; ?>");
              selProveedor.appendChild(optProveedor);
            <?php endforeach; ?>
            document.getElementById("sel_edit_proveedor").value = idProveedor;  
          }
          if (tipoProveedor == 2) {
            document.getElementById("sel_edit_proveedor").hidden = false;
            document.getElementById("lbl_edit_sel_proveedor").hidden = false;
            document.getElementById("edit_proveedor_especificar").value = "";
            document.getElementById("edit_proveedor_especificar").hidden = true;
            document.getElementById("lbl_edit_proveedor_especificar").hidden = true;
            document.getElementById("edit_proveedor_especificar").disabled = false;

            document.getElementById("sel_edit_proveedor").disabled = false;
            $('#sel_edit_proveedor').find('option').remove().end().append('<option value="" disabled selected>Seleccione un proveedor:</option>');
            $('#edit_proveedor_especificar').find('input').remove().end();
            var selProveedor = document.getElementById("sel_edit_proveedor");
            <?php foreach ($allProveedoresMaquinas as $unProveedorMaquina) : 
              $sql_prov = $db->query("SELECT * FROM provincia WHERE id_provincia = '".$unProveedorMaquina['provincia']."' LIMIT 1");
              $prov = $db->fetch_assoc($sql_prov);
              if ($unProveedorMaquina['id'] == 1 || $unProveedorMaquina['id'] == 2){
          } else {
            ?>
              var optProveedor = document.createElement('option');
              var textProveedor = "<?php echo $unProveedorMaquina['razon_social'].", ( ".$unProveedorMaquina['localidad']." - ".$prov['nombre']." )";?>";
              optProveedor.appendChild(document.createTextNode(textProveedor));
              optProveedor.value = parseInt("<?php echo $unProveedorMaquina['id']; ?>");
              selProveedor.appendChild(optProveedor);
            <?php } endforeach; ?>
            document.getElementById("sel_edit_proveedor").value = idProveedor;  
          }
          if (tipoProveedor == 3) {
            document.getElementById("sel_edit_proveedor").value = "";
            document.getElementById("sel_edit_proveedor").hidden = true;
            document.getElementById("lbl_edit_sel_proveedor").hidden = true;
            document.getElementById("edit_proveedor_especificar").value = proveedorAdicional;
            document.getElementById("edit_proveedor_especificar").hidden = false;
            document.getElementById("edit_proveedor_especificar").disabled = false;
            document.getElementById("lbl_edit_proveedor_especificar").hidden = false;
          }
}
//-------------------EDITAR INGRESO------------------------------------------
function editarIngreso(idIngreso,idCaja,idOrigen,idPersonal,idCliente,clienteAdicional,monto,fecha,tipoIngreso,idMoneda) {
  document.getElementById("inp_edit_ingreso_id").value = idIngreso;
  document.getElementById("inp_edit_ingreso_caja_id").value = idCaja;
  document.getElementById("sel_edit_origen_ingre").value = idOrigen;
  document.getElementById("sel_edit_personal_ingre").value = idPersonal;
  document.getElementById("sel_edit_cliente_ingre").value = idCliente;
  document.getElementById("otro_edit_ingre").value = clienteAdicional;
  document.getElementById("inp_edit_monto_ingre").value = monto;
  var today = fecha;
    var elem = today.split('-');
    dd = elem[2];
    mm = elem[1];
    yyyy = elem[0];
  today = dd + '-' + mm + '-' + yyyy;
  document.getElementById("edit_fecha_ing").value = today;
  document.getElementById("sel_edit_tipo_ingreso_ingre").value = tipoIngreso;
  document.getElementById("sel_edit_tipo_moneda_ingre").value = idMoneda;

  document.getElementById("sel_edit_tipo_ingreso_ingre").disabled = false;
  document.getElementById("inp_edit_monto_ingre").disabled = false;
  document.getElementById("sel_edit_tipo_moneda_ingre").disabled = false;

  if (tipoIngreso == 1) {
    document.getElementById("sel_edit_tipo_moneda_ingre").disabled = false;
    document.getElementById("sel_edit_cliente_ingre").disabled = true;
    document.getElementById("sel_edit_personal_ingre").disabled = false;
    document.getElementById("otro_edit_ingre").disabled = true;
    document.getElementById("otro_edit_ingre").value = "";
    $('#sel_edit_cliente_ingre').find('option').remove().end().append('<option value="" disabled selected>Seleccione un cliente:</option>');
    $('#otro_edit_ingre').find('input').remove().end();
    var selPersonal = document.getElementById("sel_edit_personal_ingre");
    <?php foreach ($allPersonal as $unPersonal) : ?>
      var optPersonal = document.createElement('option');
      var textPersonal = "<?php echo $unPersonal['apellido'].", ".$unPersonal['nombre'];?>";
      optPersonal.appendChild(document.createTextNode(textPersonal));
      optPersonal.value = parseInt("<?php echo $unPersonal['id']; ?>");
      selPersonal.appendChild(optPersonal);
    <?php endforeach; ?>
    document.getElementById("sel_edit_personal_ingre").value = idPersonal;  
  }
  if (tipoIngreso == 2) {
    document.getElementById("sel_edit_tipo_moneda_ingre").disabled = false;
    document.getElementById("sel_edit_cliente_ingre").disabled = true;
    document.getElementById("sel_edit_personal_ingre").disabled = true;
    document.getElementById("otro_edit_ingre").disabled = true;
    document.getElementById("otro_edit_ingre").value = "";
    $('#sel_edit_cliente_ingre').find('option').remove().end().append('<option value="" disabled selected>Seleccione un cliente:</option>');
    $('#sel_edit_personal_ingre').find('option').remove().end().append('<option value="" disabled selected>Seleccione un personal:</option>');
    $('#otro_edit_ingre').find('input').remove().end(); 
  }
  if (tipoIngreso == 3) {
    document.getElementById("sel_edit_tipo_moneda_ingre").disabled = false;
    document.getElementById("sel_edit_personal_ingre").disabled = true;
    document.getElementById("sel_edit_cliente_ingre").disabled = false;
    document.getElementById("otro_edit_ingre").disabled = true;
    document.getElementById("otro_edit_ingre").value = "";
    $('#sel_edit_personal_ingre').find('option').remove().end().append('<option value="" disabled selected>Seleccione un personal:</option>');
    $('#sel_edit_cliente_ingre').find('option').remove().end().append('<option value="" disabled selected>Seleccione un cliente:</option>');
    $('#otro_edit_ingre').find('value').remove().end();
    var selCliente = document.getElementById("sel_edit_cliente_ingre");
    <?php foreach ($allCLientes as $unCliente) : 
      $sql_prov = $db->query("SELECT * FROM provincia WHERE id_provincia = '".$unCliente['provincia']."' LIMIT 1");
      $prov = $db->fetch_assoc($sql_prov);
    ?>
      var optCliente = document.createElement('option');
      var textCliente = "<?php echo $unCliente['razon_social']." - Suc.: ".$unCliente['num_suc']." (".$unCliente['localidad']." - ".$prov['nombre'].") Dir.: ".$unCliente['direccion']; ?>";
      optCliente.appendChild(document.createTextNode(textCliente));
      optCliente.value = parseInt("<?php echo $unCliente['id']; ?>");
      selCliente.appendChild(optCliente);
    <?php endforeach; ?>
    document.getElementById("sel_edit_cliente_ingre").value = idCliente;
  }
  if (tipoIngreso == 4) {
    document.getElementById("sel_edit_tipo_moneda_ingre").disabled = false;
    document.getElementById("sel_edit_personal_ingre").disabled = true;
    document.getElementById("sel_edit_cliente_ingre").disabled = false;
    document.getElementById("otro_edit_ingre").disabled = true;
    $('#sel_edit_personal_ingre').find('option').remove().end().append('<option value="" disabled selected>Seleccione un personal:</option>');
    $('#sel_edit_cliente_ingre').find('option').remove().end().append('<option value="" disabled selected>Seleccione un cliente:</option>');
    document.getElementById("otro_edit_ingre").value = "";
    var selClienteMaquina = document.getElementById("sel_edit_cliente_ingre");
    <?php foreach ($allClienteMaquina as $unClienteMaquina) : 
      $sql_prov = $db->query("SELECT * FROM provincia WHERE id_provincia = '".$unClienteMaquina['provincia']."' LIMIT 1");
      $prov = $db->fetch_assoc($sql_prov);
    ?>
      var optClienteMaquina = document.createElement('option');
      var textClienteMaquina = "<?php echo $unClienteMaquina['razon_social']." (".$unClienteMaquina['localidad']." - ".$prov['nombre'].") Dir.: ".$unClienteMaquina['direccion']; ?>";
      optClienteMaquina.appendChild(document.createTextNode(textClienteMaquina));
      optClienteMaquina.value = parseInt("<?php echo $unClienteMaquina['id']; ?>");
      selClienteMaquina.appendChild(optClienteMaquina);
    <?php endforeach; ?>
    document.getElementById("sel_edit_cliente_ingre").value = idCliente;
  }
  if (tipoIngreso == 5) {
    document.getElementById("sel_edit_personal_ingre").disabled = true;
    document.getElementById("sel_edit_cliente_ingre").disabled = true;
    document.getElementById("otro_edit_ingre").disabled = false;
    document.getElementById("sel_edit_tipo_moneda_ingre").disabled = false;
    $('#sel_edit_personal_ingre').find('option').remove().end().append('<option value="" disabled selected>Seleccione un personal:</option>');
    $('#sel_edit_cliente_ingre').find('option').remove().end().append('<option value="" disabled selected>Seleccione un cliente:</option>');
    document.getElementById("otro_edit_ingre").value = clienteAdicional;
  }
}
//------------------------------ FIN EDITAR INGRESO --------------------------
//------------------------------ INICIO SELECCION DE CAJA --------------------
function cambiarCaja(idCajaActual) {
  var idCajaCambio = document.getElementById("sel_cajas").value;
  var result = confirm("¿Está seguro/a que desea cambiar a ésta caja?");
  if (result) {
    if (idCajaCambio) {
      cambiar_caja = "&idCajaCambio=" + idCajaCambio + "&idCajaActual=" + idCajaActual;
      $('#procesando').html('<div class="loader"><br><p style="font-size: 24px;position: absolute;left: 40%;top: 57%;">Procesando, un momento por favor...</p></div>');
      $.ajax({
        type:"POST",
        url:"cajaCambio.php",
        data:cambiar_caja,
        success:function(r) {
          if (r == 1) {
            alertify.success("Acción realizada con éxito.");
            setInterval('location.reload()', 1000);
            //$("#modalAgregarRepuesto").modal("hide");                    
            //location.reload();                    
          } else {
            alertify.error("Error.");
          }
        }
      });
    }
  }
}
//------------------------------ FIN SELECCION DE CAJA -----------------------
//------------------------------ INICIO ELIMINAR CAJA --------------------
function eliminarCaja(idCajaActual) {
  //document.getElementById('btn_eliminar_caja').disabled = true;
  var result = confirm("¿Está seguro/a que desea eliminar ésta caja?.\nTenga cuidado, ésta acción es irreversible.");
  if (result) {
    var result2 = confirm("Por favor, confirme que está seguro/a de ELIMINAR ésta caja.");
  }
  if (result2) {
    document.getElementById('btn_eliminar_caja').disabled = true;
    if (idCajaActual) {
      eliminar_caja = "&idCajaActual=" + idCajaActual;
      //$('#procesando').html('<div class="loader"><br><p style="font-size: 24px;position: absolute;left: 40%;top: 57%;">Procesando, un momento por favor...</p></div>');
      $.ajax({
        type:"POST",
        url:"cajaDelete.php",
        data:eliminar_caja,
        success:function(r) {
          if (r == 1) {
            alertify.success("Acción realizada con éxito.");
            setInterval('location.reload()', 2000);
            //$("#modalAgregarRepuesto").modal("hide");                    
            //location.reload();                    
          } else {
            alertify.error("Error.");
          }
        }
      });
    }
  }
}
//------------------------------ FIN ELIMINAR CAJA -----------------------
//------------------------------ INICIO CIERRE DE CAJA -----------------------
function cerrarCaja(idCajaCierre,fondoCierre) {
  var result = confirm("¿Está seguro/a que desea cerrar ésta caja?.\nAl hacerlo tenga en cuenta que automáticamente se abrirá una nueva con un fondo inicial de $"+fondoCierre);
  if (result) {
    if (idCajaCierre) {
      cerrar_caja = "&idCajaCierre=" + idCajaCierre + "&fondoCierre=" + fondoCierre;
      $('#procesando').html('<div class="loader"><br><p style="font-size: 24px;position: absolute;left: 40%;top: 57%;">Procesando, un momento por favor...</p></div>');
      $.ajax({
        type:"POST",
        url:"cajaCierre.php",
        data:cerrar_caja,
        success:function(r) {
          if (r == 1) {
            //alertify.success("Acción realizada con éxito.");
            setInterval('location.reload()', 500);
            //$("#modalAgregarRepuesto").modal("hide");                    
            //location.reload();                    
          } else {
            alertify.error("Error.");
          }
          //window.alert(r);
        }
      });
    }
  }
}
//------------------------------ FIN CIERRE DE CAJA --------------------------


//------------------------------ VISUALES ------------------------------------
function origenSeleccionado() {
  document.getElementById('sel_tipo_ingreso').disabled = false;
  document.getElementById('sel_personal').disabled = true;
  document.getElementById('sel_cliente').disabled = true;
  document.getElementById('otro').disabled = true;

  $('#sel_tipo_ingreso').find('option').remove().end().append('<option value="" disabled selected>Seleccione un tipo de ingreso:</option>');
  $('#sel_personal').find('option').remove().end().append('<option value="" disabled selected>Seleccione un personal:</option>');
  $('#sel_cliente').find('option').remove().end().append('<option value="" disabled selected>Seleccione un cliente:</option>');
  document.getElementById("otro").value = "";
  var selTipoIngreso = document.getElementById("sel_tipo_ingreso");
  for (i = 1; i <= 5; i++) {
    if (i == 1) {
      var optTipoIngreso = document.createElement('option');
      var textTipoIngreso = "<?php echo "De personal"; ?>";
      optTipoIngreso.appendChild(document.createTextNode(textTipoIngreso));
      optTipoIngreso.value = parseInt(i);
      selTipoIngreso.appendChild(optTipoIngreso);
    }
    if (i == 2) {
      var optTipoIngreso = document.createElement('option');
      var textTipoIngreso = "<?php echo "De caja maquinarias"; ?>";
      optTipoIngreso.appendChild(document.createTextNode(textTipoIngreso));
      optTipoIngreso.value = parseInt(i);
      selTipoIngreso.appendChild(optTipoIngreso);
    }
    if (i == 3) {
      var optTipoIngreso = document.createElement('option');
      var textTipoIngreso = "<?php echo "De cliente regular"; ?>";
      optTipoIngreso.appendChild(document.createTextNode(textTipoIngreso));
      optTipoIngreso.value = parseInt(i);
      selTipoIngreso.appendChild(optTipoIngreso);
    }
    if (i == 4) {
      var optTipoIngreso = document.createElement('option');
      var textTipoIngreso = "<?php echo "De cliente máquina"; ?>";
      optTipoIngreso.appendChild(document.createTextNode(textTipoIngreso));
      optTipoIngreso.value = parseInt(i);
      selTipoIngreso.appendChild(optTipoIngreso);
    }
    if (i == 5) {
      var optTipoIngreso = document.createElement('option');
      var textTipoIngreso = "<?php echo "Otro"; ?>";
      optTipoIngreso.appendChild(document.createTextNode(textTipoIngreso));
      optTipoIngreso.value = parseInt(i);
      selTipoIngreso.appendChild(optTipoIngreso);
    }
  }
}

function huboSeleccion() {
  document.getElementById('sel_tipo_moneda').disabled = false;
}

function huboSeleccionEdit() {
  document.getElementById('sel_edit_tipo_moneda_ingre').disabled = false;
}

function goMonto() {
  document.getElementById('inp_monto').disabled = false;
}

function monedaEgreSelec() {
  document.getElementById("inp_monto_egre").disabled = false;
  //document.getElementById("inp_monto_egre").value = "";
}

function soloNumeros(e){
  var key = window.event ? e.which : e.keyCode;
  if (key < 48 || key > 57) {
    e.preventDefault();
  }
}

</script>    
<div id="cargando" class="loader"></div>
<div id="procesando"></div>
    <!-- Main content -->
    <section class="content" style="margin-left: -15px;margin-right: -15px;">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <form action="caja_actual.php" method="POST">
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3 style="font-size: calc(2.5rem + 0.6vw);margin-top: 0;margin-bottom: 0.5rem;font-weight: 600;line-height: 1.2;"><?php echo "$ ".$caja['fondo_inicio'];?></h3>
                <p style="margin-top: 0;margin-bottom: 1rem;font-size: 1.5rem;font-weight: bold;"><?php echo "Inicio de caja ".$partInicioCajaActiva[2]." de ".$partInicioCajaActiva[1]." del ".$partInicioCajaActiva[0];?></p>
              </div>
              <div class="icon">
                <i class="fas fa-cash-register"></i>
              </div>
              <a type="button" id="a_modal_gestion-cajas" class="small-box-footer" data-toggle="modal" data-target="#modal-gestion-cajas" data-id_caja="<?php echo $caja['id'];?>"><input hidden>Más información <i class="fas fa-arrow-circle-right" id="open_modal-gestion-cajas"></i></a>              
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3 style="font-size: calc(2.5rem + 0.6vw);margin-top: 0;margin-bottom: 0.5rem;font-weight: 600;line-height: 1.2;"><?php echo "$ ".$sumaIngresos;?> <!--<sup style="font-size: 20px">%</sup> --></h3>
                <p style="margin-top: 0;margin-bottom: 1rem;font-size: 1.5rem;font-weight: bold;"><?php echo $rows_ingre; ?> Ingresos en total</p>
              </div>
              <div class="icon">
                <i class="ion ion-arrow-graph-down-left"></i>
              </div>
              <a type="button" id="a_modal_ingresos" class="small-box-footer" data-toggle="modal" data-target="#modal_ingresos" data-id_caja="<?php echo $caja['id'];?>"><input hidden>Nuevo ingreso <i class="fas fa-arrow-circle-right" id="open_modal_agregar_ingreso"></i></a> 
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
              <a type="button" id="a_modal_egresos" class="small-box-footer" data-toggle="modal" data-target="#modal_egresos" data-id_caja="<?php echo $caja['id'];?>"><input hidden>Nuevo egreso <i class="fas fa-arrow-circle-right" id="open_modal_agregar_egreso"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3 style="font-size: calc(2.5rem + 0.6vw);margin-top: 0;margin-bottom: 0.5rem;font-weight: 600;line-height: 1.2;"><?php echo "$ ".$balanceCaja ; ?></h3>
                <p style="margin-top: 0;margin-bottom: 1rem;font-size: 1.5rem;font-weight: bold;">Balance</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <a type="button" id="a_modal_balance" class="small-box-footer" data-toggle="modal" data-target="#modal-balance"><input hidden>Más información <i class="fas fa-arrow-circle-right" id="open_modal_balance"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>
        </form>
        <!-- /.row -->
      </div>
    </section>
    <div class="row"><!-- Main row -->
      <section class="col-lg-8 connectedSortable"><!-- LEFT col -->
        <div class="card card-success">
          <div class="card-header" style="height: 48px;">
            <h3 class="card-title" style="font-size: 1.75rem; margin-top: 0.5rem;margin-bottom: 0.5rem;font-weight: 500;line-height: 1.2;">
              <i class="glyphicon glyphicon-transfer" style="margin-right: 4px;"></i>
              <p style="margin-left: 25px;margin-top: -18px;margin-bottom: 0px;">Movimientos</p>
            </h3>
          </div><!-- /.card-header -->
          <div class="card-body">
         
            <div class="table-responsive" style="padding-left: 2px;padding-right: 2px;width: 99.5%;">
              <table id="caja_movimientos" name="caja_movimientos" class="table table-bordered table-striped table-hover" style="width: 100%;font-size: 12px;">
                <thead>
                  <tr>
                    <th style="width: 5%;">Tipo</th>
                    <th style="width: 12%;">Origen</th>
                    <th style="width: 20%;">Entidad</th>
                    <th style="width: 15%;">Concepto</th>
                    <th style="width: 7%;">Divisa</th>
                    <th style="width: 10%;">Monto</th>
                    <th style="width: 8%;">Fecha</th>
                    <th style="width: 8%;">Hora</th>
                    <th style="width: 8%;">Estado</th>
                    <th style="width: 7%;"></th>                    
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

                            <?php if ($unIngreso['tipo_ingreso'] == 1) { 
                            $personal = find_by_id('personal',$unIngreso['personal_id']); ?>
                          <td class="text-center"><?php echo $personal['apellido'].", ".$personal['nombre'];?></td> 
                            <?php } ?>
                            <?php if ($unIngreso['tipo_ingreso'] == 2) { ?>
                          <td class="text-center"><?php echo "Caja de maquinarias";?></td>
                            <?php } ?>  
                            <?php if ($unIngreso['tipo_ingreso'] == 3) { //cliente normal
                            $esteCliente = find_by_id('cliente',$unIngreso['cliente_id']); ?>
                          <td class="text-center"><?php echo $esteCliente['razon_social'];?></td>
                            <?php } ?>
                            <?php if ($unIngreso['tipo_ingreso'] == 4) {  
                            $esteCliente = find_by_id('clientemaquina',$unIngreso['cliente_id']); ?>
                          <td class="text-center"><?php echo $esteCliente['razon_social'];?></td>
                            <?php } ?>
                            <?php if ($unIngreso['tipo_ingreso'] == 5) { ?>
                          <td class="text-center"><?php echo $unIngreso['cliente_adicional'];?></td>
                          <?php } ?>

                          <td class="text-center">-</td>

                          <td class="text-center"><?php echo $moneda['codigo'];?></td>                       

                          <td class="text-center"><?php echo $unIngreso['monto'];?></td>                          

                          <td class="text-center"><?php echo $unIngreso['fecha'];?></td>

                          <td class="text-center"><?php echo $unIngreso['hora'];?></td>                             

                          <td class="text-center">-</td>

                          <td class="text-center">
                            <div class="btn-group">
                              <button id="btn_editar_ingreso_<?php echo $unIngreso['id'];?>" type="submit" class="btn btn-xs btn-warning" data-toggle="modal" data-target="#modal_editar_ingresos" title="Editar Ingreso" onclick="javascript:editarIngreso('<?php echo $unIngreso['id'];?>','<?php echo $unIngreso['caja_id'];?>','<?php echo $unIngreso['origen_id'];?>','<?php echo $unIngreso['personal_id'];?>','<?php echo $unIngreso['cliente_id'];?>','<?php echo $unIngreso['cliente_adicional'];?>','<?php echo $unIngreso['monto'];?>','<?php echo $unIngreso['fecha'];?>','<?php echo $unIngreso['tipo_ingreso'];?>','<?php echo $unIngreso['moneda_id'];?>')">
                              <span class="glyphicon glyphicon-pencil" style="height: 15px;"></span>
                              </button>
                              <button id="btn_borrar_ingreso_<?php echo $unIngreso['id'];?>" type="submit" class="btn btn-xs btn-danger" data-toggle="tooltip" title="Borrar Ingreso" onclick="javascript:borrarIngreso('<?php echo $unIngreso['id'];?>')">
                              <span class="glyphicon glyphicon-trash" style="height: 15px;"></span>
                              </button>
                            </div>
                          </td>

                        </tr>
                  <?php endforeach; ?>
                  <!--------------------- EGRESOS --------------------->
                  <?php foreach ($allEgresos as $unEgreso):
                    $origen = find_by_id('caja_origen',$unEgreso['origen_id']); 
                    $moneda = find_by_id('caja_moneda',$unEgreso['moneda_id']);
                  ?>
                    <tr class="danger">
                      <td class="text-center"><span class="label label-danger">Egreso</span></td>

                      <td class="text-center"><?php echo $origen['descripcion']; ?></td>

                        <?php if ($unEgreso['personal_id'] != null) { 
                          $personal = find_by_id('personal',$unEgreso['personal_id']);
                        ?>
                      <td class="text-center"><?php echo $personal['apellido'].", ".$personal['nombre'];?></td>
                        <?php } else { ?>
                      <td class="text-center">-</td>
                        <?php } ?>
                        <?php $esteConcepto = find_by_id('caja_conceptos',$unEgreso['concepto_id']); 
                          if ($esteConcepto['id'] == 1 || $esteConcepto['id'] == 14) {
                            $tdTitle = $unEgreso['litros_combustible']." litros";
                          } else {
                            $tdTitle = $unEgreso['aclaracion'];
                          }
                          if($esteConcepto['id'] == 12) {
                            $tdTitle = $unEgreso['concepto_adicional'];
                          }                          
                        ?>
                      <td class="text-center" title="<?php echo $tdTitle; ?>"><?php echo $esteConcepto['descripcion']; ?></td>

                      <td class="text-center"><?php echo $moneda['codigo'];?></td>
                      <td class="text-center"><?php echo $unEgreso['monto'];?></td>
                      <td class="text-center"><?php echo $unEgreso['fecha'];?></td>
                      <td class="text-center"><?php echo $unEgreso['hora'];?></td> 
                      <td class="text-center">-</td>
                      <td class="text-center">
                        <div class="btn-group">
                          <button id="btn_editar_egreso_<?php echo $unEgreso['id'];?>" type="submit" class="btn btn-xs btn-warning" data-toggle="modal" data-target="#modal_editar_egresos" title="Editar Egreso" onclick="javascript:editarEgreso('<?php echo $unEgreso['id'];?>','<?php echo $unEgreso['caja_id'];?>','<?php echo $unEgreso['origen_id'];?>','<?php echo $unEgreso['personal_id'];?>','<?php echo $unEgreso['fecha'];?>','<?php echo $unEgreso['monto'];?>','<?php echo $unEgreso['moneda_id'];?>','<?php echo $unEgreso['num_factura'];?>','<?php echo $unEgreso['num_remito'];?>','<?php echo $unEgreso['tipo_proveedor'];?>','<?php echo $unEgreso['proveedor_id'];?>','<?php echo $unEgreso['proveedor_adicional'];?>','<?php echo $unEgreso['concepto_id'];?>','<?php echo $unEgreso['concepto_adicional'];?>','<?php echo $unEgreso['aclaracion'];?>','<?php echo $unEgreso['litros_combustible'];?>')">
                            <span class="glyphicon glyphicon-pencil" style="height: 15px;"></span>
                          </button> 
                          <button id="btn_borrar_egreso_<?php echo $unEgreso['id'];?>" type="submit" class="btn btn-xs btn-danger" data-toggle="tooltip" title="Borrar Egreso" onclick="javascript:borrarEgreso('<?php echo $unEgreso['id'];?>')">
                            <span class="glyphicon glyphicon-trash" style="height: 15px;"></span>
                          </button>
                        </div>
                      </td>
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
                      <td class="text-center"><?php echo $unGasto['hora'];?></td> 
                      <td class="text-center">-</td>
                      <td class="text-center"></td>
                    </tr>
                    <?php  
                  endforeach;
                  ?>                  
                </tbody>
                <tfoot>
                  <tr>
                    <th style="width: 5%;">Tipo</th>
                    <th style="width: 12%;">Origen</th>
                    <th style="width: 20%;">Entidad</th>
                    <th style="width: 15%;">Concepto</th>
                    <th style="width: 7%;">Divisa</th>
                    <th style="width: 10%;">Monto</th>
                    <th style="width: 8%;">Fecha</th>
                    <th style="width: 8%;">Hora</th>
                    <th style="width: 8%;">Estado</th>
                    <th style="width: 7%;"></th>
                  </tr>
                </tfoot>
              </table>
              <script>
                $('#caja_movimientos').DataTable({ "order": [[ 6, "desc" ],[ 7, "desc" ]] }); 
                $('.dataTables_length').addClass('bs-select');
              //FUNCION PARA PONER UN INPUT DE BUSCARDOR
/*                var tablita = $('#caja_movimientos').DataTable();
                  $('#caja_movimientos thead th').each( function() {
                    var titulito = $(this).text();
                    $(this).html('<input type="text" placeholder="Buscar"'+ titulito +'"/>');
                  });
                tablita.columns().every( function () {
                  var that = this;
                  $('input', this.header() ).on( 'keyup change', function() {
                    if ( that.search() !== this.value ) {
                      that.search( this.value ).draw();
                    }
                  });
                });
*/
              </script>
            </div>   
          </div><!-- /.card-body -->
        </div>
        <!-- Custom tabs (Charts with tabs)-->
        <div class="card card-info">
          <div class="card-header">
            <h3 class="card-title" style="font-size: 1.75rem; margin-top: 0.6rem;margin-bottom: 0.5rem;font-weight: 500;line-height: 1.2;">
              <i class="fas fa-chart-area mr-1"></i>
              Historicos
            </h3>
            <div class="card-tools">
              <ul class="nav nav-pills ml-auto">
                <li class="nav-item">
                  <a class="nav-link active" href="#bar-chart" data-toggle="tab">Barras</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#revenue-chart" data-toggle="tab">Línea</a>
                </li>                
                <li class="nav-item">
                  <a class="nav-link" href="#combustible-chart" data-toggle="tab">Combustible</a>
                </li>
                <!--<li class="nav-item">
                  <a class="nav-link" href="#sales-chart" data-toggle="tab">Donut</a>
                </li>-->
              </ul>
            </div>
          </div><!-- /.card-header -->
          <div class="card-body">
            <div class="tab-content p-0">
              <!-- Morris chart - Sales -->
              <div class="chart tab-pane" id="revenue-chart" style="position: relative; height: 300px;">
                <canvas id="revenue-chart-canvas" height="300" style="height: 300px;"></canvas>                         
              </div>
               <!-- barChart -->
              <div class="chart tab-pane active" id="bar-chart" style="position: relative; height: 300px;">
                <canvas id="barChart" height="300" style="height: 300px;"></canvas>                         
              </div>
              <!-- chart combustibles --> 
              <div class="chart tab-pane" id="combustible-chart" style="position: relative; height: 300px;">
                <canvas id="chart-combustible-canvas" height="300" style="height: 300px;"></canvas>                         
              </div>
               <!-- chart sales (el grafico de pie trae los datos desde aca) -->
              <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px;">
                <canvas id="sales-chart-canvas" height="300" style="height: 300px;"></canvas>                         
              </div>   
            </div>
          </div><!-- /.card-body -->
        </div>
      </section> <!-- end LEFT col -->
      <!-- RIGHT col -->
      <section class="col-lg-4 connectedSortable">
        <!-- PIE CHART -->
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
            <canvas id="pieChart" style="min-height: 250px; height: 275px; max-height: 350px; max-width: 100%;"></canvas>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
        <!-- /.card Lista de egresos -->
        <div class="card card-info">
          <div class="card-header" style="height: 48px;">
            <h3 class="card-title" style="font-size: 1.75rem; margin-top: 0.5rem;margin-bottom: 0.5rem;font-weight: 500;line-height: 1.2;">
              <i class="glyphicon glyphicon-transfer" style="margin-right: 4px;"></i>
              <p style="margin-left: 25px;margin-top: -18px;margin-bottom: 0px;">Listado de Egresos</p>
            </h3>
          </div><!-- /.card-header -->
          <div class="card-body">
            <div class="table-responsive" style="padding-right: 3px;padding-left: 3px;">
              <table id="tabla_egresos" name="tabla_egresos" class="table table-bordered table-striped table-hover" style="font-size: 12px;">
                <thead>
                  <tr>
                    <th style="width: 65%;">Concepto</th>
                    <th style="width: 35%;">Monto</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                  $montoConcept2 = 0;
                  $colorcito = 0;
                  foreach ($result_tipo_conceptos as $unResult_tipo_conceptos2) :
                    ?><tr class="info"><?php
                    $sql_concept2 = $db->query("SELECT monto FROM caja_egresos WHERE caja_id = '{$idCaja}' AND concepto_id = ".$unResult_tipo_conceptos2['id']."");
                    $concept2 = $db->while_loop($sql_concept2); ?>
                    <td> <?php echo $unResult_tipo_conceptos2['descripcion'];?> </td>
                    <?php
                    foreach ($concept2 as $unConcept2) :
                      $montoConcept2 = $montoConcept2 + $unConcept2['monto'];
                    endforeach; ?>
                    <td><?php echo $montoConcept2;?></td>
                    <?php $montoConcept2 = 0 ; ?> 
                  </tr>
                  <?php $colorcito = $colorcito + 1;
                  endforeach; ?>
                </tbody>
                <tfoot>
                  <tr>
                    <th class="danger" style="width: 65%;font-size: 14px;">TOTALES</th>
                    <th class="danger" style="width: 35%;font-size: 14px;"><?php echo "$ ".$sumaEgresos;?></th>
                  </tr>
                </tfoot>
              </table>
              <script>
                $('#tabla_egresos').DataTable(/*{ "order": [[ 6, "desc" ],[ 7, "desc" ]] }*/); 
                $('.dataTables_length').addClass('bs-select');
              </script>
            </div><!-- .table end -->   
          </div><!-- /.card-body -->
        </div><!-- /. card-end -->
        
        <!-- /.card Divisas -->
<!--        <div class="card card-success">
          <div class="card-header">
            <h3 class="card-title" style="font-size: 1.75rem; margin-top: 0.6rem;margin-bottom: 0.5rem;font-weight: 500;line-height: 1.2;">
            <i class="fas fa-chart-pie mr-1"></i>
              Divisas</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive" style="width: 99%;"> --> <!-- Inicio tabla divisas -->
<!--              <table id="tabla_divisas" name="tabla_divisas" class="table table-bordered table-striped table-hover" style="width: 99%;height: 99%;font-size: 12px;margin-left: 2px;">
                <thead>
                  <tr>
                    <th style="width: 40%;">Divisa</th>
                    <th style="width: 30%;">Compra</th>
                    <th style="width: 30%;">Venta</th>
                  </tr>
                </thead>
                <tbody>
                  <td></td>
                  <td></td>
                  <td></td>
                </tbody>
                <tfoot>
                  <tr>
                    <th style="width: 40%;">Divisa</th>
                    <th style="width: 30%;">Compra</th>
                    <th style="width: 30%;">Venta</th>
                  </tr>
                </tfoot>
              </table>
              <script>
                $('#tabla_divisas').DataTable(/*{ "order": [[ 6, "desc" ],[ 7, "desc" ]] }*/{"searching": false}); 
                $('.dataTables_length').addClass('bs-select');
              </script>
            </div> --><!-- .table end -->
<!--          </div>--> <!-- /.card-body -->
<!--        </div>--> <!-- /.card-end -->
      </section><!-- /. end section de la derecha -->
    </div><!-- end main row -->

  </div><!-- CIERRA EL LOS ENCABEZADOS DEL HEADER -->
</div><!-- CIERRA EL LOS ENCABEZADOS DEL HEADER -->

<!--------------- MODAL GESTION DE CAJAS ------------------>
<div class="modal bd-example-modal-lg" id="modal-gestion-cajas" tabindex="-1" role="dialog" aria-labelledby="modal-gestion-cajas-label" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content bg-warning">
      <div class="modal-header">
        <h3 class="modal-title">Gestionar cajas</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-10">
            <?php

            $dateInicioCajaActiva2 = $caja['fecha_inicio'];
            $partInicioCajaActiva2 = array();
            $partInicioCajaActiva2 = explode("-",$dateInicioCajaActiva2);
            $arrayInicioCajaActiva2 = array($partInicioCajaActiva2[2],$partInicioCajaActiva2[1],$partInicioCajaActiva2[0]);
            $dateInicioCajaActivaOk2 = implode("-", $arrayInicioCajaActiva2);
            ?>
            <label class="control-label">Caja ACTIVA actualmente:</label>
            <input type="text" name="input_datos_caja_activa_actual" class="form-control" value="<?php echo $caja['id'].' - Inicio el '.$dateInicioCajaActivaOk2.' a las '.$caja['hora_inicio'].', un fondo incial de $'.$caja['fondo_inicio'].' y balance $'.$balanceCaja;?>" disabled>
          </div>
          <div class="col-md-2">
            <label for="" class="control-label">Acción:</label>
            <button type="button" id="btn_eliminar_caja" class="btn btn-outline-dark hoverdanger bgroundwhite btn-block" onclick="javascript:eliminarCaja('<?php echo $caja['id']; ?>','<?php echo $balanceSinCierre; ?>');" style="border: 1px solid black;"><span class="glyphicon glyphicon-trash" style="color: red;"></span> Eliminar</button>
          </div>
        </div>
        <hr>
        <div class="row">
          <div class="col-md-10">
            <label for="sel_cajas" class="control-label">Cambiar de caja:</label>
            <select class="form-control" data-show-subtext="true" data-live-search="true" name="sel_cajas" id="sel_cajas" data-width="100%" onclick="" required>
              <option value="" disabled selected >Seleccione una caja:</option>
              <?php  foreach ($allCajas as $unaCaja):
              $dateInicio = $unaCaja['fecha_inicio'];
              $partInicio = array();
              $partInicio = explode("-",$dateInicio);
              $arrayInicio = array($partInicio[2], $partInicio[1], $partInicio[0]);
              $dateInicioOk = implode("-", $arrayInicio);
              $dateCierre = $unaCaja['fecha_cierre'];
              $partCierre = array();
              $partCierre = explode("-",$dateCierre);
              $arrayCierre = array($partCierre[2], $partCierre[1], $partCierre[0]);
              $dateCierreOk = implode("-", $arrayCierre); 
              ?>
                <option value="<?php echo $unaCaja['id']; ?>">
                 <?php echo $unaCaja['id']." - [ Fecha Inicio: ".$dateInicioOk." a Cierre: ".$dateCierreOk." ] con [ Fondo de inicio: $".$unaCaja['fondo_inicio']." y Fondo de cierre: $".$unaCaja['fondo_cierre']." ]";?>
                </option>                    
              <?php endforeach; ?>                   
            </select>
          </div>
          <div class="col-md-2">
            <label class="control-label">Acción:</label>
            <button type="button" class="btn btn-outline-dark hoverdanger bgroundwhite btn-block" onclick="javascript:cambiarCaja('<?php echo $idCaja ; ?>');" style="border: 1px solid black;">Seleccionar</button>
          </div>
        </div>
        <hr>
        <div class="row">
          <div class="col-md-10">
            <?php
            $dateInicioSinCierre = $cajaSinCierre['fecha_inicio'];
            $partInicioSinCierre = array();
            $partInicioSinCierre = explode("-",$dateInicioSinCierre);
            $arrayInicioSinCierre = array($partInicioSinCierre[2], $partInicioSinCierre[1], $partInicioSinCierre[0]);
            $dateInicioSinCierreOk = implode("-", $arrayInicioSinCierre);
            ?>
            <label class="control-label">Última caja sin CIERRE:</label>
            <input type="text" name="input_datos_caja_cierre" class="form-control" value="<?php echo $cajaSinCierre['id'].' - Inicio el '.$dateInicioSinCierreOk.' a las '.$cajaSinCierre['hora_inicio'].', un fondo incial de $'.$cajaSinCierre['fondo_inicio'].' y balance $'.$balanceSinCierre;?>" disabled>
          </div>
          <div class="col-md-2">
            <label for="" class="control-label">Acción:</label>
            <button type="button" class="btn btn-outline-dark hoverdanger bgroundwhite btn-block" onclick="javascript:cerrarCaja('<?php echo $cajaSinCierre['id']; ?>','<?php echo $balanceSinCierre; ?>');" style="border: 1px solid black;">Dar Cierre</button>
          </div>
        </div>       

      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-outline-dark hoverdanger" data-dismiss="modal" style="border: 1px solid black;">Cerrar</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<!--------------- END MODAL GESTION DE CAJAS -------------->

<!----- MODAL AGREGAR INGRESO ----->
<div class="modal bd-example-modal-lg" id="modal_ingresos" tabindex="-1" role="dialog" aria-labelledby="modal_ingreso" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content bg-success">
      <div class="modal-header">        
        <h3 class="modal-title">Nuevo ingreso</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-8">
            <div class="row">
              <div class="col-md-6">
                <label for="sel_origen" class="control-label">Origen:</label>
                <select class="form-control" data-show-subtext="true" data-live-search="true" name="sel_origen" id="sel_origen" data-width="100%" onclick="javascript:origenSeleccionado();" required>
                  <option value="" disabled selected >Seleccione un origen:</option>
                  <?php  foreach ($allOrigenes as $unOrigen): ?>                                    
                    <option value="<?php echo $unOrigen['id']; ?>">
                     <?php echo $unOrigen['descripcion'];?>
                    </option>                    
                  <?php endforeach; ?>                   
                </select>
              </div>
              <div class="col-md-6">
              <label for="sel_tipo_ingreso" class="control-label">Tipo de ingreso:</label>
              <select class="form-control" name="sel_tipo_ingreso" id="sel_tipo_ingreso" data-width="100%" onchange="javascript:tipoIngresoSeleccionado();" required disabled>
                  <option value="" disabled selected >Seleccione un tipo de ingreso:</option>                                 
                    <option value="1">De personal</option>
                    <option value="2">De caja maquinarias</option>
                    <option value="3">De cliente regular</option>
                    <option value="4">De cliente máquina</option>
                    <option value="5">Otro</option>                                        
                </select>            
              </div>
            </div>
          <br>
            <div class="row">
              <div class="col-md-6">
                <label for="sel_personal" class="control-label">Personal:</label>
                <select class="form-control" name="sel_personal" id="sel_personal" data-width="100%" disabled onchange="javascript:huboSeleccion();">
                  <option value="" disabled selected >Seleccione un personal:</option>                 
                </select>
              </div>      
              <div class="col-md-6">
                <label for="sel_cliente" class="control-label">Cliente:</label>
                <select class="form-control" name="sel_cliente" id="sel_cliente" data-width="100%" disabled onchange="javascript:huboSeleccion();">
                  <option value="" disabled selected >Seleccione un cliente:</option>                                        
                </select>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <label>Otro <sup>(opcional)</sup>:</label>
            <textarea type="text" class="form-control" placeholder="Descripción" id="otro" maxlength="250" style="resize: none; height: 107.5px;" disabled></textarea>
          </div> 
        </div>
        <hr>
        <div class="row">
          <div class="col-md-4">
            <label for="sel_tipo_moneda" class="control-label">Tipo moneda:</label>
            <select class="form-control" name="sel_tipo_moneda" id="sel_tipo_moneda" data-width="100%" disabled onchange="javascript:goMonto();">
              <option value="" disabled selected >Seleccione un tipo de moneda:</option>                                  
              <?php foreach ($allTipoMoneda as $unTipoMoneda) : 
                if ($unTipoMoneda['id'] != 1) { ?>
              <option disabled value="<?php echo $unTipoMoneda['id'];?>"><?php echo $unTipoMoneda['codigo']." - ".$unTipoMoneda['divisa'];?></option>
              <?php } else { ?>
              <option value="<?php echo $unTipoMoneda['id'];?>"><?php echo $unTipoMoneda['codigo']." - ".$unTipoMoneda['divisa'];?></option>
              <?php } endforeach; ?>                                        
            </select>
          </div>
          <div class="col-md-4">
            <label for="inp_monto" class="control-label">Monto:</label>
            <input class="form-control" type="number" min="0" name="inp_monto" id="inp_monto" data-width="100%" placeholder="Ingrese un monto" disabled>
          </div>
          <div class="col-md-4">
            <label for="fecha_ing" class="control-label">Fecha:</label>
            <input type="text" class="form-control" name="fecha_ing" id="fecha_ing" readonly>
            <script>
              $('#fecha_ing').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                language: 'es',
                toggleActive: true
              });
            </script>               
          </div>
        </div>
          <!--
          <div class="col-md-4" style="text-align: center-left;">                              
            <a class="btn btn-primary" data-dismiss="modal" data-toggle="modal" title="Crear un repuesto" href="#modalCrearRepuesto"><span class="glyphicon glyphicon-plus"></span> Crear un repuesto</a>
          </div>
          -->
      </div>
      <div class="modal-footer">
      <!--
        <div class="row">
          <div class="col-md-12" style="text-align: right;">
            <label vertical-align="middle" id="stockDisponible" style="display: none">(Stock Disponible)</label>
          </div>
        </div>
      -->
      <br>
        <div class="row">          
          <div class="col-md-6">            
            <button type="button" class="btn btn-outline-light" style="border: 1px solid white;"  data-dismiss="modal">Cancelar</button>
          </div>
          <div class="col-md-6">                                        
            <button type="button" class="btn btn-outline-light" style="border: 1px solid white;" id="btn_agregar_ingreso">Aceptar</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!----- FIN MODAL INGRESO ----->

<!----- MODAL AGREGAR EGRESO ----->
<div class="modal bd-example-modal-lg" id="modal_egresos" tabindex="-1" role="dialog" aria-labelledby="modal_egreso" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content bg-danger">
      <div class="modal-header">        
        <h3 class="modal-title">Nuevo Egreso</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-8">
            <div class="row">
              <div class="col-md-6">
                <label for="fecha_egre" class="control-label">Fecha:</label>
                <input type="text" class="form-control" name="fecha_egre" id="fecha_egre" readonly>
                <script>
                  $('#fecha_egre').datepicker({
                    format: 'dd-mm-yyyy',
                    autoclose: true,
                    language: 'es',
                    toggleActive: true
                  });
                </script>
              </div>
              <div class="col-md-6">
              <label for="sel_personal_egre" class="control-label">Personal:</label>
                <select class="form-control" name="sel_personal_egre" id="sel_personal_egre" data-width="100%" onchange="javascript:personalEgreSelec();">
                  <option value="" disabled selected >Seleccione un personal:</option>
                  <?php foreach ($allPersonal as $unPersonal) : ?>
                    <option value="<?php echo $unPersonal['id']; ?>"><?php echo $unPersonal['apellido'].", ".$unPersonal['nombre']; ?></option>
                  <?php endforeach ; ?> 
                </select>            
              </div>
            </div>
          <br>
            <div class="row">
              <div class="col-md-6">
                <label for="sel_concepto_egre" class="control-label">Concepto:</label>
                <select class="form-control" name="sel_concepto_egre" id="sel_concepto_egre" data-width="100%" onchange="conceptoEgreSelec();">
                  <option value="" disabled selected >Seleccione un concepto:</option>
                  <?php foreach ($allConceptos as $unConcepto) : 
                  if ($unConcepto['id'] == 12) { ?>
                  <option value="<?php echo $unConcepto['id'];?>"><?php echo "---".$unConcepto['descripcion']."---";?></option>
                  <?php } else { ?>
                  <option value="<?php echo $unConcepto['id'];?>"><?php echo $unConcepto['descripcion'];?></option>
                  <?php } endforeach; ?>                                       
                </select>
              </div>      
              <div class="col-md-6">
                <label hidden for="inp_egre_combustible_lt" id="lbl_egre_combustible_lt" class="control-label">Litros de combustible:</label>
                <input hidden class="form-control" type="number" name="inp_egre_combustible_lt" id="inp_egre_combustible_lt" data-width="100%" placeholder="Ingrese una cantidad:" disabled>
                <label hidden for="inp_concepto_aclaracion_egre" id="lbl_concepto_aclaracion_egre" class="control-label">Aclaración:</label>
                <input hidden class="form-control" type="text" name="inp_concepto_aclaracion_egre" id="inp_concepto_aclaracion_egre" data-width="100%" placeholder="Ingrese una aclaración:" disabled>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <label>Especifique concepto: <sup>(opcional)</sup></label>
            <textarea type="text" class="form-control" placeholder="Descripción" id="concepto_especificar" maxlength="250" style="resize: none; height: 107.5px;" disabled></textarea>
          </div> 
        </div>
        <hr>


        <div class="row">
          <div class="col-md-8">
            <div class="row">
              <div class="col-md-6">
                <label for="sel_origen_egre" class="control-label">Origen:</label>
                <select class="form-control" data-show-subtext="true" data-live-search="true" name="sel_origen_egre" id="sel_origen_egre" data-width="100%" onclick="javascript:origenEgreSelec();" required>
                  <option value="" disabled selected >Seleccione un origen:</option>
                  <?php  foreach ($allOrigenes as $unOrigen): ?>                                    
                    <option value="<?php echo $unOrigen['id']; ?>">
                     <?php echo $unOrigen['descripcion'];?>
                    </option>                    
                  <?php endforeach; ?>                   
                </select>
              </div>
              <div class="col-md-6">
              <label for="sel_tipo_proveedor" class="control-label">Tipo proveedor: <sup>(opcional)</sup></label>
                <select class="form-control" name="sel_tipo_proveedor" id="sel_tipo_proveedor" data-width="100%" onchange="javascript:tipoProveeSelec();" disabled>
                  <option value="" disabled selected>Seleccione un tipo de proveedor:</option>
                  <option value="1">Proveedor regular</option>
                  <option value="2">Proveedor de maquinarias</option>
                  <option value="3">Otro</option>                 
                </select>            
              </div>
            </div>
          <br>
            <div class="row">
              <div class="col-md-6">
                <label for="inp_num_factura" class="control-label">N° de factura: <sup>(opcional)</sup></label>
                <input class="form-control" type="text" name="inp_num_factura" id="inp_num_factura" placeholder="N° de factura:" data-width="100%" maxlength="240" disabled>                  
              </div>      
              <div class="col-md-6">
                <label for="inp_num_remito" class="control-label">N° de remito: <sup>(opcional)</sup></label>
                <input class="form-control" type="text" name="inp_num_remito" id="inp_num_remito" placeholder="N° de remito:" data-width="100%" maxlength="240" disabled>                  
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <label for="sel_proveedor" id="lbl_sel_proveedor" class="control-label">Proveedor: <sup>(opcional)</sup></label>
                <select class="form-control" data-show-subtext="true" data-live-search="true" name="sel_proveedor" id="sel_proveedor" data-width="100%" onclick="" required disabled>
                  <option value="" disabled selected >Seleccione un proveedor:</option>>                   
                </select>
            <label hidden for="proveedor_especificar" id="lbl_proveedor_especificar">Especifique un proveedor:</label>
            <textarea type="text" class="form-control" placeholder="Descripción" id="proveedor_especificar" maxlength="245" style="resize: none; height: 106.5px;" disabled hidden></textarea>
          </div> 
        </div>


        <hr>
        <div class="row">
          <div class="col-md-4">
            <label for="sel_tipo_moneda_egre" class="control-label">Tipo moneda:</label>
            <select class="form-control" name="sel_tipo_moneda_egre" id="sel_tipo_moneda_egre" data-width="100%" disabled onchange="javascript:monedaEgreSelec();">
              <option value="" disabled selected>Seleccione un tipo de moneda:</option>                                  
              <?php foreach ($allTipoMoneda as $unTipoMoneda) : 
                if ($unTipoMoneda['id'] != 1) { ?>
              <option disabled value="<?php echo $unTipoMoneda['id'];?>"><?php echo $unTipoMoneda['codigo']." - ".$unTipoMoneda['divisa'];?></option>
              <?php } else { ?>
              <option value="<?php echo $unTipoMoneda['id'];?>"><?php echo $unTipoMoneda['codigo']." - ".$unTipoMoneda['divisa'];?></option>
              <?php } endforeach; ?>                                        
            </select>
          </div>
          <div class="col-md-4">
            <label for="inp_monto_egre" class="control-label">Monto:</label>
            <input class="form-control" type="number" min="0" name="inp_monto_egre" id="inp_monto_egre" data-width="100%" placeholder="Ingrese un monto" disabled>
          </div>
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
          <div class="col-md-6">                                        
            <button type="button" class="btn btn-outline-light" style="border: 1px solid white;" id="btn_agregar_egreso">Aceptar</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!----- FIN MODAL EGRESO ----->

<!--------------- MODAL BALANCE ------------------>
<div class="modal bd-example-modal-lg" id="modal-balance" tabindex="-1" role="dialog" aria-labelledby="modal-balance-label" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content bg-info">
      <div class="modal-header">
        <h3 class="modal-title">Saldo de caja</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-2">
            <button type="button" class="btn btn-outline-light btn-block" data-dismiss="modal" style="border: 1px solid black;">Cerrar caja</button>
          </div>
          <div class="col-md-10">
            <!--<h5><?php //echo "Fondo inicio + Ingresos - Egresos = Saldo -> $ ".$caja['fondo_inicio']." + $ ".$sumaIngresos." - $ ".$sumaEgresos." = $ ".$balanceCaja;?></h5>-->
            <h5><?php echo "Fecha de inicio y Hora de inicio: ".$caja['fecha_inicio']." ".$caja['hora_inicio'];?></h5>
          </div>
        </div>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-outline-light" data-dismiss="modal" style="border: 1px solid black;">Cerrar</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<!--------------- END MODAL BALANCE -------------->

<!----- MODAL MODIFICAR INGRESO ----->
<div class="modal bd-example-modal-lg" id="modal_editar_ingresos" tabindex="-1" role="dialog" aria-labelledby="modal_editar_ingreso" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">        
        <h3 class="modal-title">Editar ingreso</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="text" name="inp_edit_ingreso_id" id="inp_edit_ingreso_id" value="" hidden>
        <input type="text" name="inp_edit_ingreso_caja_id" id="inp_edit_ingreso_caja_id" value="" hidden>
        <div class="row">
          <div class="col-md-8">
            <div class="row">
              <div class="col-md-6">
                <label for="sel_edit_origen_ingre" class="control-label">Origen:</label>
                <select class="form-control" data-show-subtext="true" data-live-search="true" name="sel_edit_origen_ingre" id="sel_edit_origen_ingre" data-width="100%" onclick="javascript:origenSeleccionado();" required>
                  <option value="" disabled selected >Seleccione un origen:</option>
                  <?php  foreach ($allOrigenes as $unOrigen): ?>                                    
                    <option value="<?php echo $unOrigen['id']; ?>">
                     <?php echo $unOrigen['descripcion'];?>
                    </option>                    
                  <?php endforeach; ?>                   
                </select>
              </div>
              <div class="col-md-6">
              <label for="sel_edit_tipo_ingreso_ingre" class="control-label">Tipo de ingreso:</label>
              <select class="form-control" name="sel_tipo_ingreso" id="sel_edit_tipo_ingreso_ingre" data-width="100%" onchange="javascript:tipoIngresoSeleccionadoEdit();" required disabled>
                  <option value="" disabled selected >Seleccione un tipo de ingreso:</option>                                 
                    <option value="1">De personal</option>
                    <option value="2">De caja maquinarias</option>
                    <option value="3">De cliente regular</option>
                    <option value="4">De cliente máquina</option>
                    <option value="5">Otro</option>                                        
                </select>            
              </div>
            </div>
          <br>
            <div class="row">
              <div class="col-md-6">
                <label for="sel_edit_personal_ingre" class="control-label">Personal:</label>
                <select class="form-control" name="sel_edit_personal_ingre" id="sel_edit_personal_ingre" data-width="100%" disabled onchange="javascript:huboSeleccionEdit();">
                  <option value="" disabled selected >Seleccione un personal:</option>                 
                </select>
              </div>      
              <div class="col-md-6">
                <label for="sel_edit_cliente_ingre" class="control-label">Cliente:</label>
                <select class="form-control" name="sel_edit_cliente_ingre" id="sel_edit_cliente_ingre" data-width="100%" disabled onchange="javascript:huboSeleccionEdit();">
                  <option value="" disabled selected >Seleccione un cliente:</option>                                        
                </select>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <label>Otro <sup>(opcional)</sup>:</label>
            <textarea type="text" class="form-control" placeholder="Descripción" id="otro_edit_ingre" maxlength="250" style="resize: none; height: 107.5px;" disabled></textarea>
          </div> 
        </div>
        <hr>
        <div class="row">
          <div class="col-md-4">
            <label for="sel_edit_tipo_moneda_ingre" class="control-label">Tipo moneda:</label>
            <select class="form-control" name="sel_edit_tipo_moneda_ingre" id="sel_edit_tipo_moneda_ingre" data-width="100%" disabled onchange="javascript:goMonto();">
              <option value="" disabled >Seleccione un tipo de moneda:</option>                                  
              <?php foreach ($allTipoMoneda as $unTipoMoneda) : 
                if ($unTipoMoneda['id'] != 1) { ?>
              <option disabled value="<?php echo $unTipoMoneda['id'];?>"><?php echo $unTipoMoneda['codigo']." - ".$unTipoMoneda['divisa'];?></option>
              <?php } else { ?>
              <option selected value="<?php echo $unTipoMoneda['id'];?>"><?php echo $unTipoMoneda['codigo']." - ".$unTipoMoneda['divisa'];?></option>
              <?php } endforeach; ?>                                        
            </select>
          </div>
          <div class="col-md-4">
            <label for="inp_edit_monto_ingre" class="control-label">Monto:</label>
            <input class="form-control" type="number" min="0" name="inp_edit_monto_ingre" id="inp_edit_monto_ingre" data-width="100%" placeholder="Ingrese un monto" disabled>
          </div>
          <div class="col-md-4">
            <label for="edit_fecha_ing" class="control-label">Fecha:</label>
            <input type="text" class="form-control" name="edit_fecha_ing" id="edit_fecha_ing" readonly>
            <script>
              $('#edit_fecha_ing').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                language: 'es',
                toggleActive: true
              });
            </script>               
          </div>
        </div>
      </div>
      <div class="modal-footer">
      <br>
        <div class="row">          
          <div class="col-md-6">            
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
          </div>
          <div class="col-md-6">                                        
            <button type="button" class="btn btn-success" id="btn_editar_ingreso">Aceptar</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!----- FIN MODAL MODIFICAR INGRESO ----->

<!----- INICIO MODAL MODIFICAR EGRESO ----->
<div class="modal bd-example-modal-lg" id="modal_editar_egresos" tabindex="-1" role="dialog" aria-labelledby="modal_editar_egreso" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">        
        <h3 class="modal-title">Editar Egreso</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="text" name="inp_edit_egreso_id" id="inp_edit_egreso_id" value="" hidden>
        <input type="text" name="inp_edit_egreso_caja_id" id="inp_edit_egreso_caja_id" value="" hidden>
        <div class="row">
          <div class="col-md-8">
            <div class="row">
              <div class="col-md-6">
                <label for="fecha_edit_egre" class="control-label">Fecha:</label>
                <input type="text" class="form-control" name="fecha_edit_egre" id="fecha_edit_egre" readonly>
                <script>
                  $('#fecha_edit_egre').datepicker({
                    format: 'dd-mm-yyyy',
                    autoclose: true,
                    language: 'es',
                    toggleActive: true
                  });
                </script>
              </div>
              <div class="col-md-6">
              <label for="sel_edit_personal_egre" class="control-label">Personal:</label>
                <select class="form-control" name="sel_edit_personal_egre" id="sel_edit_personal_egre" data-width="100%">
                  <option value="" disabled selected >Seleccione un personal:</option>
                  <?php foreach ($allPersonal as $unPersonal) : ?>
                    <option value="<?php echo $unPersonal['id']; ?>"><?php echo $unPersonal['apellido'].", ".$unPersonal['nombre']; ?></option>
                  <?php endforeach ; ?>
                </select>            
              </div>
            </div>
          <br>
            <div class="row">
              <div class="col-md-6">
                <label for="sel_edit_concepto_egre" class="control-label">Concepto:</label>
                <select class="form-control" name="sel_edit_concepto_egre" id="sel_edit_concepto_egre" data-width="100%" onchange="conceptoEditEgreSelec();">
                  <option value="" disabled selected >Seleccione un concepto:</option>
                  <?php foreach ($allConceptos as $unConcepto) : 
                  if ($unConcepto['id'] == 12) { ?>
                  <option value="<?php echo $unConcepto['id'];?>"><?php echo "---".$unConcepto['descripcion']."---";?></option>
                  <?php } else { ?>
                  <option value="<?php echo $unConcepto['id'];?>"><?php echo $unConcepto['descripcion'];?></option>
                  <?php } endforeach; ?>                                       
                </select>
              </div>      
              <div class="col-md-6">
                <label hidden for="inp_edit_egre_combustible_lt" id="lbl_edit_egre_combustible_lt" class="control-label">Litros de combustible:</label>
                <input hidden class="form-control" type="number" name="inp_edit_egre_combustible_lt" id="inp_edit_egre_combustible_lt" data-width="100%" placeholder="Ingrese una cantidad:" disabled>
                <label hidden for="inp_edit_concepto_aclaracion_egre" id="lbl_edit_concepto_aclaracion_egre" class="control-label">Aclaración:</label>
                <input hidden class="form-control" type="text" name="inp_edit_concepto_aclaracion_egre" id="inp_edit_concepto_aclaracion_egre" data-width="100%" placeholder="Ingrese una aclaración:" disabled>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <label>Especifique concepto: <sup>(opcional)</sup></label>
            <textarea type="text" class="form-control" placeholder="Descripción" id="edit_concepto_especificar" maxlength="250" style="resize: none; height: 107.5px;" disabled></textarea>
          </div> 
        </div>
        <hr>


        <div class="row">
          <div class="col-md-8">
            <div class="row">
              <div class="col-md-6">
                <label for="sel_edit_origen_egre" class="control-label">Origen:</label>
                <select class="form-control" data-show-subtext="true" data-live-search="true" name="sel_edit_origen_egre" id="sel_edit_origen_egre" data-width="100%" required>
                  <option value="" disabled selected >Seleccione un origen:</option>
                  <?php  foreach ($allOrigenes as $unOrigen): ?>                                    
                    <option value="<?php echo $unOrigen['id']; ?>">
                     <?php echo $unOrigen['descripcion'];?>
                    </option>                    
                  <?php endforeach; ?>                   
                </select>
              </div>
              <div class="col-md-6">
              <label for="sel_edit_tipo_proveedor" class="control-label">Tipo proveedor: <sup>(opcional)</sup></label>
                <select class="form-control" name="sel_edit_tipo_proveedor" id="sel_edit_tipo_proveedor" data-width="100%" onchange="javascript:tipoProveeSelecEgre();">
                  <option value="" selected>Seleccione un tipo de proveedor:</option>
                  <option value="1">Proveedor regular</option>
                  <option value="2">Proveedor de maquinarias</option>
                  <option value="3">Otro</option>                 
                </select>            
              </div>
            </div>
          <br>
            <div class="row">
              <div class="col-md-6">
                <label for="inp_edit_num_factura" class="control-label">N° de factura: <sup>(opcional)</sup></label>
                <input class="form-control" type="text" name="inp_edit_num_factura" id="inp_edit_num_factura" placeholder="N° de factura:" data-width="100%" maxlength="240">                  
              </div>      
              <div class="col-md-6">
                <label for="inp_edit_num_remito" class="control-label">N° de remito: <sup>(opcional)</sup></label>
                <input class="form-control" type="text" name="inp_edit_num_remito" id="inp_edit_num_remito" placeholder="N° de remito:" data-width="100%" maxlength="240">                  
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <label for="sel_edit_proveedor" id="lbl_edit_sel_proveedor" class="control-label">Proveedor: <sup>(opcional)</sup></label>
                <select class="form-control" data-show-subtext="true" data-live-search="true" name="sel_edit_proveedor" id="sel_edit_proveedor" data-width="100%" onclick="" required>
                  <option value="" disabled selected >Seleccione un proveedor:</option>>                   
                </select>
            <label hidden for="edit_proveedor_especificar" id="lbl_edit_proveedor_especificar">Especifique un proveedor:</label>
            <textarea type="text" class="form-control" placeholder="Descripción" id="edit_proveedor_especificar" maxlength="245" style="resize: none; height: 106.5px;" hidden></textarea>
          </div> 
        </div>


        <hr>
        <div class="row">
          <div class="col-md-4">
            <label for="sel_edit_tipo_moneda_egre" class="control-label">Tipo moneda:</label>
            <select class="form-control" name="sel_edit_tipo_moneda_egre" id="sel_edit_tipo_moneda_egre" data-width="100%" >
              <option value="" disabled>Seleccione un tipo de moneda:</option>                                  
              <?php foreach ($allTipoMoneda as $unTipoMoneda) : 
                if ($unTipoMoneda['id'] != 1) { ?>
              <option disabled value="<?php echo $unTipoMoneda['id'];?>"><?php echo $unTipoMoneda['codigo']." - ".$unTipoMoneda['divisa'];?></option>
              <?php } else { ?>
              <option selected value="<?php echo $unTipoMoneda['id'];?>"><?php echo $unTipoMoneda['codigo']." - ".$unTipoMoneda['divisa'];?></option>
              <?php } endforeach; ?>                                        
            </select>
          </div>
          <div class="col-md-4">
            <label for="inp_edit_monto_egre" class="control-label">Monto:</label>
            <input class="form-control" type="number" min="0" name="inp_edit_monto_egre" id="inp_edit_monto_egre" data-width="100%" placeholder="Ingrese un monto">
          </div>
          <div class="col-md-4">
                       
          </div>
        </div>
      </div>
      <div class="modal-footer">
      <br>
        <div class="row">          
          <div class="col-md-6">            
            <button type="button" class="btn btn-danger"  data-dismiss="modal">Cancelar</button>
          </div>
          <div class="col-md-6">                                        
            <button type="button" class="btn btn-success" id="btn_editar_egreso">Actualizar</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!----- FIN MODAL MODIFICAR EGRESO ----->


  
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
  <script src="dist/js/pages/dashboard.js"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="dist/js/demo.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
</html>

<?php if(isset($db)) { $db->db_disconnect(); } ?>

