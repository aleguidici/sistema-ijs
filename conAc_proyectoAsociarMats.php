<?php
  $page_title = 'Asociación de materiales / insumos';
  require_once('includes/load.php');
  $current_user_ok = current_user();
  page_require_level(2);
  $mydata = Array();
  $tabla = "";
  $query_parts = Array();

  $all_matsDisp = find_materiales_dispobibles();
  $proyecto = find_by_id('proyecto',$_GET['id']);
?>
  
<?php
  if (isset($_POST['datos'])){
    $datos_form = json_decode($_POST['datos'], true);

    $fecha_ok = remove_junk($db->escape($datos_form['fech']));
    $hora_ok = remove_junk($db->escape($datos_form['hor']));
    $id_user_ok = remove_junk($db->escape($datos_form['usuari']));
    $id_proyecto_ok = remove_junk($db->escape($datos_form['proyect']));

    if(empty($errors)){
      $sql2  = "INSERT INTO movimientos (`id_user`, `id_proyecto`, `fecha`, `hora`, `tipo`) VALUES ('{$id_user_ok}', '{$id_proyecto_ok}', '{$fecha_ok}', '{$hora_ok}', 2)";

      foreach($datos_form['datos'] as $unDetalle){
        $query2 = "UPDATE inv_materiales_insumos SET cant_disp=cant_disp-{$unDetalle["cant"]} WHERE id = '{$unDetalle['cod']}'";  
        $result = $db->query($query2);
      }

      if($db->query($sql2)){
        $ultimo_movimiento = find_last('movimientos');

        $query = "INSERT INTO movimientos_detalles (`id_movimiento`, `id_matInsu`, `cant`) VALUES ";

        foreach($datos_form['datos'] as $unDetalle){
          $query_parts[] = " ({$ultimo_movimiento["id"]}, {$unDetalle["cod"]}, {$unDetalle["cant"]}) ";

          $existe = find_matInsu_IJS_by_proyecto($id_proyecto_ok, $unDetalle['cod']);
          if(!empty($existe)) {
            $query3 = "UPDATE proy_mats SET cantidad=cantidad+{$unDetalle['cant']} WHERE id_proyecto = '{$id_proyecto_ok}' AND id_materiales = '{$unDetalle['cod']}' AND material_IJS = 1";
          }
          else {
            $query3 = "INSERT INTO proy_mats (`id_proyecto`, `id_materiales`, `cantidad`, `cantidad_usada`, `material_IJS`) VALUES ('{$id_proyecto_ok}', '{$unDetalle['cod']}', '{$unDetalle['cant']}', 0, 1)";
          }
          $result = $db->query($query3);
        }

        $query .= implode(',', $query_parts);
        $db->query($query);
        
        $session->msg("s","Materiales asociados satisfactoriamente.");
        
      } else {
        redirect('home.php',false);
      }
    } else {
      $session->msg("d", $errors);
      redirect('home.php',false);
    }
  }
?>

