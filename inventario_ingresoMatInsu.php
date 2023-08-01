<?php
  $page_title = 'Ingreso de materiales / insumos';
  require_once('includes/load.php');
  $current_user_ok = current_user();
  page_require_level(2);
  $mydata = Array();
  $tabla = "";
  $query_parts = Array();

  $all_proveedores = find_all('proveedor');
  $all_clientes = find_all('cliente');
  $all_matsInsu = find_all('inv_materiales_insumos');
  
  function cmp($a, $b)
  {
      return strcmp($a["razon_social"], $b["razon_social"]);
  }
  usort($all_clientes, "cmp");
  usort($all_proveedores, "cmp");
?>

<?php
  if (isset($_POST['datos'])){
    $datos_form = json_decode($_POST['datos'], true);

    $id_proveedor_ok = remove_junk($db->escape($datos_form['proveedo']));
    $id_proyecto_ok = remove_junk($db->escape($datos_form['proyect']));
    
    $id_user_ok = remove_junk($db->escape($datos_form['use']));
    $origen_ok = remove_junk($db->escape($datos_form['orige']));
    $fecha_ok = remove_junk($db->escape($datos_form['fech']));
    $hora_ok = remove_junk($db->escape($datos_form['hor']));
    $tipo_ok = remove_junk($db->escape($datos_form['tip']));
    $n_rem_ok = remove_junk($db->escape($datos_form['n_rem']));
    $l_rem_ok = remove_junk($db->escape($datos_form['l_rem']));
    $n_fac_ok = remove_junk($db->escape($datos_form['n_fac']));
    $l_fac_ok = remove_junk($db->escape($datos_form['l_fac']));

    if(empty($errors)){

      if ($origen_ok == 1) {
        $sql2  = "INSERT INTO movimientos (`id_user`, `id_proveedor`, `id_cliente`, `id_proyecto`, `descripcion`, `fecha`, `hora`, `tipo`, `concepto`, `num_factura`, `letra_factura`, `num_remito`, `letra_remito`, `num_nc`) VALUES ('{$id_user_ok}', '{$id_proveedor_ok}', NULL, NULL, '', '{$fecha_ok}', '{$hora_ok}', '{$tipo_ok}', 1, '{$n_fac_ok}', '{$l_fac_ok}', '{$n_rem_ok}', '{$l_rem_ok}', '')";
        foreach($datos_form['datos'] as $unDetalle){
          $query2 = "UPDATE inv_materiales_insumos SET cant=cant+{$unDetalle["cant"]}, cant_disp=cant_disp+{$unDetalle["cant"]} WHERE id = '{$unDetalle['cod']}'";  
          $result = $db->query($query2);
        }
      }
      else {
        $sql2  = "INSERT INTO movimientos (`id_user`, `id_proveedor`, `id_cliente`, `id_proyecto`, `descripcion`, `fecha`, `hora`, `tipo`, `concepto`, `num_factura`, `letra_factura`, `num_remito`, `letra_remito`, `num_nc`) VALUES ('{$id_user_ok}', NULL, NULL, '{$id_proyecto_ok}', '', '{$fecha_ok}', '{$hora_ok}', '{$tipo_ok}', 2, '{$n_fac_ok}', '{$l_fac_ok}', '{$n_rem_ok}', '{$l_rem_ok}', '')";
        foreach($datos_form['datos'] as $unDetalle){
          $query2 = "UPDATE inv_materiales_insumos SET cant=cant+{$unDetalle["cant"]} WHERE id = '{$unDetalle['cod']}'";  
          $result = $db->query($query2);
        }
      }

      if($db->query($sql2)){
        $ultimo_movimiento = find_last('movimientos');
        // $url = 'pdf_presupuesto.php?id='.$ultimo_movimiento['id'];

        $query = "INSERT INTO movimientos_detalles (`id_movimiento`, `id_matInsu`, `cant`) VALUES ";

        foreach($datos_form['datos'] as $unDetalle){
          $query_parts[] = " ({$ultimo_movimiento["id"]}, {$unDetalle["cod"]}, {$unDetalle["cant"]}) ";

          if ($origen_ok == 2) {
            $existe = find_matInsu_ajeno_by_proyecto($id_proyecto_ok, $unDetalle['cod']);
            if(!empty($existe)) {
              $query3 = "UPDATE proy_mats SET cantidad=cantidad+{$unDetalle['cant']} WHERE id_proyecto = '{$id_proyecto_ok}' AND id_materiales = '{$unDetalle['cod']}' AND material_IJS = 0";
            }
            else {
              $query3 = "INSERT INTO proy_mats (`id_proyecto`, `id_materiales`, `cantidad`, `cantidad_usada`, `material_IJS`) VALUES ('{$id_proyecto_ok}', '{$unDetalle['cod']}', '{$unDetalle['cant']}', 0, 0)";
            }
            $result = $db->query($query3);
          }
        }

        $query .= implode(',', $query_parts);
        $db->query($query);

        
        
        $session->msg("s","Movimiento (INGRESO) creado satisfactoriamente.");
        
      } else {
        redirect('inventario.php',false);
      }
    } else {
      $session->msg("d", $errors);
      redirect('inventario.php',false);
    }
  }
