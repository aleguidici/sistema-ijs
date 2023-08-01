<?php
  require_once('includes/load.php');
  page_require_level(2);
  $allReparaciones = find_all('reparacion_maquina');
?>
<div class="table-responsive" style="width: 100%;">
	<table id="tbl_reparaciones" class="table table-hover table-condensed table-bordered table-striped">
	  <thead>
		<tr>
	      <th style="width: 3%;">Rep</th>
	      <th style="width: 26%;">Datos del Cliente</th>
	      <th style="width: 27%;">Máquina</th>
	      <th style="width: 9%;">Cant. Repuestos</th>
	      <th style="width: 10%;">Monto Reparación</th>
	      <th style="width: 11%;">Fecha de creación</th>
	      <th style="width: 11%;">Estado</th>
	      <th style="width: 3%;">Ver</th>
	    </tr>
	  </thead>
	  <tbody>
	  	<?php
	  	foreach ($allReparaciones as $unaReparacion):
	  		$estadoReparacion = $db->fetch_assoc($db->query("SELECT * FROM reparacion_estado WHERE id = '".$unaReparacion['id_estado']."'"));
	  		$maquinaReparacion = $db->fetch_assoc($db->query("SELECT * FROM maquina WHERE id = '".$unaReparacion['id_maquina']."'"));
	  		$modeloMaquina = $db->fetch_assoc($db->query("SELECT * FROM maquina_modelo WHERE id = '".$maquinaReparacion['modelo_id']."'"));
	  		$marcaMaquina = $db->fetch_assoc($db->query("SELECT * FROM maquina_marca WHERE id = '".$modeloMaquina['marca_id']."'"));
	  		$tipoMaquina = $db->fetch_assoc($db->query("SELECT * FROM maquina_tipo WHERE id = '".$modeloMaquina['tipo_id']."'"));
	  		$clienteReparacion = $db->fetch_assoc($db->query("SELECT * FROM clientemaquina WHERE id = '".$maquinaReparacion['id_cliente']."'"));
  			$cantRepuestos = mysqli_num_rows($db->query("SELECT * FROM reparacion_repuesto WHERE reparacion_id = '".$unaReparacion['id']."' AND elegido = 1"));
  			$cotizacionReparacion = $db->fetch_assoc($db->query("SELECT * FROM reparacion_cotizacion WHERE reparacion_id = '".$unaReparacion['id']."'"));

	  	?>
	  	<?php
	  	if ($unaReparacion['id_estado'] != 0) {   	
	  	if ($unaReparacion['id_estado'] == 1) { ?>	
	      <tr class="danger">
	    <?php }
	     elseif ($unaReparacion['id_estado'] == 7) { if ($maquinaReparacion['sin_reparacion'] == 1) { ?>
	      <tr class="no-reparacion" data-toggle="tooltip" title="<?php echo "No tiene reparación, razón: ".$maquinaReparacion['razon_noreparacion']; ?>">
	    <?php } else { ?>
	      <tr class="success">
	    <?php } }
	     elseif ($unaReparacion['id_estado'] == 8) { if ($maquinaReparacion['sin_reparacion'] == 1) { ?>
	      <tr class="no-reparacion" data-toggle="tooltip" title="<?php echo "No tiene reparación, razón: ".$maquinaReparacion['razon_noreparacion']; ?>">
	    <?php } else { ?> 
	   	  <tr class="info">
	   	<?php } } else { ?>
	   	<tr class="warning">
	   	<?php } } ?> 	
	      	<td class="text-center"><?php echo $unaReparacion['id']; ?></td>
	      	<td class=""><?php echo $clienteReparacion['razon_social']." -- ( ".$clienteReparacion['cuit']." )"; ?></td>
	      	<td class=""><?php echo "[IJS-ME: ".$maquinaReparacion['id']."] - ".$tipoMaquina['descripcion']." - ".$marcaMaquina['descripcion']." - ".$modeloMaquina['codigo']; ?></td>
	      	<td class="text-center"><?php echo $cantRepuestos; ?></td>
	      	<td class="text-center"><?php if($cotizacionReparacion) { echo "$ ".$cotizacionReparacion['total']; } else { echo "Sin Cotización"; } ?></td>
	      	<td class="text-center"><?php echo $unaReparacion['fecha_ingreso']; ?></td>
	      	<td class="text-center"><?php
                if ($unaReparacion['id_estado'] == 1) {?>
                  <span class="label label-danger">
                <?php } elseif ($unaReparacion['id_estado'] == 8) { if ($maquinaReparacion['sin_reparacion'] == 1) { ?>
                  <span class="label" style="background-color: rgba(105,105,105,0.8);">
                <?php } else { ?>
                  <span class="label label-info"> 
                <?php } ?>   
                <?php } elseif ($unaReparacion['id_estado'] == 7) {?> 
                  <span class="label label-success">
                <?php } else { ?>
                  <span class="label label-warning">
                <?php }
                 echo $estadoReparacion['descripcion']; if ($maquinaReparacion['sin_reparacion'] == 1) { echo " / Sin reparación"; } ?></span>
             </td>
	      	<td class="text-center"><a type="button" class="btn btn-info btn-xs" title="Abrir Reparación" data-toggle="tooltip" target='_blank' href="repuestos.php?idMaquina=<?php echo (int)$unaReparacion['id_maquina']; ?>&idReparacion=<?php echo (int)$unaReparacion['id'];?>"><span class="glyphicon glyphicon-open"></span></a></td>
	        <!--<td class="text-center"><a target = '_blank' href="<?php// echo 'aa';?>"> Ver detalles </a></td>-->
	      </tr>
	    <?php
		endforeach ;
		?>
	  </tbody>
	  <tfoot>
	    <tr>
	      <th style="width: 3%;">Rep</th>
	      <th style="width: 26%;">Datos del Cliente</th>
	      <th style="width: 27%;">Máquina</th>
	      <th style="width: 9%;">Cant. Repuestos</th>
	      <th style="width: 10%;">Monto Reparación</th>
	      <th style="width: 11%;">Fecha de creación</th>
	      <th style="width: 11%;">Estado</th>
	      <th style="width: 3%;">Ver</th>
	    </tr>
	  </tfoot>
	</table>
	<script>
	  $('#tbl_reparaciones').DataTable(/*{ "order": [[ 0, "desc" ],[ 2, "desc" ]]  }*/);
	  $('.dataTables_length').addClass('bs-select');
	  
	  /*$(function() {
	    $('#mediciones tr').each(function() {
	      $(this).find('th:eq(0)').addClass("hidden");
	    });
	  });*/
	</script>
</div>