<?php include_once('layouts/header.php'); ?>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round|Open+Sans">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="http://weareoutman.github.io/clockpicker/dist/bootstrap-clockpicker.min.css">

    <script src="http://weareoutman.github.io/clockpicker/dist/bootstrap-clockpicker.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>

      
    <div class="col-md-12">
      <?php echo display_msg($msg); ?>
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Materiales e Insumos</span>
          </strong>
        </div>

        <div class="panel-body">
          <div class="row">
            <div class="col-xs-1">
              <h5><b>Fecha: </b></h5>
            </div>
            <div class="col-xs-9">
              <h5><?php 
                $fecha = date('d/m/Y');
                echo $fecha;
              ?></h5>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-1">
              <h5><b>Proyecto: </b></h5>
            </div>
            <div class="col-xs-9">
              <h5><?php 
                echo $proyecto['nombre_proyecto'].' (Cod. IJS: '.$proyecto['id'].')';
              ?></h5>
            </div>
          </div>
          <hr>

          <div class="rowtemp"></div>

          <h2><b>Asociación </b>- Materiales internos</h2>
          <h4><b><u>Agregar materiales:</u></b></h4>
          
          <div class="row">
            <div class="col-md-7">
              <label for="prod" class="control-label">Material / Insumo:</label>
            </div>
            <div class="col-md-2">
              <label>Disponible: </label>
            </div>
            <div class="col-md-3">
              <label>Cantidad:</label>
            </div>
          </div>
          <div class="col-md-7">
            <select class="selectpicker" data-show-subtext="true" data-live-search="true" name="matInsu" id="matInsu" required="required" data-width="100%" onchange="javascript:cant_disponib()">
              <option value="" disabled selected>Seleccione un elemento</option>
              <?php  foreach ($all_matsDisp as $un_matInsu): ?>
                <option value="<?php echo $un_matInsu['id']?>">
                <?php echo 'Cod. IJS ['.$un_matInsu['id'].'] - ';
                ?> 
                <b><?php 
                  $long = 0;
                  echo $un_matInsu['marca'];
                  $long = strlen($un_matInsu['marca']);?></b>
                <em><?php
                  if (!empty($un_matInsu['tipo'])){
                    echo ' - '.$un_matInsu['tipo'];
                    $long = $long+strlen($un_matInsu['tipo']);
                  }
                  if (!empty($un_matInsu['cod'])){
                    echo ' - [Cod.: '.$un_matInsu['cod'].']';
                    $long = $long+strlen(' - [Cod.: '.$un_matInsu['cod'].']');
                  }
                  if(!empty($un_matInsu['descripcion'])){
                    if ($long > 130) {
                      echo ': '.substr($un_matInsu['descripcion'], 0, 130-$long), ' [...]';
                    } else {
                      echo ': '.substr($un_matInsu['descripcion'], 0, 130-$long);
                    }
                  }
                  ?>
                </em></option>
              <?php endforeach; ?>
            </select>
          </div>

          <script>
            $('#matInsu').on('show.bs.select', function() {
              $('html,body').animate({
                  scrollTop: $(".rowtemp").offset().top},
                  'fast');
            });
            
            function cant_disponib() {
              var cant = 0;
              $('#tabla_detalles tr').each(function(index,element){
                if(index>0){
                  var fila = $(element).find('td');
                  if (parseInt(fila.eq(0).html()) == parseInt(document.getElementById("matInsu").value))
                    cant = cant + parseInt(fila.eq(3).html());
                }
              });

              <?php  foreach ($all_matsDisp as $un_matIns): ?>
                if (parseInt(document.getElementById("matInsu").value) == parseInt("<?php echo $un_matIns['id']?>")){
                  cant_disp_unElem.value = parseInt("<?php echo $un_matIns['cant_disp']?> ")-cant; 
                  document.getElementById("cant_unElem").setAttribute("max",parseInt("<?php echo $un_matIns['cant_disp']?> "));
                  if (parseInt("<?php echo $un_matIns['cant_disp']?> ")-cant == 0){
                    document.getElementById("cant_unElem").value = 0;
                    document.getElementById("boton_agregar").disabled = true;
                    document.getElementById("cant_unElem").disabled = true;
                  }
                  else{
                    document.getElementById("cant_unElem").value = 1;
                    document.getElementById("boton_agregar").disabled = false;
                    document.getElementById("cant_unElem").disabled = false;
                  }
                }
              <?php endforeach; ?>
            }
          </script>

          <div class="col-md-2">
              <input name="cant_disp_unElem" id="cant_disp_unElem" class="form-control" type="number" min="1" value="" disabled>
            </div>
            <div class="col-md-2">
              <input name="cant_unElem" id="cant_unElem" class="form-control" type="number" min="1" value="" disabled onchange="javascript:cantidad_change()">
            </div>
            <script>
              $(function() {
                document.getElementById("matInsu").selectedIndex = "0";
              });

              function cantidad_change(){
                var cant = 0;
                $('#tabla_detalles tr').each(function(index,element){
                  if(index>0){
                    var fila = $(element).find('td');
                    if (parseInt(fila.eq(0).html()) == parseInt(document.getElementById("matInsu").value))
                      cant = cant + parseInt(fila.eq(3).html());
                  }
                });

                var cant_h = parseInt(document.getElementById("cant_unElem").value);
                if (Number.isInteger(cant_h)) {
                  if (cant_h > 0){
                    if (cant_h > parseInt(document.getElementById("cant_unElem").getAttribute("max"))-cant)
                      cant_unElem.value = parseInt(document.getElementById("cant_unElem").getAttribute("max"))-cant;
                    else
                      cant_unElem.value = cant_h;
                  }
                  else
                    cant_unElem.value = "1";
                } else
                  cant_unElem.value = "1";
              };
            </script>
            <div class="col-md-1">
              <button type="button" class="btn btn-info add-new" disabled id="boton_agregar">OK</button>
            </div>
            
