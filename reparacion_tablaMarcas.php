<?php
  require_once('includes/load.php');
  page_require_level(2);
  $allMarcas = $db->while_loop($db->query("SELECT * FROM `maquina_marca` ORDER BY 2"));
  $allModelos = $db->while_loop($db->query("SELECT `maquina_modelo`.`id`, `marca_id`, `codigo`, `maquina_modelo`.`descripcion`, `maquina_tipo`.`descripcion` AS `tipo` FROM `maquina_modelo` JOIN `maquina_tipo` ON `maquina_tipo`.`id` = `maquina_modelo`.`tipo_id` ORDER BY 5"));
  $allMaquinaTipos = $db->while_loop($db->query("SELECT * FROM `maquina_tipo` ORDER BY 2"));
  $allMaquinaTamanio = $db->while_loop($db->query("SELECT * FROM `maquina_tamanio` ORDER BY 1"));
?>

<script type="text/javascript">
	$(document).ready(function() {
    $('#sel_marcas').selectpicker();
    $('#sel_modelos').selectpicker();
    $('#div_sel-modelos').hide();
    $('#div_btn_agregar-modelo').hide();

    // </. reestablece los datos del modal add-modelo 
    $('#modal_add-modelo').on('hide.bs.modal', function (event) {
    	$(this).find('input,textarea,select').val('');
    	$('#sel_tamanio-modelo-tbl-marcas').val('1');
    	$('#sel_inalambrico-tbl-marcas').val('0');
    	document.getElementById('btn_add-modelo').disabled = false;
    });
    // </. hidden.bs.modal
    // </. reestablece los datos del modal add-modelo   

  });

	function cargarModelos() {
    $('#contenedor-repuestos').fadeOut(1000);
		$('#div_sel-modelos').fadeOut(1000);
		$('#div_btn_agregar-modelo').fadeOut(1000);
		var idMarca = document.getElementById("sel_marcas").value;
		alertify.success("Cargando, por favor espere.");
		$('#contenedor-modelos').fadeOut(1000);
		setTimeout(function() {              
      $('#contenedor-modelos').load('reparacion_tablaMarcasDatos.php?id_marca='+ idMarca +'');
      $('#contenedor-modelos').fadeIn(1000);
      $('#sel_modelos').fadeIn(1000);
      
    },1000);
		$('#div_sel-modelos').fadeIn(1000);
		$('#div_btn_agregar-modelo').fadeIn(1000);
		$('#sel_modelos').find('option').remove().end().append('<option value="" disabled selected>Seleccione un modelo:</option>');
    var selModelos = document.getElementById("sel_modelos");
    <?php foreach ($allModelos as $unModelo) : ?>
    	if (idMarca == <?php echo $unModelo['marca_id']; ?>) {
        var optModelos = document.createElement('option');
        var textModelos ="<?php echo $unModelo['tipo']." - '".$unModelo['codigo']."'";?>";
        optModelos.appendChild(document.createTextNode(textModelos));
        optModelos.value = parseInt("<?php echo $unModelo['id']; ?>");
        selModelos.appendChild(optModelos);
      }
    <?php endforeach ; ?>
		$('#sel_modelos').selectpicker('refresh'); // actualiza los valores del selectpicker*/
		document.getElementById('inp_id-marca-tbl-marcas').value = idMarca; //pasa el idMarca al input del boton para relacionar un modelo en caso de crearlo
	}

	function addMarca() {
		var nameMarca = document.getElementById('inp_name-marca').value;
		if(nameMarca) {
			datosMarca = "&nameMarca=" + nameMarca + "&accion=" + 1;
	    $('#btn_add-marca').prop('disabled', true);
	    $.ajax({
	      type:"POST",
	      url:"add_marcaMaquina.php",
	      data:datosMarca,
	      success:function(r) {
	        if (r == 1) {
	          alertify.success("Marca agregada correctamente.");
	          $('#contenedor-tablas').load('reparacion_tablaMarcas.php');
	          $("#modal_add-marca").modal("hide");                   
	        } else {
        		if (r == 2) {
          		alertify.error("Al parecer ésa marca ya está registrada.");
          		$('#btn_add-marca').prop('disabled', false);
          	} else {
          		alertify.error("Error.");
          		$('#btn_add-marca').prop('disabled', false);
        		}
	      	}
	    	}
			});
	  } else {
	  	alertify.warning("¡Ups! No puede dejar el campo del nombre vacío.");
	  }
	}

	function addModelo() {
		var codigo = document.getElementById('inp_codigo-modelo-tbl-marcas').value;
		var descripcion = document.getElementById('text_detalle-modelo-tbl-marcas').value;
		var inalambrico = document.getElementById('sel_inalambrico-tbl-marcas').value;
		var anio = document.getElementById('inp_anio-tbl-marcas').value;
		var tipo = document.getElementById('sel_tipo-modelo-tbl-marcas').value;
		var tamanio = document.getElementById('sel_tamanio-modelo-tbl-marcas').value;
		var marca = document.getElementById('inp_id-marca-tbl-marcas').value;
		if (codigo && tipo && tamanio && inalambrico) {
			$('#btn_add-modelo').prop('disabled', true);
			datosModelo = "&codigo=" + codigo + "&descripcion=" + descripcion + "&inalambrico=" + inalambrico + "&anio=" + anio + "&tipo=" + tipo + "&tamanio=" + tamanio + "&idMarca=" + marca;
	    $.ajax({
	      type:"POST",
	      url:"add_modeloMaquina.php",
	      data:datosModelo,
	      success:function(r) {
	        if (r == 1) {
	          alertify.success("Modelo agregado correctamente y actualizando datos. Por favor espere...");
	          $("#modal_add-modelo").modal("hide");
	          //smooth fade de la pagina
            $('#contenedor-tablas').fadeOut(500);
            setTimeout(function() {              
              $('#contenedor-tablas').load('reparacion_tablaMarcas.php');
              setTimeout(function() {   
                $('#contenedor-tablas').fadeIn(1000);
              },50);
            },750);
      ///             
	        } else {
        		if (r == 2) {
          		alertify.error("Al parecer ése modelo ya está registrado.");
          		$('#btn_add-modelo').prop('disabled', false);
          	} else {
          		alertify.error("Error.");
          		$('#btn_add-modelo').prop('disabled', false);
        		}
	      	}
	    	}
			});
	  } else {
	  	alertify.warning("¡Ups! Al parecer hay campos requeridos sin completar.");
	  }
	}

  function cargarRepuestos() {
    var idModelo = document.getElementById('sel_modelos').value;
    alertify.success("Cargando, por favor espere.");
      $('#contenedor-modelos').fadeOut(1000);
      setTimeout(function() {              
        $('#contenedor-repuestos').load('reparacion_tablaRepuestos.php?id_modelo='+ idModelo +'');
        $('#contenedor-repuestos').fadeIn(1000);
      },1000);
  }

