<?php
  require_once('includes/load.php');
  page_require_level(2);
  $current_user_ok = current_user();
  $all_matsInsu = find_all('inv_materiales_insumos');
?>

<!-- datatable -->
<script type="text/javascript">
    $(document).ready(function() {
        $('#faltaMatInsu').DataTable();
    } );
</script>

<div class="table-responsive">
  <table id="faltaMatInsu" class="table table-hover table-condensed table-bordered">
    <thead>
      <tr>
        <th style="width: 5px;">Cod. IJS</th>
        <th style="width: 100px;"><b>Marca</b> - <em>Tipo - [Cod.]</em></th> 
        <th style="width: 60px;">Descripción</th>
        <th style="width: 20px;">Cant. disp. / TOTAL</th> 
        <th style="width: 15px;">Cant. min. disp.</th>
        <th style="width: 10px;">Uni.</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($all_matsInsu as $un_matInsu):
        if ($un_matInsu['cant_disp'] < $un_matInsu['cant_min']) {?>
        <tr>
          <td class="text-center"><?php echo $un_matInsu['id'];?></td>
          <td><b><?php 
            echo $un_matInsu['marca']; ?></b>
            <em><?php
            if (!empty($un_matInsu['tipo']))
              echo ' - '.$un_matInsu['tipo'];
            if (!empty($un_matInsu['cod']))
              echo ' - [Cod.: '.$un_matInsu['cod'].']';?>
            </em>
          </td>
          <td><?php 
          if (!empty($un_matInsu['descripcion']))
            echo $un_matInsu['descripcion'];
          else
            echo ' - ';?></td>
          <td class="text-center"><?php echo $un_matInsu['cant_disp']. " / ".$un_matInsu['cant'];?></td>
          <td class="text-center">
            <b><span style="color:#EC1919;"><?php echo $un_matInsu['cant_min']; ?></span></b>
          <td class="text-center"><?php 
          if (!empty($un_matInsu['unidad']))
            echo $un_matInsu['unidad'];
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