<!-- Detalles -->
            <script type="text/javascript">
              $(document).ready(function(){
                $('[data-toggle="tooltip"]').tooltip();
                var actions = $("table td:last-child").html();
              
                $('#guardar2').click(function(){
                  if (confirm('¿Está seguro que desea asignar estos materiales al proyecto?')) {
                    var arrayCarac = {};
                    var miTabla = [];
                    $('#tabla_detalles tr').each(function(index,element){
                      if(index>0){    
                        var fila = $(element).find('td');
                        miTabla.push({
                          "cod" : fila.eq(0).html(),
                          "cant" : fila.eq(3).html()
                        });
                      } 
                    });

                    arrayCarac.proyect = '<?php echo $proyecto['id'];?>';
                    arrayCarac.fech= '<?php echo date('Y-m-d');?>';
                    arrayCarac.hor= '<?php echo date('H:i:s');?>';
                    arrayCarac.usuari = '<?php echo $current_user_ok['id'];?>';
                    arrayCarac.datos=miTabla;

                    var miJson = JSON.stringify(arrayCarac);
                    $.ajax({
                      data : {'datos':miJson},
                      method:'post',
                      success: function(response){
                        // window.alert("¡Éxito!\nEl movimiento fue creado satisfactoriamente");
                      },
                      error: function(xhr, textStatus, error){
                        console.log(xhr.statusText);
                        console.log(textStatus);
                        console.log(error);
                      }
                    });
                    $(document).ajaxStop(function(){
                      window.location.replace("conAc_proyecto.php?id=<?php echo $proyecto['id'];?>");
                    });
                  } else {
                    return false;
                  }
                });

                $(".add-new").click(function(){
                  document.getElementById("guardar2").disabled = false;
                  var ban = 0;

                  $('#tabla_detalles tr').each(function(index,element){
                    if(index>0) {
                      var fila = $(element).find('td');
                      var cantAnt = parseInt(fila.eq(3).html())+parseInt(document.getElementById("cant_unElem").value);
                      if (parseInt(fila.eq(0).html()) == parseInt(document.getElementById("matInsu").value)){
                        fila.eq(3).html(cantAnt);
                        ban = 1;
                      }
                    }
                  });

                  if (!ban){
                    <?php  foreach ($all_matsDisp as $un_matInsu): ?>
                      if( "<?php echo $un_matInsu['id']?>" == document.getElementById("matInsu").value){
                        var codigo = "<?php echo $un_matInsu['id']?>";
                        var marca = "<?php echo $un_matInsu['marca']?>";
                        var tipo = "<?php echo $un_matInsu['tipo']?>";
                        var descrip = "<?php echo $un_matInsu['descripcion']?>";
                        var unidad = "<?php echo $un_matInsu['unidad']?>";
                      }
                    <?php endforeach;?>

                    var index = $("table tbody tr:last-child").index();
                    var row = '<tr>' +
                        '<td>' + codigo  + '</td>' + 
                        '<td>' + descrip + ' - ' + tipo + ' <b>[Marca: ' + marca + ']</b>' + '</td>' + 
                        '<td>' + unidad  + '</td>' + 
                        '<td class="text-center">' + document.getElementById("cant_unElem").value +'</td>' + 
                        '<td><a class="add" title="Add" data-toggle="tooltip"><i class="material-icons">&#xE03B;</i></a><a class="edit" title="Edit" data-toggle="tooltip"><i class="material-icons">&#xE254;</i></a><a class="delete" title="Delete" data-toggle="tooltip"><i class="material-icons">&#xE872;</i></a></td>' + '</tr>';
                    $("table").append(row);
                    $('[data-toggle="tooltip"]').tooltip();
                  }

                  var caaant = document.getElementById("cant_disp_unElem").value - document.getElementById("cant_unElem").value;
                  if (caaant == 0) {
                    document.getElementById("cant_unElem").value = "0";
                    document.getElementById("cant_unElem").disabled = true;
                    document.getElementById("boton_agregar").disabled = true;
                  } else 
                    document.getElementById("cant_unElem").value = "1";
                  document.getElementById("cant_disp_unElem").value = caaant;
                });

              // Add row on add button click
                $(document).on("click", ".add", function(){
                  var empty = false;
                  var input = $(this).parents("tr").find('input[type="number"]');
                  
                  input.each(function(){
                    if(!$(this).val()){
                      $(this).addClass("error");
                      empty = true;
                    } else {
                      if ($(this).val() == ""){
                        $(this).addClass("error");
                        empty = true;
                      } else {
                        $(this).removeClass("error");
                      }
                      
                    }
                  }); //Control de errores para los campos

                  input.each(function(){
                    $(this).parent("td").html($(this).val());
                  });

                  $(this).parents("tr").find(".add, .edit").toggle();
                  $(".add-new").removeAttr("disabled");
                  document.getElementById("guardar2").disabled = false;

                  var tbl  = document.getElementById('tabla_detalles');
                  var rows = tbl.getElementsByTagName('tr');

                  for (var row=1; row<$('#tabla_detalles tr').length;row++) {
                    var cels = rows[row].getElementsByTagName('td');
                    cels[4].style.display = '';
                  }

                  document.getElementById("matInsu").disabled = false;
                  $("#matInsu").selectpicker("refresh");
                  document.getElementById("boton_agregar").disabled = true;
                });

              // Edit row on edit button click
                $(document).on("click", ".edit", function(){
                  var cont = 0;
                  var maxi;
                  var id_ok;
                  $(this).parents("tr").find("td:not(:last-child)").each(function(){
                    switch (cont) {
                      case 0:
                        id_ok = parseInt($(this).html());
                        <?php  foreach ($all_matsDisp as $un_matInsee): ?>
                          if( "<?php echo $un_matInsee['id']?>" == id_ok){
                            maxi = parseInt("<?php echo $un_matInsee['cant_disp']?>");
                          }
                        <?php endforeach;?>
                        break;
                      case 3:
                        $(this).html('<input name="cant_det" id="cant_det" class="form-control" type="number" min="1" value='+ $(this).text() +' max='+maxi+' onchange="javascript:cant_det_change()"></input>');

                        break;
                    }
                    cont = cont + 1;
                  });

                  var tbl  = document.getElementById('tabla_detalles');
                  var rows = tbl.getElementsByTagName('tr');

                  for (var row=1; row<$('#tabla_detalles tr').length;row++) {
                    if (row != $(this).parents('tr').index()){
                      var cels = rows[row].getElementsByTagName('td');
                      cels[4].style.display = 'none';
                    }
                  }

                  $(this).parents("tr").find(".add, .edit").toggle();
                  $(".add-new").attr("disabled", "disabled");
                  document.getElementById("guardar2").disabled = true;

                  document.getElementById("matInsu").disabled = true;
                  document.getElementById("matInsu").selectedIndex = "0";
                  $("#matInsu").selectpicker("refresh");
                  document.getElementById("cant_unElem").disabled = true;
                  document.getElementById("cant_unElem").value = "0";
                  document.getElementById("boton_agregar").disabled = true;
                  document.getElementById("cant_disp_unElem").value = "0";
                });

              // Delete row on delete button click
                $(document).on("click", ".delete", function(){
                  $(this).parents("tr").remove();
                  $(".add-new").removeAttr("disabled");
                  document.getElementById("guardar2").disabled = false;

                  var tbl  = document.getElementById('tabla_detalles');
                  var rows = tbl.getElementsByTagName('tr');

                  for (var row=1; row<$('#tabla_detalles tr').length;row++) {
                    var cels = rows[row].getElementsByTagName('td');
                    cels[4].style.display = '';
                  }

                  if ($('#tabla_detalles tr').length < 2) {
                    document.getElementById("guardar2").disabled = true;
                  }


                  document.getElementById("matInsu").selectedIndex = "0";
                  $("#matInsu").selectpicker("refresh");
                  document.getElementById("cant_unElem").disabled = true;
                  document.getElementById("cant_unElem").value = "";
                  document.getElementById("cant_disp_unElem").value = "";
                  document.getElementById("boton_agregar").disabled = true;
                });
              });

              $('#mydata').keyup(function(){
                $.ajax({
                type: 'post',
                data: {ajax: 1,mydata: mydata},
                success: function(tabla){
                  $('#tabla').text(tabla);
                }
                });
              });
            </script>

            <br>
        <!-- <div class="table-wrapper"> -->
            <div class="col-md-12"><br></div>
            <div class="col-md-12">
              <table id="tabla_detalles" name="tabla_detalles" class="table table-striped table-bordered">
                <tr>
                  <th style="width: 8%;" class="text-center" name="col1" id="col1">Cod. IJS</th>
                  <th style="width: 80%;" class="text-center" name="col2" id="col2">Descripción</th>
                  <th style="width: 10%;" class="text-center" name="col3" id="col3">Unidad</th>
                  <th style="width: 15%;" class="text-center" name="col4" id="col4">Cant.</th>
                  <th style="width: 12%;" class="text-center"></th>
                </tr>
              </table>
            </div>
          
            <hr>
            <div class="form-group text-right">
              <a href="conAc_proyecto.php?id=<?php echo (int)$_GET['id']?>" class="btn btn-primary" role=button>Volver al Proyecto</a>
              <button type="button" class="btn btn-success" name="guardar2" id="guardar2" disabled="disabled">Asignar</button>
            </div>

            <script>
              function cant_det_change(){
                var cant_det_h = parseInt(document.getElementById("cant_det").value);
                if (Number.isInteger(cant_det_h)) {
                  if (cant_det_h > 0){
                    if (cant_det_h > parseInt(document.getElementById("cant_det").getAttribute("max")))
                      cant_det.value = parseInt(document.getElementById("cant_det").getAttribute("max"));
                    else
                      cant_det.value = cant_det_h;
                  }
                  else
                    cant_det.value = "1";
                } else
                  cant_det.value = "1";

              };
            </script>
            <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
          </div>
        </div>
      </div>


<?php include_once('layouts/footer.php'); ?>