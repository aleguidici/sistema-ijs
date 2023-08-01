<?php
  require_once('includes/load.php');
  page_require_level(3);
  $all_mediciones = null;
  $current_user_ok = current_user();
  if ($current_user_ok['user_level'] <= 2)
    $all_mediciones = find_all('datos_medicion');
?>

  <!-- datatable -->
  <script type="text/javascript">
  </script>
  

  <div class="table-responsive">
    <table id="mediciones" class="table table-hover table-condensed table-bordered">
      <thead>
        <tr>
          <th style="width: 70px;"></th>
          <th style="width: 75px;">Fecha de medición</th>
          <th style="width: 35px;">Cod. IJS</th> 
          <th style="width: 145px;">Cliente - Sucursal</th> <!-- num_suc -->
          <th style="width: 215px;">Dirección</th> <!-- direccion -->
          <th style="width: 25px;">C.P.</th> <!-- cp -->
          <th style="width: 75px;">Localidad</th> <!-- localidad -->
          <th style="width: 55px;">Provincia / Estado</th> <!-- provincia.nombre -->
          <th style="width: 70px;"></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($all_mediciones as $una_medic):
          $un_cliente = find_by_id_suc('cliente', $una_medic['num_suc']);
          $provincia = find_by_id_prov('provincia', $un_cliente['provincia']);?>
          <tr class="info">
            <td class="hidden" style="visibility:hidden;"><?php echo $una_medic['fecha_medicion'];?></td>
            <td class="text-center"><?php 
            list($año, $mes, $dia) = explode('-', $una_medic['fecha_medicion']);
            echo remove_junk($dia."/".$mes."/".$año);?></td>
            <td class="text-center"><?php echo remove_junk($una_medic['id_medicion']);?></td>
            <td><?php echo remove_junk(($un_cliente['razon_social']));
            if (!empty($un_cliente['num_suc']))
              echo remove_junk(' - '.$un_cliente['num_suc'])?></td>
            <td ><?php echo remove_junk(($un_cliente['direccion']));?></td>
            <td class="text-center"><?php echo remove_junk($un_cliente['cp']);?></td>
            <td ><?php echo($un_cliente['localidad']);?></td>
            <td ><?php echo remove_junk($provincia['nombre']);?></td>

            
            <td class="text-center"><a target = '_blank' href="<?php echo 'pdf_protocolo.php?datos_medic='.$una_medic['id_medicion'];?>"> Ver detalles </a></td>
            
          </tr>
        <?php endforeach; ?>
      </tbody>
      <tfoot>
        <tr>
          <th style="width: 70px;"></th>
          <th style="width: 75px;">Fecha de medición</th>
          <th style="width: 35px;">Cod. IJS</th> 
          <th style="width: 145px;">Cliente - Sucursal</th> <!-- num_suc -->
          <th style="width: 215px;">Dirección</th> <!-- direccion -->
          <th style="width: 25px;">C.P.</th> <!-- cp -->
          <th style="width: 75px;">Localidad</th> <!-- localidad -->
          <th style="width: 55px;">Provincia / Estado</th> <!-- provincia.nombre -->
          <th style="width: 70px;"></th>
        </tr>
      </tfoot>
    </table>

    <script>
      $('#mediciones').DataTable({ "order": [[ 0, "desc" ],[ 2, "desc" ]]  });
      $('.dataTables_length').addClass('bs-select');
      
      //-----
      $(function() {
        $('#mediciones tr').each(function() {
          $(this).find('th:eq(0)').addClass("hidden");
        });
      });
    </script>
  </div>