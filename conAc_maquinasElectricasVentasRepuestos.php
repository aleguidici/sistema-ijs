<?php
  $page_title = 'Venta de repuestos de Máquinas Eléctricas';
  require_once('includes/load.php');
  page_require_level(2);
  $current_user_ok = current_user();
?>

<br>
<div class="pull-right">
  <div class="btn-group">
    <!--<button class="btn btn-info" onclick="javascript:goGestion();"><span class="glyphicon glyphicon-new-window"> </span> Gestión </button></div>&emsp;-->
    <a href="#" type="button" class="btn btn-info" id="a_modal-control-access" data-toggle="modal" data-target="#modal-control-access"><span class="glyphicon glyphicon-new-window"></span> Gestión </a>&emsp;
  </div>
  <div class="btn-group btn-group-toggle" data-toggle="buttons">
    <label class="btn btn-warning active">
      <input type="radio" name="options" id="option1" autocomplete="off" onchange="javascript:minTablaMaquinas();" checked>Vista normal <span class="glyphicon glyphicon-resize-small"> </span>
    </label>
    <label class="btn btn-warning">
      <input type="radio" name="options" id="option2" autocomplete="off" onchange="javascript:fullTablaMaquinas();"> <span class="glyphicon glyphicon-resize-full"> </span> Vista completa 
    </label>
  </div>
 <!-- <a href="" class="btn btn-warning">Mostrar todas las reparaciones</a> -->
</div>