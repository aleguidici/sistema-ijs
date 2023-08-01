<?php
  require_once('includes/load.php');
  page_require_level(2);
  $current_user_ok = current_user();
  $all_maquin = find_all('inv_maquinarias');

?>

<!-- datatable -->
<script type="text/javascript">
    $(document).ready(function() {
        $('#maquInstru').DataTable();
    } );
</script>

<div class="table-responsive">
  <table id="maquInstru" class="table table-hover table-condensed table-bordered">
    <thead>
      <tr>
        <th style="width: 6px;">Cod. IJS</th>
        <th style="width: 90px;"><b>Marca</b> - <em>Modelo - [Nº serie]</em></th>
        <th style="width: 90px;">Descripción</th>
        <th style="width: 22px;">Tipo</th>
        <th style="width: 12px;">Año</th>
        <th style="width: 22px;">Estado actual</th>
        <?php if($current_user_ok['user_level'] <= 1) : ?>
          <th class="text-center" style="width: 10px;"></th>
        <?php endif; ?>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($all_maquin as $una_maquinaria):?>
        <tr>
          <td class="text-center"><?php echo $una_maquinaria['id'];?></td>
          <td><b><?php 
            echo $una_maquinaria['marca']; ?></b>
            <em><?php
            if (!empty($una_maquinaria['modelo']))
              echo ' - '.$una_maquinaria['modelo'];
            if (!empty($una_maquinaria['num_serie']))
              echo ' - [Nº serie: '.$una_maquinaria['num_serie'].']';?>
            </em>
          </td>
          <td><?php 
          if (!empty($una_maquinaria['descripcion']))
            echo $una_maquinaria['descripcion'];
          else
            echo ' - ';?></td>
          <td class="text-center"><?php echo $una_maquinaria['tipo'];?></td>
          <td class="text-center"><?php echo $una_maquinaria['anio'];?></td>
          <td class="text-center"><?php 
          $estado_act = find_last_historial($una_maquinaria['id']);
          switch ($estado_act['estado']){
              case "Muy malo":
                echo '<b><span style="color:#6C0000;">5. '.$estado_act['estado'].'</span></b>';
                break;
              case "Malo":
                echo '<b><span style="color:#EC1919;">4. '.$estado_act['estado'].'</span></b>';
                break;
              case "Regular":
                echo '<b><span style="color:#EC8F19;">3. '.$estado_act['estado'].'</span></b>';
                break;
              case "Bueno":
                echo '<b><span style="color:#66AD00;">2. '.$estado_act['estado'].'</span></b>';
                break;
              case "Muy bueno":
                echo '<b><span style="color:#088300;">1. '.$estado_act['estado'].'</span></b>';
                break;
          };
          echo '<a href="inventario_historialMaquin.php?id='.$una_maquinaria['id'].'"> (Ver detalles) </a>';?>
          </td>
          
          <?php if ($current_user_ok['user_level'] <= 1) {?>
            <td class="text-center" style="vertical-align:middle">
              <div class="btn-group">
                <a href="edit_maquin.php?id=<?php echo (int)$una_maquinaria['id'];?>" class="btn btn-primary btn-xs"  title="Editar" data-toggle="tooltip">
                  <span class="glyphicon glyphicon-edit"></span>
                </a>
                
                <a href="delete_maquin.php?id=<?php echo (int)$una_maquinaria['id'];?>" class="btn btn-danger btn-xs"  title="Eliminar" data-toggle="tooltip" onclick="return confirm('¿Seguro que desea eliminar esta maquinaria? \nCod. IJS: <?php echo $una_maquinaria['id'];?>')">
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
        <th style="width: 80px;"><b>Marca</b> - <em>Modelo - [Nº serie]</em></th>
        <th style="width: 80px;">Descripción</th>
        <th style="width: 22px;">Tipo</th>
        <th style="width: 22px;">Año</th>
        <th style="width: 22px;">Estado actual</th>
        <?php if($current_user_ok['user_level'] <= 1) : ?>
          <th class="text-center" style="width: 10px;"></th>
        <?php endif; ?>
      </tr>
    </tfoot>
  </table>
</div>