?>

<?php include_once('layouts/header.php'); ?>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round|Open+Sans">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>

      
    <div class="col-md-12">
      <?php echo display_msg($msg); ?>
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>ingreso de Materiales / Insumos </span>
          </strong>
        </div>

        <div class="panel-body">
          <div class="row">
            <div class="col-xs-1">
              <h5><b>Fecha: </b></h5>
            </div>
            <div class="col-xs-2">
              <h5><?php 
                $fecha = date('d/m/Y');
                echo $fecha;
              ?></h5>
            </div>
          </div>
          <hr />
          <div class="row">
            <div class="col-xs-1">
              <h5><b>Origen: </b></h5>
            </div>
            <div class="form-group col-xs-3  mr-2">
              <select style="border:1px solid #000000" required="required" class="form-control" name="origen" id="origen" onchange="javascript:origen_i()" data-width="100%">
                <option value="" disabled selected>Seleccione una opción</option>
                <option value="1">Proveedor</option>
                <option value="2">Cliente</option>
              </select>
            </div>


            <div class="col-xs-1">
            </div>

            <script>
              function origen_i() {
                $("#listado_origen").selectpicker();
                $("#listado_origen").attr('disabled', false);
                document.getElementById("boton_confirmar").disabled = true;
                var origen_ok = document.getElementById("origen").selectedIndex;
                var cont = 0;

                $('#listado_origen')
                  .find('option')
                  .remove()
                  .end()
                  .append('<option value="" disabled selected>Seleccione a opción</option>')
                ;

                switch (origen_ok){
                  case 1:
                    <?php  foreach ($all_proveedores as $proveedor): ?>
                      cont += 1;
                      var provi = "<?php echo find_by_id_prov('provincia', $proveedor['provincia'])['nombre'];?>";
                      var nombre = "<?php echo $proveedor['razon_social'], ' - Dirección: ',  $proveedor['direccion'], ' - ',  $proveedor['localidad'], ', ';?> "+provi;
                      var val = parseInt("<?php echo $proveedor['id']?> "); 
                      $("#listado_origen").append('<option value="'+val+'">'+nombre+'</option>'); 
                    <?php endforeach; ?>
                    
                    $('#listado_proyectos').find('option').remove().end().append('<option value="" disabled selected>-</option>');
                    $("#listado_proyectos").selectpicker();
                    $("#listado_proyectos").attr('disabled', true);
                    break;
                  case 2:
                    <?php  foreach ($all_clientes as $cliente): ?>
                      cont += 1;
                      var provi = "<?php echo find_by_id_prov('provincia', $cliente['provincia'])['nombre'];?>";
                      var nombre = "<?php echo $cliente['razon_social'], ' - Dirección: ',  $cliente['direccion'], ' - ',  $cliente['localidad'], ', ';?> "+provi;
                      var val = parseInt("<?php echo $cliente['id']?> ");
                      $("#listado_origen").append('<option value="'+val+'">'+nombre+'</option>'); 
                    <?php endforeach; ?>
                    break;
                }
                $('#listado_origen').selectpicker('setStyle', 'btn-info');
                
                $("#listado_origen").selectpicker("refresh");
                $("#listado_proyectos").selectpicker("refresh");
              }
            </script>
          </div>

          <div class="row">
            <div class="col-xs-1">
              <h5><b>Entidad: </b></h5>
            </div>
            <div class="form-group col-xs-8  mr-2">
              <select class="selectpicker" data-show-subtext="true" data-live-search="true" name="listado_origen" id="listado_origen" required="required" disabled onchange="javascript:listado_origen_i()" data-width="100%">
                <option value="" disabled selected>-</option>
              </select>
            </div>
            <script>
              function listado_origen_i() {
                var list_origen = document.getElementById("origen").selectedIndex;
                var cont = 0;

                if (list_origen == 2){
                  $("#listado_proyectos").selectpicker();
                  $("#listado_proyectos").attr('disabled', false);
                  $('#listado_proyectos').find('option').remove().end().append('<option value="" disabled selected>Seleccione a opción</option>');
                  $('#listado_proyectos').selectpicker('setStyle', 'btn-info');

                  <?php 
                  $all_proyectos = find_all('proyecto');
                  foreach ($all_proyectos as $proyecto): 
                    $temp = $proyecto['nombre_proyecto'];?>
                    cont += 1;
                    if (parseInt("<?php echo $proyecto['id_cliente'];?> ") == parseInt(document.getElementById("listado_origen").value)){
                      
                      if ("<?php echo strlen($temp);?> " < 200)
                        var nombre = "<?php echo $temp;?> ";
                      else
                        var nombre = "<?php echo substr($temp, 0, 180), ' [...]';?>";

                      var val = parseInt("<?php echo $proyecto['id']?> ");
                      $("#listado_proyectos").append('<option value="'+val+'">'+nombre+'</option>'); 
                    }
                  <?php endforeach; ?>
                } else {
                  document.getElementById("boton_confirmar").disabled = false;
                }
                $("#listado_proyectos").selectpicker("refresh");
              }
            </script>
          </div>


          <div class="row" id="fila_proyecto">
            <div class="col-xs-1">
              <h5><b>Proyecto: </b></h5>
            </div>
            <div class="form-group col-xs-8  mr-2">
              <select class="selectpicker" data-show-subtext="true" data-live-search="true" name="listado_proyectos" id="listado_proyectos" required="required" disabled data-width="100%" onchange="javascript:listado_proyectos_i()">
                <option value="" disabled selected>-</option>
              </select>

              <script>
                function listado_proyectos_i() {
                  document.getElementById("boton_confirmar").disabled = false;
                }
              </script>
            </div>

            <div class="col-md-1">
              <button type="button" class="btn btn-info" id="boton_confirmar" name="boton_confirmar" disabled onClick="confirm_datos()">Confirmar</button>
            </div>
            <script>
              function confirm_datos() {
                $('#letra_remito').attr("style", "border:1px solid #000000");
                $('#num_remito').attr("style", "border:1px solid #000000");
                $('#cant_unElem').attr("style", "border:1px solid #000000");

                document.getElementById("origen").disabled = true;
                $("#origen").selectpicker("refresh");
                document.getElementById("listado_origen").disabled = true;
                $("#listado_origen").selectpicker("refresh");
                document.getElementById("listado_proyectos").disabled = true;
                $("#listado_proyectos").selectpicker("refresh");
                document.getElementById("boton_confirmar").disabled = true;

                document.getElementById("matInsu").disabled = false;
                document.getElementById("cant_unElem").disabled = false;
                document.getElementById("cant_unElem").value = "1";

                document.getElementById("chkbox_factura").disabled = false;
                document.getElementById("chkbox_remito").disabled = false;
                document.getElementById("chkbox_remito").checked = true;
                document.getElementById("chkbox_sinComprob").disabled = false;
                document.getElementById("num_remito").disabled = false;

                $('#letra_remito').prop('disabled', false);
                document.getElementById("letra_remito").value = "R";
                $("#matInsu").selectpicker("refresh");
                
              }
            </script>
          </div>

          <div class="row">
            <div class="col-xs-2">
              <h5><b>Documentos: </b></h5>
            </div>
            <div class="col-xs-2">
              <h5><input type="checkbox" id="chkbox_sinComprob" value="" disabled onClick="chk_sinComprob()"> <label>Sin comprob.</label></h5>
            </div>
            <script>
              function chk_sinComprob() {
                if (document.getElementById("chkbox_sinComprob").checked) {
                  document.getElementById("chkbox_factura").disabled = true;
                  document.getElementById("chkbox_factura").checked = false;
                  document.getElementById("num_factura").disabled = true;
                  document.getElementById("num_factura").value = "";
                  $('#letra_factura').prop('disabled', true);
                  document.getElementById("letra_factura").value = "";

                  document.getElementById("chkbox_remito").disabled = true;
                  document.getElementById("chkbox_remito").checked = false;
                  document.getElementById("num_remito").disabled = true;
                  document.getElementById("num_remito").value = "";
                  $('#letra_remito').prop('disabled', true);
                  document.getElementById("letra_remito").value = "";
                  $('#letra_factura').attr("style", "");
                  $('#num_factura').attr("style", "");
                  $('#letra_remito').attr("style", "");
                  $('#num_remito').attr("style", "");
                } else {
                  document.getElementById("chkbox_factura").disabled = false;
                  document.getElementById("chkbox_remito").disabled = false;
                }
              }
            </script>
          </div>

          <div class="row">
            <div class="col-xs-1">
            </div>
            <div class="col-xs-2">
              <h5><input type="checkbox" id="chkbox_factura" value="" disabled onClick="chk_fact()"> <label>Factura</label></h5>
            </div>
            <script>
              function chk_fact() {
                if (document.getElementById("chkbox_factura").checked) {
                  document.getElementById("num_factura").disabled = false;
                  $('#letra_factura').prop('disabled', false);
                  document.getElementById("letra_factura").value = "A";
                  $('#letra_factura').attr("style", "border:1px solid #000000");
                  $('#num_factura').attr("style", "border:1px solid #000000");
                }
                else {
                  document.getElementById("num_factura").disabled = true;
                  document.getElementById("num_factura").value = "";
                  $('#letra_factura').prop('disabled', true);
                  document.getElementById("letra_factura").value = "";
                  $('#letra_factura').attr("style", "");
                  $('#num_factura').attr("style", "");
                }
              }
            </script>
            <div class="form-group col-xs-2">
              <select class="form-control" name="letra_factura" id="letra_factura" data-width="100%" disabled>
                <option value="" disabled selected>Tipo</option>
                <option value="A">A</option>
                <option value="B">B</option>
                <option value="C">C</option>
                <option value="Ticket">Ticket</option>
              </select>
            </div>
            <div class="col-xs-2">
              <input type="name" class="form-control" id="num_factura" maxlength="20" placeholder="Nº factura" disabled onkeypress="return blockSpecialChar(event)">
            </div>

            <script type="text/javascript">
              function blockSpecialChar(e) {
                var k = e.keyCode;
                return (!(k == 34 || k == 39));
              }
            </script>
          </div>

          <div class="row">
            <div class="col-xs-1">
            </div>
            <div class="col-xs-2">
              <h5><input type="checkbox" id="chkbox_remito" value="" disabled onClick="chk_remi()"> <label>Remito</label></h5>
            </div>
            <script>
              function chk_remi() {
                if (document.getElementById("chkbox_remito").checked) {
                  document.getElementById("num_remito").disabled = false;
                  $('#letra_remito').prop('disabled', false);
                  document.getElementById("letra_remito").value = "R";
                  $('#letra_remito').attr("style", "border:1px solid #000000");
                  $('#num_remito').attr("style", "border:1px solid #000000");
                } else {
                  document.getElementById("num_remito").disabled = true;
                  document.getElementById("num_remito").value = "";
                  $('#letra_remito').prop('disabled', true);
                  document.getElementById("letra_remito").value = "";
                  $('#letra_remito').attr("style", "");
                  $('#num_remito').attr("style", "");
                }
              };
            </script>
            <div class="form-group col-xs-2">
              <select class="form-control" name="letra_remito" id="letra_remito" data-width="100%" disabled>
                <option value="" disabled selected>Tipo</option>
                <option value="R">R</option>
              </select>
            </div>
            <div class="col-xs-2">
              <input type="name" class="form-control" id="num_remito" maxlength="20" placeholder="Nº Remito" disabled onkeypress="return blockSpecialChar(event)">
            </div>
          </div>
          <hr>

          <div class="rowtemp"></div>
          <h2><b>Ingreso </b>- Detalles</h2>
          <h4><b><u>Agregar detalles:</u></b></h4>
          <div class="row">
            <div class="col-md-7">
              <label for="prod" class="control-label">Material / Insumos:</label>
            </div>
            <div class="col-md-2">
              <label>Cantidad:</label>
            </div>
          </div>
          <div class="row">
            <div class="col-md-7">
              <select class="selectpicker" data-show-subtext="true" data-live-search="true" name="matInsu" id="matInsu" required="required" disabled data-width="100%" onchange="javascript:change_matInsu();">
                <option value="0" disabled selected>Seleccione un elemento</option>
                <?php foreach ($all_matsInsu as $un_matInsu): ?>
                  <option value="<?php echo $un_matInsu['id']?>">
                  <?php 
                  $long = strlen($un_matInsu['marca']);?></b>
                <em><?php
                    $long = 0;
                    echo 'IJS-'.$un_matInsu['id'].' ['.$un_matInsu['marca'];
                    $long = strlen('IJS-'.$un_matInsu['id'].' ['.$un_matInsu['marca']);
                    if (!empty($un_matInsu['tipo'])){
                      echo ' - '.$un_matInsu['tipo'];
                      $long = $long+strlen(' - '.$un_matInsu['tipo']);
                    };
                    if (!empty($un_matInsu['cod'])){
                      echo ' - (Cod.: '.$un_matInsu['cod'].')]';
                      $long = $long+strlen(' - (Cod.: '.$un_matInsu['cod'].')]');
                    } else {
                      echo ']';
                      $long = $long+strlen(']');
                    };
                    if(!empty($un_matInsu['descripcion'])){
                      if ($long > 130) {
                        echo ': '.substr($un_matInsu['descripcion'], 0, 130-$long), ' [...]';
                      } else {
                        echo ': '.substr($un_matInsu['descripcion'], 0, 130-$long);
                      }
                    };
                    ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <script>
              $('#matInsu').on('show.bs.select', function() {
                $('html,body').animate({
                    scrollTop: $(".rowtemp").offset().top},
                    'fast');
              });

              function change_matInsu() {
                document.getElementById("boton_agregar").disabled = false;
              }
            </script>

            <div class="col-md-2">
              <input name="cant_unElem" id="cant_unElem" class="form-control" type="number" min="1" value="" disabled onchange="javascript:cantidad_change()"></input>
            </div>
            <script>              
              function cantidad_change(){
                var cant_h = parseInt(document.getElementById("cant_unElem").value);
                if (Number.isInteger(cant_h)) {
                  if (cant_h > 0)
                    cant_unElem.value = cant_h;
                  else
                    cant_unElem.value = "1";
                } else
                  cant_unElem.value = "1";
              };

            </script>
            <div class="col-md-1">
              <button type="button" class="btn btn-info add-new" disabled id="boton_agregar">OK</button>
            </div>
          </div>
            
