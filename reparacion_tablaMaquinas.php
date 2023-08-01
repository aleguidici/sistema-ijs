<?php
  require_once('includes/load.php');
  page_require_level(2);
  $allMaquinas = $db->while_loop($db->query("SELECT * FROM maquina ORDER BY 1 DESC"));
  $allReparaciones = $db->while_loop($db->query("SELECT * FROM reparacion_maquina ORDER BY 1 ASC"));
  $allReparacionesOrderByDate = $db->while_loop($db->query("SELECT * FROM reparacion_maquina ORDER BY 3 DESC"));
?>
<script type="text/javascript">
	function cargarReparacionesMaquina(idMaquina) {
		<?php foreach ($allMaquinas as $unaMaquinaModal) : ?>
			if('<?php echo $unaMaquinaModal['id']; ?>' == idMaquina) {
		<?php	
				$modeloMaquinaModal = $db->fetch_assoc($db->query("SELECT * FROM maquina_modelo WHERE id = '".$unaMaquinaModal['modelo_id']."'"));
				$marcaMaquinaModal = $db->fetch_assoc($db->query("SELECT * FROM maquina_marca WHERE id = '".$modeloMaquinaModal['marca_id']."'"));
				$tipoMaquinaModal = $db->fetch_assoc($db->query("SELECT * FROM maquina_tipo WHERE id = '".$modeloMaquinaModal['tipo_id']."'"));
				$clienteMaquinaModal = $db->fetch_assoc($db->query("SELECT * FROM clientemaquina WHERE id = '".$unaMaquinaModal['id_cliente']."'"));  
		?>
			document.getElementById("input_idmaquina").value = '<?php echo $unaMaquinaModal['id']; ?>';
			document.getElementById("p_ijsme").innerHTML = '<?php echo "IJS-ME : ".$unaMaquinaModal['id']; ?>';
			document.getElementById("p_tipo").innerHTML = '<?php echo "Tipo: ".$tipoMaquinaModal['descripcion']; ?>';
			document.getElementById("p_marca").innerHTML = '<?php echo "Marca: ".$marcaMaquinaModal['descripcion']; ?>';
			document.getElementById("p_modelo").innerHTML = '<?php echo "Modelo: ".$modeloMaquinaModal['codigo']; ?>';
			document.getElementById("p_serie").innerHTML = '<?php echo "N° Serie: ".$unaMaquinaModal['num_serie']; ?>';
			document.getElementById("p_ncliente").innerHTML = '<?php echo "Nombre: ".$clienteMaquinaModal['razon_social']; ?>';
			document.getElementById("p_dircliente").innerHTML = '<?php if($clienteMaquinaModal['direccion']) echo "Dirección: ".$clienteMaquinaModal['direccion']; else echo "Direccion: No se registró"; ?>';
			document.getElementById("p_cuitcliente").innerHTML = '<?php echo "Dni/Cuit: ".$clienteMaquinaModal['cuit']; ?>';
			document.getElementById("p_celcliente").innerHTML = '<?php if(!$clienteMaquinaModal['cel']) echo "Teléfono: ".$clienteMaquinaModal['tel']; else echo "Celular: ".$clienteMaquinaModal['cel']; ?>';
		} else { }		
		<?php endforeach ; ?>

		$('#sel_reparacion').find('option').remove().end().append('<option value="" disabled selected>Seleccione una reparación:</option>');
		var selReparacion = document.getElementById("sel_reparacion");
		<?php foreach ($allReparacionesOrderByDate as $unaReparacion) : ?>
			if ('<?php echo $unaReparacion['id_maquina']; ?>' == idMaquina) {
			<?php 
			$maquinaUnaReparacion = $db->fetch_assoc($db->query("SELECT * FROM maquina WHERE id = '".$unaReparacion['id_maquina']."'")); 
			$cotizacionUnaReparacion = $db->fetch_assoc($db->query("SELECT * FROM reparacion_cotizacion WHERE reparacion_id = '".$unaReparacion['id']."'"));
			?>
				var optSelReparacion = document.createElement('option');
			<?php if ($cotizacionUnaReparacion) { ?>	
				var textSelReparacion = "<?php echo 'ID: '.$unaReparacion['id'].' -- Ingreso: '.$unaReparacion['fecha_ingreso'].' a las '.$unaReparacion['hora_ingreso'].' -- Egreso: '.$unaReparacion['fecha_egreso'].' a las '.$unaReparacion['hora_egreso'].' -- Costo de repuestos: $'.$cotizacionUnaReparacion['repuestos'].' -- Cobro total de reparación: $'.$cotizacionUnaReparacion['total']; ?>";
			<?php } else { ?>
				var textSelReparacion = "<?php echo 'ID: '.$unaReparacion['id'].' -- Ingreso: '.$unaReparacion['fecha_ingreso'].' a las '.$unaReparacion['hora_ingreso'].' -- Egreso: '.$unaReparacion['fecha_egreso'].' a las '.$unaReparacion['hora_egreso'].' -- No hay datos de cotización'; ?>";
			<?php } ?>	
				optSelReparacion.appendChild(document.createTextNode(textSelReparacion));
				optSelReparacion.value = "<?php echo $unaReparacion['id']; ?>";
				selReparacion.appendChild(optSelReparacion);
			} else { }		
		<?php endforeach ; ?>
	}

	function openReparacion() {
		document.getElementById('btn_ir-reparacion').disabled = true ;
		var idReparacion = document.getElementById('sel_reparacion').value ;
		var idMaquina = document.getElementById('input_idmaquina').value ;
		alertify.success("Redireccionando a la reparación, por favor espere.");
		setTimeout(function() {              
            window.open('repuestos.php?idMaquina='+idMaquina+'&idReparacion='+idReparacion+'','_blank');
        },2000);
	}

	function msgSinReparaciones() {
		alertify.error("Ésta máquina no se encuentra registrada en ninguna reparación");
	}

	function enableIrReparacion() {
		document.getElementById('btn_ir-reparacion').disabled = false;
		//document.getElementById('btn_ir-reparacion').style['backgroundColor'] = 'red';
	}
	function irClienteME(idCliente) {
		alertify.success("Redireccionando a la ventada del cliente, por favor espere.");
		setTimeout(function() {              
            window.open('clienteMaquinaMaquinas.php?id='+idCliente+'','_blank');
        },2000);
	}

	function reloadPage() {
		location.reload();
	}
	
