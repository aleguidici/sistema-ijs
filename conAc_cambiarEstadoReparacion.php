<?php
  $page_title = 'Cambiar Estado';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
  $estaMaquina = find_by_id('maquina',$_GET['idMaquina']);
  $clienteOK = find_by_id('clientemaquina',$estaMaquina['id_cliente']);
  $provinciaOK = find_by_id_prov('provincia',$clienteOK['provincia']);
  $esteModelo = find_by_id('maquina_modelo',$estaMaquina['modelo_id']);
  $estaMarca = find_by_id('maquina_marca',$esteModelo['marca_id']);
  $estaReparacionEnviar = find_by_id('reparacion_maquina',$_GET['id']);
   
  // $all_matriculados = find_all_matriculados('personal_matriculas');
  // $mydata = Array();
  // $tabla = "";
  // $query_parts = Array();

  if (isset($_POST['btn_no_tiene_arreglo'])) {
    $estaRazonNoReparacion = $_POST['detalleNoSirve'];
   
    if ($estaRazonNoReparacion != "") {
      $estaReparacion = $_GET['id'];
      $estaMaquina2 = $_GET['idMaquina'];

      $sql="UPDATE reparacion_maquina SET id_estado = 7 WHERE id = '{$estaReparacion}'";
      echo $result=$db->query($sql);

      $sql2="UPDATE maquina SET sin_reparacion = 1, razon_noreparacion = '{$estaRazonNoReparacion}' WHERE id = '{$estaMaquina2}'";
      echo $result2=$db->query($sql2);

      header("Location: conAc.php");
    }
  }

  if (isset($_POST['btn_colocacion_limpieza'])) {
      $estaReparacion = $_GET['id'];
      $estaMaquina = $_GET['idMaquina'];
      $sql="UPDATE reparacion_maquina SET id_estado = 5 WHERE id = '{$estaReparacion}'";
      echo $result=$db->query($sql);
      header("Location: conAc.php");
  }

?>


<?php include_once('layouts/header.php'); ?>
    <style type="text/css">
      body { 
        padding-right: 0 !important;
      }
    </style>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round|Open+Sans">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
    <link rel="stylesheet" type="text/css" href="libs/alertifyjs/css/alertify.css">
    <link rel="stylesheet" type="text/css" href="libs/alertifyjs/css/themes/default.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
    <script src="libs/alertifyjs/alertify.js"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> 
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
      
      <?php echo display_msg($msg); ?>

      <div class="panel panel-default" id="panelcito">
        <div class="panel-heading">

        </div>

        <div class="panel-body">
          <div class="row">
            <div class="col-md-10">
              <h5><label class="control-label">Cliente:</label></h5>
            </div>
          </div>
          <div class="row">
            <div class="col-md-2">
              &emsp;&emsp;
              <b>Razón social: </b>
            </div>
            <div class="col-md-10">
              <?php echo $clienteOK['razon_social'];?>
            </div>
          </div>

          <div class="row">
            <div class="col-md-2">
              &emsp;&emsp;
              <b>Dirección: </b>
            </div>
            <div class="col-md-10">
              <?php echo $clienteOK['direccion']?> <em><?php echo ' - '.$clienteOK['localidad'].' ('.$clienteOK['cp'].'), '.$provinciaOK['nombre'];?></em>
            </div>
          </div>

          <div class="row">
            <div class="col-md-2">
              &emsp;&emsp;
              <b>DNI/CUIT: </b>
            </div>
            <div class="col-md-4">
              <?php echo $clienteOK['cuit'].' ('.$clienteOK['iva'].')'  ;?>
            </div>
          </div>

          <div class="row">
            <div class="col-md-2">
              &emsp;&emsp;
              <b>Teléfono: </b>
            </div>
            <div class="col-md-4">
              <?php if ($clienteOK['tel'] != null) 
                echo $clienteOK['tel'];
              else
                echo "-"; ?>
            </div>
            <div class="col-md-2">
              &emsp;&emsp;
              <b>Celular: </b>
            </div>
            <div class="col-md-4">
              <?php if ($clienteOK['cel'] != null) 
                echo $clienteOK['cel'];
              else
                echo "-"; ?>
            </div>
          </div>

          <hr>
          <hr>

          <form method="post"> 
            <h5><label class="control-label">Datos máquina:</label></h5>
            <div class="row">
            </div>
            <div class="row">
              <div class="col-md-2">
                &emsp;&emsp;
                <b>Marca: </b>
              </div>
              <div class="col-md-10">
                <?php echo $estaMarca['descripcion'];?>
              </div>
            </div>

            <div class="row">
              <div class="col-md-2">
                &emsp;&emsp;
                <b>Modelo: </b>
              </div>
              <div class="col-md-10">
                <?php echo $esteModelo['codigo'];?>
              </div>
            </div>

            <div class="row">
              <div class="col-md-2">
                &emsp;&emsp;
                <b>Número de serie: </b>
              </div>
              <div class="col-md-4">
                <?php echo $estaMaquina['num_serie'];?>
              </div>
            </div>

            <hr>
            <hr>

            <div class="row">
              <div class="col-md-2">
                <a href="conAc.php" class="btn btn-primary" >Volver</a>
              </div>
              <div class="col-md-1">                
              </div>
              <div class="col-md-3">
                <a href="repuestos.php?idMaquina=<?php echo $estaMaquina['id'];?>&idReparacion=<?php echo $estaReparacionEnviar['id'];?>" class="btn btn-block btn-warning">Buscar repuestos</a>
              </div>
              <div class="col-md-3">
                <a href="conAc_cambiarEstadoReparacionSet.php?id=<?php echo $estaReparacionEnviar['id'];?>&estadoCambio=5"class="btn btn-block btn-success" name="btn_colocacion_limpieza" id="btn_colocacion_limpieza" onclick="return confirm('¿Seguro que desea cambiar el estado?')">Cambiar a colocación</a>
              </div>
              <div class="col-md-3">
                <button class="btn btn-block btn-danger" name="btn_no_tiene_arreglo" id="btn_no_tiene_arreglo" onclick="detallesNoSirve()">No tiene arreglo</button>
                <input type="text" name="detalleNoSirve" hidden id="detalleNoSirve" maxlength="3"></p>

                <script>
                  function detallesNoSirve() {
                    var razon = prompt("Por favor, especifique por qué no tiene más arreglo:", "");
                    if (razon != null){
                      //window.alert (typeof(razon));
                      if (razon != "") {
                        document.getElementById("detalleNoSirve").value = razon;
                      } else {
                        document.getElementById("detalleNoSirve").value  = "Sin razón";
                      }
                    } else {
                      document.getElementById("detalleNoSirve").value = "";
                    }
                  }
                  
                  
                </script>
              </div>
              <div class="col-md-3"></div>
            </div>
          </form> 


        </div>
      </div>



<?php include_once('layouts/footer.php'); ?>