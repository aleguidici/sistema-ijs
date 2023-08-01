<?php
  $page_title = 'Nueva Reparación';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
  $estaMaquina = find_by_id('maquina',$_GET['idMaquina']);
  $clienteOK = find_by_id('clientemaquina',$estaMaquina['id_cliente']);
  $provinciaOK = find_by_id_prov('provincia',$clienteOK['provincia']);
  $esteModelo = find_by_id('maquina_modelo',$estaMaquina['modelo_id']);
  $estaMarca = find_by_id('maquina_marca',$esteModelo['marca_id']);
 
  // $all_matriculados = find_all_matriculados('personal_matriculas');
  // $mydata = Array();
  // $tabla = "";
  // $query_parts = Array();

  if(isset($_POST['btnCrear'])){
   
   $idMaquinaok = remove_junk($db->escape($_POST['idMaquinaFinal']));
   $senia = remove_junk($db->escape($_POST['senia']));
   $fecha = date('Y-m-d');
   $hora = date('H:i:s');
   $observacionesPost = remove_junk($db->escape($_POST['observaciones']));
   if ($observacionesPost != null) {
    $observaciones = $observacionesPost ;
   } else {
    $observaciones = "" ;
   }
   //echo '<script language="javascript">';
   //echo 'window.alert('.$senia.');';
   //echo '</script>';
 //echo "<script>console.log('b');</script>";
   //$idModelo = remove_junk($db->escape($_POST['modelo']));
   //$numSerie = remove_junk($db->escape($_POST['numSerie']));
   //$descripcionFinal = remove_junk($db->escape($_POST['descripcionFinal']));
   //$forma_p = remove_junk($db->escape($_POST['forma_pago']));
 

	 //$query  = "INSERT INTO reparacion_maquina (`id_maquina`, `fecha_ingreso`, `hora_ingreso`, `senia`, `id_estado`) VALUES ('{$idMaquinaok}', '{$fecha}', '{$hora}', '{$senia}', 1)";
   $query  = "INSERT INTO reparacion_maquina (`id_maquina`, `fecha_ingreso`, `hora_ingreso`, `senia`, `id_estado`, `descripcion`) VALUES ('{$idMaquinaok}', '{$fecha}', '{$hora}', '{$senia}', '1', '{$observaciones}')";

	 if($db->query($query)){
	  $session->msg("s", "Reparación agregada exitosamente.");
	  redirect('conAc.php',false);
	 } else {
	  $session->msg("d", "Lo siento, el registro falló");
	  redirect('conAc.php',false);
  }  
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

          <form method="post" action="conAc_agregarReparacionMaquina.php"> 
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
              <div class="form-group col-xs-4 mr-4">

                <label for="senia" class="control-label">Observaciones:</label>
                <textarea type="text" class="form-control" name="observaciones" id="observaciones" rows="3" placeholder="Observaciones" maxlength="235"></textarea>              

              </div>
              <div class="form-group col-xs-2 mr-2">
                <label for="senia" class="control-label">Seña:</label>
                <input type="number" min="0" step="50" class="form-control" name="senia" id="senia" placeholder="Seña:" maxlength="10" required onchange="javascript:seniado()">
                <input type="hidden" class="form-control" name="idMaquinaFinal" value="<?php echo ($_GET['idMaquina'])?>">

              </div>
            </div>

            <div class="row">
              <div class="form-group col-xs-12 mr-2">
                <div class="form-group text-right">   
                  <a class="btn btn-primary" href="conAc.php" role=button>Volver</a>
                  <button type="submit" name="btnCrear" class="btn btn-danger">Crear Reparación</button>
                </div>
              </div>
            </div>
          </form> 
        </div>
      </div>

<script>

  $(document).ready(function(){
    $('#btnCrear').prop("disabled",true);
    $('#btnCrear').attr("disabled",true);

  });

  function seniado() {
    document.getElementById("btnCrear").disabled = false;
  }
</script>


<?php include_once('layouts/footer.php'); ?>