</script>
<div class="table-responsive" style="width: 100%;">
	<table id="tbl_clientes" class="table table-hover table-condensed table-bordered">
	  <thead>
		<tr>
	      <th style="width: 4%;">IJS-ME</th>
	      <th style="width: 9%;">Tipo</th>
	      <th style="width: 9%;">Marca</th>
	      <th style="width: 9%;">Modelo</th>
	      <th style="width: 9%;">N° Serie</th>
	      <th style="width: 17%;">Detalles Máquina</th>
	      <th style="width: 25%;">Cliente</th>
	      <th style="width: 5%;">Veces Rep.</th>
	      <th style="width: 12%;">Estado</th>
	      <th style="width: 1%;">Máq. Cliente</th>
	    </tr>
	  </thead>
	  <tbody>
	  	<?php
	  	foreach ($allMaquinas as $unaMaquina):
	  		$reparacionMaquina = $db->fetch_assoc($db->query("SELECT * FROM reparacion_maquina WHERE id_maquina = '".$unaMaquina['id']."'"));
	  		$estadoReparacion = $db->fetch_assoc($db->query("SELECT * FROM reparacion_estado WHERE id = '".$reparacionMaquina['id_estado']."'"));
	  		$modeloMaquina = $db->fetch_assoc($db->query("SELECT * FROM maquina_modelo WHERE id = '".$unaMaquina['modelo_id']."'"));
	  		$marcaMaquina = $db->fetch_assoc($db->query("SELECT * FROM maquina_marca WHERE id = '".$modeloMaquina['marca_id']."'"));
	  		$tipoMaquina = $db->fetch_assoc($db->query("SELECT * FROM maquina_tipo WHERE id = '".$modeloMaquina['tipo_id']."'"));
	  		$clienteReparacion = $db->fetch_assoc($db->query("SELECT * FROM clientemaquina WHERE id = '".$unaMaquina['id_cliente']."'"));
  			$cotizacionReparacion = $db->fetch_assoc($db->query("SELECT * FROM reparacion_cotizacion WHERE reparacion_id = '".$reparacionMaquina['id']."'"));
  			$vecesReparada = mysqli_num_rows($db->query("SELECT * FROM reparacion_maquina WHERE id_maquina = '".$unaMaquina['id']."'"));
	  	?>	
	    <?php
	  	if ($estadoReparacion['id']) {   	
	  	if ($estadoReparacion['id'] == 1) { ?>	
	      <tr class="danger">
	    <?php }
	     elseif ($estadoReparacion['id'] == 7) { if ($unaMaquina['sin_reparacion'] == 1) { ?>
	      <tr class="no-reparacion" data-toggle="tooltip" title="<?php echo "No tiene reparación, razón: ".$unaMaquina['razon_noreparacion']; ?>">
	    <?php } else { ?>
	      <tr class="success">
	    <?php } }
	     elseif ($estadoReparacion['id'] == 8) { if ($unaMaquina['sin_reparacion'] == 1) { ?>
	      <tr class="no-reparacion" data-toggle="tooltip" title="<?php echo "No tiene reparación, razón: ".$unaMaquina['razon_noreparacion']; ?>">
	    <?php } else { ?> 
	   	  <tr class="info">
	   	<?php } } else { ?>
	   	<tr class="warning">
	   	<?php } } else { ?> 
	   		<tr class="no-registrada-en-reparacion">
	   	<?php } ?>
	      	<td class="text-center"><?php echo "IJS-ME: ".$unaMaquina['id']; ?></td>

	      	<td class=""><?php echo $tipoMaquina['descripcion']; ?></td>

	      	<td class=""><?php echo $marcaMaquina['descripcion']; ?></td>

	      	<td class=""><?php echo $modeloMaquina['codigo']; ?></td>

	      <?php if ($unaMaquina['num_serie']) { ?>
	      	<td class=""><?php echo $unaMaquina['num_serie']; ?></td>
	      <?php } else { ?>
	      	<td class="text-center"><?php echo "- -"; ?></td>
	      <?php } ?>

	      <?php if ($unaMaquina['detalles']) { ?>
	      	<td class=""><?php echo $unaMaquina['detalles']; ?></td>
	      <?php } else { ?>
	      	<td class="text-center"><?php echo "- -"; ?></td>
	      <?php } ?>

	      	<td class="" data-toggle="tooltip" title="<?php echo 'DNI/CUIT: '. $clienteReparacion['cuit'] ;?>"><?php echo $clienteReparacion['razon_social']; ?></td>

	      	<td class="text-center"><?php if ($vecesReparada != 0) { echo $vecesReparada ; ?>&emsp;<a type="button" id="a_modal-ver-repraciones" class="btn btn-info btn-xs" data-toggle="modal" data-target="#modal-ver-reparaciones" onclick="javascript:cargarReparacionesMaquina('<?php echo $unaMaquina['id']; ?>');"><span class="glyphicon glyphicon-open" style="color: white;"></span></a><?php } else { echo $vecesReparada ; ?>&emsp;<a type="button" class="btn btn-xs" style="background-color: rgba(120,120,120,0.5);" onclick="javascript:msgSinReparaciones();"><span class="glyphicon glyphicon-open" style="color: white;"></span></a><?php } ?></td>

	      	<td class="text-center"><?php if ($reparacionMaquina['id_estado'] == 1) { ?>
                  <span class="label label-danger">
                <?php } elseif ($reparacionMaquina['id_estado'] == 8) { if ($unaMaquina['sin_reparacion'] == 1) { ?>
                  <span class="label" style="background-color: rgba(105,105,105,0.8);">
                <?php } else { ?>
                  <span class="label label-info"> 
                <?php } ?>   
                <?php } elseif ($reparacionMaquina['id_estado'] == 7) { ?> 
                  <span class="label label-success">
                <?php } elseif (!$reparacionMaquina['id_estado']) { ?>
                <span class="label" style="background-color: rgba(120,120,120,0.5);">
                <?php } else { ?> 
                  <span class="label label-warning">
                <?php }
                if (!$estadoReparacion) {
                	echo "Sin registro de reparación";
                } else {
                	echo $estadoReparacion['descripcion']; if ($unaMaquina['sin_reparacion'] == 1) { echo " / Sin reparación"; } ?></span></td>
                <?php } ?>

	      	<td class="text-center"><a type="button" id="" class="btn btn-info btn-xs" onclick="javascript:irClienteME('<?php echo $unaMaquina['id_cliente']; ?>');" data-toggle="tootip" title="Ver todas las máquinas de éste cliente"><span class="glyphicon glyphicon-user" style="color: white;"></span></a></td>	
	      </tr>
	    <?php
		endforeach ;
		?>
	  </tbody>
	  <tfoot>
	    <tr>
	      <th style="width: 7%;">IJS-ME</th>
	      <th style="width: 9%;">Tipo</th>
	      <th style="width: 9%;">Marca</th>
	      <th style="width: 9%;">Modelo</th>
	      <th style="width: 9%;">N° Serie</th>
	      <th style="width: 17%;">Detalles Máquina</th>
	      <th style="width: 25%;">Cliente</th>
	      <th style="width: 5%;">Veces Rep.</th>
	      <th style="width: 12%;">Estado</th>
	      <th style="width: 1%;">Máq. Cliente</th>
	    </tr>
	  </tfoot>
	</table>
	<script>
	  $('#tbl_clientes').DataTable({"ordering": false});
	  $('.dataTables_length').addClass('bs-select');
	  
	  /*$(function() {
	    $('#mediciones tr').each(function() {
	      $(this).find('th:eq(0)').addClass("hidden");
	    });
	  });*/
	</script>
