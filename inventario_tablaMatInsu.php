<?php
  require_once('includes/load.php');
  page_require_level(2);
  $current_user_ok = current_user();
  $all_matsInsu = find_all('inv_materiales_insumos');
  $todos = $_GET['todos'];
?>

<!-- datatable -->

<div class="progress" id="progre">
      <div id="dynamic" class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 5%">
      </div>
    </div>

    <script>
      $(function() {
        var current_progress = 5;
        var interval = setInterval(function() {
            current_progress += 10;
            $("#dynamic")
            .css("width", current_progress + "%")
            .attr("aria-valuenow", current_progress);
            if (current_progress >= 100)
                $("#progre").hide();
        }, 250);
      });
    </script>

<script type="text/javascript">
    $(document).ready(function() {
        $('#matsInsu').DataTable();
    } );
</script>

<div id="divMat" class="table-responsive">
  <table id="matsInsu" class="table table-hover table-condensed table-bordered">
    <thead>
      <tr>
        <th style="width: 20px;">Cod. IJS</th>
        <th style="width: 25%;">Descripción</th>
        <th style="width: 30%;"><b>Marca</b> - <em>Tipo - [Cod.]</em></th> 
        <th style="width: 55px;">Cant. disp. / TOTAL</th> 
        <th style="width: 50px;">Cant. min. disp.</th>
        <th style="width: 40px;">Precio lista U$S</th>
        <?php if($current_user_ok['user_level'] <= 1 || $current_user_ok['id'] == 15) : ?>
          <th class="text-center" style="width: 63px;"></th>
        <?php endif; ?>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($all_matsInsu as $un_matInsu):
        if (((int)$todos == 1) || ($un_matInsu['cant'] > 0)){?>
        <tr>
          <td class="text-center"><?php echo $un_matInsu['id'];?></td>
          <td><?php 
          if (!empty($un_matInsu['descripcion']))
            echo $un_matInsu['descripcion'];
          else
            echo ' - ';?></td>
          <td><b><?php 
            echo $un_matInsu['marca']; ?></b>
            <em><?php
            if (!empty($un_matInsu['tipo']))
              echo ' - '.$un_matInsu['tipo'];
            if (!empty($un_matInsu['cod']))
              echo ' - [Cod.: '.$un_matInsu['cod'].']';?>
            </em>
          </td>
          <td class="text-center">
            <?php if ($un_matInsu['cant_disp'] >= $un_matInsu['cant_min']){
              ?><span style="color:#088300;"><?php echo $un_matInsu['cant_disp']?></span><?php
            } else {
              ?><span style="color:#EC1919;"><?php echo $un_matInsu['cant_disp']?></span><?php
            }?></span>
            <b><?php echo " / ".$un_matInsu['cant'];?></b><?php echo " ".$un_matInsu['unidad'];?>
          </td>
          <td class="text-center"><b><?php 
            if ($un_matInsu['cant_min'] > 0)
               echo $un_matInsu['cant_min']." ".$un_matInsu['unidad'];
            else
              echo ' - ';
            ?>
            </b>
          </td>
          <td class="text-center"><?php 
          if ($un_matInsu['precio_lista'] > 0)
            echo str_replace('.', ',',remove_junk($un_matInsu['precio_lista']));
          else
            echo ' - ';?></td>
      
          <?php if ($current_user_ok['user_level'] <= 1 || $current_user_ok['id'] == 15) {?>
            <td class="text-center" style="vertical-align:middle">
              <div class="btn-group">
                <a href="ingreso_matInsu.php?id=<?php echo (int)$un_matInsu['id'];?>" class="btn btn-success btn-xs"  title="Nuevo Ingreso" data-toggle="tooltip">
                  <span class="glyphicon glyphicon-plus"></span>
                </a>
                <a href="edit_matInsu.php?id=<?php echo (int)$un_matInsu['id'];?>" class="btn btn-primary btn-xs"  title="Editar" data-toggle="tooltip">
                  <span class="glyphicon glyphicon-edit"></span>
                </a>
                <a href="delete_matInsu.php?id=<?php echo (int)$un_matInsu['id'];?>" class="btn btn-danger btn-xs"  title="Eliminar" data-toggle="tooltip" onclick="return confirm('¿Seguro que desea eliminar el material / insumo? \nCod. IJS: <?php echo $un_matInsu['id'];?>')">
                  <span class="glyphicon glyphicon-trash"></span>
                </a>
              </div>
            </td>
          <?php } ?>
        </tr>
      <?php }
      endforeach; ?>
    </tbody>
    <tfoot>
      <tr>
        <th style="width: 20px;">Cod. IJS</th>
        <th style="width: 25%;">Descripción</th>
        <th style="width: 30%;"><b>Marca</b> - <em>Tipo - [Cod.]</em></th> 
        <th style="width: 55px;">Cant. disp. / TOTAL</th> 
        <th style="width: 50px;">Cant. min. disp.</th>
        <th style="width: 40px;">Precio lista U$S</th>
        <?php if($current_user_ok['user_level'] <= 1 || $current_user_ok['id'] == 15) : ?>
          <th class="text-center" style="width: 63px;"></th>
        <?php endif; ?>
      </tr>
    </tfoot>
  </table>
</div>