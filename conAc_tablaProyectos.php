<?php
  require_once('includes/load.php');
  page_require_level(2);
  $current_user_ok = current_user();
  $all_proyectos = find_all('proyecto');
?>

<!-- datatable -->
<script type="text/javascript">
</script>

<div class="table-responsive">
  <table id="proye" class="table table-hover table-condensed table-bordered">
    <thead>
      <tr>
        <th style="width: 6%;">Cod. IJS</th>
        <th style="width: 8%;">Estado</th>
        <th style="width: 35%;">Proyecto</th>
        <th style="width: 25%;">Cliente</th>
        <th style="width: 8%;">Fecha inicio</th>
        <th style="width: 6%;">Link Público</th>
        <th style="width: 6%;">Link IJS</th>
        <th style="width: 6%;"></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($all_proyectos as $un_proyecto):
        if ($un_proyecto['estado']==1) { ?>
          <tr class="success">
        <?php } else { ?>
          <tr class="danger">
        <?php } ?>
            <td class="text-center"><?php echo $un_proyecto['id'];?></td>
            <td class="text-center"><?php 
              if ($un_proyecto['estado']==1)
                echo '<span class="label label-success">Activo</span>';
              else
                echo '<span class="label label-danger">Finalizado</span>';
            ?></td>
            <td><?php echo $un_proyecto['nombre_proyecto'];?></td>
            <td class="text-center"><?php echo find_by_id('cliente', $un_proyecto['id_cliente'])['razon_social'];?></td>
            <td class="text-center"><?php 
              list($año, $mes, $dia) = explode('-', $un_proyecto['fecha_inicio']);
              echo remove_junk($dia."/".$mes."/".$año);?></td>

            <td class="text-center"><a target = '_blank' href="<?php echo $un_proyecto['link_IJS'];?>"> Link </a></td>
            <td class="text-center"><a target = '_blank' href="<?php echo $un_proyecto['link_public'];?>"> Link </a></td>
            <td class="text-center" style="vertical-align:middle">
              <div class="btn-group">
                <a href="conAc_proyecto.php?id=<?php echo (int)$un_proyecto['id'];?>" class="btn btn-primary btn-xs"  title="Ver proyecto" data-toggle="tooltip">
                  <span class="glyphicon glyphicon-arrow-right"></span>
                </a>
              </div>
            </td>
          </tr>
      <?php endforeach; ?>
    </tbody>
    <tfoot>
      <tr>
        <th style="width: 6%;">Cod. IJS</th>
        <th style="width: 8%;">Estado</th>
        <th style="width: 35%;">Proyecto</th>
        <th style="width: 25%;">Cliente</th>
        <th style="width: 8%;">Fecha inicio</th>
        <th style="width: 6%;">Link Público</th>
        <th style="width: 6%;">Link IJS</th>
        <th style="width: 6%;"></th>
      </tr>
    </tfoot>
  </table>

  <script>
    $('#proye').DataTable({ "order": [[ 0, "desc" ]] });
    $('.dataTables_length').addClass('bs-select');
  </script>
</div>