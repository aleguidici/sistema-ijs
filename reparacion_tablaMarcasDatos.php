<?php
	require_once('includes/load.php');
	page_require_level(2);
	$idMarca = remove_junk($db->escape($_GET['id_marca']));
	$allModelos = $db->while_loop($db->query("SELECT `maquina_modelo`.`id`, `codigo`, `maquina_modelo`.`descripcion`, `maquina_tipo`.`id` AS `tipo_id`, `maquina_tipo`.`descripcion` AS `tipo`, `inalambrico`, `anio`, `tamanio_id`, `maquina_tamanio`.`descripcion` AS `tamanio`, `despiece_id`, `maquina_despieces`.`file_name` AS `despiece` FROM `maquina_modelo` JOIN `maquina_tipo` ON `maquina_tipo`.`id` = `maquina_modelo`.`tipo_id` LEFT JOIN `maquina_despieces` ON `maquina_despieces`.`id` = `maquina_modelo`.`despiece_id` JOIN `maquina_tamanio` ON `maquina_tamanio`.`id` = `maquina_modelo`.`tamanio_id` WHERE `marca_id` = '".$idMarca."' ORDER BY 2"));
	$allMarcas = $db->while_loop($db->query("SELECT * FROM `maquina_marca` ORDER BY 2"));
	$allMaquinaTipos = $db->while_loop($db->query("SELECT * FROM `maquina_tipo` ORDER BY 2"));
	$allMaquinaTamanio = $db->while_loop($db->query("SELECT * FROM `maquina_tamanio` ORDER BY 2"));
?>
<script type="text/javascript">
	function editModelo(idModelo, idMarca, idTipo, codigo, idTamanio, inalambrico, anio, descripcion) {
		document.getElementById('inp_id-modelo').value = idModelo;
		document.getElementById('sel_marca').value = idMarca;
		document.getElementById('sel_tipo-modelo').value = idTipo;
		document.getElementById('inp_codigo-modelo').value = codigo;
		document.getElementById('sel_tamanio-modelo').value = idTamanio;
		document.getElementById('sel_inalambrico').value = inalambrico;
		document.getElementById('inp_anio').value = anio;
		document.getElementById('text_detalle-modelo').value = descripcion;
	}

	function updateModelo() {
		var modelo = document.getElementById('inp_id-modelo').value;
		var marca = document.getElementById('sel_marca').value;
		var tipo = document.getElementById('sel_tipo-modelo').value;
		var codigo = document.getElementById('inp_codigo-modelo').value;
		var tamanio = document.getElementById('sel_tamanio-modelo').value;
		var inalambrico = document.getElementById('sel_inalambrico').value;
		var anio = document.getElementById('inp_anio').value;
		var detalle = document.getElementById('text_detalle-modelo').value;

		if (marca && tipo && codigo && tamanio && inalambrico) {
			$('#btn_update-modelo').prop('disabled', true);
			datosModelo = "&modelo=" + modelo + "&marca=" + marca + "&tipo=" + tipo + "&codigo=" + codigo + "&tamanio=" + tamanio + "&inalambrico=" + inalambrico + "&anio=" + anio + "&detalle=" + detalle;
	    $.ajax({
	      type:"POST",
	      url:"edit_modeloMaquina.php",
	      data:datosModelo,
	      success:function(r) {
	        if (r == 1) {
	          alertify.success("Modelo editado correctamente y actualizando datos. Por favor espere...");
	          $("#modal_edit-modelo").modal("hide");
	          //smooth fade de la pagina
            $('#contenedor-tablas').fadeOut(500);
            setTimeout(function() {              
              $('#contenedor-tablas').load('reparacion_tablaMarcas.php');
              setTimeout(function() {   
                $('#contenedor-tablas').fadeIn(1000);
              },50);
            },750);       
	        } else {
	        	if (r == 2) {
	        		alertify.warning("Error. Al parecer ya existe ése código de modelo para ésta marca");
          		$('#btn_update-modelo').prop('disabled', false);
	        	} else {
          		alertify.error("Error.");
          		$('#btn_update-modelo').prop('disabled', false);
        		}
	      	}
	      }
	    });			
	  } else {
	  	alertify.warning("¡Ups! Al parecer hay campos requeridos sin completar.");
	  }
	}
</script>
<div class="row" style="background-color: rgba(150, 150, 150, 0.2); border-radius: 5px;height: 36px;">
	<div class="col-md-6"><p style="font-size: 22px;line-height: 23px;margin-top: 6px;margin-bottom: -8px;">Lista de modelos</p></div>
	<div class="col-md-6"></div>
