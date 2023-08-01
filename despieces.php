<?php
  $page_title = 'Despieces';
  require_once('includes/load.php');  
  // Checkin What level user has permission to view this page
  page_require_level(2);
?>
  <?php
  $allModelos = $db->while_loop($db->query("SELECT * FROM `maquina_modelo`"));
  $allModelosSinDespiece = $db->while_loop($db->query("SELECT * FROM `maquina_modelo` WHERE `despiece_id` = 0"));
  $allArchivos = $db->while_loop($db->query("SELECT * FROM `maquina_despieces` WHERE `id` NOT IN (SELECT `despiece_id` FROM `maquina_modelo` WHERE `despiece_id` <> 0)"));


  if(isset($_POST['subir'])) {
    $directorio = 'uploads/despieces/';
    $subir_archivo = $directorio.basename($_FILES['archivo']['name']);
    $archi = $_FILES['archivo']['name'];
    $ext = strtolower(substr( $archi, strrpos( $archi, '.' ) + 1 ) );
    $archivExist = $db->fetch_assoc($db->query("SELECT * FROM `maquina_despieces` WHERE `file_name` = '{$archi}'"));
    if($archi != "") {
      if(!$archivExist) {
        if ($ext == "pdf") {
          //global $db;
          $sql = "INSERT INTO maquina_despieces (`file_name`, `file_type`) VALUES ('{$archi}', '{$ext}')";
          echo $result=$db->query($sql);
          if (move_uploaded_file($_FILES['archivo']['tmp_name'], $subir_archivo)) {
          //sleep(2); se puede usar para simular el efeto de carga en el servidor
              $session->msg("s", "El archivo es válido y se cargó correctamente.");
              //echo "<script>alertify.success('El archivo es válido y se cargó correctamente.');</script>"; 
              header("Refresh:0");
            } else {
              $session->msg("d", "Error, la subida ha fallado.");
              //echo '<script>alertify.error("Error, la subida ha fallado.");</script>';
              header("Refresh:0");
            }
        } else {
          $session->msg("d", "Error, la subida ha fallado.. Compruebe que el archivo sea '.pdf'");
          header("Refresh:0");
        }
      } else {
        $session->msg("d", "Error, ya existe un archivo en el servidor con el mismo nombre.");
        header("Refresh:0");
      } 
    } else {
      $session->msg("d", "Error, no se ha seleccionado ningún archivo");
      header("Refresh:0");
    }
  }
/////////////////
  if(isset($_POST['linkear'])) {
    $idMarca = $_POST['marca'];
    $idModelo = $_POST['modelo'];
    $idArchivo = $_POST['archivos'];
    if ($idModelo && $idArchivo) {
      global $db;
      $sql = "UPDATE maquina_modelo SET `despiece_id` = '{$idArchivo}' WHERE id = '{$idModelo}'";
      echo $result=$db->query($sql);
      $session->msg("s", "Asignación realizada exitosamente.");
    } else {
      $session->msg("d", "Error.");
    }
    header("Refresh:0");
  }

  if(isset($_POST['btn_delete-despiece'])) {
    //primero delete on maquina_modelo
    //delete on maquina_despieces
    //delete archivo
    $dir = 'uploads/despieces/';
    $idModeloForDelete = $_POST['inp_id-modelo'];
    $idDespieceForDelete = $_POST['inp_id-despiece'];
    $thisDespiece = $db->fetch_assoc($db->query("SELECT * FROM `maquina_despieces` WHERE `id` = '{$idDespieceForDelete}'"));
    if($idModeloForDelete) {
      $result1 = $db->query("UPDATE `maquina_modelo` SET `despiece_id` = '0' WHERE `id` = '{$idModeloForDelete}'");
      if($result1) {
        $result2 = $db->query("DELETE FROM `maquina_despieces` WHERE `id` = '{$idDespieceForDelete}'");
      } else {
        $session->msg("d", "Error al borrar la relación del despiece al modelo.");
      }
      if($result2) {
        //redirect('despieces.php', false);
        if(unlink($dir.$thisDespiece['file_name'])) {
        // file was successfully deleted
          $session->msg("s", "Despiece eliminado exitosamente.");
        } else {
        // there was a problem deleting the file
          $session->msg("d", "Error al eliminar el archivo.");
        }
        redirect('despieces.php');
        header("Refresh:0");
      } else {
        $session->msg("d", "Error borrar el despiece de la BD.");
      }
    }
  } 

  ?>

