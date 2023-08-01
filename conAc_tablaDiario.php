<?php
  require_once('includes/load.php');
  page_require_level(2);
  $current_user_ok = current_user();
  $all_actividades = find_actividades_diarias($_GET['id']);
?>

<!-- datatable -->
<script type="text/javascript">
    $(document).ready(function() {
        $('#diario').DataTable();
    } );
</script>


<div class="table-responsive">
  <table id="diario" class="table table-hover table-condensed table-bordered">
    <thead>
      <tr>
        <th style="width: 100px;"></th>
        <th style="width: 12px;">Fecha</th>
        <th style="width: 10px;">Horario</th>
        <th style="width: 30px;">Personal</th>
        <th style="width: 54px;">Actividad</th>
        <th style="width: 58px;">Observaciones</th>
        <?php if ($current_user_ok['user_level'] <= 1) {?>
          <th style="width: 5px;">Visado</th>
        <?php }?>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($all_actividades as $una_actividad): 
        if ($una_actividad['visado']==1) { ?>
          <tr class="success">
        <?php } else { ?>
          <tr class="danger">
        <?php } ?>
          <td class="hidden" style="visibility:hidden;"><?php 
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
          <td><?php 
            $activ_est = find_by_id('proy_actividades_estaticas', $una_actividad['id_actividad_estatica']);
            echo $activ_est['nivel']." - ".$activ_est['nombre'];?></td>
          <td><?php 
            echo $una_actividad['observaciones'];?></td>
          <td class="text-center" style="vertical-align:middle">
            <div class="btn-group">
              <?php if ($current_user_ok['user_level'] <= 1) {
                if ($una_actividad['visado'] == 0) {
              ?>
                <a href="conAc_proyectoVisarActiv.php?id=<?php echo (int)$una_actividad['id'];?>&visar=1&id_proy=<?php echo (int)$_GET['id'];?>" class="btn btn-success btn-xs"  title="Visar / Confirmar" data-toggle="tooltip">
                <span class="glyphicon glyphicon-check"></span>
              <?php } else { ?>
                <a href="conAc_proyectoVisarActiv.php?id=<?php echo (int)$una_actividad['id'];?>&visar=0&id_proy=<?php echo (int)$_GET['id'];?>" class="btn btn-danger btn-xs"  title="Cancelar visado" data-toggle="tooltip">
                <span class="glyphicon glyphicon-ban-circle"></span>
              <?php }
                } ?>
              </a>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
    <tfoot>
      <tr>
        <th style="width: 100px;"></th>
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
    $('#diario').DataTable({ "order": [[ 0, "desc" ]] });
    $('.dataTables_length').addClass('bs-select');
    
    $(function() {
      $('#diario tr').each(function() {
        $(this).find('th:eq(0)').addClass("hidden");
      });
    });
  </script>
</div>