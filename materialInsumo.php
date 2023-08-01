<?php
  $page_title = 'Agregar Cliente';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
?>

<?php
  if(isset($_POST['nuevo_matInsu'])){
    $marcaOK = remove_junk($db->escape($_POST['marca_temp']));
    $modeloOK = remove_junk($db->escape($_POST['modelo_temp']));
    $descripcionOK = remove_junk($db->escape($_POST['descrip']));
    $precio_listaOK = remove_junk($db->escape($_POST['precio']));
    $precio_listaOK = str_replace(',', '.', $precio_listaOK);
    $observacionesOK = remove_junk($db->escape($_POST['observ']));

    $query = "INSERT INTO inv_materiales_insumos (`marca`, `modelo`, `descripcion`, `cant`, `cant_disp`, `precio_lista`, `observaciones`) VALUES ('{$marcaOK}', '{$modeloOK}', '{$descripcionOK}', 0, 0, '{$precio_listaOK}', '{$observacionesOK}')";

    if($db->query($query)){
      $session->msg("s", "Material / insumo agregado exitosamente.");
      redirect('inventario.php',false);
    } else {
      $session->msg("d", "Lo siento, el registro falló");
      redirect('inventario.php',false);
    }               
  }
?>

<?php include_once('layouts/header.php'); ?>
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Nuevo Material / Insumo</span>
          </strong>
        </div>

        <form method="post">
          <div class="panel-body">
            <div class="form-group col-xs-6 mr-2 ">
              <label for="marca_temp" class="control-label">Marca:</label>
              <input type="name" name= "marca_temp" id="marca_temp" class="form-control" placeholder="Marca" required maxlength="100">
            </div>  
            <div class="form-group col-xs-6 mr-2">
              <label for="modelo_temp" class="control-label">Modelo (opcional):</label>
              <input type="name" class="form-control" name="modelo_temp" id="modelo_temp" placeholder="Modelo" maxlength="255">
            </div>

            <div class="form-group col-xs-12 mr-2">
              <label for="descrip" class="control-label">Proveedor y Descripción:</label>
              <textarea type="text" class="form-control" placeholder="Proveedor y Descripción" id="descrip" name="descrip" required maxlength="350"></textarea>
            </div>
            
            <div class="form-group col-xs-4 mr-2">
              <label for="precio" class="control-label">Precio de lista en dólares (opcional):</label>
              <input type="text" class="form-control" placeholder="Precio de lista" id="precio" name="precio" value="0,0" pattern="[0-9,.+\-\s]+" title="Sólo números" onchange="javascript:precio_change()">
            </div>
            <script>
              function precio_change(){
                var precio_temp = document.getElementById("precio").value;
                precio_temp = precio_temp.replace(",", ".");
                precio_temp = parseFloat(precio_temp).toFixed(2);
                if (precio_temp == "NaN")
                  precio_temp = 0;
                precio.value = precio_temp.replace(".", ",");
              };
            </script>

            <div class="form-group col-xs-8 mr-2 ">
              <label for="observ" class="control-label">Observaciones (opcional):</label>
              <input type="name" name="observ" id="observ" class="form-control" maxlength="255" placeholder="Observaciones">
            </div>
          </div>
          <div class="panel-body">
            <div class="form-group text-right">
              <button type="submit" name="nuevo_matInsu" class="btn btn-primary">Crear Material / Insumo</button>     
            </div>
          </div>
        </form> 
      </div>
    </div>
  </div>
<?php include_once('layouts/footer.php'); ?>