<?php
  $page_title = 'Editar Material o Insumo';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
?>
<?php
  $matInsu = find_by_id('inv_materiales_insumos',(int)$_GET['id']);
?>

<?php
  if(isset($_POST['edit_matInsu'])){
    $req_field = array('marca_temp_mi','descrip_temp_mi');
    validate_fields($req_field); 

    $marca = ucfirst(remove_junk($db->escape($_POST['marca_temp_mi'])));
    $unidad = remove_junk($db->escape($_POST['unidad_temp_mi']));
    $descripcion = ucfirst(remove_junk($db->escape($_POST['descrip_temp_mi'])));
    $tipo = ucfirst(remove_junk($db->escape($_POST['tipo_temp_mi'])));
    $codigo = ucfirst(remove_junk($db->escape($_POST['cod_temp_mi'])));
    $precio = str_replace(',', '.',remove_junk($db->escape($_POST['precio_temp_mi'])));
    $cant_minOK = (int)remove_junk($db->escape($_POST['cant_min_temp_mi']));


    if(empty($errors)){ 
      $query = "UPDATE inv_materiales_insumos SET marca='{$marca}', tipo='{$tipo}', cod='{$codigo}', descripcion='{$descripcion}', precio_lista='{$precio}', unidad='{$unidad}', cant_min='{$cant_minOK}' WHERE id = '{$matInsu['id']}'";  
      $result = $db->query($query);
      
      if($result && $db->affected_rows() === 1) {
        $session->msg("s", "Material / Insumo actualizado con éxito. [Cod. IJS: '{$matInsu['id']}']");
        redirect('inventario.php',false);
      } else {
        $session->msg("d", "Lo siento, actualización del material / insumo falló.");
        redirect('inventario.php',false);
      }
    }else{
      $session->msg("d", $errors);
      redirect('inventario.php',false);
    }
  }
?>

<?php include_once('layouts/header.php'); ?>

<link rel="stylesheet" type="text/css" href="libs/alertifyjs/css/alertify.css">
<link rel="stylesheet" type="text/css" href="libs/alertifyjs/css/themes/default.css">

<script src="libs/alertifyjs/alertify.js"></script>    

<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Editando Material / Insumo:</span>
          <em><h4>Cod. IJS: <?php echo $matInsu['id'];?></h4></em>
        </strong>
      </div>

      <div class="panel-body">
        <form method="post" action="edit_matInsu.php?id=<?php echo (int)$matInsu['id'];?>">
          <h4>Campos obligatorios:</h4>
          <div class="form-group  col-xs-7 mr-2">
            <label>Marca:</label>
            <input type="name" id="marca_temp_mi" name="marca_temp_mi" class="form-control" placeholder="Marca" required maxlength="100" value="<?php echo $matInsu['marca'];?>">
          </div>
          <div class="form-group  col-xs-5 mr-2">
            <label>Unidad:</label>
            <select required="required" class="form-control" name="unidad_temp_mi" id="unidad_temp_mi">
              <option value="" disabled selected>Seleccione una unidad</option>
              <option value="Uds."> Unidades </option>
              <option value="Kgs."> Kilos </option>
              <option value="Mts."> Metros </option>
              <option value="Lts."> Litros </option>
              <option value="Caj. / Paq."> Cajas / Paquetes </option>
            </select>
            <script>
                var val = "<?php echo $matInsu['unidad'] ?>";
                document.getElementById("unidad_temp_mi").value = val;
            </script>
          </div>       
          <div class="form-group  col-xs-12 mr-2">
              <label>Descripción:</label>
              <textarea type="name" class="form-control" placeholder="Descripción" id="descrip_temp_mi" name="descrip_temp_mi" required="required" maxlength="350"></textarea>
          </div>
          <script>
              document.getElementById("descrip_temp_mi").value = "<?php echo $matInsu['descripcion'];?>";
          </script>
          
          <hr>
          <h4>Otros datos (Opcionales):</h4>            
          <div class="form-group col-xs-4 mr-4">
            <label>Tipo o Modelo:</label>
            <input type="name" class="form-control" id="tipo_temp_mi" name="tipo_temp_mi" placeholder="Tipo" maxlength="255" value="<?php echo $matInsu['tipo'];?>">
          </div>
          <div class="form-group col-xs-3 mr-3">
            <label>Cod.:</label>
            <input type="name" class="form-control" id="cod_temp_mi" name="cod_temp_mi" placeholder="Cod." maxlength="50" value="<?php echo $matInsu['cod'];?>">
          </div>
          <div class="form-group col-xs-3 ml-3">
            <label>Precio de lista en dólares:</label>
            <input type="name" class="form-control" placeholder="Precio de lista" id="precio_temp_mi" name="precio_temp_mi" value="<?php echo str_replace('.', ',',number_format($matInsu['precio_lista'],2));?>" pattern="[0-9,.+\-\s]+" title="Sólo números" onchange="javascript:precio_mi_change()">
          </div>
          <script>
            document.getElementById("descrip_temp_mi").value = "<?php echo $matInsu['descripcion'];?>";
            
            function precio_mi_change(){
              var precio_reemp_mi = document.getElementById("precio_temp_mi").value;
              precio_reemp_mi = precio_reemp_mi.replace(",", ".");
              precio_reemp_mi = parseFloat(precio_reemp_mi).toFixed(2);
              if (isNaN(precio_reemp_mi)){
                precio_temp_mi.value = "";
                alertify.error("El monto ingresado no es válido.");
              }
              else 
                precio_temp_mi.value = precio_reemp_mi.replace(".", ",");
            };
          </script>
          <div class="form-group col-xs-2 ml-2">
            <label>Cant. mínima:</label>
            <input class="form-control" name="cant_min_temp_mi" id="cant_min_temp_mi" type="number" min="0" value="<?php echo $matInsu['cant_min'];?>" required onchange="javascript:cantidad_min_mi_change()">
          </div>
          <script>
            function cantidad_min_mi_change(){
              var cant_min_mi = parseInt(document.getElementById("cant_min_temp_mi").value);
              if (Number.isInteger(cant_min_mi))
                cant_min_temp_mi.value = cant_min_mi;
              else
                cant_min_temp_mi.value = "";
            };
          </script>
          
          <div class="form-group col-xs-12 mr-2" align="right">
            <a href="inventario.php" class="btn btn-success">Volver</a>
            <button type="submit" name="edit_matInsu" class="btn btn-primary">Editar Material / Insumo</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include_once('layouts/footer.php'); ?>
