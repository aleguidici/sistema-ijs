<?php
  require_once('includes/load.php');
  page_require_level(2);
  $current_user_ok = current_user();
  $all_matsInsu_ajenos = find_materiales_proyecto($_GET['id']);
  $imagenes_project = find_all_imagenes('2');
?>


<script type="text/javascript">
    $(document).ready(function() {
        $('#imagenesProject').DataTable();
    } );
</script>

<div class="table-responsive">
  <table id="imagenesProject" class="table table-hover table-condensed table-bordered">
    <thead>
      <tr>
        <th style="width: 90px;">Miniatura</th> 
        <th style="width: 115px;">Descripción</th>
        <th style="width: 40px;">Fecha imagen</th> 
        
        <?php if ($current_user_ok['user_level'] <= 1) {?>
          <th style="width: 20px;"></th>
        <?php }?>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($imagenes_project as $una_imagen):
        $datos_imagen = find_datos_imagen($_GET['id'], $una_imagen['id']);
        if(!empty($datos_imagen)){ ?>
          <tr class="primary">
            <td class="text-center">

              <a target="_blank" href="uploads/imagenes/<?php echo $una_imagen['file_name'];?>">
                <img src="uploads/imagenes/<?php echo $una_imagen['file_name'];?>" class="img-thumbnail" style="width: 200px; height:auto; border:1px solid #000000 "/>
              </a>
            </td>

            <div class="modal fade" id="verImg" tabindex="-1" role="dialog" aria-labelledby="modalInfooo" aria-hidden="true" modal-content="width: 100%;">
              <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-body">
                    <img class="modal-content img-responsive" id="img01">
                    <div id="caption"></div>
                    <div id="caption2"></div>
                  </div>
                </div>
              </div>
            </div>

            <td class="text-center">
              <?php echo $datos_imagen['descripcion'];?>
            </td>
            <td class="text-center">
              <?php 
              list($año, $mes, $dia) = explode('-', $datos_imagen['fecha_actualizacion']);
              echo remove_junk($dia."/".$mes."/".$año);
              ?>  
            </td>
            <?php if ($current_user_ok['user_level'] <= 1) {?>
              <td class="text-center">
                <a href="conAc_deleteImagen.php?id=<?php echo (int) $una_imagen['id'];?> &proyecto=<?php echo $_GET['id'];?>" class="btn btn-xs btn-danger"  title="Eliminar" data-toggle="tooltip" onclick="return confirm('¿Seguro que desea eliminar esta imagen?')">
                  <span class="glyphicon glyphicon-trash"></span>
                </a>
              </td>
            <?php } ?>
            </tr>
          <?php }
      endforeach; ?>
    </tbody>
    <tfoot>
      <tr>
        <th style="width: 90px;">Miniatura</th> 
        <th style="width: 115px;">Descripción</th>
        <th style="width: 40px;">Fecha imagen</th> 
        <?php if ($current_user_ok['user_level'] <= 1) {?>
          <th style="width: 20px;"></th>
        <?php }?>
      </tr>
    </tfoot>
  </table>
</div>
