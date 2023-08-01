<?php
  require_once('includes/load.php');
  page_require_level(2);
  $allClientes = $db->while_loop($db->query("SELECT * FROM `clientemaquina` ORDER BY 7 ASC"));
?>

<script type="text/javascript">
	function goMaquinasCliente(idCliente) {
		if (idCliente) {
			alertify.success("Redireccionando a la ventana de máquinas, por favor espere.");
			setTimeout(function() {              
            	window.open('clienteMaquinaMaquinas.php?id='+idCliente+'','_blank');
        	},2000);
		}
	}
	function goEditarCliente(idCliente) {
		if (idCliente) {
			alertify.success("Redireccionando a la ventana de edición del cliente, por favor espere.");
			setTimeout(function() {              
            	window.open('edit_clienteMaq.php?id='+idCliente+'','_blank');
        	},2000);
		}
	}

	//crea un form y lo envia
	function goVerReparacionesMaquina(idCliente) {
		var url = 'reparacion_verPorMaquinas.php'; // URL a la cual enviar los datos
	    var form = document.createElement('form');
	    document.body.appendChild(form);
	    form.method = 'post';
	    form.target = '_blank';
	    form.action = url;
	        var input = document.createElement('input');
	        input.type = 'hidden';
	        input.name = 'id_cliente';
	        input.value = idCliente;
	        form.appendChild(input);
	    form.submit();
	}

</script>

