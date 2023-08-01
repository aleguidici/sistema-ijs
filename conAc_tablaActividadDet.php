<?php
  require_once('includes/load.php');
  page_require_level(2);
  $current_user_ok = current_user();
  $all_actividades = find_actividades_diarias_segun_actividad_estatica($_GET['id']);
?>

<!-- datatable -->
<script type="text/javascript">
    $(document).ready(function() {
        $('#detallesActi').DataTable();
    } );
</script>


<div class="table-responsive">
  <table id="detallesActi" class="table table-hover table-condensed table-bordered">
    <thead>
      <tr>
        <th class="hidethis" style="width: 100px;"></th>
        <th style="width: 12px;">Fecha</th>
        <th style="width: 10px;">Horario</th>
        <th style="width: 22px;">Personal</th>
        <th style="width: 46px;">Actividad</th>
        <th style="width: 50px;">Observaciones</th>
        <?php if ($current_user_ok['user_level'] <= 1) {?>
          <th style="width: 5px;">Visado</th>
        <?php }?>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($all_actividades)) {
      foreach ($all_actividades as $una_actividad): 
        if ($una_actividad['visado']==1) { ?>
          <tr class="success">
        <?php } else { ?>
          <tr class="danger">
        <?php } ?>
          <td class="hidethis"><?php 
            echo $una_actividad['fecha'];?></td>
          <td class="text-center"><?php 
            list($año, $mes, $dia) = explode('-', $una_actividad['fecha']);
            echo $dia."/".$mes."/".$año;?></td>
          <td class="text-center"><?php 
            list($H1, $m1, $s1) = explode(':', $una_actividad['hora_inicio']);
            list($H2, $m2, $s2) = explode(':', $una_actividad['hora_fin']);
            $horaI = $H1.":".$m1;
            $horaF = $H2.":".$m2;
            echo $horaI." - ".$horaF;?></td>
          <td><?php 
            $all_personas_act = find_personal_actividad_diaria($una_actividad['id']);
            //$persona = find_by_id('personal', $una_actividad['id_personalafectado']);
            foreach ($all_personas_act as $un_personal):
              $persona = find_by_id('personal', $un_personal['id_pers']);
              echo nl2br ("- ".$persona['apellido'].", ".$persona['nombre']."\n");
            endforeach;?></td>
          <td class="text-center"><?php 
            $activ_est = find_by_id('proy_actividades_estaticas', $una_actividad['id_actividad_estatica']);
            echo $activ_est['nivel']." - ".$activ_est['nombre'];?></td>
          <td class="text-center"><?php 
            echo $una_actividad['observaciones'];?></td>
          <td class="text-center" style="vertical-align:middle">
            <div class="btn-group">
              <?php if ($current_user_ok['user_level'] <= 1) {
                if ($una_actividad['visado'] == 0) {
              ?>
                <a href="conAc_proyectoVisarActivDet.php?id=<?php echo (int)$una_actividad['id'];?>&visar=1&id_act=<?php echo (int)$_GET['id'];?>" class="btn btn-success btn-xs"  title="Visar / Confirmar" data-toggle="tooltip">
                <span class="glyphicon glyphicon-check"></span>
              <?php } else { ?>
                <a href="conAc_proyectoVisarActivDet.php?id=<?php echo (int)$una_actividad['id'];?>&visar=0&id_act=<?php echo (int)$_GET['id'];?>" class="btn btn-danger btn-xs"  title="Cancelar visado" data-toggle="tooltip">
                <span class="glyphicon glyphicon-ban-circle"></span>
              <?php }
                } ?>
              </a>
            </div>
          </td>
        </tr>
      <?php endforeach; 
      }?>
    </tbody>
    <tfoot>
      <tr>
        <th class="hidethis" style="width: 100px;"></th>
        <th style="width: 12px;">Fecha</th>
        <th style="width: 10px;">Horario</th>
        <th style="width: 22px;">Personal</th>
        <th style="width: 46px;">Actividad</th>
        <th style="width: 50px;">Observaciones</th>
        <?php if ($current_user_ok['user_level'] <= 1) {?>
          <th style="width: 5px;">Visado</th>
        <?php }?>
      </tr>
    </tfoot>
  </table>

  <script>
    $('#detallesActi').DataTable({ "order": [[ 0, "desc" ]] });
    $('.dataTables_length').addClass('bs-select');
    $('.hidethis').hide();
  </script>
</div>