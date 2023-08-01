<?php
  require_once('includes/load.php');
  page_require_level(2);
  $current_user_ok = current_user();
  $all_movimientos = find_all('movimientos');
?>

<!-- datatable -->
<script type="text/javascript">
    $(document).ready(function() {
        $('#movimientosMatInsu').DataTable();
    } );
</script>

<div class="table-responsive">
  <table id="movimientosMatInsu" class="table table-hover table-condensed table-bordered">
    <thead>
      <tr>
        <th style="width: 5px;">Cod. IJS</th>
        <th style="width: 100px;"><b>Marca</b> - <em>Tipo - [Cod.]</em></th> 
        <th style="width: 60px;">Descripción</th>
        <th style="width: 20px;">Cant. disp. / TOTAL</th> 
        <th style="width: 15px;">Cant. min. disp.</th>
        <th style="width: 10px;">Uni.</th>
        <th style="width: 10px;">Creado por</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($all_movimientos as $un_movMatInsu):?>
        <tr>
          <td class="text-center"><?php echo $un_movMatInsu['id'];?></td>
          <td><b><?php 
            echo $un_movMatInsu['marca']; ?></b>
            <em><?php
            if (!empty($un_movMatInsu['tipo']))
              echo ' - '.$un_movMatInsu['tipo'];
            if (!empty($un_movMatInsu['cod']))
              echo ' - [Cod.: '.$un_movMatInsu['cod'].']';?>
            </em>
          </td>
          <td><?php 
          if (!empty($un_movMatInsu['descripcion']))
            echo $un_movMatInsu['descripcion'];
          else
            echo ' - ';?></td>
          <td class="text-center"><?php echo $un_movMatInsu['cant_disp']. " / ".$un_movMatInsu['cant'];?></td>
          <td class="text-center">
            <b><span style="color:#EC1919;"><?php echo $un_movMatInsu['cant_min']; ?></span></b>
          <td class="text-center"><?php 
          if (!empty($un_movMatInsu['unidad']))
            echo $un_movMatInsu['unidad'];
          else
            echo ' - ';?></td>          
        </tr>
      <?php }
      endforeach; ?>
    </tbody>
    <tfoot>
      <tr>
        <th style="width: 5px;">Cod. IJS</th>
        <th style="width: 100px;"><b>Marca</b> - <em>Tipo - [Cod.]</em></th> 
        <th style="width: 60px;">Descripción</th>
        <th style="width: 25px;">Cant. disp. / TOTAL</th> 
        <th style="width: 15px;">Cant. min. disp.</th>
        <th style="width: 10px;">Uni.</th>
      </tr>
    </tfoot>
  </table>
</div>