</script>
<div class="row">
	<div class="col-md-4">
		<div>
			<br>
			<label for="sel_marcas" class="control-label">Seleccione una marca:</label>
			<select class="selectpicker" data-show-subtext="true" data-live-search="true" name="sel_marcas" id="sel_marcas" data-width="100%" onchange="javascript:cargarModelos();">
            <option value="" disabled selected>Seleccione una marca:</option>
      <?php
            foreach ($allMarcas as $unaMarca) :
            	if(substr($unaMarca['descripcion'], 0,3) != "---") { ?>
            			<option value="<?php echo $unaMarca['id']; ?>"><?php echo $unaMarca['descripcion']; ?></option>
      <?php   }
            endforeach; 
          	?>        
      </select>
		</div>
	</div>
	<div class="col-md-1" style="display: flex; flex-wrap: wrap; align-content:flex-end; padding: 0;"><a type="button" id="a_modal_add-marca" class="btn btn-success" data-toggle="modal" data-target="#modal_add-marca" onclick=""><span class="glyphicon glyphicon-plus" style="color: white;"></span></a>
	</div>
	
		<div class="col-md-4">
			<div>
				<br>
        <div id="contenedor-select-modelos">
  				<div id="div_sel-modelos">
  				<label for="sel_modelos" class="control-label">Seleccione un modelo:</label>
  				<select class="selectpicker" data-show-subtext="true" data-live-search="true" name="sel_modelos" id="sel_modelos" data-width="100%" onchange="javascript:cargarRepuestos();" hidden="true">
  	      </select>
  	  		</div>
        </div>
			</div>
		</div>
		<input type="text" name="inp_id-marca-tbl-marcas" id="inp_id-marca-tbl-marcas" hidden>
		<div class="col-md-1" id="div_btn_agregar-modelo" style="display: flex; flex-wrap: wrap; align-content:flex-end; padding: 0;"><a type="button" id="a_modal_add-modelo" class="btn btn-success" data-toggle="modal" data-target="#modal_add-modelo" onclick=""><span class="glyphicon glyphicon-plus" style="color: white;"></span></a>
		</div>
		<script type="text/javascript">
			$(document).on("click", "#a_modal_add-modelo", function () {
				var idMarcaToModal = document.getElementById('inp_id-marca-tbl-marcas').value;
				<?php foreach ($allMarcas as $unaMarca): ?>
				if (idMarcaToModal == '<?php echo $unaMarca['id']; ?>') {
  			document.getElementById('h_name-marca').innerHTML = '<?php echo $unaMarca['descripcion']; ?>'; //carga la marca en el modal
  			}
  		<?php endforeach; ?>
			});
		</script>

	<div class="col-md-2">
	</div>