<!-- Detalles -->
            <script type="text/javascript">
              $(document).ready(function(){
                $('[data-toggle="tooltip"]').tooltip();
                var actions = $("table td:last-child").html();
              
                $('#guardar2').click(function(){
                  if (document.getElementById("chkbox_sinComprob").checked || (document.getElementById("num_factura").value != "") || (document.getElementById("num_remito").value != "")) {
                    if (confirm('¿Está seguro que desea INGRESAR estos materiales?')) {
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
                      arrayCarac.orige= $("#origen").val();
                      if ($("#origen").val() == 1)
                        arrayCarac.proveedo=$('#listado_origen').val();
                      else
                        arrayCarac.proyect=$('#listado_proyectos').val();
                      arrayCarac.fech= '<?php echo date('Y-m-d');?>';
                      arrayCarac.n_rem = $('#num_remito').val();
                      arrayCarac.l_rem = $('#letra_remito').val();
                      arrayCarac.n_fac = $('#num_factura').val();
                      arrayCarac.l_fac = $('#letra_factura').val();
                      arrayCarac.hor= '<?php echo date('H:i:s');?>';
                      arrayCarac.tip = "1"; //INGRESO tipo 1, EGRESO tipo 0
                      arrayCarac.use = '<?php echo $current_user_ok['id']?>';
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
                        window.location.replace("inventario.php");
                      });
                    } else {
                      return false;
                    }
                  } else {
                    window.alert("Si no hay comprobantes, debe tildar la casilla 'Sin comprobantes'. De lo contrario, complete los números de comprobantes correspondientes.");
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
                    <?php  foreach ($all_matsInsu as $un_matInsu): ?>
                      if( "<?php echo $un_matInsu['id']?>" == document.getElementById("matInsu").value){
                        var id = "<?php echo $un_matInsu['id']?>";
                        var marca = "<?php echo $un_matInsu['marca']?>";
                        var tipo = "<?php echo $un_matInsu['tipo']?>";
                        var descrip = "<?php echo $un_matInsu['descripcion']?>";
                        var unidad = "<?php echo $un_matInsu['unidad']?>";
                        var cod = "<?php echo $un_matInsu['cod']?>";
                      }
                    <?php endforeach;?>

                    var index = $("table tbody tr:last-child").index();
                    var row = '<tr>' +
                        '<td>' + id  + '</td>' + 
                        '<td>' + descrip + ' - ' + tipo + ' <b>[Marca: ' + marca + ' - Cod: ' + cod + ']</b>' + '</td>' + 
                        '<td>' + unidad  + '</td>' + 
                        '<td class="text-center">' + document.getElementById("cant_unElem").value +'</td>' + 
                        '<td><a class="add" title="Add" data-toggle="tooltip"><i class="material-icons">&#xE03B;</i></a><a class="edit" title="Edit" data-toggle="tooltip"><i class="material-icons">&#xE254;</i></a><a class="delete" title="Delete" data-toggle="tooltip"><i class="material-icons">&#xE872;</i></a></td>' + '</tr>';
                    $("table").append(row);
                    $('[data-toggle="tooltip"]').tooltip();
                  }

                  document.getElementById("cant_unElem").value = "1";
                  document.getElementById("boton_agregar").disabled = true;
                  document.getElementById("matInsu").value = "0";
                  $("#matInsu").selectpicker("refresh");
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
                  document.getElementById("cant_unElem").disabled = false;
                  document.getElementById("boton_agregar").disabled = true;
                });

              // Edit row on edit button click
                $(document).on("click", ".edit", function(){
                  var cont = 0;
                  $(this).parents("tr").find("td:not(:last-child)").each(function(){
                    switch (cont) {
                      case 3:
                        $(this).html('<input name="cant_det" id="cant_det" class="form-control" type="number" min="1" value='+ $(this).text() +' onchange="javascript:cant_det_change()"></input>');

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
                  document.getElementById("matInsu").value = "0";
                  $("#matInsu").selectpicker("refresh");
                  document.getElementById("cant_unElem").disabled = true;
                  document.getElementById("cant_unElem").value = "1";
                  document.getElementById("boton_agregar").disabled = true;
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
            <table id="tabla_detalles" name="tabla_detalles" class="table table-striped table-bordered">
              <tr>
                <th style="width: 8%;" class="text-center" name="col1" id="col1">Cod. IJS</th>
                <th style="width: 80%;" class="text-center" name="col2" id="col2">Descripción</th>
                <th style="width: 10%;" class="text-center" name="col3" id="col3">Unidad</th>
                <th style="width: 15%;" class="text-center" name="col4" id="col4">Cant.</th>
                <th style="width: 12%;" class="text-center"></th>
              </tr>
            </table>
          
            <hr>
            <div class="form-group text-right">
              <a href="inventario.php" class="btn btn-primary" role=button>Volver a Inventario</a>
              <button type="button" class="btn btn-success" name="guardar2" id="guardar2" disabled="disabled">Aceptar</button>
            </div>

            <script>
              function cant_det_change(){
                var cant_det_h = parseInt(document.getElementById("cant_det").value);
                if (Number.isInteger(cant_det_h)) {
                  if (cant_det_h > 0)
                    cant_det.value = cant_det_h;
                  else
                    cant_det.value = "1";
                } else
                  cant_det.value = "1";
              };
            </script>
            <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
        </div>

<?php include_once('layouts/footer.php'); ?>