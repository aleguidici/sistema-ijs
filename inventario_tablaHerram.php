<?php
  require_once('includes/load.php');
  page_require_level(2);
  $current_user_ok = current_user();
  $all_herramientas = find_all('inv_herramientas');
?>

<!-- datatable -->
<script type="text/javascript">
    $(document).ready(function() {
        $('#herram').DataTable();
    } );
</script>


<div class="table-responsive">
  <table id="herram" class="table table-hover table-condensed table-bordered">
    <thead>
      <tr>
        <th style="width: 30px;">Cod. IJS</th>
        <th style="width: 80px;"><b>Marca</b> - <em>Tipo - [Cod.]</em></th>
        <th style="width: 80px;">Descripción</th>
        <th style="width: 22px;">Cantidad</th>
        <?php if($current_user_ok['user_level'] <= 1) : ?>
          <th class="text-center" style="width: 10px;"></th>
        <?php endif; ?>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($all_herramientas as $una_herram):?>
        <tr>
          <td class="text-center"><?php echo $una_herram['id'];?></td>
          <td><b><?php 
            echo $una_herram['marca']; ?></b>
            <em><?php
            if (!empty($una_herram['tipo']))
              echo ' - '.$una_herram['tipo'];
            if (!empty($una_herram['cod']))
              echo ' - [Cod.: '.$una_herram['cod'].']';?>
            </em>
          </td>
          <td><?php 
          if (!empty($una_herram['descripcion']))
            echo $una_herram['descripcion'];
          else
            echo ' - ';?></td>
          <td class="text-center"><?php echo $una_herram['cant'];?></td>
      
          <?php if ($current_user_ok['user_level'] <= 1) {?>
            <td class="text-center" style="vertical-align:middle">
              <div class="btn-group">
                <a href="edit_herram.php?id=<?php echo (int)$una_herram['id'];?>" class="btn btn-primary btn-xs"  title="Editar" data-toggle="tooltip">
                  <span class="glyphicon glyphicon-edit"></span>
                </a>
                
                <a href="delete_herram.php?id=<?php echo (int)$una_herram['id'];?>" class="btn btn-danger btn-xs"  title="Eliminar" data-toggle="tooltip" onclick="return confirm('¿Seguro que desea eliminar esta herramienta? \nCod. IJS: <?php echo $una_herram['id'];?>')">
                  <span class="glyphicon glyphicon-trash"></span>
                </a>
              </div>
            </td>
          <?php } ?>
        </tr>
      <?php endforeach; ?>
    </tbody>
    <tfoot>
      <tr>
        <th style="width: 30px;">Cod. IJS</th>
        <th style="width: 80px;"><b>Marca</b> - <em>Tipo - [Cod.]</em></th> 
        <th style="width: 80px;">Descripción</th>
        <th style="width: 22px;">Cantidad</th>
        <?php if($current_user_ok['user_level'] <= 1) : ?>
          <th class="text-center" style="width: 10px;"></th>
        <?php endif; ?>
      </tr>
    </tfoot>
  </table>
</div>