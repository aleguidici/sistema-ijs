<?php
  $page_title = 'Editar Maquinaria o Instrumento';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);
?>
<?php
  $maquinaria = find_by_id('inv_maquinarias',(int)$_GET['id']);
?>

<?php
  if(isset($_POST['edit_maquin'])){
    $req_field = array('marca_temp', 'modelo_temp', 'tipo_temp', 'descrip_temp', 'anio_temp');
    validate_fields($req_field); 

    $marca = $db->escape($_POST['marca_temp']);
    $modelo = $db->escape($_POST['modelo_temp']);
    $tipo = $db->escape($_POST['tipo_temp']);
    $descrip = $db->escape($_POST['descrip_temp']);
    $anio = $db->escape($_POST['anio_temp']);
    $nSerie = $db->escape($_POST['nSerie_temp']);

    if(empty($errors)){ 
      $query = "UPDATE inv_maquinarias SET marca='{$marca}', modelo='{$modelo}', num_serie='{$nSerie}', descripcion='{$descrip}', anio='{$anio}', tipo='{$tipo}' WHERE id = '{$maquinaria['id']}'";  
      $result = $db->query($query);
      
      if($result && $db->affected_rows() === 1) {
        $session->msg("s", "Maquinaria / Instrumento actualizado con éxito. [Cod. IJS: '{$maquinaria['id']}']");
        redirect('inventario.php',false);
      } else {
        $session->msg("d", "Lo siento, actualización de Maquinaria / Instrumento falló.");
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
          <span>Editando Maquinaria / Herramienta:</span>
          <em><h4>Cod. IJS: <?php echo $maquinaria['id'];?></h4></em>
        </strong>
      </div>

      <div class="panel-body" >
        <form method="post" action="edit_maquin.php?id=<?php echo (int)$maquinaria['id'];?>">
          <h4>Campos obligatorios:</h4>
          <div class="row">
            <div class="form-group col-xs-5 mr-2">
              <label>Marca:</label>
              <input type="name" id="marca_temp" name="marca_temp" class="form-control" placeholder="Marca" required maxlength="100" value="<?php echo $maquinaria['marca'];?>">
            </div>
            <div class="form-group  col-xs-4 mr-2">
              <label>Modelo:</label>
              <input type="name" id="modelo_temp" name="modelo_temp" class="form-control" placeholder="Modelo" required maxlength="255" value="<?php echo $maquinaria['modelo'];?>">
            </div>  
            <div class="form-group  col-xs-3 mr-2">
              <label>Tipo:</label>
              <select required class="form-control" name="tipo_temp" id="tipo_temp">
                <option value="" disabled selected>Seleccione un tipo</option>
                <option value="Eléctrico/a"> Eléctrico/a </option>
                <option value="Hidráulico/a"> Hidráulico/a </option>
                <option value="Electro-Hidráulico/a"> Electro-Hidráulico/a </option>
                <option value="Mecánico/a"> Mecánico/a </option>
                <option value="Neumático/a"> Neumático/a </option>
              </select>
              <script>
                var val = "<?php echo $maquinaria['tipo'] ?>";
                document.getElementById("tipo_temp").value = val;
              </script>
            </div>
          </div>
          <div class="row">
            <div class="form-group  col-xs-9 mr-2">
              <label>Descripción:</label>
              <textarea type="name" class="form-control" placeholder="Descripción" id="descrip_temp" name="descrip_temp" required="required" maxlength="350"></textarea>
            </div>
            <script>
              document.getElementById("descrip_temp").value = "<?php echo $maquinaria['descripcion'];?>";
            </script>
            <div class="col-md-3">
              <label>Año:</label>
              <input type="number" id="anio_temp" name="anio_temp" class="form-control" placeholder="Año" min="1980" value="<?php echo $maquinaria['anio'];?>">
              <script>
                document.querySelector("input[name=anio_temp]")
                .oninput = e => console.log(new Date(e.target.valueAsNumber, 0, 1))

                var today = new Date();
                var yyyy = today.getFullYear();
                document.getElementById("anio_temp").setAttribute("max", yyyy);

                $("[id='anio_temp']").keypress(function (evt){ evt.preventDefault(); });
              </script>
            </div>
          </div>

          <hr>
          <h4>Otros datos (Opcionales):</h4>
          <div class="row">
            <div class="col-md-4">
              <label>Nº serie:</label>
              <input type="name" class="form-control" id="nSerie_temp" name="nSerie_temp" placeholder="Nº serie" maxlength="100" value="<?php echo $maquinaria['num_serie'];?>">
            </div>
          </div>
          <hr>          
          <div class="form-group col-xs-12 mr-2" align="right">
            <a href="inventario.php" class="btn btn-success">Volver</a>
            <button type="submit" name="edit_maquin" class="btn btn-primary">Editar Maquinaria / Instrumento</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include_once('layouts/footer.php'); ?>
