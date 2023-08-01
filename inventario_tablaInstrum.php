<?php
  require_once('includes/load.php');
  page_require_level(2);
  $current_user_ok = current_user();
  $all_instrum = find_all('inv_instrumentos');

?>

<!-- datatable -->
<script type="text/javascript">
    $(document).ready(function() {
        $('#instrum').DataTable();
    } );
</script>

<div class="table-responsive">
  <table id="instrum" class="table table-hover table-condensed table-bordered">
    <thead>
      <tr>
        <th style="width: 6px;">Cod. IJS</th>
        <th style="width: 220px;"><b>Marca</b> - <em>Modelo - [Nº serie]</em></th>
        <th style="width: 20px;">Fecha última calibración</th>
        <?php if($current_user_ok['user_level'] <= 1) : ?>
          <th class="text-center" style="width: 10px;"></th>
        <?php endif; ?>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($all_instrum as $un_instrum):?>
        <tr>
          <td class="text-center" id="<?php echo (int) $un_instrum['nro_instrumento']?>" name="<?php echo (int)$un_instrum['nro_instrumento']?>"><?php echo $un_instrum['nro_instrumento'];?></td>
          <td><b><?php 
            echo $un_instrum['marca']; ?></b>
            <em><?php
            if (!empty($un_instrum['modelo']))
              echo ' - '.$un_instrum['modelo'];
            if (!empty($un_instrum['num_serie']))
              echo ' - [Nº serie: '.$un_instrum['num_serie'].']';?>
            </em>
          </td>
          <td class="text-center"><?php 
            list($año, $mes, $dia) = explode('-', $un_instrum['fecha_calibracion']);
            echo remove_junk($dia."/".$mes."/".$año);?>
          </td>
          
          <?php if ($current_user_ok['user_level'] <= 1) {?>
            <td class="text-center" style="vertical-align:middle">
              <div class="btn-group">
                <a href="edit_instrumento.php?id=<?php echo (int)$un_instrum['nro_instrumento'];?>" class="btn btn-primary btn-xs"  title="Editar" data-toggle="tooltip">
                  <span class="glyphicon glyphicon-edit"></span>
                </a>
                
                <a href="delete_instrumento.php?id=<?php echo (int)$un_instrum['nro_instrumento'];?>" class="btn btn-danger btn-xs"  title="Eliminar" data-toggle="tooltip" onclick="return confirm('¿Seguro que desea eliminar este instrumento? \nCod. IJS: <?php echo $un_instrum['nro_instrumento'];?>')">
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
        <th style="width: 6px;">Cod. IJS</th>
        <th style="width: 90px;"><b>Marca</b> - <em>Modelo - [Nº serie]</em></th>
        <th style="width: 12px;">Fecha última calibración</th>
        <?php if($current_user_ok['user_level'] <= 1) : ?>
          <th class="text-center" style="width: 10px;"></th>
        <?php endif; ?>
      </tr>
    </tfoot>
  </table>
</div>