<script type="text/javascript">
  

  function marcaSelect() {                  
    document.getElementById("modelo").disabled = false;
    var marca = document.getElementById("marca").value;
    var marca_nombre = $("#marca option:selected").text();
    var sel = document.getElementById("modelo");
    $('#modelo').find('option').remove().end().append('<option value="" required disabled selected>Seleccione un modelo</option>');
    <?php 
    foreach ($allModelosSinDespiece as $unModeloSinDespiece): ?>
      if ("<?php echo $unModeloSinDespiece['marca_id'];?>" == marca){
        var opt = document.createElement('option');
        var nombre = "<?php echo find_by_id('maquina_tipo',$unModeloSinDespiece['tipo_id'])['descripcion']." '".$unModeloSinDespiece['codigo']."'";?>";
        opt.appendChild( document.createTextNode(nombre) );
        opt.value = parseInt("<?php echo $unModeloSinDespiece['id'];?> "); 
        sel.appendChild(opt); 
      }
    <?php endforeach; ?>
    $('#modelo').selectpicker('refresh'); 
  }

  function modeloSeleccionado() {
    document.getElementById('archivos').disabled = false;
  }

  function callDeleteThisDespiece(idDespiece) {
    var result = confirm("¿Está seguro que desea eliminar éste despiece?");
    if(result) {
      $('#btn_delete-despiece_'+idDespiece).click();
    }
  }

  function deleteThisDespiece(idModelo, idDespiece, fileName) {
    document.getElementById('inp_id-modelo').value = idModelo;
    document.getElementById('inp_id-despiece').value = idDespiece;
  }  

  function goFadeOut() {
    $('.loader2').fadeOut(1000);
  }

</script>

<html>
<head>
<?php include_once('layouts/newHeader.php'); ?>  

<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> 
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.15.3/dist/bootstrap-table.min.css">
<link rel="stylesheet" type="text/css" href="libs/alertifyjs/css/alertify.css">
<link rel="stylesheet" type="text/css" href="libs/alertifyjs/css/themes/default.css">
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>
<script src="libs/alertifyjs/alertify.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script> -->

  <script type="text/javascript">
    $(document).ready(function() {
      $('#maquinas').DataTable(); 
    });
  </script>

</head>
<body>
<!--<div id="loader" class="loader2"></div>-->  
<form enctype="multipart/form-data" action="despieces.php" method="POST">
<?php echo display_msg($msg); ?>  
  <div class="row">
    <div class="col-md-3">
      <div class="col-md-12 input-group">
        <input class="form-control" id="archivo_nombre" type="text" placeholder="Seleccionar archivo..." style="width: 250px;" disabled />
        <div class="input-group-btn">
            <label for="archivo" class="btn btn-warning" style="height: 34px;"><span class="glyphicon glyphicon-folder-open"></span></label>
            <input type="submit" class="btn btn-success" name="subir" id="subir" value="Subir" onclick="javascript:goFadeOut();"/>
            <input id="archivo" name="archivo" type="file" class="btn btn-default" onchange="javascripit:actualiza(this.files[0].name)" style="visibility:hidden;"/>            
            <script type="text/javascript">
              function actualiza(nombre){ 
                //console.log(nombre);
                document.getElementById('archivo_nombre').value = nombre; 
              } 
            </script>            
        </div>
      </div>
    </div>
    <div class="col-md-9">
      <div class="pull-right">
        <a href="home.php" class="btn btn-danger">Volver</a> 
      </div>
    </div>
  </div>    
</form>
<form id="enlace" name="enlace" action="despieces.php" method="POST">
<div class="row">
  <div class="form-group col-xs-3">
    <label for="marca" class="control-label">Marca:</label>
    <select class="selectpicker" data-show-subtext="true" data-live-search="true" data-width="100%" name="marca" id="marca" onchange="javascript:marcaSelect()" required>
      <option value="" disabled selected>Seleccione una marca</option>
      <?php 
        $allMarcas = $db->while_loop($db->query("SELECT * FROM `maquina_marca` ORDER BY 2"));
        foreach ($allMarcas as $unaMarca): 
        if(substr($unaMarca['descripcion'], 0,3) != "---"){?>
        <option value="<?php echo (int) $unaMarca['id']?>">
          <?php 
          echo $unaMarca['descripcion'];?>                      
        </option>
      <?php } endforeach; ?>
    </select>
    <script type="text/javascript">
      $(document).ready(function() {
        $('#marca').selectpicker();
      });
    </script>
  </div>
  <div class="form-group col-xs-3">
    <label for="modelo" class="control-label">Modelo:</label>
    <select class="selectpicker" data-show-subtext="true" data-live-search="true" data-width="100%" name="modelo" id="modelo" disabled required onchange="javascript:modeloSeleccionado();">
      <option value="" disabled selected>Seleccione un modelo</option>
    </select>
    <script type="text/javascript">
      $(document).ready(function() {
        $('#modelo').selectpicker();
      });
    </script>
  </div>
  <div class="form-group col-xs-3">
    <label for="archivos" class="control-label">Archivos:</label>
    <select class="form-control" name="archivos" id="archivos" required disabled>
      <option value="" disabled selected>Seleccione un archivo</option>
      <?php 
        foreach ($allArchivos as $unArchivo): ?>
        <option value="<?php echo (int) $unArchivo['id']?>">
          <?php echo $unArchivo['file_name']; ?>                      
        </option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="form-group col-xs-3">
    <label for="linkear" class="control-label">Acción:</label><br>
    <button type="submit" name="linkear" id="linkear" class="btn btn-info">Asignar</button>
  </div>  
