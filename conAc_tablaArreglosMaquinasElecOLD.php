<?php
  require_once('includes/load.php');
  page_require_level(2);
  $current_user_ok = current_user();
  //$all_reparaciones = find_all('reparacion_maquina');
  //$sql_allReparaciones = $db->query("SELECT * FROM reparacion_maquina where id_estado <> 8 ORDER BY id_estado desc, fecha_ingreso ASC");
  //$sql_allReparaciones = $db->query("( SELECT * FROM reparacion_maquina WHERE id_estado = 7 ORDER by fecha_ingreso ) UNION ( SELECT * FROM reparacion_maquina WHERE id_estado <> 8 ORDER by fecha_ingreso, hora_ingreso )");
  $sql_allReparaciones = $db->query("SELECT * FROM( SELECT * FROM reparacion_maquina WHERE id_estado = 7 ORDER BY fecha_ingreso asc, hora_ingreso asc ) AS dummy1 UNION SELECT * FROM( SELECT * FROM reparacion_maquina WHERE id_estado <> 8 ORDER BY fecha_ingreso asc, hora_ingreso asc ) AS dummy2");
  $all_reparaciones = $db->while_loop($sql_allReparaciones);
?>
<!-- datatable -->
<script type="text/javascript">
</script>

<div class="table-responsive">
  <table id="reparac_maq" class="table table-hover table-condensed table-bordered" style="width: 100%;">
    <thead>
      <tr>
        <th style="width: 4%;">Orden</th>
        <th style="width: 40%;">Cliente - Máquina</th>
        <th style="width: 11.5%;">Fecha / hora ingreso</th>
        <th style="width: 8%;">Días en taller</th>
        <th style="width: 11.5%;">Fecha / hora egreso</th>
        <th style="width: 5%;">Seña</th>
        <th style="width: 12%;">Estado</th>
        <th style="width: 8%;"></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($all_reparaciones as $una_reparacion):
        if ($una_reparacion['id_estado']!=8){
          if ($una_reparacion['id_estado']==1){ ?>
            <tr class="danger">
          <?php } elseif ($una_reparacion['id_estado']==7) { ?>
            <tr class="success">
          <?php } else { ?>
            <tr class="warning">
          <?php } ?>
              <td class="text-center"><?php echo $una_reparacion['id'];?></td>
              <td><?php 
                $maquina = find_by_id('maquina',$una_reparacion['id_maquina']);
                $modelo = find_by_id('maquina_modelo',$maquina['modelo_id']);
                $marca = find_by_id('maquina_marca',$modelo['marca_id']);
                $tipo = find_by_id('maquina_tipo',$modelo['tipo_id']);
                $cliente = find_by_id('clientemaquina',$maquina['id_cliente']);

                echo "(".utf8_encode($cliente['razon_social'])." / ".$cliente['cuit'].")"." [ IJS-ME: ".$maquina['id']."] - ".$tipo['descripcion']." - ".$marca['descripcion']." - ".$modelo['codigo']." ( ".$maquina['num_serie']." )";

                ?></td>

              <td class="text-center"><?php 
                list($año, $mes, $dia) = explode('-', $una_reparacion['fecha_ingreso']);
                echo remove_junk($dia."/".$mes."/".$año)." - ".$una_reparacion['hora_ingreso'];?></td>

              <td class="text-center"><?php
                if (($una_reparacion['id_estado']!=8)) {
                  if ($una_reparacion['fecha_ingreso']) {
                    $fechaInicio = strtotime($una_reparacion['fecha_ingreso']);
                    $fechaActual = strtotime(date("Y-m-d"));
                    //$diff = $fechaInicio->diff($fechaActual);
                    //echo $diff->days .' dias';
                      //echo dias_pasados($fechaInicio,$fechaActual);
                    //echo ($fechaActual-$fechaInicio)/86400 ." dias.";
                    $enTaller = ($fechaActual-$fechaInicio)/86400;
                    if ($una_reparacion['id_estado'] == 7) {
                      echo "".$enTaller;
                    } else {
                      echo $enTaller;
                    } 
                  } else {
                  echo "- -";                 
                  }
                }
              ?></td>

              <td class="text-center"><?php

                if ($una_reparacion['fecha_egreso']) {
                  list($año2, $mes2, $dia2) = explode('-', $una_reparacion['fecha_egreso']);
                }else{
                  echo " - ";
                }
                if ($una_reparacion['hora_egreso']) {
                  echo remove_junk($dia2."/".$mes2."/".$año2)." - ".$una_reparacion['hora_egreso'];
                }else{
                  echo " - ";
                }

                ?></td>
                

              <td class="text-center"><?php 
                if ($una_reparacion['id_estado'] == 8)
                  echo "PAGADO";
                else
                  echo "$ ".$una_reparacion['senia'];?>
                    
              </td>
              <td class="text-center"><?php 
                $estado = find_by_id('reparacion_estado',$una_reparacion['id_estado']);
                if ($una_reparacion['id_estado'] == 1) {?>
                  <span class="label label-danger">
                <?php } elseif ($una_reparacion['id_estado'] == 7) {?> 
                  <span class="label label-success">
                <?php } else { ?>
                  <span class="label label-warning">
                <?php }
                 echo $estado['descripcion']; ?></span>
              </td>
              <td class="text-center" style="vertical-align:middle">
                <div class="btn-group">
                  <?php
                  if ($una_reparacion['id_estado'] == 1 ){ ?>
                    <a type="button" target = '_blank' href="pdf_ComprobanteME.php?idReparacion=<?php echo $una_reparacion['id']?>" class="btn btn-info btn-xs" title="Ver Comprobante" data-toggle="tooltip" id="btn_ver_pdf" name="btn_ver_pdf">
                      <span class="glyphicon glyphicon-open-file"></span></a>

                      <a href="conAc_cambiarEstadoReparacionSet.php?id=<?php echo (int)$una_reparacion['id'];?>&estadoCambio=2" class="btn btn-danger btn-xs"  title="A revisión" data-toggle="tooltip" onclick="return confirm('¿Seguro que desea cambiar el estado de ésta máquina a Revisión?')">
                      <span class="glyphicon glyphicon-share-alt"></span>
                    </a>
                  <?php }elseif($una_reparacion['id_estado'] == 2) { ?>
                    <a type="button" target = '_blank' href="pdf_ComprobanteME.php?idReparacion=<?php echo $una_reparacion['id']?>" class="btn btn-info btn-xs" title="Ver Comprobante" data-toggle="tooltip" id="btn_ver_pdf" name="btn_ver_pdf">
                      <span class="glyphicon glyphicon-open-file"></span></a>

                    <!--<a href="conAc_cambiarEstadoReparacion.php?id=<?php// echo (int)$una_reparacion['id'];?>&idMaquina=<?php// echo (int)$una_reparacion['id_maquina'];?>" class="btn btn-warning btn-xs"  title="Editar estado" data-toggle="tooltip">
                      <span class=" glyphicon glyphicon-time"></span>
                    </a>-->

                    <a type="button" href="repuestos.php?idMaquina=<?php echo (int)$una_reparacion['id_maquina'];?>&idReparacion=<?php echo (int)$una_reparacion['id'];?>" class="btn btn-warning btn-xs"  title="Ver reparación" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-time"></span>
                    </a>
                  <?php }if($una_reparacion['id_estado'] == 3) { ?>
                    <a type="button" target = '_blank' href="pdf_ComprobanteME.php?idReparacion=<?php echo $una_reparacion['id']?>" class="btn btn-info btn-xs" title="Ver Comprobante" data-toggle="tooltip" id="btn_ver_pdf" name="btn_ver_pdf">
                      <span class="glyphicon glyphicon-open-file"></span></a>
                      
                    <a type="button" href="repuestos.php?idMaquina=<?php echo (int)$una_reparacion['id_maquina'];?>&idReparacion=<?php echo (int)$una_reparacion['id'];?>" class="btn btn-warning btn-xs"  title="Ver reparación" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-shopping-cart"></span>
                    </a>
                  <?php }if($una_reparacion['id_estado'] == 4) { ?>
                    <a type="button" href="repuestos.php?idMaquina=<?php echo (int)$una_reparacion['id_maquina'];?>&idReparacion=<?php echo (int)$una_reparacion['id'];?>" class="btn btn-info btn-xs"  title="Ver reparación" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-open"></span>
                    </a>
                    <a type="button" href="conAc_cambiarEstadoReparacionSet.php?id=<?php echo (int)$una_reparacion['id'];?>&estadoCambio=5" class="btn btn-warning btn-xs"  title="Editar estado" data-toggle="tooltip" onclick="return confirm('¿Seguro que desea cambiar el estado de ésta máquina a Colocación de repuestos?')">
                      <span class="glyphicon glyphicon-road"></span>
                    </a>
                  <?php }if($una_reparacion['id_estado'] == 5) { ?>
                    <a type="button" href="repuestos.php?idMaquina=<?php echo (int)$una_reparacion['id_maquina'];?>&idReparacion=<?php echo (int)$una_reparacion['id'];?>" class="btn btn-info btn-xs"  title="Ver reparación" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-open"></span>
                    </a> 
                    <a type="button" href="conAc_cambiarEstadoReparacionSet.php?id=<?php echo (int)$una_reparacion['id'];?>&estadoCambio=6" class="btn btn-warning btn-xs"  title="Editar estado" data-toggle="tooltip" onclick="return confirm('¿Seguro que desea cambiar el estado de ésta máquina a Limpieza?')">
                      <span class="glyphicon glyphicon-wrench"></span>
                    </a>
                  <?php }if($una_reparacion['id_estado'] == 6) { ?>
                    <a type="button" href="repuestos.php?idMaquina=<?php echo (int)$una_reparacion['id_maquina'];?>&idReparacion=<?php echo (int)$una_reparacion['id'];?>" class="btn btn-info btn-xs"  title="Ver reparación" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-open"></span>
                    </a>
                    <a type="button" href="conAc_cambiarEstadoReparacionSet.php?id=<?php echo (int)$una_reparacion['id'];?>&estadoCambio=7" class="btn btn-warning btn-xs"  title="Ver reparación" data-toggle="tooltip" onclick="return confirm('¿Seguro que desea cambiar el estado de ésta máquina a Listo para entregar?')">
                      <span class="glyphicon glyphicon-tint"></span>
                    </a> 
                  <?php }if($una_reparacion['id_estado'] == 7) { ?>
                    <a type="button" href="repuestos.php?idMaquina=<?php echo (int)$una_reparacion['id_maquina'];?>&idReparacion=<?php echo (int)$una_reparacion['id'];?>" class="btn btn-info btn-xs"  title="Ver reparación" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-open"></span>
                    </a>
                    <?php 
                      $sqlHayCotizacionReparacion = $db->query("SELECT * FROM reparacion_cotizacion WHERE reparacion_id = ".$una_reparacion['id']."");
                      $hayCotizacionReparacion = $db->fetch_assoc($sqlHayCotizacionReparacion);
                      if ($hayCotizacionReparacion) {
                    ?>
                    <a type="button" href="conAc_cambiarEstadoReparacionSet.php?id=<?php echo (int)$una_reparacion['id'];?>&estadoCambio=8" class="btn btn-success btn-xs"  title="Entregar" data-toggle="tooltip" onclick="return confirm('¿Seguro que desea entregar ésta máquina?')">
                      <span class="glyphicon glyphicon-check"></span> 
                    </a>
                  <?php } else { ?>
                    <a type="button" href="#" class="btn btn-success btn-xs" title="Entregar" data-toggle="tooltip" onclick="javascript:irCotizacion('<?php echo (int)$una_reparacion['id_maquina'];?>','<?php echo (int)$una_reparacion['id'];?>');">
                      <span class="glyphicon glyphicon-check"></span> 
                    </a>
                  <?php } ?>
                  <script type="text/javascript">
                      function irCotizacion(idMaquina,idReparacion) {
                        alertify.error("Error. Ésta reparación no tiene registrado ningún dato de cotización, por favor haga un registro.");
                        setInterval('location.replace("repuestos.php?idMaquina='+idMaquina+'&idReparacion='+idReparacion+'")', 800);
                      }
                    </script>
                  <?php }elseif($una_reparacion['id_estado'] == 8) { ?>
                    <a type="button" href="#" class="btn btn-primary btn-xs"  title="Revisar informe" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-eye-open"></span>
                    </a>
                  <?php } ?>
                </div>
              </td>
            </tr>
        <?php }
    endforeach; ?>
    </tbody>
    <tfoot>
      <tr>
        <th style="width: 4%;">Orden</th>
        <th style="width: 40%;">Cliente - Máquina</th>
        <th style="width: 11.5%;">Fecha / hora ingreso</th>
        <th style="width: 8%;">Días en taller</th>
        <th style="width: 11.5%;">Fecha / hora egreso</th>
        <th style="width: 5%;">Seña</th>
        <th style="width: 12%;">Estado</th>
        <th style="width: 8%;"></th>
      </tr>
    </tfoot>
  </table>
  <script type="text/javascript">
  $(document).ready(function() {
    $('#reparac_maq').DataTable({/* "order": [[ 2, "desc" ] */"aaSorting": []}); 
   /* $('#reparac_maq').DataTable({
    columnDefs: [{ 
      orderable: true, targets: '_all' 
    }],
    order: [[3, 'desc']]
    });  */
    $('.dataTables_length').addClass('bs-select');
   //var table = $('#reparac_maq').DataTable();
 
// Sort by columns 1 and 2 and redraw
//table
    //.order( [] )
    //.draw();
  
  });
  </script>
</div>