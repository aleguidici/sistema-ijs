<?php
  $page_title = 'Reparaciones de Máquinas Eléctricas';
  require_once('includes/load.php');
  page_require_level(2);
  $current_user_ok = current_user();
?>

<?php include_once('layouts/newHeader.php'); ?>
<h2><b>Máquinas Eléctricas</b></h2>
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>
<ul class="nav nav-tabs" id="tabMaquinas">
  <li class="active">
    <a data-toggle="tab" role="tab" href="#menu1" onclick="javascript:loadMenu(1);">Reparaciones</a>
  </li>
  <!-- <li><a data-toggle="tab" href="#menu2">Órdenes de servicio</a></li> -->
  <li>
    <a data-toggle="tab" role="tab" href="#menu2" onclick="javascript:loadMenu(2);">Venta de repuestos</a>
  </li>
  <!--<li>
    <a data-toggle="tab" role="tab" href="#menu4" onclick="javascript:reloadPage();">Máquinas Eléctricas</a>
  </li>-->
</ul>
<div class="tab-content">
<!-- Solapa de Proyectos -->
  <div id="menu1" class="tab-pane fade in active"></div>
  <!-- Solapa de Mediciones de PaT -->
  <div id="menu2" class="tab-pane fade">  </div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
    $('#menu1').load('conAc_maquinasElectricasReparaciones.php');
  });

  function loadMenu(menu){
  	if (menu == 1) {
  		$('#menu1').load('conAc_maquinasElectricasReparaciones.php');
  	}
  	if (menu == 2) {
  		$('#menu2').load('conAc_maquinasElectricasVentasRepuestos.php');
  	}
  }
</script>
    

<?php include_once('layouts/newFooter.php'); ?>