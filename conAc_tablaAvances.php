<?php
  require_once('includes/load.php');
  page_require_level(2);
  $current_user_ok = current_user();
  //$all_activ_diarias = find_actividades_diarias($_GET['id']);

  $all_activ_estaticas = find_actEstat_by_proy($_GET['id']);
?>

<!-- datatable -->
<script type="text/javascript">
    $(document).ready(function() {
        $('#avances').DataTable();
    } );
</script>


<div class="table-responsive">
  <table id="avances" class="table table-hover table-condensed table-bordered">
    <thead>
      <tr>
        <th style="width: 70%;">Actividad</th>
        <th style="width: 12%;">Total horas trabajadas</th>
        <th style="width: 12%;">Total días trabajados</th>
        <?php if ($current_user_ok['user_level'] <= 1) {?>
          <th style="width: 6%;"></th>
        <?php }?>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($all_activ_estaticas as $una_activ_estat):
        $all_activ_diarias = find_actividades_diarias_segun_actividad_estatica($una_activ_estat['id']);
        $horas = 0;
        $dias = 0;
        $minutos = 0;
        foreach ($all_activ_diarias as $una_activ_diaria):
          list($H1, $m1, $s1) = explode(':', $una_activ_diaria['hora_inicio']);
          list($H2, $m2, $s2) = explode(':', $una_activ_diaria['hora_fin']);
          $minutos = $minutos + ($H2-$H1)*60 + ($m2-$m1);
        endforeach;
        $horas = ceil($minutos/60);
        $dias =  ceil($horas/8);

      ?>
      <tr class="">
        <td><?php 
          echo $una_activ_estat['nivel']." - ".$una_activ_estat['nombre'];?></td>
        <td class="text-center"><?php 
          echo $horas;?></td>
        <td class="text-center"><?php 
          echo $dias;?></td>
        <td class="text-center" style="vertical-align:middle">
          <div class="btn-group">
            <?php if ($current_user_ok['user_level'] <= 1) {
              if ($minutos > 0) {
            ?>
              <a href="conAc_actividadDetalles.php?id=<?php echo (int)$una_activ_estat['id'];?>" class="btn btn-success btn-xs"  title="Ver detalles" data-toggle="tooltip">
                <span class="glyphicon glyphicon-arrow-right"></span>
              </a>
            <?php }
              } ?>
          </div>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
    <tfoot>
      <tr>
        <th style="width: 80%;">Actividad</th>
        <th style="width: 7%;">Total horas trabajadas</th>
        <th style="width: 7%;">Total días trabajados</th>
        <?php if ($current_user_ok['user_level'] <= 1) {?>
          <th style="width: 6%;"></th>
        <?php }?>
      </tr>
    </tfoot>
  </table>

  <script>
    //$('#avances').DataTable({ "order": [[ 0, "desc" ]] });
    $('.dataTables_length').addClass('bs-select');
  </script>
</div>