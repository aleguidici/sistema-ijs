<?php
  require_once('includes/load.php');
  page_require_level(2);
  $idModelo = remove_junk($db->escape($_GET['id_modelo']));
  $allRepuestos = $db->while_loop($db->query("SELECT * FROM `maquina_repuesto` WHERE `id_modelo` = '".$idModelo."' ORDER BY `codigo`"));
?>
<script type="text/javascript">
	function addRepuesto(idModelo) {
		var parte = document.getElementById('inp_num-parte-tbl-repuestos').value;
		var codigo = document.getElementById('inp_codigo-repuesto-tbl-repuestos').value;
		var descripcion = document.getElementById('text_descripcion-repuesto-tbl-repuestos').value;
		if (parte && codigo && descripcion) {
			$('#btn_add-repuesto').prop('disabled', true);
			datosRepuesto = "&idModelo=" + idModelo + "&parte=" + parte + "&codigo=" + codigo + "&descripcion=" + descripcion;
	    $.ajax({
	      type:"POST",
	      url:"add_repuestoMaquina.php",
	      data:datosRepuesto,
	      success:function(r) {
	        if (r == 1) {
	          alertify.success("Repuesto agregado correctamente y actualizando datos. Por favor espere...");
	          $("#modal_add-repuesto").modal("hide");
	          //smooth fade de la pagina
            $('#contenedor-repuestos').fadeOut(500);
            setTimeout(function() {              
              $('#contenedor-repuestos').load('reparacion_tablaRepuestos.php?id_modelo='+ idModelo +'');
              setTimeout(function() {   
                $('#contenedor-repuestos').fadeIn(1000);
              },50);
            },750);       
	        } else {
	        	if (r == 2) {
	        		alertify.warning("Error. Al parecer ya existe un repuesto con ése #Pieza y #Parte, para éste modelo");
          		$('#btn_add-repuesto').prop('disabled', false);
	        	} else {
          		alertify.error("Error.");
          		$('#btn_add-repuesto').prop('disabled', false);
        		}
	      	}
	      }
	    });			
	  } else {
	  	alertify.warning("¡Ups! Al parecer hay campos requeridos sin completar.");
	  }
	}

	function editRepuesto(idRepuesto, codigo, parte, descripcion, stock, idModelo) {
		document.getElementById('inp_id-modelo-edit').value = idModelo;
		document.getElementById('inp_id-repuesto-edit').value = idRepuesto;
		document.getElementById('inp_codigo-repuesto-tbl-repuestos-edit').value = codigo;
		document.getElementById('inp_num-parte-tbl-repuestos-edit').value = parte;
		document.getElementById('text_descripcion-repuesto-tbl-repuestos-edit').value = descripcion;
	}

	function updateRepuesto() {
		var idModelo = document.getElementById('inp_id-modelo-edit').value;
		var idRepuesto = document.getElementById('inp_id-repuesto-edit').value;
		var codigo = document.getElementById('inp_codigo-repuesto-tbl-repuestos-edit').value;
		var parte = document.getElementById('inp_num-parte-tbl-repuestos-edit').value;
		var descripcion = document.getElementById('text_descripcion-repuesto-tbl-repuestos-edit').value;

		if (idRepuesto && codigo && parte && descripcion) {
			$('#btn_update-repuesto').prop('disabled', true);
			updateRepuesto = "&idModelo=" + idModelo + "&idRepuesto=" + idRepuesto + "&codigo=" + codigo + "&parte=" + parte + "&descripcion=" + descripcion;
	    $.ajax({
	      type:"POST",
	      url:"edit_repuestoMaquina.php",
	      data:updateRepuesto,
	      success:function(r) {
	        if (r == 1) {
	          alertify.success("Repuesto editado correctamente y actualizando datos. Por favor espere...");
	          $('#modal_edit-repuesto').modal("hide");
	          //smooth fade de la pagina
            $('#contenedor-repuestos').fadeOut(500);
            setTimeout(function() {              
              $('#contenedor-repuestos').load('reparacion_tablaRepuestos.php?id_modelo='+ idModelo +'');
              setTimeout(function() {   
                $('#contenedor-repuestos').fadeIn(1000);
              },50);
            },750);       
	        } else {
	        	if (r == 2) {
	        		alertify.warning("Error. Al parecer ya existe ése código de modelo para ésta marca");
	        		$('#contenedor-repuestos').load('reparacion_tablaRepuestos.php?id_modelo='+idModelo);
	        		$('#modal_edit-repuesto').modal("hide");
	        		setTimeout(function() {
	        			alertify.warning("Recargando los datos, por favor espere...");
	        			setTimeout(function() {
	        				$('#a_modal_edit-repuesto-'+idRepuesto).click();
	        			},250);
	        		},50);	
          		$('#btn_update-repuesto').prop('disabled', false);
	        	} else {
          		alertify.error("Error.");
          		$('#btn_update-repuesto').prop('disabled', false);
        		}
	      	}
	      	console.log(r);
	      }
	    });			
	  } else {
	  	alertify.warning("¡Ups! Al parecer hay campos requeridos sin completar.");
	  }
	}