</div>
<br>
<div class="table-responsive" style="width: 100%;">
	<table id="tbl_reparaciones" class="table table-hover table-condensed table-bordered table-striped">
	  <thead>
			<tr>
				<th class="text-center" style="width: 18%;">Tipo</th>
	      <th class="text-center" style="width: 15%;">Código</th>
	      <th style="width: 30%;">Detalles</th>
	      <th class="text-center" style="width: 10%;">Tamaño</th>
	      <th class="text-center" style="width: 8%;">Portabilidad</th>
	      <th class="text-center" style="width: 5%;">Año</th>
	      <th class="text-center" style="width: 7%;">Despiece</th>
	      <th class="text-center" style="width: 7%;">Aciones</th>
	    </tr>
	  </thead>
	  <tbody>
	  	<?php foreach ($allModelos as $unModelo) : ?>
	  	<tr>
	  		<td class="text-center"><?php echo $unModelo['tipo']; ?></td>

	    	<td class="text-center info"><?php echo $unModelo['codigo']; ?></td>

	    	<td class=""><?php echo $unModelo['descripcion']; ?></td>

	    	<td class="text-center">
	    		<?php if ($unModelo['tamanio_id'] == 1) { ?>
	    		<span class="label label-info"><?php echo $unModelo['tamanio']; ?></span>
	    	<?php } elseif ($unModelo['tamanio_id'] == 2) { ?>
	    		<span class="label label-success"><?php echo $unModelo['tamanio']; ?></span>
	    		<?php } elseif ($unModelo['tamanio_id'] == 3) { ?>
	    		<span class="label label-warning"><?php echo $unModelo['tamanio']; ?></span>
	    		<?php } elseif ($unModelo['tamanio_id'] == 4) { ?>
	    			<span class="label label-danger"><?php echo $unModelo['tamanio']; ?></span>
	    			<?php } ?></td>

	    	<td class="text-center"><?php if ($unModelo['inalambrico'] == 1) { echo "Inalámbrica"; } else { echo "Cableada"; } ?></td>		

	    	<td class="text-center"><?php if ($unModelo['anio']) { echo $unModelo['anio']; } else { echo " -- -- "; } ?></td>		

	    	<td class="text-center">
	    		<?php if ($unModelo['despiece_id'] != 0) { ?>
	    		<div class="btn-group">
	    			<a type="button" class="btn btn-success btn-xs" title="Ver despiece" data-toggle="tooltip" target='_blank' href="uploads/despieces/<?php echo $unModelo['despiece'];?>"><span class="glyphicon glyphicon-eye-open"></span></a>
	    			<a type="button" class="btn btn-warning btn-xs" title="Editar despiece actual" data-toggle="tooltip" target='_blank' href="despieces.php"><span class="glyphicon glyphicon-pencil"></span></a>
	    		</div>
	    	<?php } else { ?>
	    		<div class="btn-group">
	    			<a disabled type="button" class="btn btn-warning btn-xs" title="Todavía no tiene ningún despiece agregado" data-toggle="tooltip"><span class="glyphicon glyphicon-eye-close"></span></a>
	    			<a type="button" class="btn btn-success btn-xs" title="Agregar despiece" data-toggle="tooltip" target='_blank' href="despieces.php"><span class="glyphicon glyphicon-plus"></span></a>
	    		</div>
	    		<?php } ?></td>

	    	<td class="text-center">
	    		<div class="btn-group">
	    			<a type="button" class="btn btn-warning btn-xs" id="a_modal_edit-modelo" data-toggle="modal" data-target="#modal_edit-modelo" onclick="javascript:editModelo('<?php echo $unModelo['id']; ?>','<?php echo $idMarca; ?>','<?php echo $unModelo['tipo_id']; ?>','<?php echo $unModelo['codigo']; ?>','<?php echo $unModelo['tamanio_id']; ?>','<?php echo $unModelo['inalambrico']; ?>','<?php echo $unModelo['anio']; ?>','<?php echo $unModelo['descripcion']; ?>');"><span class="glyphicon glyphicon-pencil" style="color: white;"></span></a>
	    			<a disabled type="button" class="btn btn-danger btn-xs" title="Dar de baja al modelo" data-toggle="tooltip" href="#"><span class="glyphicon glyphicon-trash" style="color: white;"></span></a>
	    		</div></td>
	    </tr>
		<?php endforeach ;?>
	  </tbody>
	  <tfoot>
	    <tr>
	      <th class="text-center" style="width: 18%;">Tipo</th>
	      <th class="text-center" style="width: 15%;">Código</th>
	      <th style="width: 30%;">Detalles</th>
	      <th class="text-center" style="width: 10%;">Tamaño</th>
	      <th class="text-center" style="width: 8%;">Portabilidad</th>
	      <th class="text-center" style="width: 5%;">Año</th>
	      <th class="text-center" style="width: 7%;">Despiece</th>
	      <th class="text-center" style="width: 7%;">Aciones</th>
	    </tr>
	  </tfoot>
	</table>
	<script>
	  $('#tbl_reparaciones').DataTable({"ordering": false});
	  $('.dataTables_length').addClass('bs-select');
	  
	  /*$(function() {
	    $('#mediciones tr').each(function() {
	      $(this).find('th:eq(0)').addClass("hidden");
	    });
	  });*/
	</script>
