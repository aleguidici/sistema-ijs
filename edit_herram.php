<?php
  $page_title = 'Editar Herramienta';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(1);
?>
<?php
  $herram = find_by_id('inv_herramientas',(int)$_GET['id']);
?>

<?php
  if(isset($_POST['edit_herram'])){
    $req_field = array('marca_temp_h','descrip_temp_h', 'cantNueva_h');
    validate_fields($req_field); 

    $marca = ucfirst(remove_junk($db->escape($_POST['marca_temp_h'])));
    $cant = (int)remove_junk($db->escape($_POST['cantNueva_h']));
    $descripcion = ucfirst(remove_junk($db->escape($_POST['descrip_temp_h'])));
    $tipo = ucfirst(remove_junk($db->escape($_POST['tipo_temp_h'])));
    $codigo = ucfirst(remove_junk($db->escape($_POST['cod_temp_h'])));


    if(empty($errors)){ 
      $query = "UPDATE inv_herramientas SET marca='{$marca}', tipo='{$tipo}', cod='{$codigo}', descripcion='{$descripcion}', cant='{$cant}' WHERE id = '{$herram['id']}'";  
      $result = $db->query($query);
      
      if($result && $db->affected_rows() === 1) {
        $session->msg("s", "Herramienta actualizada con éxito. [Cod. IJS: '{$herram['id']}']");
        redirect('inventario.php',false);
      } else {
        $session->msg("d", "Lo siento, actualización de la herramienta falló.");
        redirect('group.php',false);
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
          <span>Editando Herramienta:</span>
          <em><h4>Cod. IJS: <?php echo $herram['id'];?></h4></em>
        </strong>
      </div>

      <div class="panel-body">
        <form method="post" action="edit_herram.php?id=<?php echo (int)$herram['id'];?>">
          <h4>Campos obligatorios:</h4>
          <div class="form-group col-xs-8 mr-2">
            <label>Marca:</label>
            <input type="name" id="marca_temp_h" name="marca_temp_h" class="form-control" placeholder="Marca" required maxlength="100" value="<?php echo $herram['marca'];?>">
          </div>
          <div class="form-group col-xs-4 mr-2">
            <label>Cantidad:</label>
            <input class="form-control" name="cantNueva_h" id="cantNueva_h" type="number" min="1" value="<?php echo $herram['cant'];?>" required onchange="javascript:cantidad_h_change()">
          </div>
          <script>
            function cantidad_h_change(){
              var cant_h = parseInt(document.getElementById("cantNueva_h").value);
              if (Number.isInteger(cant_h))
                cantNueva_h.value = cant_h;
              else
                cantNueva_h.value = "";
            };
          </script>

          <div class="form-group  col-xs-12 mr-2">
              <label>Descripción:</label>
              <textarea type="name" class="form-control" placeholder="Descripción" id="descrip_temp_h" name="descrip_temp_h" required="required" maxlength="350"></textarea>
          </div>
          <script>
              document.getElementById("descrip_temp_h").value = "<?php echo $herram['descripcion'];?>";
          </script>
          
          <hr>
          <h4>Otros datos (Opcionales):</h4>            
          <div class="form-group col-xs-7 mr-2">
            <label>Tipo o Modelo:</label>
            <input type="name" class="form-control" id="tipo_temp_h" name="tipo_temp_h" placeholder="Tipo" maxlength="255" value="<?php echo $herram['tipo'];?>">
          </div>
          <div class="form-group col-xs-5 mr-2">
            <label>Cod.:</label>
            <input type="name" class="form-control" id="cod_temp_h" name="cod_temp_h" placeholder="Cod." maxlength="50" value="<?php echo $herram['cod'];?>">
          </div>
          <script>
            document.getElementById("descrip_temp_h").value = "<?php echo $herram['descripcion'];?>";
          </script>
          
          <div class="form-group col-xs-12 mr-2" align="right">
            <a href="inventario.php" class="btn btn-success">Volver</a>
            <button type="submit" name="edit_herram" class="btn btn-primary">Editar Herramienta</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include_once('layouts/footer.php'); ?>