</script>

<div class="row" style="background-color: rgba(150, 150, 150, 0.2); border-radius: 5px;">
	<div class="col-md-6"><p style="font-size: 22px;line-height: 23px;margin-top: 6px;margin-bottom: -8px;">Lista de Repuestos</p></div>
	<div class="col-md-4"></div>
	<div class="col-md-2"><div class="pull-right"><button class="btn btn-success" id="a_modal_add-repuesto" data-toggle="modal" data-target="#modal_add-repuesto" onclick=""><span class="glyphicon glyphicon-plus"></span> Agregar repuesto</button></div></div>
</div>
<br>
<div class="table-responsive" style="width: 100%;">
	<table id="tbl_repuestos" class="table table-hover table-condensed table-bordered table-striped">
	  <thead>
			<tr>
				<th class="text-center" style="width: 8%;">Cód IJS</th>
				<th class="" style="width: 12%;"># Pieza / Código pieza</th>
				<th class="" style="width: 12%;"># Parte / Referencia</th>				
				<th class="" style="width: 30%;">Descripción</th>
				<th class="text-center" style="width: 8%;">Stock</th>
				<th class="text-center" style="width: 8%;">Precio</th>
				<th class="text-center" style="width: 17%;"></th>
				<th class="text-center" style="width: 5%;">Acciones</th>
	    </tr>
	  </thead>
	  <tbody>
	  	<?php foreach ($allRepuestos as $unRepuesto) : ?>
	  	<tr>
	  		<td class="text-center"><?php echo "IJS-RE: ".$unRepuesto['id']; ?></td>
	  		<td class=""><?php echo $unRepuesto['codigo']; ?></td>
	  		<td class=""><?php echo $unRepuesto['parte']; ?></td>	  		
	  		<td class=""><?php echo $unRepuesto['descripcion']; ?></td>
	  		<td class="text-center"><?php echo $unRepuesto['stock']; ?></td>
	  		<td class="text-center"> -- -- </td>
	  		<td></td>
	    	<td class="text-center">
	    		<div class="btn-group">
	    			<a type="button" class="btn btn-warning btn-xs" id="a_modal_edit-repuesto-<?php echo $unRepuesto['id']; ?>" data-toggle="modal" data-target="#modal_edit-repuesto" onclick="javascript:editRepuesto('<?php echo $unRepuesto['id']; ?>','<?php echo $unRepuesto['codigo']; ?>','<?php echo $unRepuesto['parte']; ?>','<?php echo $unRepuesto['descripcion']; ?>','<?php echo $unRepuesto['stock']; ?>','<?php echo $unRepuesto['id_modelo']; ?>');"><span class="glyphicon glyphicon-pencil" style="color: white;"></span></a>
	    			<a disabled type="button" class="btn btn-danger btn-xs" title="Dar de baja al modelo" data-toggle="tooltip" href="#"><span class="glyphicon glyphicon-trash" style="color: white;"></span></a>
	    		</div></td>
	    </tr>
		<?php endforeach ;?>
	  </tbody>
	  <tfoot>
	    <tr>
	      <th class="text-center" style="width: 8%;">Cód IJS</th>
				<th class="" style="width: 12%;"># Pieza / Código pieza</th>
				<th class="" style="width: 12%;"># Parte / Referencia</th>				
				<th class="" style="width: 30%;">Descripción</th>
				<th class="text-center" style="width: 8%;">Stock</th>
				<th class="text-center" style="width: 8%;">Precio</th>
				<th class="text-center" style="width: 17%;"></th>
				<th class="text-center" style="width: 5%;">Acciones</th>
	    </tr>
	  </tfoot>
	</table>
	<script>
	  $('#tbl_repuestos').DataTable({"ordering": false});
	  $('.dataTables_length').addClass('bs-select');
	  
	  /*$(function() {
	    $('#mediciones tr').each(function() {
	      $(this).find('th:eq(0)').addClass("hidden");
	    });
	  });*/
	</script>
</div>

