<?php
	$page_title = 'Gestión de reparaciones';
	require_once('includes/load.php');
	include_once('layouts/newHeader.php');
  // Checkin What level user has permission to view this page
	page_require_level(2);
 ?>
<style type="text/css">
 	.gradient {
  		/*background: -webkit-linear-gradient(80deg,orange 50%, yellow 50%);*/
  		/*background: -webkit-linear-gradient(135deg, yellow 10%, orange);*/
  		background: -webkit-linear-gradient(30deg, orange, yellow);
  		-webkit-background-clip: text;
  		-webkit-text-fill-color: transparent;
	}
</style>
<script type="text/javascript">
  $(window).load(function() {
    $('.loader2').fadeOut(2000);
  });

  $(document).ready(function() {
    $('#contenedor-tablas').load('reparacion_tablaReparaciones.php');
  });

	function loadTable(tabla) {
		if (tabla == 'reparaciones') {
      $('#contenedor-tablas').fadeOut(500);
      setTimeout(function() {              
        $('#contenedor-tablas').load('reparacion_tablaReparaciones.php');
        
      },750);
      setTimeout(function() {   
          $('#contenedor-tablas').fadeIn(500);
        },1000);
    }
    if (tabla == 'maquinas') {
      $('#contenedor-tablas').fadeOut(500);
      setTimeout(function() {              
        $('#contenedor-tablas').load('reparacion_tablaMaquinas.php');
        
      },750);
      setTimeout(function() {   
          $('#contenedor-tablas').fadeIn(500);
        },1250);
    }
    if (tabla == 'clientes') {
      $('#contenedor-tablas').fadeOut(500);
      setTimeout(function() {              
        $('#contenedor-tablas').load('reparacion_tablaClientes.php');
        
      },750);
      setTimeout(function() {   
          $('#contenedor-tablas').fadeIn(500);
        },1000);
    }
    if (tabla == 'marcas') {
      $('#contenedor-tablas').fadeOut(500);
      setTimeout(function() {              
        $('#contenedor-tablas').load('reparacion_tablaMarcas.php');
        
      },750);
      setTimeout(function() {   
          $('#contenedor-tablas').fadeIn(500);
        },1000);
    }
    if (tabla == 'estadisticas') {
      $('#contenedor-tablas').fadeOut(500);
      setTimeout(function() {              
        $('#contenedor-tablas').load('reparacion_estadisticas.php');
        
      },750);
      setTimeout(function() {   
          $('#contenedor-tablas').fadeIn(500);
        },1000);
    }
    /*if (tabla == 'repuestos') {
      $('#contenedor-tablas').fadeOut(500);
      setTimeout(function() {              
           $('#contenedor-tablas').load('reparacion_tablaRepuestos.php');
           $('#contenedor-tablas').fadeIn(1000);
      },250);
    }*/
	}

  function reloadPage() {
    location.reload();
  }

  function goBack() {
    location.replace('conAc.php');
  }

</script>
<div id="loader" class="loader2"></div>
<div class="row">
	<div class="col-md-12">
		<iframe src="particulas/demo/index.html" frameborder="0" scrolling="no" name="contenedor" style="border-radius: 20px 40px; height: 50px; width: 100%; box-shadow: 2px 2px 10px 2px rgba(0, 0, 0, 0.6);"></iframe>
		<center>
		<div style="position: relative; width: 60%; text-align: center; color: white; vertical-align: center; margin-top: -48px;"><p class="gradient" style="font-size: 25px;font-weight: 200;">G&emsp;E&emsp;S&emsp;T&emsp;I&emsp;Ó&emsp;N&emsp;&emsp;&emsp;D&emsp;E&emsp;&emsp;&emsp;R&emsp;E&emsp;P&emsp;A&emsp;R&emsp;A&emsp;C&emsp;I&emsp;O&emsp;N&emsp;E&emsp;S</p></div></center>
	</div>
</div>
<br>
<div class="row">
  <div class="col-md-12">
    <div class="pull-right">
      <button class="btn btn-warning" onclick="javascript:reloadPage();"><span class="glyphicon glyphicon-refresh"></span> Recargar página</button>
      <button class="btn btn-danger" onclick="javascript:goBack();"><span class="glyphicon glyphicon-arrow-left"></span> Volver</button>
    </div>
  </div>
</div>
<ul class="nav nav-tabs" id="myTab">
  <li class="active">
    <a data-toggle="tab" role="tab" href="#contenedor-tablas" onclick="javascript:loadTable('reparaciones');">Ver por reparaciones</a>
  </li>
  <li>
    <a data-toggle="tab" role="tab" href="#contenedor-tablas" onclick="javascript:loadTable('maquinas');">Ver por máquinas</a>
  </li>
  <li>
  	<a data-toggle="tab" role="tab" href="#contenedor-tablas" onclick="javascript:loadTable('clientes');">Ver por clientes</a>
  </li>
  <p style="font-size: 25px;">|</p>
  <li>
    <a data-toggle="tab" role="tab" href="#contenedor-tablas" onclick="javascript:loadTable('marcas');">Gestionar Marcas y Repuestos</a>
  </li>
  <li>
    <a data-toggle="tab" role="tab" href="#contenedor-tablas" onclick="javascript:loadTable('estadisticas');">Estadísticas</a>
  </li>
  <!--<li>
    <a data-toggle="tab" role="tab" href="#contenedor-tablas" onclick="javascript:loadTable('repuestos');">Gestionar Repuestos</a>
  </li>-->
</ul>
<br>
<div class="tab-content">
  <div id="contenedor-tablas"></div>
</div>

<script type="text/javascript">
  //Script que permite setear como activo el tab que estaba antes de recargar la pagina
  /*  $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
        localStorage.setItem('activeTab', $(e.target).attr('href'));
    });
    var activeTab = localStorage.getItem('activeTab');
    if(activeTab){
		$('#myTab a[href="' + activeTab + '"]').tab('show');
    }
  //Permite la correcta carga de las columnas de los dataTable a traves de los TABS
    $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
        $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
    });*/

</script>
<?php include_once('layouts/newFooter.php'); ?>
  
















