<?php
  $page_title = 'Orden de compra';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
  $all_proveedores = find_all('proveedor');
  $cod = find_by_sql("SELECT id FROM orden_compra ORDER BY id DESC LIMIT 1");
  $mydata = Array();
  $tabla = "";
  $query_parts = Array();
?>

<?php
  if(isset($_POST['datos'])){
    $datos_form = json_decode($_POST['datos'], true);

    $num_prov = remove_junk($db->escape($datos_form['proveedor']));
    $moned = remove_junk($db->escape($datos_form['moneda']));
    $obs = remove_junk($db->escape($datos_form['observaciones']));
    $form_pago = remove_junk($db->escape($datos_form['formaPago']));
    $form_envio = remove_junk($db->escape($datos_form['formaEnvio']));    

    $fech_emi = remove_junk($db->escape($datos_form['fechaEmision']));
    $fech_emi = explode ("-", $fech_emi); 
    $fech1 = $fech_emi[2]."-".$fech_emi[1]."-".$fech_emi[0];

    $fech_val = remove_junk($db->escape($datos_form['validez']));
    $fech_val = explode ("-", $fech_val); 
    $fech2 = $fech_val[2]."-".$fech_val[1]."-".$fech_val[0];

    
    if(empty($errors)){
      $sql  = "INSERT INTO orden_compra ( `id_proveedor`, `fecha_emision`, `fecha_validez`,`observaciones`,`forma_pago`,`forma_envio`, `moneda`) VALUES ( '{$num_prov}','{$fech1}','{$fech2}','{$obs}','{$form_pago}','{$form_envio}','{$moned}')";
      
      if($db->query($sql)){
        
        $ultima_orden = find_last('orden_compra');

        //RECORRES DETALLES
        $query = "INSERT INTO orden_detalles (`id_orden`, `item`, `descripcion`,`cantidad`,`precio_unit`,`bonificacion`, `iva`) VALUES ";

        foreach($datos_form['datos'] as $unDetalle){
          $query_parts[] = " ('{$ultima_orden['id']}', '{$unDetalle["item"]}', '{$unDetalle["descripcion"]}', '{$unDetalle["cantidad"]}', '{$unDetalle["precioUnitario"]}', '{$unDetalle["porcBonif"]}', '{$unDetalle["alicuotaIva"]}') ";

        }
        $query .= implode(',', $query_parts);

        if($db->query($query)){
          $session->msg("s", "Orden de compra agregada exitosamente.");
          redirect('orden_compra.php',false);
        } else {
          $session->msg("d", "Lo siento, el registro falló");
          redirect('orden_compra.php',false);
        }
      } else {
        $session->msg("d", "Lo siento, el registro falló");
        redirect('orden_compra.php',false);
      }
    } else {
      $session->msg("d", $errors);
      redirect('orden_compra.php',false);
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
            <?php  foreach ($cod as $c): ?>
              <h4><span class="glyphicon glyphicon-th"></span><b> Nueva orden de compra - </b> Nº <?php 
              for ($i = 1; $i <= (7-strlen($c['id'])); $i++) {
                  echo 0;
                  if ($i == '3')
                    echo '-';
              }
              echo $c['id']+1;
              ?></h4>
            <?php endforeach; ?>  
          </strong>
        </div>

  <!-- "seleccionar un proveedor" -->
        <div class="panel-body">
          <div class="row">
            <div class="col-md-7">
              <label for="ban" class="control-label">Proveedor:</label>
            </div>
            <div class="col-md-4">
              <label for="ban" class="control-label">Moneda de la orden de compra:</label>
            </div>
          </div>
          <div class="row">
            <div class="col-md-7">
              <select class="selectpicker" data-show-subtext="true" data-live-search="true" name="pro" id="pro" required="required" data-width="100%">
                <option value="" disabled selected>Seleccione un Proveedor</option>
                <?php  foreach ($all_proveedores as $prov): ?>
                  <option value="<?php echo (int) $prov['id']?>">
                  <?php echo $prov['razon_social'] , ' - ',  $prov['direccion'] , ' - ',  $prov['localidad'], ' - ', find_by_id_prov('provincia', $prov['provincia'])['nombre']?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-4">
              <select class="selectpicker" data-show-subtext="true" data-live-search="true" name="mone" id="mone" required="required" data-width="100%">
                <option value="" disabled selected>Seleccione una Moneda</option>
                <option value="0">$ - Peso argentino</option>
                <option value="1">U$S - Dolar estadounidense</option>
                <option value="2">R$ - Reales</option>
                <option value="3">Gs. - Guaraníes</option>
              </select>
            </div>
          </div>
          <script>
            document.getElementById("mone").selectedIndex = "1";
          </script>
          <div class="row">
            <div class="col-md-7">
              <span class="label label-danger" id='invalid_pro' style="display: none;">Debe seleccionar un proveedor</span>
            </div>          
            <div class="col-md-4">
              <span class="label label-danger" id='invalid_mone' style="display: none;">Debe seleccionar una moneda</span>
            </div>
          </div>


  <!-- "Observaciones / Forma de pago / Forma de envío" -->     
          <hr>
          <div class="row">
            <div class="col-md-11">
              <label for="obs" class="control-label">Observaciones</label>
            </div>
          </div>
          <div class="row">
            <div class="col-md-11">
              <textarea type="text" class="form-control" placeholder="Observaciones" id="obs" name="obs" rows="6" required>El pago del producto se realizará mediante transferencia bancaria en pesos argentinos, de acuerdo a facturación previamente enviada, por lo que se solicita factura 'A' para realizar dicha transferencia una vez que el producto se encuentra en el país, a nombre de Julio Jorge Sosa, CUIT: 20-18576428-2, Av. Lucas Braulio Areco N°2353 PB. Además, se solicitan datos bancarios para efectuar el pago por dichos productos.</textarea>
            </div>
          </div>
          <div class="row">
            <div class="col-md-11">
              <span class="label label-danger" id='invalid_obs' style="display: none;">El espacio para observaciones no puede quedar vacío</span>
            </div>
          </div>

          <hr>
          <div class="row">
            <div class="col-md-5">
              <label for="forma_pago" class="control-label">Forma de pago</label>
            </div>
            <div class="col-md-1"></div>
            <div class="col-md-5">
              <label for="forma_envio" class="control-label">Forma de envío</label>
            </div>
          </div>
          <div class="row">
            <div class="col-md-5">
              <input type="text" class="form-control" placeholder="Forma de pago" id="forma_pago" name="forma_pago" required></input>
            </div>
            <div class="col-md-1"></div>
            <div class="col-md-5">
              <input type="text" class="form-control" placeholder="Forma de envío" id="forma_envio" name="forma_envio" required></input>
            </div>
          </div>
          <div class="row">
            <div class="col-md-5">
              <span class="label label-danger" id='invalid_forma_pago' style="display: none;">Se debe ingresar una forma de pago</span>
            </div>
            <div class="col-md-1"></div>
            <div class="col-md-5">
              <span class="label label-danger" id='invalid_forma_envio' style="display: none;">Se debe ingresar una forma de envío</span>
            </div>
          </div>

  <!-- "Datos Importantes" -->
          <hr>
          <div class="row">
            <div class="col-md-5">
              <label for="fecha_emi" class="control-label">Fecha de emisión:</label>
            </div>
            <div class="col-md-1"></div>
            <div class="col-md-5">
              <label for="fecha_val" class="control-label">Validez de la orden de compra:</label>
            </div>
          </div>
          <div class="row">
            <div class="col-md-5">
              <input type="text" class="datepicker form-control" name="fecha_emi" id="fecha_emi" readonly required>
            </div>
            <div class="col-md-1"></div>
            <div class="col-md-5">
              <input type="text" class="datepicker form-control" name="fecha_val" id="fecha_val" value="Ingrese fecha de emisión" readonly disabled required>
            </div>
            <div class="col-md-2">
              <label vertical-align="middle" id="dias_habiles" style="display: none">(X días hábiles)</label>
            </div>
          </div>
          <div class="row">
            <div class="col-md-5">
              <span class="label label-danger" id='invalid_fecha_emi' style="display: none;">Seleccione una fecha de emisión</span>
            </div>
            <div class="col-md-1"></div>
            <div class="col-md-5">
              <span class="label label-danger" id='invalid_fecha_val' style="display: none;">Seleccione una fecha de validez</span>
            </div>
          </div>
          <br>
          
          <script>
            $('#fecha_emi').datepicker( {
              format: 'dd-mm-yyyy',
              autoclose: true
            }).on('changeDate', dateChanged);

            function dateChanged(selected) {
              $("#fecha_val").attr('disabled', false);
              $('#fecha_val').val("").datepicker("update");
              var minDate = new Date(selected.date.valueOf());
              $('#fecha_val').datepicker('setStartDate', minDate);

              var diha = document.getElementById("dias_habiles");
              diha.style.display = "none";
            }

            $('#fecha_val').datepicker( {
              format: 'dd-mm-yyyy',
              autoclose: true
            }).on('changeDate', dateChanged2);

            function dateChanged2(selected) {
              var cont = 0;

              const date1 = $('#fecha_emi').datepicker('getDate');
              const date2 = $('#fecha_val').datepicker('getDate');


              document.getElementById("dias_habiles").innerHTML = "(" + date2.workingDaysFrom(date1) + " días hábiles)";
              var diha = document.getElementById("dias_habiles");
              diha.style.display = "block";              
            }


            Date.prototype.workingDaysFrom=function(fromDate){
             // ensure that the argument is a valid and past date
             if(!fromDate||isNaN(fromDate)||this<fromDate){return -1;}
             
             // clone date to avoid messing up original date and time
             var frD=new Date(fromDate.getTime()),
                 toD=new Date(this.getTime()),
                 numOfWorkingDays=1;
             
             // reset time portion
             frD.setHours(0,0,0,0);
             toD.setHours(0,0,0,0);
             
             while(frD<toD){
              frD.setDate(frD.getDate()+1);
              var day=frD.getDay();
              if(day!=0&&day!=6){numOfWorkingDays++;}
             }
             return numOfWorkingDays;
            };

          </script>

        </div>
      </div>
    </div>
    
<!-- Detalle Orden de Compra -->
    <div class="col-md-12">
        <script type="text/javascript">
          $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
            var actions = $("table td:last-child").html();
            
            $('#add_orden').click(function(){
              
              var arrayCarac = {};
              var miTabla = [];
              if ($('#tabla_detalles tr').length>1){ // Controla que haya al menos una fila que no sea el encabezado de la tabla
                $('#tabla_detalles tr').each(function(index,element){
                  if(index>0){    
                    var fila = $(element).find('td');
                    miTabla.push({
                      "item" : fila.eq(0).html(),
                      "descripcion" : fila.eq(1).html(),
                      "cantidad" : fila.eq(2).html(),
                      "precioUnitario" : fila.eq(3).html().replace(",", "."),
                      "porcBonif" : fila.eq(4).html().replace(",", "."),
                      "alicuotaIva" : fila.eq(6).html().replace(",", ".")
                    });
                  } 
                });
                if (controlCampos()){
                  arrayCarac.proveedor=$("#pro").val();
                  arrayCarac.moneda=$('#mone').val();
                  arrayCarac.observaciones=$("#obs").val();
                  arrayCarac.formaPago=$("#forma_pago").val();
                  arrayCarac.formaEnvio=$("#forma_envio").val();
                  arrayCarac.fechaEmision=$('#fecha_emi').val();
                  arrayCarac.validez=$('#fecha_val').val();
                  arrayCarac.datos=miTabla;
                  var miJson = JSON.stringify(arrayCarac);
                  $.ajax({
                    data : {'datos':miJson},
                    method:'post',
                    success: function(response){
                      window.alert("¡Éxito!\nLa orden de compra fue creada satisfactoriamente");
                    },
                    error: function(xhr, textStatus, error){
                      console.log(xhr.statusText);
                      console.log(textStatus);
                      console.log(error);
                    }
                  });
                  $(document).ajaxStop(function(){
                    window.location.replace("ordenes_compra.php");
                  });
                } else {
                  window.alert("Por favor complete todos los campos.");
                }
              } else {
                window.alert("Debe ingresar al menos un detalle en la tabla.");
              }
            });

            // NUEVO: Cuando cambia el estado de alguna de las celdas que poseen error, se elimina la clase is-invalid

            $(document).ready(function(){
              $("#pro").change(function(){
                if ($('#pro')[0].classList.contains("is-invalid"))
                    $("#pro")[0].classList.remove("is-invalid");
              });
              $("#mone").change(function(){
                if ($('#mone')[0].classList.contains("is-invalid"))
                    $("#mone")[0].classList.remove("is-invalid");
              });
              $("#obs").change(function(){
                if ($('#obs')[0].classList.contains("is-invalid"))
                    $("#obs")[0].classList.remove("is-invalid");
              });
              $("#forma_pago").change(function(){
                if ($('#forma_pago')[0].classList.contains("is-invalid"))
                    $("#forma_pago")[0].classList.remove("is-invalid");
              });
              $("#forma_envio").change(function(){
                if ($('#forma_envio')[0].classList.contains("is-invalid"))
                    $("#forma_envio")[0].classList.remove("is-invalid");
              });
              $("#fecha_emi").change(function(){
                if ($('#fecha_emi')[0].classList.contains("is-invalid"))
                    $("#fecha_emi")[0].classList.remove("is-invalid");
              });
              $("#fecha_val").change(function(){
                if ($('#fecha_val')[0].classList.contains("is-invalid"))
                    $("#fecha_val")[0].classList.remove("is-invalid");
              });
            });

            /*NUEVO: Controla que los campos no estén vacios, si es asi, agrega la clase is-invalid y fija camposCompletos en false, lo que corta el proceso de armado del JSON.*/

            function controlCampos(){
              camposCompletos = true;

              var x1 = document.getElementById("invalid_pro");
              var x7 = document.getElementById("invalid_mone");
              var x2 = document.getElementById("invalid_obs");
              var x3 = document.getElementById("invalid_forma_pago");
              var x4 = document.getElementById("invalid_forma_envio");
              var x5 = document.getElementById("invalid_fecha_emi");
              var x6 = document.getElementById("invalid_fecha_val");

              if(!$("#pro")[0].checkValidity()){
                $("#pro")[0].classList.add("is-invalid");
                x1.style.display = "block";
                camposCompletos = false;
              } else {
                x1.style.display = "none";
              }

              if(!$("#mone")[0].checkValidity()){
                $("#mone")[0].classList.add("is-invalid");
                x7.style.display = "block";
                camposCompletos = false;
              } else {
                x7.style.display = "none";
              }

              if(!$("#obs")[0].checkValidity()){
                $("#obs")[0].classList.add("is-invalid");
                x2.style.display = "block";
                camposCompletos = false;
              } else {
                x2.style.display = "none";
              }

              if(!$("#forma_pago")[0].checkValidity()){
                $("#forma_pago")[0].classList.add("is-invalid");
                x3.style.display = "block";
                camposCompletos = false;
              } else {
                x3.style.display = "none";
              }

              if(!$("#forma_envio")[0].checkValidity()){
                $("#forma_envio")[0].classList.add("is-invalid");
                x4.style.display = "block";
                camposCompletos = false;
              } else {
                x4.style.display = "none";
              }

              if($("#fecha_emi").val() == ""){
                $("#fecha_emi")[0].classList.add("is-invalid");
                x5.style.display = "block";
                camposCompletos = false;
              } else {
                x5.style.display = "none";
              }

              if($("#fecha_val").val() == ""){
                $("#fecha_val")[0].classList.add("is-invalid");
                x6.style.display = "block";
                camposCompletos = false;
              } else {
                x6.style.display = "none";
              }

              return camposCompletos
            }

          // Append table with add row form on add new button click
            $(".add-new").click(function(){
              $(this).attr("disabled", "disabled");
              document.getElementById("add_orden").disabled = true;
              var index = $("table tbody tr:last-child").index();
              var row = '<tr>' +
                  '<td><input id="item" type="text" name="item" class="form-control" readonly="readonly" value="asd"></td>' + //0
                  '<td><input id="descripcion" type="text" name="descripcion" class="form-control" value=""></td>' + //1
                  '<td><input id="cantidad" type="text" name="cantidad" class="form-control" value="1" onchange="javascript:calculo()"></td>' + //2
                  '<td><input id="precio_unit" type="text" name="precio_unit" class="form-control" value="0" onchange="javascript:calculo()"></td>' + //3
                  '<td><input id="bonif" type="text" name="bonif" class="form-control" value="0" onchange="javascript:bonif_func()"></td>' + //4
                  '<td><input id="subtotal" type="text" name="subtotal" class="form-control" readonly="readonly" value="0.0"></td>' + //5
                  '<td><input id="porc_iva" type="text" name="porc_iva" class="form-control" value="21" onchange="javascript:calculo()"></td>' + //6
                  '<td><input id="subtotal_iva" type="text" name="subtotal_iva" class="form-control" readonly="readonly" value="0.0"></td>' + //7
                  '<td><a class="add" title="Add" data-toggle="tooltip"><i class="material-icons">&#xE03B;</i></a><a class="edit" title="Edit" data-toggle="tooltip"><i class="material-icons">&#xE254;</i></a><a class="delete" title="Delete" data-toggle="tooltip"><i class="material-icons">&#xE872;</i></a></td>' + '</tr>';
              $("table").append(row);     
              $("table tbody tr").eq(index + 1).find(".add, .edit").toggle();
              $('[data-toggle="tooltip"]').tooltip();

              var tbl  = document.getElementById('tabla_detalles');
              var rows = tbl.getElementsByTagName('tr');

              for (var row=1; row<$('#tabla_detalles tr').length-1;row++) {
                var cels = rows[row].getElementsByTagName('td');
                cels[8].style.display = 'none';
              }
             
              document.getElementById('item').value = document.getElementById('tabla_detalles').getElementsByTagName("tr").length-1;
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

              var input2 = $(this).parents("tr").find('select[name="item"]');
              var sel2 = document.getElementById('item');
              var input3 = $(this).parents("tr").find('select[name="descripcion"]');
              var sel3 = document.getElementById('descripcion');
              var input4 = $(this).parents("tr").find('select[name="cantidad"]');
              var sel4 = document.getElementById('cantidad');
              var input5 = $(this).parents("tr").find('select[name="precio_unit"]');
              var sel5 = document.getElementById('precio_unit');
              var input6 = $(this).parents("tr").find('select[name="bonif"]');
              var sel6 = document.getElementById('bonif');
              var input7 = $(this).parents("tr").find('select[name="subtotal"]');
              var sel7 = document.getElementById('subtotal');
              var input8 = $(this).parents("tr").find('select[name="porc_iva"]');
              var sel8 = document.getElementById('porc_iva');
              var input8 = $(this).parents("tr").find('select[name="subtotal_iva"]');
              var sel8 = document.getElementById('subtotal_iva');

              $(this).parents("tr").find(".error").first().focus();
              if(!empty){

                input.each(function(){
                  $(this).parent("td").html($(this).val());
                });  

                $(this).parents("tr").find(".add, .edit").toggle();
                $(".add-new").removeAttr("disabled");
                document.getElementById("add_orden").disabled = false;
                calcular_totales();
              }

              var tbl  = document.getElementById('tabla_detalles');
              var rows = tbl.getElementsByTagName('tr');

              for (var row=1; row<$('#tabla_detalles tr').length;row++) {
                var cels = rows[row].getElementsByTagName('td');
                cels[8].style.display = '';
              }
            });

          // Edit row on edit button click
            $(document).on("click", ".edit", function(){
              var cont = 0;
              document.getElementById("add_orden").disabled = true;
              $(this).parents("tr").find("td:not(:last-child)").each(function(){
                switch (cont) {
                  case 0:
                    $(this).html('<input id="item" type="text" name="item" class="form-control" value="' + $(this).text() + '" readonly="readonly">');
                    break;
                  case 1:
                    $(this).html('<input id="descripcion" type="text" name="descripcion" class="form-control" value="' + $(this).text() + '">');
                    break;
                  case 2:
                    $(this).html('<input id="cantidad" type="text" name="cantidad" class="form-control" onchange="javascript:calculo()" value="' + $(this).text() + '">');
                    break;
                  case 3:
                    $(this).html('<input id="precio_unit" type="text" name="precio_unit" class="form-control" value="' + $(this).text() + '" onchange="javascript:calculo()">');
                    break;
                  case 4:
                    $(this).html('<input id="bonif" type="text" name="bonif" class="form-control" value="' + $(this).text() + '" onchange="javascript:bonif_func()">');
                    break;
                  case 5:
                    $(this).html('<input id="subtotal" type="text" name="subtotal" class="form-control" readonly="readonly" value="' + $(this).text() + '">');
                    break;
                  case 6:
                    $(this).html('<input id="porc_iva" type="text" name="porc_iva" class="form-control" value="' + $(this).text() + '" onchange="javascript:calculo()">');
                    break;
                  case 7:
                    $(this).html('<input id="subtotal_iva" type="text" name="subtotal_iva" class="form-control" readonly="readonly" value="' + $(this).text() + '">');
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

            });

          // Delete row on delete button click
            $(document).on("click", ".delete", function(){
              $(this).parents("tr").remove();
              $(".add-new").removeAttr("disabled");
              document.getElementById("add_orden").disabled = false;
              calcular_totales();

              var tbl  = document.getElementById('tabla_detalles');
              var rows = tbl.getElementsByTagName('tr');

              for (var row=1; row<$('#tabla_detalles tr').length;row++) {
                var cels = rows[row].getElementsByTagName('td');
                cels[0].innerHTML = row;
              }

              for (var row=1; row<$('#tabla_detalles tr').length;row++) {
                var cels = rows[row].getElementsByTagName('td');
                cels[8].style.display = '';
              }

              if ($('#tabla_detalles tr').length < 2) {
                document.getElementById("add_orden").disabled = true;
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
          });;
        </script>

        <div class="table-wrapper">
          <div class="table-title">
            <div class="col">
              <button type="button" class="btn btn-info add-new"> Nuevo detalle</button>
              <h2><b>Orden de compra - </b> Detalles</h2>
            </div>
          </div>
          <table id="tabla_detalles" name="tabla_detalles" class="table table-striped table-bordered">
            <tr>
              <th style="width: 7%;" class="text-center" name="col1" id="col1">Item</th>
              <th style="width: 21%;" class="text-center" name="col2" id="col2">Descripción</th>
              <th style="width: 8%;" class="text-center" name="col3" id="col3">Cantidad</th>
              <th style="width: 12%;" class="text-center" name="col4" id="col4">Precio unitario</th>
              <th style="width: 8%;" class="text-center" name="col5" id="col5">Bonif. (%)</th>
              <th style="width: 11%;" class="text-center" name="col6" id="col6">Subtotal</th>
              <th style="width: 8%;" class="text-center" name="col7" id="col7">Alícuota IVA (%)</th>
              <th style="width: 13%;" class="text-center" name="col8" id="col8">Subtotal c/IVA</th>
              <th style="width: 9%;" class="text-center"></th>
            </tr>
          </table>
          <hr>
          <div style="margin-left:550px">
            <div class="row" style="background-color:#DADADA">
              <div class="col-md-5" align="right">
                <label bgcolor="#DADADA" scope="row">Importe Neto Gravado</label>
              </div>
              <div class="col-md-6" align="right">
                <label bgcolor="#DADADA" name="importe_neto_grav" id="importe_neto_grav">$ 0</label>
              </div>
            </div>
            <div class="row" style="background-color:#DADADA">
              <div class="col-md-5" align="right">
                <label bgcolor="#DADADA" scope="row">IVA</label>
              </div>
              <div class="col-md-6" align="right">
                <label bgcolor="#DADADA" name="total_iva" id="total_iva">$ 0</label>
              </div>
            </div>
            <div class="row" style="background-color:#DADADA">
              <div class="col-md-5" align="right">
                <label bgcolor="#DADADA" scope="row">TOTAL PRESUPUESTO</label>
              </div>
              <div class="col-md-6" align="right">
                <label bgcolor="#DADADA" name="total" id="total">$ 0</label>
              </div>
            </div>
          </div>
          <div class="panel-body">
            <div class="form-group text-right">
              <a class="btn btn-primary" href="ordenes_compra.php" role=button>Volver a órdenes de compra</a>
              <button type="button" id="add_orden" class="btn btn-danger" disabled>Crear orden de compra</button>
              <div id='response'> </div>
            </div>
          </div>
        </div>

        <script>
          function calculo(){
            var cant = document.getElementById("cantidad").value;
            cant = cant.replace(",", ".");
            cant = parseInt(cant);
            cantidad.value = cant;

            var precio = document.getElementById("precio_unit").value;
            precio = precio.replace(",", ".");
            precio = parseFloat(precio).toFixed(2);
            precio_unit.value = precio.replace(".", ",");

            var iva = document.getElementById("porc_iva").value;
            iva = iva.replace(",", ".");
            iva = parseFloat(iva).toFixed(2);
            porc_iva.value = iva.replace(".", ",");

            if (cant == 'NaN' || precio == 'NaN' || iva == 'NaN') {
              subtotal.value = '0,0';
              subtotal_iva.value = '0,0';
            } else {
              var bonifica = document.getElementById("bonif").value.replace(",", ".");

              var subtot = (parseFloat(cant) * precio * (1-parseFloat(bonifica/100))).toFixed(2);
              subtotal.value = subtot.replace(".", ",");
              subtotal_iva.value = (((parseFloat(iva) + 100) / 100) * subtot).toFixed(2).replace(".", ",");
            }
          };

          function bonif_func(){
            var bonificado = document.getElementById("bonif").value;
            bonificado = bonificado.replace(",", ".");
            bonificado = parseFloat(bonificado).toFixed(2);
            bonif.value = bonificado.replace(".", ",");

            bonificado = document.getElementById("bonif").value;
            if (bonificado == '' || bonificado == 'NULL' || bonificado == '0') {
              bonif.value = '0,00';
            } 
            calculo();
          };

          function calcular_totales(){
            var importe_neto = 0;
            var iva = 0;
            var total = 0;
            $('#tabla_detalles tr').each(function(index,element){
              if(index>0){    
                var fila = $(element).find('td');
                importe_neto += parseFloat(fila.eq(5).html().replace(",", "."));
                iva += parseFloat((fila.eq(6).html().replace(",", ".") / 100) * fila.eq(5).html().replace(",", "."));
                total += parseFloat(fila.eq(7).html().replace(",", "."));
              } 
            });

            document.getElementById('importe_neto_grav').innerHTML = "$ "+importe_neto.toFixed(2).replace(".", ",");
            document.getElementById('total_iva').innerHTML = "$ "+iva.toFixed(2).replace(".", ",");
            document.getElementById('total').innerHTML = "$ "+total.toFixed(2).replace(".", ",");
          }

        </script>
  </div>

<?php include_once('layouts/footer.php'); ?>