<!-- MODAL ADD REPUESTO-->
  <div class="modal bd-example-modal-xs" id="modal_add-repuesto" tabindex="-1" role="dialog" aria-labelledby="modal_add-repuesto-label" data-backdrop="static" data-keyboard="false" aria-hidden="true" style="justify-content: center;align-content: center;">
  <div class="modal-dialog modal-xs" role="document"  style="width: 350px !important; margin: 35px auto;">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title">Agregar repuesto</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
     		<div class="row">
     			<div class="col-md-12">
     				<label class="control-label" for="inp_codigo-repuesto-tbl-repuestos"># Pieza / Código: <sup>(No es necesario escribir en mayúsculas)</sup></label>
     				<input type="text" name="inp_codigo-repuesto-tbl-repuestos" id="inp_codigo-repuesto-tbl-repuestos" class="form-control" placeholder="# Pieza o Código para proveedor">
      		</div>
      	</div>
      	<br>
      	<div class="row">
      		<div class="col-md-12">
     				<label class="control-label" for="inp_num-parte-tbl-repuestos"># Parte / Referencia: <sup>(No es necesario escribir en mayúsculas)</sup></label>
     				<input type="text" name="inp_num-parte-tbl-repuestos" id="inp_num-parte-tbl-repuestos" class="form-control" placeholder="# Parte o Referencia">
      		</div>
      	</div>
      	<br>
      	<!--
      	<div class="row">
     			<div class="col-md-12">
     				<label class="control-label" for="inp_stock-inicial-tbl-repuestos">Código de repuesto:</label>
     				<input type="text" name="inp_stock-inicial-tbl-repuestos" id="inp_stock-inicial-tbl-repuestos" class="form-control" placeholder="Código de repuesto según proveedor">
      		</div>
      	</div>
      	-->
      	<div class="row">
      		<div class="col-md-12">
      			<label for="text_descripcion-repuesto-tbl-repuestos" class="control-label">Descripción: <sup>(No es necesario escribir en mayúsculas)</sup></label>
        		<textarea type="text" class="form-control" placeholder="Descripción o Nombre del repuesto" name="text_descripcion-repuesto-tbl-repuestos" id="text_descripcion-repuesto-tbl-repuestos" maxlength="250" style="resize: none;"></textarea>
      		</div>
      	</div>       	
      </div>
      <div class="modal-footer">
      <br>
        <div class="row">          
          <div class="col-md-6">            
            <button type="button" class="btn btn-danger" style="border: 1px solid white;" id="btn_cerrar-add-repuesto"  data-dismiss="modal">Cancelar</button>
          </div>
       		<div class="col-md-6">                                        
            <button type="button" class="btn btn-success" style="border: 1px solid white;" id="btn_add-repuesto" onclick="javascript:addRepuesto('<?php echo $idModelo; ?>');">Aceptar</button>
          </div>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<!-- END MODAL ADD REPUESTO -->
<!-- MODAL EDIT REPUESTO-->
  <div class="modal bd-example-modal-xs" id="modal_edit-repuesto" tabindex="-1" role="dialog" aria-labelledby="modal_edit-repuesto-label" data-backdrop="static" data-keyboard="false" aria-hidden="true" style="justify-content: center;align-content: center;">
  <div class="modal-dialog modal-xs" role="document"  style="width: 350px !important; margin: 35px auto;">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title">Editar repuesto</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
      	<input hidden type="text" name="inp_id-repuesto-edit" id="inp_id-repuesto-edit">
      	<input hidden type="text" name="inp_id-modelo-edit" id="inp_id-modelo-edit">
     		<div class="row">
     			<div class="col-md-12">
     				<label class="control-label" for="inp_codigo-repuesto-tbl-repuestos-edit"># Pieza / Código: <sup>(No es necesario escribir en mayúsculas)</sup></label>
     				<input type="text" name="inp_codigo-repuesto-tbl-repuestos-edit" id="inp_codigo-repuesto-tbl-repuestos-edit" class="form-control" placeholder="# Pieza o Código para proveedor">
      		</div>
      	</div>
      	<br>
      	<div class="row">
      		<div class="col-md-12">
     				<label class="control-label" for="inp_num-parte-tbl-repuestos-edit"># Parte / Referencia: <sup>(No es necesario escribir en mayúsculas)</sup></label>
     				<input type="text" name="inp_num-parte-tbl-repuestos-edit" id="inp_num-parte-tbl-repuestos-edit" class="form-control" placeholder="# Parte o Referencia">
      		</div>
      	</div>
      	<br>
      	<!--
      	<div class="row">
     			<div class="col-md-12">
     				<label class="control-label" for="inp_stock-inicial-tbl-repuestos">Código de repuesto:</label>
     				<input type="text" name="inp_stock-inicial-tbl-repuestos" id="inp_stock-inicial-tbl-repuestos" class="form-control" placeholder="Código de repuesto según proveedor">
      		</div>
      	</div>
      	-->
      	<div class="row">
      		<div class="col-md-12">
      			<label for="text_descripcion-repuesto-tbl-repuestos-edit" class="control-label">Descripción: <sup>(No es necesario escribir en mayúsculas)</sup></label>
        		<textarea type="text" class="form-control" placeholder="Descripción o Nombre del repuesto" name="text_descripcion-repuesto-tbl-repuestos-edit" id="text_descripcion-repuesto-tbl-repuestos-edit" maxlength="250" style="resize: none;"></textarea>
      		</div>
      	</div>       	
      </div>
      <div class="modal-footer">
      <br>
        <div class="row">          
          <div class="col-md-6">            
            <button type="button" class="btn btn-danger" style="border: 1px solid white;" id="btn_cerrar-edit-repuesto"  data-dismiss="modal">Cancelar</button>
          </div>
       		<div class="col-md-6">                                        
            <button type="button" class="btn btn-success" style="border: 1px solid white;" id="btn_update-repuesto" onclick="javascript:updateRepuesto();">Aceptar</button>
          </div>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<!-- END MODAL EDIT REPUESTO -->