</div>

<!-- INICIO MODAL EDITAR MAQUINA -- >
<!-- MODAL EDITAR DATOS MAQUINA-->
  <div class="modal bd-example-modal-lg" style="justify-content: center;align-content: center;" id="modal_edit-modelo" tabindex="-1" role="dialog" aria-labelledby="modal_edit-modelo-label" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="width: 700px !important; margin: 35px auto;" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title">Editar modelo</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
     	<div class="row">
     		<div class="col-md-8">
      		<div class="row">
		        <div class="col-md-6">
		        	<input hidden type="text" name="inp_id-modelo" id="inp_id-modelo">
		        	<label for="sel_marca" class="control-label">Marca: *</label>
		  				<select class="form-control" name="sel_marca" id="sel_marca">
		  				<option value="" disabled selected>Seleccione una marca:</option>        
		    			<?php	foreach ($allMarcas as $unaMarca): 
		    				if(substr($unaMarca['descripcion'], 0,3) != "---") { ?>
		      			<option value="<?php echo (int) $unaMarca['id'];?>"><?php echo ($unaMarca['descripcion']);?></option>
		    			<?php } endforeach; ?>
		  				</select>
		        </div>
		        <div class="col-md-6">
      				<label for="sel_tipo-modelo" class="control-label">Tipo: *</label>
      				<select class="form-control" name="sel_tipo-modelo" id="sel_tipo-modelo">
      				<option value="" disabled selected>Seleccione un tipo:</option>        
        			<?php	foreach ($allMaquinaTipos as $unTipo): ?>
          			<option value="<?php echo (int) $unTipo['id'];?>">                                  
            	<?php echo ($unTipo['descripcion']);?>
          			</option>
        			<?php endforeach; ?>
      				</select>
      			</div>
      		</div>
      		<br>
      		<div class="row">
      			<div class="col-md-6">
      				<label for="inp_codigo-modelo" class="control-label">Código de modelo (Nombre): *</label>
      				<input type="text" name="inp_codigo-modelo" id="inp_codigo-modelo" class="form-control" placeholder="Código">
      			</div>
      			<div class="col-md-6">
      				<label for="sel_tamanio-modelo" class="control-label">Tamaño: *</label>
      				<select class="form-control" name="sel_tamanio-modelo" id="sel_tamanio-modelo">        
        				<?php	foreach ($allMaquinaTamanio as $unTamanio): ?>
          			<option value="<?php echo (int) $unTamanio['id'];?>">                                  
            		<?php echo ($unTamanio['descripcion']);?>
          			</option>
        				<?php endforeach; ?>
      				</select>
      			</div>
      		</div>
      		<br>
      		<div class="row">
      			<div class="col-md-6">
      				<label for="sel_inalambrico" class="control-label">Inalámbrico: *</label>
      				<select class="form-control" name="sel_inalambrico" id="sel_inalambrico">        
          			<option value="0" selected>NO</option>
          			<option value="1">SI</option>
      				</select>
      			</div>
      			<div class="col-md-6">
      				<label for="inp_anio" class="control-label">Año: <sup>(opcional)</sup></label>
      				<input type="text" name="inp_anio" id="inp_anio" class="form-control" placeholder="Año" maxlength="10">
      			</div>
      		</div>
      	</div>
      	<div class="col-md-4">
      		<label for="text_detalle-modelo">Detalles: <sup>(Opcional)</sup></label>
        	<textarea type="text" class="form-control" placeholder="Descripción" id="text_detalle-modelo" maxlength="250" style="resize: none; height: 179px;"></textarea>
      	</div>
      </div>     	
      </div>
       <div class="modal-footer">
        <br>
        <div class="row">          
          <div class="col-md-6">            
            <button type="button" class="btn btn-danger" style="border: 1px solid white;" id="btn_cerrar-add-modelo"  data-dismiss="modal">Cancelar</button>
          </div>
       		<div class="col-md-6">                                        
            <button type="button" class="btn btn-success" style="border: 1px solid white;" id="btn_update-modelo" onclick="javascript:updateModelo();">Aceptar</button>
          </div>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
  <!-- END MODAL EDITAR MAQUINA -->