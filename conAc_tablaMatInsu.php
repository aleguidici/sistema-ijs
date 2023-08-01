<?php
  require_once('includes/load.php');
  page_require_level(2);
  $current_user_ok = current_user();
  $all_matsInsu_ajenos = find_materiales_proyecto($_GET['id']);
  $int = $_GET['int'];
?>

<?php if ($int == 0) { ?>
  <script type="text/javascript">
    $(document).ready(function() {
        $('#matsInsu').DataTable();
    } );
  </script>

  <h4><b>Stock de materiales del cliente</b></h4>
  
  <div>
    &emsp;&emsp;<label>Dar nuevo ingreso de mercadería del cliente:&emsp;&emsp;</label>
    <a href="inventario_ingresoMatInsu.php" class="btn btn-success" data-toggle="tooltip"><span class="glyphicon glyphicon-log-in"></span>&emsp;Ingreso</a>
  </div>

  <br>

  <div class="table-responsive">
    <table id="matsInsu" class="table table-hover table-condensed table-bordered">
      <thead>
        <tr>
          <th style="width: 8px;">Cod. IJS</th>
          <th style="width: 95px;">Descripción</th>
          <th style="width: 100px;"><b>Marca</b> - <em>Tipo - [Cod.]</em></th> 
          <th style="width: 20px;">Cant. reservada</th> 
        </tr>
      </thead>
      <tbody>
        <?php foreach ($all_matsInsu_ajenos as $un_registro):
          if($un_registro['material_IJS']==0) {
            $un_matInsu = find_by_id('inv_materiales_insumos', $un_registro['id_materiales']);?>
            <tr class="warning">
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
              <td class="text-center"><?php echo $un_registro['cantidad']." ".$un_matInsu['unidad'];?></td>
            </tr>
          <?php }
        endforeach; ?>
      </tbody>
      <tfoot>
        <tr>
          <th style="width: 8px;">Cod. IJS</th>
          <th style="width: 95px;">Descripción</th>
          <th style="width: 100px;"><b>Marca</b> - <em>Tipo - [Cod.]</em></th> 
          <th style="width: 20px;">Cant. reservada</th> 
        </tr>
      </tfoot>
    </table>
  </div>

<?php } else { ?>

  <script type="text/javascript">
    $(document).ready(function() {
        $('#matsInsu2').DataTable();
    } );
  </script>

  <h4><b>Stock de materiales de IJS</b></h4>

  <div>
    &emsp;&emsp;<label>Asociar materiales internos al proyecto&emsp;&emsp;</label>
    <a href="conAc_proyectoAsociarMats.php?id=<?php echo (int)$_GET['id'];?>" class="btn btn-primary"><span class="glyphicon glyphicon-plus-sign"></span>&emsp;Asociar</a>
  </div>

  <br>

  <div class="table-responsive">
    <table id="matsInsu2" class="table table-hover table-condensed table-bordered">
      <thead>
        <tr>
          <th style="width: 8px;">Cod. IJS</th>
          <th style="width: 95px;">Descripción</th>
          <th style="width: 100px;"><b>Marca</b> - <em>Tipo - [Cod.]</em></th> 
          <th style="width: 20px;">Cant. reservada</th> 
        </tr>
      </thead>
      <tbody>
        <?php foreach ($all_matsInsu_ajenos as $un_registro):
          if($un_registro['material_IJS']==1) {
            $un_matInsu = find_by_id('inv_materiales_insumos', $un_registro['id_materiales']);?>
            <tr class="success">
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
              <td class="text-center"><?php echo $un_registro['cantidad']." ".$un_matInsu['unidad'];?></td>
            </tr>
        <?php }
        endforeach; ?>
      </tbody>
      <tfoot>
        <tr>
          <th style="width: 8px;">Cod. IJS</th>
          <th style="width: 95px;">Descripción</th>
          <th style="width: 100px;"><b>Marca</b> - <em>Tipo - [Cod.]</em></th> 
          <th style="width: 20px;">Cant. reservada</th> 
        </tr>
      </tfoot>
    </table>
  </div>
<?php } ?>