</div>
</form>

<hr>

<form id="delete-despiece" name="delete-despiece" enctype="multipart/form-data" action="despieces.php" method="POST">
<div class="panel panel-default">
  <div class="panel-heading">
    <div class="row">
      <div class="col-md-12">
        <div><strong><p style="font-size: 25px; margin-top: 1px; margin-bottom: -5px;">LISTA DE DESPIECES</p></strong></div>
      </div>
    </div> 
  </div>
    
    <div class="table-responsive" style="padding: 15px;">
      <table id="tabla_despieces" class="table table-bordered table-striped table-hover">
        <thead>
          <tr>
            <th style="vertical-align:middle; width: 13.3%;" class="text-center">Marca</th>
            <th style="vertical-align:middle; width: 13.3%;" class="text-center">Tipo</th>
            <th style="vertical-align:middle; width: 13.4%;" class="text-center">Modelo</th>
            <th style="vertical-align:middle; width: 35%;" class="text-center">Nombre del archivo</th>
            <th style="vertical-align:middle; width: 15%;" class="text-center">Thumbnail</th>                  
            <th style="vertical-align:middle; width: 10%;" class="text-center">Acciones</th>             
          </tr>
        </thead>
        <tbody>
        <?php           
          foreach ($allModelos as $unModelo) :
            if ($unModelo['despiece_id'] != 0) {
              $marcaDespiece = find_by_id('maquina_marca',$unModelo['marca_id']);
              $despiece = find_by_id('maquina_despieces',$unModelo['despiece_id']);
              $tipo = find_by_id('maquina_tipo',$unModelo['tipo_id']);
        ?>
          <tr>            
            <td style="vertical-align:middle; width: 13.3%;" class="text-center"><?php echo $marcaDespiece['descripcion'];?></td>
            <td style="vertical-align:middle; width: 13.4%;" class="text-center"><?php echo $tipo['descripcion'];?></td>
            <td style="vertical-align:middle; width: 13.3%;" class="text-center"><?php echo $unModelo['codigo'];?></td>
            <td style="vertical-align:middle; width: 35%;" class="text-center"><?php echo $despiece['file_name'];?></td>
            <td style="vertical-align:middle; width: 15%;" class="text-center"><embed src="uploads/despieces/<?php echo $despiece['file_name'];?>#toolbar=0&navpanes=0&scrollbar=0" type="application/pdf" width="100%" height="75"/></td>
            <td style="vertical-align:middle; width: 10%;" class="text-center">
                <a type="button" class="btn btn-primary" target='_blank' href="uploads/despieces/<?php echo $despiece['file_name'];?>" title="Ver despiece" data-toggle="tooltip"><span class="glyphicon glyphicon-eye-open"></span></a>
                <button hidden type="submit" class="btn btn-danger" id="btn_delete-despiece_<?php echo $despiece['id']; ?>" name="btn_delete-despiece" title="Eliminar despiece" data-toggle="tooltip" onclick="javascript:deleteThisDespiece('<?php echo $unModelo['id']; ?>','<?php echo $despiece['id']; ?>','<?php echo $despiece['file_name']; ?>');"><span class="glyphicon glyphicon-trash"></span></button>
                <button type="button" class="btn btn-danger" id="btn_delete-despiece-confirm" name="btn_delete-despiece-confirm" onclick="javascript:callDeleteThisDespiece('<?php echo $despiece['id']; ?>')"><span class="glyphicon glyphicon-trash"></span></button>
                
          </tr> 
        <?php } endforeach; ?>
            <input type="text" name="inp_id-despiece" id="inp_id-despiece" value="" hidden></td>
            <input type="text" name="inp_id-modelo" id="inp_id-modelo" value="" hidden></td>
        </tbody>
        <tfoot>
          <tr>
            <th style="vertical-align:middle; width: 13.3%;" class="text-center">Marca</th>
            <th style="vertical-align:middle; width: 13.3%;" class="text-center">Tipo</th>
            <th style="vertical-align:middle; width: 13.4%;" class="text-center">Modelo</th>
            <th style="vertical-align:middle; width: 35%;" class="text-center">Nombre del archivo</th>
            <th style="vertical-align:middle; width: 15%;" class="text-center">Thumbnail</th>                  
            <th style="vertical-align:middle; width: 10%;" class="text-center">Acciones</th>             
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
  <script>
    $('#tabla_despieces').DataTable({ "order": [[ 0, "asc" ],[ 1, 'asc' ],[ 2, 'asc' ]]});
    $('.dataTables_length').addClass('bs-select');
  </script>
  <script type="text/javascript">
    //$(window).load(function() {
      //$('.loader2').fadeOut(1000);
    //});
  </script>

</form>

<?php include_once('layouts/newFooter.php'); ?>