<div class="table-responsive" style="width: 100%;">
	<table id="tbl_maquinas" class="table table-hover table-condensed table-bordered table-striped">
	  <thead>
		<tr>
	      <th class="text-center" style="width: 4%;">ID</th>
	      <th style="width: 17%;">Nombre / Razón social</th>
	      <th style="width: 7%;">Dni / Cuit</th>
	      <th class="text-center" style="width: 10%;">IVA</th>
	      <th class="text-center" style="width: 8%;">Teléfono / Celular</th>
	      <th class="text-center" style="width: 8%;">Máquinas</th>
	      <th class="text-center" style="width: 8%;">Acciones</th>
	      <th class="text-center" style="width: 8%;">Monto última rep.</th>
	      <th class="text-center" style="width: 8%;">Monto histórico</th>
	      <th class="text-center" style="width: 8%;">Sin Cobrar</th>
	      <th class="text-center" style="width: 9%;">Estado</th>
	      <th class="text-center" style="width: 5%;">Acciones</th>
	    </tr>
	  </thead>
	  <tbody>
	  	<?php
	  	$sumaCotizacionesDelClienteCobradas = 0 ;
	  	$sumaCotizacionesDelCliente = 0 ;
	  	$sumaCotizacionesDelClienteSinCobro = 0 ;
	  	foreach ($allClientes as $unCliente) :
	  		$cantMaquinas = mysqli_num_rows($db->query("SELECT `id` FROM `maquina` WHERE `id_cliente` = '".$unCliente['id']."'"));

	  		$maquinasCliente = $db->while_loop($db->query("SELECT * FROM `maquina` WHERE `id_cliente` = '".$unCliente['id']."'"));
	  		$ultimaReparacionTemp = '1900-01-01' ;
	  		
	  		foreach ($maquinasCliente as $unaMaquinaCliente) :
	  			$ultimaReparacion = $db->fetch_assoc($db->query("SELECT * FROM `reparacion_maquina` WHERE `id_maquina` = '".$unaMaquinaCliente['id']."' AND `fecha_egreso` IS NOT NULL order by `fecha_egreso` DESC, `hora_egreso` DESC LIMIT 1"));
		  			if ( $ultimaReparacion['fecha_egreso'] >= $ultimaReparacionTemp ) {
		  				$ultimaReparacion1 = $ultimaReparacion ;
		  				$ultimaReparacionTemp = $ultimaReparacion['fecha_egreso'] ;
		  			} else {
		  				$ultimaReparacion1 = $ultimaReparacion1 ;
		  			}

		  		$reparacionesDeUnaMaquina = $db->while_loop($db->query("SELECT `id` FROM `reparacion_maquina` WHERE `id_maquina` = '".$unaMaquinaCliente['id']."'"));
		  		foreach ($reparacionesDeUnaMaquina as $unaReparacionDeUnaMaquina) :
		  			$cotizacionesDeUnaMaquinaCobradas = $db->fetch_assoc($db->query("SELECT `total` FROM `reparacion_cotizacion` WHERE `reparacion_id` = '".$unaReparacionDeUnaMaquina['id']."' AND `fecha_entrega_maquina` IS NOT NULL"));
		  			$cotizacionesDeUnaMaquina = $db->fetch_assoc($db->query("SELECT `total` FROM `reparacion_cotizacion` WHERE `reparacion_id` = '".$unaReparacionDeUnaMaquina['id']."'"));

		  			$sumaCotizacionesDelClienteCobradas = $sumaCotizacionesDelClienteCobradas + $cotizacionesDeUnaMaquinaCobradas['total'];
		  			$sumaCotizacionesDelCliente = $sumaCotizacionesDelCliente + $cotizacionesDeUnaMaquina['total'];
		  			$sumaCotizacionesDelClienteSinCobro = $sumaCotizacionesDelCliente - $sumaCotizacionesDelClienteCobradas;
		  		endforeach;

	  		endforeach;

	  		$ultimaCotizacion = $db->fetch_assoc($db->query("SELECT `total` FROM `reparacion_cotizacion` WHERE `reparacion_id` = '".$ultimaReparacion1['id']."'"));
	  		?>	
	      <tr class="">
	      	<td class="text-center"><?php echo $unCliente['id']; ?></td>

	      	<td class=""><?php echo $unCliente['razon_social']; ?></td>

	      	<td class=""><?php echo $unCliente['cuit']; ?></td>

	      	<td class="text-center"><?php echo $unCliente['iva']; ?></td>

	      	<td class=""><?php if ($unCliente['tel']) { echo "Tel: ".$unCliente['tel']; } else { echo "Cel: ".$unCliente['cel']; } ?></td>

	        <td class="text-center"><?php echo $cantMaquinas; ?></td>

	        <td class="text-center"><a type="button" id="a_ver-maquinas" class="btn btn-info btn-xs" onclick="javascript:goVerReparacionesMaquina('<?php echo $unCliente['id']; ?>');" data-toggle="tooltip" title="Ver todas las reparaciones por máquina"><span class="glyphicon glyphicon-th-list" style="color: white;"></span></a><a type="button" id="btn_go-maquinas" class="btn btn-warning btn-xs" title="Ir a las máquinas del cliente para editarlas" data-toggle="tooltip" onclick="javascript:goMaquinasCliente('<?php echo $unCliente['id']; ?>');"><span class="glyphicon glyphicon-share-alt" style="color: white;"></span></a></td>

			<td class="text-center"><?php if($ultimaCotizacion) { echo "$ ".$ultimaCotizacion['total']; } else { echo "-- --"; } ?></td>

			<td class="text-center"><?php if($sumaCotizacionesDelClienteCobradas != 0) { echo "$ ".$sumaCotizacionesDelClienteCobradas; } else { echo "-- --"; }?></td>

			<td class="text-center"><?php if ($sumaCotizacionesDelClienteSinCobro != 0) { echo "$ ".$sumaCotizacionesDelClienteSinCobro; } else { echo "-- --"; }?></td>

			<td class="text-center"><?php if ($unCliente['estado'] == 1) { ?> <span class="label label-success">Activo <?php } else { ?><span class="label label-danger">Inactivo <?php } ?></span></td>

	        <td class="text-center">
	        	<div class="btn-group">
    				<a id="btn_editar_<?php echo (int)$unCliente['id'];?>" class="btn btn-info btn-xs"  title="Editar cliente" data-toggle="tooltip" onclick="javascript:goEditarCliente('<?php echo $unCliente['id']; ?>');" style="color: white;"><span class="glyphicon glyphicon-edit"></span>
    				</a>
    				<!-- href="delete_clienteMaq.php?id=<?php// echo (int)$unCliente['id'];?>" 
    				onclick="return confirm('¿Seguro que desea eliminar este cliente?')"-->
    				<a disabled href="#" class="btn btn-xs btn-danger"  title="Eliminar cliente" data-toggle="tooltip" ><span class="glyphicon glyphicon-trash"></span>
    				</a>	
	        	</div></td>

	      </tr>
	    <?php
	    $ultimaReparacion1 = null;
	    $sumaCotizacionesDelClienteCobradas = 0 ;
	    $sumaCotizacionesDelCliente = 0 ;
	    $sumaCotizacionesDelClienteSinCobro = 0 ;
		endforeach ;
		?>
	  </tbody>
	  <tfoot>
	    <tr>
	      <th class="text-center" style="width: 4%;">ID</th>
	      <th style="width: 17%;">Nombre / Razón social</th>
	      <th style="width: 7%;">Dni / Cuit</th>
	      <th class="text-center" style="width: 10%;">IVA</th>
	      <th class="text-center" style="width: 8%;">Teléfono / Celular</th>
	      <th class="text-center" style="width: 8%;">Máquinas</th>
	      <th class="text-center" style="width: 8%;">Acciones</th>
	      <th class="text-center" style="width: 8%;">Monto última rep.</th>
	      <th class="text-center" style="width: 8%;">Monto histórico</th>
	      <th class="text-center" style="width: 8%;">Sin Cobrar</th>
	      <th class="text-center" style="width: 9%;">Estado</th>
	      <th class="text-center" style="width: 5%;">Acciones</th>
	    </tr>
	  </tfoot>
	</table>
	<script>
	  $('#tbl_maquinas').DataTable({"ordering": false}/*{ "order": [[ 0, "desc" ],[ 2, "desc" ]]  }*/);
	  $('.dataTables_length').addClass('bs-select');
	  
	  /*$(function() {
	    $('#mediciones tr').each(function() {
	      $(this).find('th:eq(0)').addClass("hidden");
	    });
	  });*/
	</script>
</div>
