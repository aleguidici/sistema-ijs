<?php
  $page_title = 'Egreso de materiales / insumos';
  require_once('includes/load.php');
  $current_user_ok = current_user();
  page_require_level(2);
  $mydata = Array();
  $tabla = "";
  $query_parts = Array();

  $all_proveedores = find_all('proveedor');
  $all_clientes = find_all('cliente');
  $all_matsInsu = find_materiales_dispobibles();
  
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

    $descripcion_ok = remove_junk($db->escape($datos_form['descripcio']));
    $id_user_ok = remove_junk($db->escape($datos_form['use']));
    $fecha_ok = remove_junk($db->escape($datos_form['fech']));
    $hora_ok = remove_junk($db->escape($datos_form['hor']));
    $tipo_ok = remove_junk($db->escape($datos_form['tip']));
    $id_proveedor_ok = remove_junk($db->escape($datos_form['proveedo']));
    $id_cliente_ok = remove_junk($db->escape($datos_form['client']));
    $concepto_ok = remove_junk($db->escape($datos_form['concept']));
    $n_rem_ok = remove_junk($db->escape($datos_form['n_rem']));
    $l_rem_ok = remove_junk($db->escape($datos_form['l_rem']));
    $n_fac_ok = remove_junk($db->escape($datos_form['n_fac']));
    $l_fac_ok = remove_junk($db->escape($datos_form['l_fac']));
    $n_nc_ok = remove_junk($db->escape($datos_form['n_nc']));

    if(empty($errors)){
      if ($concepto_ok == 1) {
        $sql2  = "INSERT INTO movimientos (`id_user`, `id_proveedor`, `id_cliente`, `id_proyecto`, `descripcion`, `fecha`, `hora`, `tipo`, `concepto`, `num_factura`, `letra_factura`, `num_remito`, `letra_remito`, `num_nc`) VALUES ('{$id_user_ok}', '{$id_proveedor_ok}', NULL, NULL, '{$descripcion_ok}', '{$fecha_ok}', '{$hora_ok}', '{$tipo_ok}', '{$concepto_ok}', '{$n_fac_ok}', '{$l_fac_ok}', '{$n_rem_ok}', '{$l_rem_ok}', '{$n_nc_ok}')";
      } else {
        if ($concepto_ok == 2) {
          $sql2  = "INSERT INTO movimientos (`id_user`, `id_proveedor`, `id_cliente`, `id_proyecto`, `descripcion`, `fecha`, `hora`, `tipo`, `concepto`, `num_factura`, `letra_factura`, `num_remito`, `letra_remito`, `num_nc`) VALUES ('{$id_user_ok}', NULL, '{$id_cliente_ok}', NULL, '{$descripcion_ok}', '{$fecha_ok}', '{$hora_ok}', '{$tipo_ok}', '{$concepto_ok}', '{$n_fac_ok}', '{$l_fac_ok}', '{$n_rem_ok}', '{$l_rem_ok}', '{$n_nc_ok}')";
        } else {
          $sql2  = "INSERT INTO movimientos (`id_user`, `id_proveedor`, `id_cliente`, `id_proyecto`, `descripcion`, `fecha`, `hora`, `tipo`, `concepto`, `num_factura`, `letra_factura`, `num_remito`, `letra_remito`, `num_nc`) VALUES ('{$id_user_ok}', NULL, NULL, NULL, '{$descripcion_ok}', '{$fecha_ok}', '{$hora_ok}', '{$tipo_ok}', '{$concepto_ok}', '{$n_fac_ok}', '{$l_fac_ok}', '{$n_rem_ok}', '{$l_rem_ok}', '{$n_nc_ok}')";
        }
      }

      foreach($datos_form['datos'] as $unDetalle){
        $query2 = "UPDATE inv_materiales_insumos SET cant=cant-{$unDetalle["cant"]}, cant_disp=cant_disp-{$unDetalle["cant"]} WHERE id = '{$unDetalle['cod']}'";  
        $result = $db->query($query2);
      }

      if($db->query($sql2)){
        $ultimo_movimiento = find_last('movimientos');

        $query = "INSERT INTO movimientos_detalles (`id_movimiento`, `id_matInsu`, `cant`) VALUES ";

        foreach($datos_form['datos'] as $unDetalle){
          $query_parts[] = " ({$ultimo_movimiento["id"]}, {$unDetalle["cod"]}, {$unDetalle["cant"]}) ";
        }

        $query .= implode(',', $query_parts);
        $db->query($query);
        
        $session->msg("s","Movimiento (EGRESO) creado satisfactoriamente.");
        
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
            <span>egreso de Materiales / Insumos </span>
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
          <hr>
          <div class="row">
            <div class="col-xs-1">
              <h5><b>Según: </b></h5>
            </div>
            <div class="form-group col-xs-3  mr-2">
              <select required="required" class="form-control" name="segun" id="segun" data-width="100%" onchange="javascript:cambio_segun()">
                <option value="" disabled selected>Seleccione una opción</option>
                <option value="1">Devolución por compra</option>
                <option value="2">Venta a Cliente</option>
                <option value="3">Rotura / Faltante / Pérdida</option>
                <option value="4">Atención / Donación</option>
                <option value="5">Uso interno</option>
              </select>
            </div>
            <script>
              function cambio_segun() {
                document.getElementById("boton_go").disabled = false;
              }
            </script>
            <div class="col-md-1">
              <button type="button" class="btn btn-info" id="boton_go" name="boton_go" disabled onClick="segun_e()">></button>
            </div>
            <script>
              function segun_e() {
                document.getElementById("boton_go").disabled = true;
                document.getElementById("boton_go").style.display = "none";
                var origen_ok = document.getElementById("segun").selectedIndex;
                var cont = 0;

                switch (origen_ok){
                  case 1:
                    document.getElementById("segun").disabled = true;
                    $("#segun").selectpicker("refresh");
                    $("#cli_o_prov").selectpicker();
                    $("#cli_o_prov").attr('disabled', false);
                    $('#cli_o_prov')
                      .find('option')
                      .remove()
                      .end()
                      .append('<option value="" disabled selected>Seleccione a opción</option>');
                    <?php  foreach ($all_proveedores as $proveedor): ?>
                      cont += 1;
                      var provi = "<?php echo find_by_id_prov('provincia', $proveedor['provincia'])['nombre'];?>";
                      var nombre = "<?php echo $proveedor['razon_social'], ' - Dirección: ',  $proveedor['direccion'], ' - ',  $proveedor['localidad'], ', ';?> "+provi;
                      var val = parseInt("<?php echo $proveedor['id']?> "); 
                      $("#cli_o_prov").append('<option value="'+val+'">'+nombre+'</option>'); 
                    <?php endforeach; ?>
                    
                    document.getElementById("tag_e").textContent = "Proveedor:";
                    document.getElementById("row_datos").style.display = "block";
                    document.getElementById("row_descrip").style.display = "block";
                    break;
                  case 2:
                    document.getElementById("segun").disabled = true;
                    $("#segun").selectpicker("refresh");
                    $("#cli_o_prov").selectpicker();
                    $("#cli_o_prov").attr('disabled', false);
                    $('#cli_o_prov')
                      .find('option')
                      .remove()
                      .end()
                      .append('<option value="" disabled selected>Seleccione a opción</option>');
                    <?php  foreach ($all_clientes as $cliente): ?>
                      cont += 1;
                      var provi = "<?php echo find_by_id_prov('provincia', $cliente['provincia'])['nombre'];?>";
                      var nombre = "<?php echo $cliente['razon_social'], ' - Dirección: ',  $cliente['direccion'], ' - ',  $cliente['localidad'], ', ';?> "+provi;
                      var val = parseInt("<?php echo $cliente['id']?> ");
                      $("#cli_o_prov").append('<option value="'+val+'">'+nombre+'</option>'); 
                    <?php endforeach; ?>
                    document.getElementById("row_datos").style.display = "block";
                    document.getElementById("tag_e").textContent = "Cliente:";
                    break;
                  case 3:
                    document.getElementById("row_descrip").style.display = "block";
                    break;
                  case 4:
                    document.getElementById("row_descrip").style.display = "block";
                    break;
                  case 6:
                    document.getElementById("row_descrip").style.display = "block";
                    break;
                }
                $("#cli_o_prov").selectpicker("refresh");
                document.getElementById("row_boton").style.display = "block";
              }

              $(function() {
                document.getElementById("row_datos").style.display = "none";
                document.getElementById("row_boton").style.display = "none";
                document.getElementById("row_descrip").style.display = "none";
                document.getElementById("row_documentos").style.display = "none";
                document.getElementById("row_documentos2").style.display = "none";
                document.getElementById("row_documentos3").style.display = "none";
                document.getElementById("row_documentos4").style.display = "none";
                document.getElementById("row_tabla").style.display = "none";
              });
            </script>
          </div>

          <div class="row" id="row_datos">
            <div class="col-xs-1">
              <h5><b id="tag_e"></b></h5>
            </div>
            <div class="form-group col-xs-8  mr-2">
              <select class="selectpicker" data-show-subtext="true" data-live-search="true" name="cli_o_prov" id="cli_o_prov" required="required" disabled onchange="javascript:listado_e()" data-width="100%">
                <option value="" disabled selected>-</option>
              </select>
            </div>

            <script>
              function listado_e() {
                if ($("#segun").val() == 2)
                  document.getElementById("boton_confirmar").disabled = false;
              }
            </script>
          </div>

          <!-- asd -->
          <div class="row" id="row_descrip">
            <div class="col-xs-12">
              <h5><b>Descripción / Detalles: </b></h5>
            </div>
            <div class="form-group col-xs-9  mr-2">
              <textarea style="resize: none;" type="name" class="form-control" placeholder="Agregue una descripción, aclaración o detalles" id="descrip_egreso" required maxlength="255" oninput="descrip_change()"></textarea>
            </div>

            <script>
              function descrip_change() {
                var x = document.getElementById("descrip_egreso").value;
                if (x)
                  document.getElementById("boton_confirmar").disabled = false;
                else
                  document.getElementById("boton_confirmar").disabled = true;
              }
            </script>
          </div>

          <div class="row" id="row_boton">
            <div class="col-md-9"></div>
            <div class="col-md-1">
              <button type="button" class="btn btn-info" id="boton_confirmar" name="boton_confirmar" disabled onClick="confirm_datos()">Confirmar</button>
            </div>
            <script>
              function confirm_datos() {
                if (document.getElementById("segun").selectedIndex <= 2){
                  document.getElementById("row_documentos").style.display = "block";
                  document.getElementById("row_documentos2").style.display = "block";
                  if (document.getElementById("segun").selectedIndex == 1){
                    document.getElementById("row_documentos3").style.display = "block";
                    document.getElementById("letra_remito").value = "R";
                  } else {
                    document.getElementById("chkbox_factura").checked = true;
                    document.getElementById("num_factura").disabled = false;
                    $('#letra_factura').prop('disabled', false);
                    $('#letra_factura').find('[value="C"]').remove();
                    $('#letra_factura').find('[value="Ticket"]').remove();
                    document.getElementById("letra_factura").value = "A";
                  }
                }
                document.getElementById("row_tabla").style.display = "block";
                document.getElementById("row_boton").style.display = "none";
                document.getElementById("cli_o_prov").disabled = true;
                $("#cli_o_prov").selectpicker("refresh");
                document.getElementById("boton_confirmar").disabled = true;

                document.getElementById("matInsu").disabled = false;
                $("#matInsu").selectpicker("refresh");

                document.getElementById("chkbox_factura").disabled = false;
                document.getElementById("chkbox_remito").disabled = false;
                document.getElementById("chkbox_remito").checked = true;
                document.getElementById("chkbox_sinComprob").disabled = false;
                document.getElementById("num_remito").disabled = false;
                $('#letra_remito').prop('disabled', false);

                document.getElementById("descrip_egreso").disabled = true;
                document.getElementById("boton_confirmar").disabled = true;

                document.getElementById("matInsu").disabled = false;
                $("#matInsu").selectpicker("refresh");

                document.getElementById("cant_unElem").value = "0";
                document.getElementById("cant_disp_unElem").value = "0";
              }
            </script>
          </div>

          <div class="row" id="row_documentos">
            <hr>
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
                  
                  document.getElementById("num_nc").disabled = true;
                  document.getElementById("num_nc").value = "";
                  document.getElementById("row_documentos4").style.display = "none";
                } else {
                  document.getElementById("chkbox_factura").disabled = false;
                  document.getElementById("chkbox_remito").disabled = false;
                }
              }
            </script>
          </div>

          <div class="row" id="row_documentos3">
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
                } else {
                  document.getElementById("num_remito").disabled = true;
                  document.getElementById("num_remito").value = "";
                  $('#letra_remito').prop('disabled', true);
                  document.getElementById("letra_remito").value = "";
                }
              }
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

            <script type="text/javascript">
              function blockSpecialChar(e) {
                var k = e.keyCode;
                return (!(k == 34 || k == 39));
              }
            </script>
          </div>

          <div class="row" id="row_documentos2">
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
                  if (document.getElementById("segun").selectedIndex == 1) {
                    document.getElementById("chkbox_nc").disabled = false;
                    document.getElementById("chkbox_nc").checked = false;
                    document.getElementById("row_documentos4").style.display = "block";
                  }
                }
                else {
                  document.getElementById("num_factura").disabled = true;
                  document.getElementById("num_factura").value = "";
                  $('#letra_factura').prop('disabled', true);
                  document.getElementById("letra_factura").value = "";
                  if (document.getElementById("segun").selectedIndex == 1) {
                    document.getElementById("num_nc").disabled = true;
                    document.getElementById("num_nc").value = "";
                    document.getElementById("row_documentos4").style.display = "none";
                  }
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
          </div>

          <div class="row" id="row_documentos4">
            <div class="col-xs-4">
            </div>
            <div class="col-xs-1">
              <h5><input type="checkbox" id="chkbox_nc" value="" disabled onClick="chk_nc()"> <label>N.C.</label></h5>
            </div>
            <script>
              function chk_nc() {
                if (document.getElementById("chkbox_nc").checked) {
                  document.getElementById("num_nc").disabled = false;
                }
                else {
                  document.getElementById("num_nc").disabled = true;
                  document.getElementById("num_nc").value = "";
                }
              }
            </script>
            <div class="col-xs-2">
              <input type="name" class="form-control" id="num_nc" maxlength="20" placeholder="Nº Nota Crédito" disabled onkeypress="return blockSpecialChar(event)">
            </div>
          </div>

          <div class="rowtemp" id="row_tabla">
            <hr>
            <div class="col-md-12">
              <h2><b>Egreso </b>- Detalles</h2>
              <h4><b><u>Agregar detalles:</u></b></h4>
            </div>
            <div class="col-md-7">
              <label for="prod" class="control-label">Material / Insumo:</label>
            </div>
            <!-- <div class="col-md-2">
              <label>Cantidad disponible:</label>
            </div> -->
            <div class="col-md-2">
              <label>Disponible: </label>
            </div>
            <div class="col-md-3">
              <label>Cantidad:</label>
            </div>

            <div class="col-md-7">
              <select class="selectpicker" data-show-subtext="true" data-live-search="true" name="matInsu" id="matInsu" required="required" disabled data-width="100%" onchange="javascript:cant_disponib()">
              <option value="" disabled selected>Seleccione un elemento</option>
              <?php  foreach ($all_matsInsu as $un_matInsu): ?>
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

                  <?php  foreach ($all_matsInsu as $un_matIns): ?>
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
            </div>
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
                  if (confirm('¿Está seguro que desea EGRESAR estos materiales según '+document.getElementById("segun").options[document.getElementById("segun").selectedIndex].text+'?')) {
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

                    arrayCarac.proveedo = "";
                    arrayCarac.client = "";
                    arrayCarac.concept = $("#segun").val(); //Concepto
                                        
                    if ($("#segun").val() == 1)
                      arrayCarac.proveedo = $('#cli_o_prov').val();
                    if ($("#segun").val() == 2)
                      arrayCarac.client = $('#cli_o_prov').val(); //id_cliente

                    arrayCarac.fech= '<?php echo date('Y-m-d');?>';
                    arrayCarac.hor= '<?php echo date('H:i:s');?>';
                    arrayCarac.n_rem = $('#num_remito').val();
                    arrayCarac.l_rem = $('#letra_remito').val();
                    arrayCarac.n_fac = $('#num_factura').val();
                    arrayCarac.l_fac = $('#letra_factura').val();
                    arrayCarac.n_nc = $('#num_nc').val();
                    arrayCarac.descripcio = $('#descrip_egreso').val();

                    arrayCarac.tip = "0"; //INGRESO tipo 1, EGRESO tipo 0
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

                  document.getElementById("boton_agregar").disabled = true;
                  document.getElementById("cant_unElem").value = "";
                  document.getElementById("cant_disp_unElem").value = "";
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
                        <?php  foreach ($all_matsInsu as $un_matInsee): ?>
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
          </div>
          
            <hr>
            <div class="form-group text-right">
              <a href="inventario.php" class="btn btn-primary" role=button>Volver a Inventario</a>
              <button type="button" class="btn btn-success" name="guardar2" id="guardar2" disabled="disabled">Aceptar</button>
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
            <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
        </div>

<?php include_once('layouts/footer.php'); ?>