</div>
<hr>

<div id="contenedor-modelos"></div>
<div id="contenedor-repuestos"></div>

<!--------------- MODAL ADD MARCA ------------------>
<div class="modal bd-example-modal-xs" id="modal_add-marca" tabindex="-1" role="dialog" aria-labelledby="modal_add-marca-label" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-xs" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title">Agregar una marca</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
      <label for="inp_name-marca" class="control-label">Nombre de la marca:</label>
       <input type="text" name="inp_name-marca" id="inp_name-marca" class="form-control">
      </div>
       <div class="modal-footer">
        <br>
        <div class="row">          
          <div class="col-md-6">            
            <button type="button" class="btn btn-danger" style="border: 1px solid white;" id="btn_cerrar-add-marca"  data-dismiss="modal">Cancelar</button>
          </div>
       		<div class="col-md-6">                                        
            <button type="button" class="btn btn-success" style="border: 1px solid white;" id="btn_add-marca" onclick="javascript:addMarca();">Aceptar</button>
          </div>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<!--------------- END MODAL MARCA -------------->
<!--------------- MODAL ADD MODELO ------------------>
<div class="modal bd-example-modal-lg" style="justify-content: center;align-content: center;" id="modal_add-modelo" tabindex="-1" role="dialog" aria-labelledby="modal_add-modelo-label" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="width: 700px !important; margin: 35px auto;" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title">Agregar un modelo</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
     	<div class="row">
        <div class="col-md-12">
        	<label>Marca:</label>
          <h3 style="font-weight: 500; letter-spacing: 2px; margin: 0;" id="h_name-marca"></h3>
        </div>
      </div>
      <hr>
      <div class="row">
      	<div class="col-md-8">
      		<div class="row">
      			<div class="col-md-6">
      				<label for="sel_tipo-modelo-tbl-marcas" class="control-label">Tipo: *</label>
      				<select class="form-control" name="sel_tipo-modelo-tbl-marcas" id="sel_tipo-modelo-tbl-marcas">
      				<option value="" disabled selected>Seleccione un tipo:</option>        
        			<?php	foreach ($allMaquinaTipos as $unTipo): ?>
          			<option value="<?php echo (int) $unTipo['id'];?>">                                  
            	<?php echo ($unTipo['descripcion']);?>
          			</option>
        			<?php endforeach; ?>
      				</select>
      			</div>
      			<div class="col-md-6">
      				<label for="inp_codigo-modelo-tbl-marcas" class="control-label">Código de modelo (Nombre): *</label>
      				<input type="text" name="inp_codigo-modelo-tbl-marcas" id="inp_codigo-modelo-tbl-marcas" class="form-control" placeholder="Código">
      			</div>
      		</div>
      		<br>
      		<div class="row">
      			<div class="col-md-4">
      				<label for="sel_tamanio-modelo-tbl-marcas" class="control-label">Tamaño: *</label>
      				<select class="form-control" name="sel_tamanio-modelo-tbl-marcas" id="sel_tamanio-modelo-tbl-marcas">        
        				<?php	foreach ($allMaquinaTamanio as $unTamanio): ?>
          			<option value="<?php echo (int) $unTamanio['id'];?>">                                  
            		<?php echo ($unTamanio['descripcion']);?>
          			</option>
        				<?php endforeach; ?>
      				</select>
      			</div>
      			<div class="col-md-4">
      				<label for="sel_inalambrico-tbl-marcas" class="control-label">Inalámbrico: *</label>
      				<select class="form-control" name="sel_inalambrico-tbl-marcas" id="sel_inalambrico-tbl-marcas">        
          			<option value="0" selected>NO</option>
          			<option value="1">SI</option>
      				</select>
      			</div>
      			<div class="col-md-4">
      				<label for="inp_anio-tbl-marcas" class="control-label">Año: <sup>(opcional)</sup></label>
      				<input type="text" name="inp_anio-tbl-marcas" id="inp_anio-tbl-marcas" class="form-control" placeholder="Año" maxlength="10">
      			</div>
      		</div>
      	</div>
      	<div class="col-md-4">
      		<label for="text_detalle-modelo-tbl-marcas">Descripción: <sup>(Opcional)</sup></label>
        	<textarea type="text" class="form-control" placeholder="Descripción" id="text_detalle-modelo-tbl-marcas" maxlength="250" style="resize: none; height: 107px;"></textarea>
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
            <button type="button" class="btn btn-success" style="border: 1px solid white;" id="btn_add-modelo" onclick="javascript:addModelo();">Aceptar</button>
          </div>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<!--------------- END MODAL MODELO -------------->
