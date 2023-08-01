<?php
  $page_title = 'Cotizador de productos WEG';
  require_once('includes/load.php');
  page_require_level(2);
  $all_productos = find_all('weg_lista_productos');
  $all_porcentajes = find_porcentaje('1');
  $all_clientes = find_all('cliente');
  $mydata = Array();
  $tabla = "";
  $query_parts = Array();
  $ultimo_presupuesto = find_last('presupuesto');
?>

<?php
  
  if (isset($_POST['datos'])){
    $datos_form = json_decode($_POST['datos'], true);

    $desc_ok = remove_junk($db->escape($datos_form['descuento']));
    $id_cliente = remove_junk($db->escape($datos_form['cliente']));
    $trans_ok = remove_junk($db->escape($datos_form['transporte']));
    $ing_br_ok = remove_junk($db->escape($datos_form['ingreso_bruto']));
    $gastos_adm_ok = remove_junk($db->escape($datos_form['gastos_admin']));
    $ganan_ok = remove_junk($db->escape($datos_form['ganancia']));
    $imp_ganan_ok = remove_junk($db->escape($datos_form['imp_ganancia']));
    $total_cotiz_ok = remove_junk($db->escape($datos_form['total_cotiz']));
    $iva_ok = remove_junk($db->escape($datos_form['ali_iva']));
    $total_c_iva_ok = remove_junk($db->escape($datos_form['total_c_iva']));
    $obs_ok = remove_junk($db->escape($datos_form['obser']));
    $forma_p_ok = remove_junk($db->escape($datos_form['forma_p']));

    if(empty($errors)){
      $hoy = date('y-m-j');

      if(empty($id_cliente))
        $sql2  = "INSERT INTO presupuesto (`total`, `descuento`, `transporte`, `ing_bruto`, `gastos_adm`, `ganancia`, `imp_ganancia`, `alicuota_iva`, `total_c_iva`, `fecha_emision`, `observaciones`, `forma_pago`) VALUES ('{$total_cotiz_ok}', '{$desc_ok}', '{$trans_ok}', '{$ing_br_ok}', '{$gastos_adm_ok}', '{$ganan_ok}', '{$imp_ganan_ok}', '{$iva_ok}', '{$total_c_iva_ok}', '{$hoy}', '{$obs_ok}', '{$forma_p_ok}')";
      else
        $sql2  = "INSERT INTO presupuesto (`id_cliente`, `total`, `descuento`, `transporte`, `ing_bruto`, `gastos_adm`, `ganancia`, `imp_ganancia`, `alicuota_iva`, `total_c_iva`, `fecha_emision`, `observaciones`, `forma_pago`) VALUES ('{$id_cliente}', '{$total_cotiz_ok}', '{$desc_ok}', '{$trans_ok}', '{$ing_br_ok}', '{$gastos_adm_ok}', '{$ganan_ok}', '{$imp_ganan_ok}', '{$iva_ok}', '{$total_c_iva_ok}', '{$hoy}', '{$obs_ok}', '{$forma_p_ok}')";

      if($db->query($sql2)){
        $ultimo_presupuesto = find_last('presupuesto');
        $url = 'pdf_presupuesto.php?id='.$ultimo_presupuesto['id'];

        $query = "INSERT INTO presupuesto_detalles (`id_presupuesto`, `id_producto`, `cantidad`, `p_unit`) VALUES ";

        foreach($datos_form['datos'] as $unDetalle){
          $query_parts[] = " ({$ultimo_presupuesto["id"]}, {$unDetalle["cod"]}, {$unDetalle["cant"]}, {$unDetalle["p_unit"]}) ";
        }

        $query .= implode(',', $query_parts);
        $db->query($query);
        
      } else {
        redirect('weg_cotizador.php',false);
      }
    } else {
      $session->msg("d", $errors);
      redirect('weg_cotizador.php',false);
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
            <h4><span class="glyphicon glyphicon-th"></span><b> Cotizador de productos WEG </b></h4>
          </strong>
        </div>


        <div class="panel-body">

          <div class="row">
            <div class="col-md-4">
              <h4><b><u>Porcentajes:</u></b></h4>
            </div>

          </div>

          <div class="row">
            <div class="col-md-3">
              <label for="prod" class="control-label">Descuento:</label>
            </div>
            <div class="col-md-3">
              <label for="prod" class="control-label">Transporte:</label>
            </div>
            <div class="col-md-3">
              <label for="prod" class="control-label">Ingreso bruto:</label>
            </div>
            <div class="col-md-3">
              <label for="prod" class="control-label">Gastos administ.:</label>
            </div>
          </div>

          <div class="row">
            <div class="col-md-3">
              <input type="text" class="form-control" placeholder="Desc." id="desc" name="desc" value="<?php echo str_replace('.', ',',number_format($all_porcentajes[0][2],2));?>" onchange="javascript:descue()"></input>
            </div>
            <script>
              function descue(){
                var descu = document.getElementById("desc").value;
                descu = descu.replace(",", ".");
                descu = parseFloat(descu).toFixed(2);
                if (descu == "NaN")
                  descu = '0,00';
                desc.value = descu.replace(".", ",");

                actualizarTabla();
                calcular_totales();
              };
            </script>

            <div class="col-md-3">
              <input type="text" class="form-control" placeholder="Transp." id="tran" name="tran" required value="<?php echo str_replace('.', ',',number_format($all_porcentajes[0][3],2));?>" onchange="javascript:transp()"></input>
            </div>
            <script>
              function transp(){
                var trans = document.getElementById("tran").value;
                trans = trans.replace(",", ".");
                trans = parseFloat(trans).toFixed(2);
                if (trans == "NaN")
                  trans = 0;
                tran.value = trans.replace(".", ",");

                actualizarTabla();
                calcular_totales();
              };
            </script>

            <div class="col-md-3">
              <input type="text" class="form-control" placeholder="Ing. bruto" id="ing_br" name="ing_br" required value="<?php echo str_replace('.', ',',number_format($all_porcentajes[0][4],2));?>" onchange="javascript:ing_bruto()"></input>
            </div>
            <script>
              function ing_bruto(){
                var ing_bru = document.getElementById("ing_br").value;
                ing_bru = ing_bru.replace(",", ".");
                ing_bru = parseFloat(ing_bru).toFixed(2);
                if (ing_bru == "NaN")
                  ing_bru = 0;
                ing_br.value = ing_bru.replace(".", ",");

                actualizarTabla();
                calcular_totales();
              };
            </script>

            <div class="col-md-3">
              <input type="text" class="form-control" placeholder="Gastos adm." id="gas_adm" name="gas_adm" required value="<?php echo str_replace('.', ',',number_format($all_porcentajes[0][5],2));?>" onchange="javascript:gas_admin()"></input>
            </div>
            <script>
              function gas_admin(){
                var gas_admi = document.getElementById("gas_adm").value;
                gas_admi = gas_admi.replace(",", ".");
                gas_admi = parseFloat(gas_admi).toFixed(2);
                if (gas_admi == "NaN")
                  gas_admi = 0;
                gas_adm.value = gas_admi.replace(".", ",");

                actualizarTabla();
                calcular_totales();
              };
            </script>
          </div>

          <br>

          <div class="row">
            <div class="col-md-3">
              <label for="prod" class="control-label">Ganancia:</label>
            </div>
            <div class="col-md-3">
              <label for="prod" class="control-label">Imp. Ganancia:</label>
            </div>
            <div class="col-md-3">
              <label for="iva" class="control-label"> Alícuota IVA: </label>
            </div>
          </div>

          <div class="row">

            <div class="col-md-3">
              <input type="text" class="form-control" placeholder="Ganancia" id="ganan" name="ganan" required value="<?php echo str_replace('.', ',',number_format($all_porcentajes[0][6],2));?>" onchange="javascript:ganancias()"></input>
            </div>
            <script>
              function ganancias(){
                var gananc = document.getElementById("ganan").value;
                gananc = gananc.replace(",", ".");
                gananc = parseFloat(gananc).toFixed(2);
                if (gananc == "NaN")
                  gananc = 0;
                ganan.value = gananc.replace(".", ",");

                actualizarTabla();
                calcular_totales();
              };
            </script>

            <div class="col-md-3">
              <input type="text" class="form-control" placeholder="Imp. Ganancia" id="imp_ganan" name="imp_ganan" required value="<?php echo str_replace('.', ',',number_format($all_porcentajes[0][7],2));?>" onchange="javascript:imp_ganancias()"></input>
            </div>
            <script>
              function imp_ganancias(){
                var imp_gananci = document.getElementById("imp_ganan").value;
                imp_gananci = imp_gananci.replace(",", ".");
                imp_gananci = parseFloat(imp_gananci).toFixed(2);
                if (imp_gananci == "NaN")
                  imp_gananci = 0;
                imp_ganan.value = imp_gananci.replace(".", ",");

                actualizarTabla();
                calcular_totales();
              };
            </script>

            <div class="col-md-3">
              <input type="text" class="form-control" placeholder="Alícuota IVA" id="alic_iva" name="alic_iva" required value="<?php echo str_replace('.', ',',number_format($all_porcentajes[0][8],2));?>" onchange="javascript:alicu_iva()"></input>
            </div>

            <script>
              function alicu_iva(){
                var alicuota = document.getElementById("alic_iva").value;
                alicuota = alicuota.replace(",", ".");
                alicuota = parseFloat(alicuota).toFixed(2);
                if (alicuota == "NaN")
                  alicuota = 0;
                alic_iva.value = alicuota.replace(".", ",");

                actualizarTabla();
                calcular_totales();
              };
            </script>
          </div> 
          <br>
          <div class="row">
            <div class="col-md-12" align="right">
              <button class="btn btn-danger" name="guardar1" id="guardar1">Editar porcentajes preestablecidos</button>

              <script type="text/javascript">
                document.getElementById("guardar1").onclick = function () {
                  location.href = "actualizar_porcentajes.php";
                };
              </script>
            </div>
          </div>
          
          <hr>

          <h4><b><u>Especificaciones:</u></b></h4>
          <div class="row">
            <div class="col-md-10">
              <label for="clie" class="control-label">Cliente:</label>
            </div>
          </div>
          <div class="row">
            <div class="col-md-10">
              <select class="selectpicker" data-show-subtext="true" data-live-search="true" name="clien_sel" id="clien_sel"data-width="100%">
                <option value="" selected >*** Sin Cliente ***</option>
                <?php  foreach ($all_clientes as $cli): ?>
                  <option value="<?php echo (int) $cli['id']?>">
                  <?php echo $cli['razon_social'] , ' - ',  $cli['direccion'] , ' - ',  $cli['localidad'], ', ', utf8_encode(find_by_id_prov('provincia', $cli['provincia'])['nombre'])?></option>
                <?php endforeach; ?>
              </select>
            </div>

          </div>
          <br>
          <div class="row">
            <div class="col-md-7">
              <label for="clie" class="control-label">Observaciones:</label>
            </div>
            <div class="col-md-3">
              <label for="clie" class="control-label">Formas de pago:</label>
            </div>
          </div>
          <div class="row">
            <div class="col-md-7">
              <textarea type="text" class="form-control" placeholder="Observaciones" id="obs" name="obs" rows="4">Precio de materiales expresado en dólares estadounidenses. La facturación se realizará en pesos argentinos de acuerdo a la cotización oficial Tipo Vendedor del BNA en el día de facturación. Validez: 7 días hábiles.</textarea>
            </div>
            <div class="col-md-3">
              <textarea type="text" class="form-control" placeholder="Formas de pago" id="forma_pago" name="forma_pago" rows="4">Anticipado.</textarea>
            </div>
          </div>

          <hr>

          <h4><b><u>Agregar productos:</u></b></h4>
          <div class="row">
            <div class="col-md-8">
              <label for="prod" class="control-label">Producto:</label>
            </div>
            <div class="col-md-2">
              <label for="cant" class="control-label">Cantidad:</label>
            </div>
          </div>
          <div class="row">
            <div class="col-md-8">
              <select class="selectpicker" data-show-subtext="true" data-live-search="true" name="prod" id="prod" required="required" data-width="100%">
                <option value="" disabled selected>Seleccione un producto de la lista</option>
                <?php  foreach ($all_productos as $prod): ?>
                  <option value="<?php echo $prod['codigo']?>">
                  <?php echo $prod['codigo'], ' - ';
                    if (strlen($prod['descripcion']) > 85) {
                      echo substr($prod['descripcion'], 0, 85), ' [...]';
                    } else {
                      echo $prod['descripcion'];
                    }
                    echo ' - ', utf8_decode($prod['referencia']), ' - U$S ',  str_replace('.', ',',$prod['precio'])?></option>
                <?php endforeach; ?>
              </select>
              <script>
                $(function() {
                  document.getElementById("prod").selectedIndex = "1";
                });
              </script>
            </div>
            <div class="col-md-2">
              <input type="text" class="form-control" placeholder="Cantidad" id="cant" name="cant" value = "1" required></input>
            </div>
            <div class="col-md-1">
              <button type="button" class="btn btn-info add-new">Agregar detalle</button>
            </div>
          </div>
            
<!-- Detalle Medición -->
          <div class="col-md-12">
            <script type="text/javascript">
              $(document).ready(function(){
                $('[data-toggle="tooltip"]').tooltip();
                var actions = $("table td:last-child").html();
              
                $('#guardar2').click(function(){
                  if (confirm('¿Está seguro que desea guardar este presupuesto? \n\nSe generará un código nuevo y se almacenará en base de datos, con una vigencia de 7 días hábiles.')) {
                    var arrayCarac = {};
                    var miTabla = [];
                    if ($('#tabla_detalles tr').length>1){
                      $('#tabla_detalles tr').each(function(index,element){
                        if(index>0){    
                          var fila = $(element).find('td');
                          miTabla.push({
                            "cod" : fila.eq(0).html().replace(",", "."),
                            "cant" : fila.eq(1).html().replace(",", "."),
                            "p_unit" : fila.eq(6).html().replace(",", ".")
                          });
                        } 
                      });
                    } else {
                      window.alert("Debe ingresar al menos un producto en la tabla.");
                    }
                    arrayCarac.cliente=$("#clien_sel").val();
                    arrayCarac.descuento=$("#desc").val().replace(",", ".");
                    arrayCarac.transporte=$('#tran').val().replace(",", ".");
                    arrayCarac.ingreso_bruto=$("#ing_br").val().replace(",", ".");
                    arrayCarac.gastos_admin=$("#gas_adm").val().replace(",", ".");
                    arrayCarac.ganancia=$("#ganan").val().replace(",", ".");
                    arrayCarac.imp_ganancia=$('#imp_ganan').val().replace(",", ".");
                    arrayCarac.ali_iva=$('#alic_iva').val().replace(",", ".");
                    arrayCarac.total_cotiz=$('#input_total_uss').val().replace(",", ".");
                    arrayCarac.total_c_iva=$('#input_total_uss_iva').val().replace(",", ".");
                    arrayCarac.obser=$('#obs').val();
                    arrayCarac.forma_p=$('#forma_pago').val();
                    arrayCarac.datos=miTabla;
                    var miJson = JSON.stringify(arrayCarac);
                    $.ajax({
                      data : {'datos':miJson},
                      method:'post',
                      success: function(response){
                        window.alert("¡Éxito!\nEl presupuesto fue creado satisfactoriamente");
                      },
                      error: function(xhr, textStatus, error){
                        console.log(xhr.statusText);
                        console.log(textStatus);
                        console.log(error);
                      }
                    });
                    $(document).ajaxStop(function(){
                      window.location.replace("presupuestos.php");
                    });
                  } else {
                    return false;
                  }
                });

                $(".add-new").click(function(){

                  document.getElementById("guardar2").disabled = false;
              
                  <?php  foreach ($all_productos as $prod): ?>
                    if( "<?php echo $prod['codigo']?>" == document.getElementById("prod").value){
                      var codigo = "<?php echo $prod['codigo']?>";
                      var ref = "<?php echo $prod['descripcion']?>";
                      var precio = parseFloat("<?php echo $prod['precio']?>");
                      var p_neto_c_descuento = precio * (1-(desc.value.replace(",", ".")/100));
                      var costo = p_neto_c_descuento + (p_neto_c_descuento * (tran.value.replace(",", ".")/100)) + (p_neto_c_descuento * (ing_br.value.replace(",", ".")/100)) + (p_neto_c_descuento * (gas_adm.value.replace(",", ".")/100));
                      var ganancia = (costo * (ganan.value.replace(",", ".")/100));
                      var p_neto_c_gastos = (costo + ganancia + (ganancia * (imp_ganan.value.replace(",", ".")/100))).toFixed(2);
                      var subtotal = p_neto_c_gastos * parseInt(cant.value.replace(",", "."));
                    }
                  <?php endforeach;?>

                  var index = $("table tbody tr:last-child").index();
                  var row = '<tr>' +
                      '<td>' + codigo +'</td>' + 
                      '<td>' + parseInt(cant.value) + '</td>' + 
                      '<td>' + ref + '</td>' + 
                      '<td>' + parseFloat(precio).toFixed(2).replace(".", ",") + '</td>' + 
                      '<td>' + parseFloat(p_neto_c_descuento).toFixed(2).replace(".", ",") + '</td>' + 
                      '<td>' + parseFloat(costo).toFixed(2).replace(".", ",") + '</td>' + 
                      '<td>' + parseFloat(p_neto_c_gastos).toFixed(2).replace(".", ",") + '</td>' + 
                      '<td>' + parseFloat(subtotal).toFixed(2).replace(".", ",") + '</td>' + 
                      '<td><a class="add" title="Add" data-toggle="tooltip"><i class="material-icons">&#xE03B;</i></a><a class="edit" title="Edit" data-toggle="tooltip"><i class="material-icons">&#xE254;</i></a><a class="delete" title="Delete" data-toggle="tooltip"><i class="material-icons">&#xE872;</i></a></td>' + '</tr>';
                  $("table").append(row);
                  $('[data-toggle="tooltip"]').tooltip();

                  calcular_totales();
                });

              // Add row on add button click
                $(document).on("click", ".add", function(){
                  var empty = false;
                  var input = $(this).parents("tr").find('input[type="text"]');
                  
                  input.each(function(){
                    if(!$(this).val()){
                      $(this).addClass("error");
                      empty = true;
                    } else {
                      if ($(this).val() == "NaN"){
                        $(this).addClass("error");
                        empty = true;
                      } else {
                        $(this).removeClass("error");
                      }
                      
                    }
                  }); //Control de errores para los campos

                  input.each(function(){
                    $(this).parent("td").html($(this).val().replace(".", ","));
                  });  

                  $(this).parents("tr").find(".add, .edit").toggle();
                  $(".add-new").removeAttr("disabled");
                  document.getElementById("guardar2").disabled = false;
                  calcular_totales();

                  var tbl  = document.getElementById('tabla_detalles');
                  var rows = tbl.getElementsByTagName('tr');

                  for (var row=1; row<$('#tabla_detalles tr').length;row++) {
                    var cels = rows[row].getElementsByTagName('td');
                    cels[8].style.display = '';
                  }

                  document.getElementById("desc").disabled = false;
                  document.getElementById("tran").disabled = false;
                  document.getElementById("ing_br").disabled = false;
                  document.getElementById("gas_adm").disabled = false;
                  document.getElementById("ganan").disabled = false;
                  document.getElementById("imp_ganan").disabled = false;
                });

              // Edit row on edit button click
                $(document).on("click", ".edit", function(){
                  var cont = 0;
                  $(this).parents("tr").find("td:not(:last-child)").each(function(){
                    switch (cont) {
                      case 1:
                        $(this).html('<input id="canti" type="text" name="canti" class="form-control" value=' + $(this).text() + ' onchange="javascript:calculo()">');
                        break;
                      case 6:
                        $(this).html('<input id="p_c_gastos" type="text" name="p_c_gastos" class="form-control" readonly="readonly" value="' + $(this).text().replace(",", ".") + '">');
                        break;
                      case 7:
                        $(this).html('<input id="subtot" type="text" name="subtot" class="form-control" readonly="readonly" value="' + $(this).text().replace(",", ".") + '">');
                        break;
                    }
                    cont = cont + 1;
                  });

                  var tbl  = document.getElementById('tabla_detalles');
                  var rows = tbl.getElementsByTagName('tr');

                  for (var row=1; row<$('#tabla_detalles tr').length;row++) {
                    if (row != $(this).parents('tr').index()){
                      var cels = rows[row].getElementsByTagName('td');
                      cels[8].style.display = 'none';
                    }
                  }

                  $(this).parents("tr").find(".add, .edit").toggle();
                  $(".add-new").attr("disabled", "disabled");
                  document.getElementById("guardar2").disabled = true;

                  document.getElementById("desc").disabled = true;
                  document.getElementById("tran").disabled = true;
                  document.getElementById("ing_br").disabled = true;
                  document.getElementById("gas_adm").disabled = true;
                  document.getElementById("ganan").disabled = true;
                  document.getElementById("imp_ganan").disabled = true;
                });

              // Delete row on delete button click
                $(document).on("click", ".delete", function(){
                  $(this).parents("tr").remove();
                  $(".add-new").removeAttr("disabled");
                  document.getElementById("guardar1").disabled = false;
                  document.getElementById("guardar2").disabled = false;

                  calcular_totales();

                  var tbl  = document.getElementById('tabla_detalles');
                  var rows = tbl.getElementsByTagName('tr');

                  for (var row=1; row<$('#tabla_detalles tr').length;row++) {
                    var cels = rows[row].getElementsByTagName('td');
                    cels[8].style.display = '';
                  } 
                  document.getElementById("desc").disabled = false;
                  document.getElementById("tran").disabled = false;
                  document.getElementById("ing_br").disabled = false;
                  document.getElementById("gas_adm").disabled = false;
                  document.getElementById("ganan").disabled = false;
                  document.getElementById("imp_ganan").disabled = false;

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

            <hr>
        <!-- <div class="table-wrapper"> -->
            <div class="table-title">
              <div class="col">
                <h2><b>WEG </b>- Detalles</h2>
              </div>
            </div>
            <table id="tabla_detalles" name="tabla_detalles" class="table table-striped table-bordered">
              <tr>
                <th style="width: 10%;" class="text-center" name="col1" id="col1">Cod.</th>
                <th style="width: 10%;" class="text-center" name="col2" id="col2">Cant.</th>
                <th style="width: 22%;" class="text-center" name="col3" id="col3">Ref.</th>
                <th style="width: 12%;" class="text-center" name="col4" id="col4">P. lista (U$S)</th>
                <th style="width: 8%;" class="text-center" name="col5" id="col5">P. Neto c/desc.</th>
                <th style="width: 11%;" class="text-center" name="col6" id="col6">Costo</th>
                <th style="width: 8%;" class="text-center" name="col7" id="col7">P. Neto c/gastos</th>
                <th style="width: 10%;" class="text-center" name="col8" id="col8">Subtotal</th>
                <th style="width: 11%;" class="text-center"></th>
              </tr>
            </table>
          
            <hr>
          
            <div style="margin-left:550px">
              <div class="row" style="background-color:#DADADA">
                <div class="col-md-8" align="left">
                  <label bgcolor="#DADADA" scope="row">TOTAL PRESUPUESTO en dólares</label>
                </div>
                <div class="col-md-4" align="right">
                  <label bgcolor="#DADADA" name="total_uss" id="total_uss">U$S 0</label>
                  <input type="hidden" class="form-control" id="input_total_uss" name="input_total_uss"></input>
                </div>
              </div>
              <div class="row" style="background-color:#DADADA">
                <div class="col-md-8" align="left">
                  <label bgcolor="#DADADA" scope="row">TOTAL PRESUPUESTO c/IVA</label>
                </div>
                <div class="col-md-4" align="right">
                  <label bgcolor="#DADADA" name="total_uss_iva" id="total_uss_iva">U$S 0</label>
                  <input type="hidden" class="form-control" id="input_total_uss_iva" name="input_total_uss_iva"></input>
                </div>
              </div>
            </div>
            <hr>
            <div class="form-group text-right">
              <a href="presupuestos.php" class="btn btn-primary" role=button>Volver a presupuestos</a>
              <button type="button" class="btn btn-danger" name="guardar2" id="guardar2" disabled="disabled">Generar PDF</button>
            </div>

            <script>
              function calculo(){
                var cant = document.getElementById("canti").value;
                cant = cant.replace(",", ".");
                cant = parseInt(cant);
                canti.value = cant;

                var precio = document.getElementById("p_c_gastos").value;
                subtot.value = parseFloat(precio * cant).toFixed(2);

              };

              function calcular_totales(){
                var total_pesos = 0;
                var total_uss = 0;
                $('#tabla_detalles tr').each(function(index,element){
                  if(index > 0){    
                    var fila = $(element).find('td');
                    total_uss += parseFloat(fila.eq(7).html().replace(",", "."));
                  } 
                });

                document.getElementById('total_uss').innerHTML = "U$S " + total_uss.toFixed(2).replace(".", ",");
                document.getElementById('input_total_uss').value = total_uss.toFixed(2);

                document.getElementById('total_uss_iva').innerHTML = "U$S " + (total_uss+total_uss * (alic_iva.value.replace(",", ".")/100)).toFixed(2).replace(".", ",");
                document.getElementById('input_total_uss_iva').value = (total_uss+total_uss * (alic_iva.value.replace(",", ".")/100)).toFixed(2);
              }

              function actualizarTabla(){
                var tbl  = document.getElementById('tabla_detalles');
                var rows = tbl.getElementsByTagName('tr');

                for (var row = 1; row < $('#tabla_detalles tr').length; row++) {
                  var cels = rows[row].getElementsByTagName('td');
                  
                  var precio = cels[3].innerText.replace(",", ".");
                  var p_neto_c_descuento = precio * (1-(desc.value.replace(",", ".")/100));
                  var costo = p_neto_c_descuento + (p_neto_c_descuento * (tran.value.replace(",", ".")/100)) + (p_neto_c_descuento * (ing_br.value.replace(",", ".")/100)) + (p_neto_c_descuento * (gas_adm.value.replace(",", ".")/100));
                  var ganancia = (costo * (ganan.value.replace(",", ".")/100));
                  var p_neto_c_gastos = costo + ganancia + (ganancia * (imp_ganan.value.replace(",", ".")/100));
                  var canti = cels[1].innerText.replace(",", ".");
                  var subtotal = p_neto_c_gastos * parseInt(canti);

                  cels[4].innerText = parseFloat(p_neto_c_descuento).toFixed(2).replace(".", ",");
                  cels[5].innerText = parseFloat(costo).toFixed(2).replace(".", ",");
                  cels[6].innerText = parseFloat(p_neto_c_gastos).toFixed(2).replace(".", ",");
                  cels[7].innerText = parseFloat(subtotal).toFixed(2).replace(".", ",");
                }
              };
            </script>
            <br><br><br>
          </div>
        </div>

<?php include_once('layouts/footer.php'); ?>