</div>

<!--------------- MODAL VER REPARACIONES ------------------>
<div class="modal bd-example-modal-lg" id="modal-ver-reparaciones" tabindex="-1" role="dialog" aria-labelledby="modal-ver-reparaciones-label" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content bg-info">
      <div class="modal-header">
        <h3 class="modal-title">Ver reparaciones</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
      	<div class="row">
      		<div class="col-md-6">
      			<div class="pull-left">
      				<input hidden id="input_idmaquina">
      				<p id="p_ijsme"></p>
	      			<p id="p_tipo"></p>
	      			<p id="p_marca"></p>
	      			<p id="p_modelo"></p>
	      			<p id="p_serie"></p>
	      		</div>
      		</div>
      		<div class="col-md-6">
      			<div class="pull-right">
	      			<p id="p_ncliente"></p>
	      			<p id="p_dircliente"></p>
	      			<p id="p_cuitcliente"></p>
	      			<p id="p_celcliente"></p>
	      		</div>
      		</div>
      	</div>
      	<hr>
      	<div class="row">
          <div class="col-md-10">
            <label for="sel_reparacion" class="control-label">Ver reparaciones:</label>
            <select class="form-control" data-show-subtext="true" data-live-search="true" name="sel_reparacion" id="sel_reparacion" data-width="100%" onchange="javascript:enableIrReparacion();">
              <option value="" disabled selected >Seleccione una reparacion:</option>                
            </select>
          </div>
          <div class="col-md-2">
            <label class="control-label">Acción:</label>
            <button disabled type="button" id="btn_ir-reparacion" class="btn btn-outline-light btn-block" onclick="openReparacion();" style="border: 1px solid white;">Abrir</button>
          </div>
        </div>
      </div>
      <br>
      <div class="modal-footer justify-content-between">
        <button type="button" id="btn_cerrar_modal-ver-reparaciones" class="btn btn-outline-light" data-dismiss="modal" onclick="" style="border: 1px solid white;">Cerrar</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<!--------------- END MODAL VER REPARACIONES -------------->