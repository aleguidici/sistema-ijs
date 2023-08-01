<?php
  $page_title = 'Scripts';
  require_once('includes/load.php');
  
  page_require_level(2);
  
if (isset($_POST['btn_script_update_cotizaciones'])) {
      $cotizacionesScrip = $db->while_loop($db->query("SELECT * FROM `reparacion_cotizacion`"));
      $controlerrores = 0;
      foreach ($cotizacionesScrip as $unScript):
        $thisrepa = $db->fetch_assoc($db->query("SELECT * FROM `reparacion_maquina` WHERE `id` = ".$unScript['reparacion_id']));
        $senia = $thisrepa['senia'];
        $acurec = round(($unScript['repuestos'] * $unScript['recargo'])/100,2);
        $subto = round(($unScript['repuestos'] + $unScript['mano_obra'] + $unScript['flete'] + $unScript['grasa'] + $acurec),2);
        $iv = round(($subto * 0.21),2);
        $tot = round(($subto + $iv - $senia),2);
        $res = $db->query("UPDATE `reparacion_cotizacion` SET `acum_recargo`='{$acurec}',`subtotal`='{$subto}',`iva`='{$iv}',`total`='{$tot}',`senia`='{$senia}' WHERE `id` = ".$unScript['id']);
        echo '<script>console.log("'.$unScript['id'].' - '.$res.' ");</script>';
        if($res != 1) {
          $controlerrores = 1;
        }
      endforeach;
      if($controlerrores != 1) {
        $session->msg("s", "Success.");
      } else {
        $session->msg("d", "Error.");
      }
      header("Refresh:0");
  }


  include_once('layouts/newHeader.php');
?> 
  <form method="post" action="scripts.php"><div class="row"><div class="col-md-2"><button type="submit" class="btn btn-info" name="btn_script_update_cotizaciones">UPDATE COTIZACIONES</button></div></div></form>
  <br>
  <?php echo display_msg($msg); ?>  

<?php include_once('layouts/newFooter.php'); ?>