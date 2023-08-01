<?php
  $page_title = 'Agregar mats insumos';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
  $matInsu = find_by_id('inv_materiales_insumos',$_GET['id']);
?>

<?php
  if(isset($_POST['ingreso_matInsu'])){
    $cantOK = (int)remove_junk($db->escape($_POST['cantNueva']));
    $cantTot = $cantOK + (int)$matInsu['cant'];
    $cantDispTot = (int)$cantOK + (int)$matInsu['cant_disp'];
    $idtemp = $matInsu['id'];

    $sql = "UPDATE inv_materiales_insumos SET cant='{$cantTot}', cant_disp='{$cantDispTot}' WHERE id = '{$matInsu['id']}'";

    if($db->query($sql)){
      $session->msg("s", "Material / insumo actualizado exitosamente.");
      redirect('inventario.php',false);
    } else {
      $session->msg("d", "Lo siento, el registro falló");
      redirect('inventario.php',false);
    }               
  }
?>

<?php include_once('layouts/header.php'); ?>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
  
  <div class="col-md-9">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Ingreso de Material / Insumo</span>
        </strong>
      </div>

      <form method="post">
        <div class="panel-body">
          <div class="row">
            <div class="col-xs-10">
              <h4><b>Detalles:</b></h4>
            </div>
          </div>
          <div class="col-xs-12" />
            <div class="row">
              <div class="col-xs-3">
                <h5><b>Cod. IJS: </b></h5>
              </div>
              <div class="col-xs-9">
                <h5><?php 
                if (!empty($matInsu['id']))
                  echo $matInsu['id'];
                else
                  echo ' - ';
                ?></h5>
              </div>
              <div class="col-xs-3">
                <h5><b>Marca: </b></h5>
              </div>
              <div class="col-xs-9">
                <h5><?php 
                if (!empty($matInsu['marca']))
                  echo $matInsu['marca'];
                else
                  echo ' - ';
                ?></h5>
              </div>
              <div class="col-xs-3">
                <h5><b>Tipo: </b></h5>
              </div>
              <div class="col-xs-9">
                <h5><?php 
                if (!empty($matInsu['tipo']))
                  echo $matInsu['tipo'];
                else
                  echo ' - ';
                ?></h5>
              </div>
              <div class="col-xs-3">
                <h5><b>Cod.: </b></h5>
              </div>
              <div class="col-xs-9">
                <h5><?php 
                if (!empty($matInsu['cod']))
                  echo $matInsu['cod'];
                else
                  echo ' - ';
                ?></h5>
              </div>
              <div class="col-xs-3">
                <h5><b>Descrip.: </b></h5>
              </div>
              <div class="col-xs-9">
                <h5><?php 
                if (!empty($matInsu['descripcion']))
                  echo $matInsu['descripcion'];
                else
                  echo ' - ';
                ?></h5>
              </div>
              <div class="col-xs-3">
                <h5><b>Cantidad (existencia): </b></h5>
              </div>
              <div class="col-xs-9">
                <h5><?php 
                if (!empty($matInsu['cant']))
                  echo $matInsu['cant'];
                else
                  echo ' - ';
                ?></h5>
              </div>
              <div class="col-xs-3">
                <h5><b>Cantidad disponible: </b></h5>
              </div>
              <div class="col-xs-9">
                <h5><?php 
                if (!empty($matInsu['cant_disp']))
                  echo $matInsu['cant_disp'];
                else
                  echo ' - ';
                ?></h5>
              </div>
              <div class="col-xs-3">
                <h5><b>Cantidad mínima: </b></h5>
              </div>
              <div class="col-xs-9">
                <h5><?php 
                if ($matInsu['cant_min'] > 0)
                  echo $matInsu['cant_min'];
                else
                  echo ' - ';
                ?></h5>
              </div>
              <div class="col-xs-3">
                <h5><b>Unidad: </b></h5>
              </div>
              <div class="col-xs-9">
                <h5><?php 
                if (!empty($matInsu['unidad']))
                  echo $matInsu['unidad'];
                else
                  echo ' - ';
                ?></h5>
              </div>
            </div>  

          <hr>

          <div class="row">
            <div class="col-xs-3">
              <label for="cantNueva" class="control-label">Cantidad a ingresar:</label>
            </div>
            <div class="col-xs-3">
              <input name="cantNueva" id="cantNueva" type="number" value="1" onchange="javascript:cantidad_h_change()">
            </div>
            <script>
              function cantidad_h_change(){
                var cant_h = parseInt(document.getElementById("cantNueva").value);
                if (Number.isInteger(cant_h))
                  cantNueva.value = cant_h;
                else
                  cantNueva.value = "";
              };
            </script>

            <div class="col-xs-6" align="right">
              <a class="btn btn-primary" href="inventario.php" role=button>Volver a Inventario</a>
              <button type="submit" name="ingreso_matInsu" class="btn btn-danger ">Aceptar</button>
            </div>
          </div>
        </div>
      </form> 
    </div>
  </div>
<?php include_once('layouts/footer.php'); ?>