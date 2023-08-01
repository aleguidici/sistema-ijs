<?php
  $page_title = 'Medición';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
  $all_instrumentos = find_all('inv_instrumentos');
  $clienteOK = find_by_id('cliente',$_GET['idCli']);
  $provinciaOK = find_by_id_prov('provincia',$clienteOK['provincia']);
  $all_personal = find_all('personal');
  $all_matriculados = find_all_matriculados('personal_matriculas');
  $mydata = Array();
  $tabla = "";
  $query_parts = Array();
?>

<?php
  if(isset($_POST['datos'])){
    $datos_form = json_decode($_POST['datos'], true);

    $num_cliente = $db->escape($datos_form['cliente']);
    $num_ins = $db->escape($datos_form['ins']);
    $fecha_ex = $db->escape($datos_form['fechMedicion']);
    $fecha_ex = explode ("-", $fecha_ex); 
    $fecha = $fecha_ex[2]."-".$fecha_ex[1]."-".$fecha_ex[0];
    $hora_ini = $db->escape($datos_form['horaInicio']);
    $hora_fin = $db->escape($datos_form['horaFin']);
    $id_tecnico = $db->escape($datos_form['tecnico']);
    $id_profesional = $db->escape($datos_form['profesional']);
    $conclu = $db->escape($datos_form['conclusiones']);
    $recom = $db->escape($datos_form['recomendaciones']);
    
    if(empty($errors)){
      if(empty($id_tecnico))
        $sql  = "INSERT INTO datos_medicion ( `nro_instrumento`, `num_suc`, `fecha_medicion`,`hora_inicio`,`hora_finalizado`,`id_profesional`, `conclusiones`, `recomendaciones`) VALUES ( '{$num_ins}','{$num_cliente}','{$fecha}','{$hora_ini}','{$hora_fin}','{$id_profesional}', '{$conclu}', '{$recom}')";
      else 
        $sql  = "INSERT INTO datos_medicion ( `nro_instrumento`, `num_suc`, `fecha_medicion`,`hora_inicio`,`hora_finalizado`,`id_tecnico`,`id_profesional`, `conclusiones`, `recomendaciones`) VALUES ( '{$num_ins}','{$num_cliente}','{$fecha}','{$hora_ini}','{$hora_fin}','{$id_tecnico}','{$id_profesional}', '{$conclu}', '{$recom}')";   
      
      if($db->query($sql)){
        
        $ultima_medicion = find_last_medicion('datos_medicion');

        //RECORRES DETALLES
        $query = "INSERT INTO detalles_medicion (`id_medicion`, `numero_toma`, `sector`,`descrip_terreno`,`uso_puesta_tierra`,`esquema_conexion`, `valor_medicion`, `cumple`, `circuito_continuo`, `conducir_corriente`, `proteccion_contactos`) VALUES ";

        foreach($datos_form['datos'] as $unDetalle){
          $query_parts[] = " ('{$ultima_medicion['id_medicion']}', '{$unDetalle["num"]}', '{$unDetalle["sector"]}', '{$unDetalle["tipoTerreno"]}', '{$unDetalle["puestaTierra"]}', '{$unDetalle["esqConexion"]}', '{$unDetalle["valorObt"]}', '{$unDetalle["cumple"]}', '{$unDetalle["circCont"]}', '{$unDetalle["condCorr"]}', '{$unDetalle["protCont"]}') ";
        }
        $query .= implode(',', $query_parts);

        if($db->query($query)){
          $session->msg("s", "Medición agregada exitosamente.");
          redirect('conAc_datosMedicion.php',false);
        } else {
          $session->msg("d", "Lo siento, el registro falló");
          redirect('conAc_datosMedicion.php',false);
        }
      } else {
        $session->msg("d", "Lo siento, el registro falló");
        redirect('conAc_datosMedicion.php',false);
      }
    } else {
      $session->msg("d", $errors);
      redirect('conAc_datosMedicion.php',false);
    }
  }
?>

<?php include_once('layouts/header.php'); ?>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round|Open+Sans">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="http://weareoutman.github.io/clockpicker/dist/bootstrap-clockpicker.min.css">

    <!-- <link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet"> -->
<!--     <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" /> -->
     

    <script src="http://weareoutman.github.io/clockpicker/dist/bootstrap-clockpicker.min.js"></script>

    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script> -->
    <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script> -->
<!--     <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script> -->

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
      
    <div class="col-md-12">
      <?php echo display_msg($msg); ?>
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Nueva medición</span>
          </strong>
        </div>

        <div class="panel-body">
          <div class="row">
            <div class="col-md-10">
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
                <div class="col-md-4">
                  <?php echo $clienteOK['razon_social'];?>
                </div>
                <div class="col-md-2">
                  &emsp;&emsp;
                  <b>Localidad: </b>
                </div>
                <div class="col-md-4">
                  <?php echo $clienteOK['localidad'];?>
                </div>
              </div>

              <div class="row">
                <div class="col-md-2">
                  &emsp;&emsp;
                  <b>Sucursal: </b>
                </div>
                <div class="col-md-4">
                  <?php if (!empty($clienteOK['num_suc'])) 
                    echo $clienteOK['num_suc'];
                  else
                    echo "-"; ?>
                </div>
                <div class="col-md-2">
                  &emsp;&emsp;
                  <b>Código postal: </b>
                </div>
                <div class="col-md-4">
                  <?php echo $clienteOK['cp'];?>
                </div>
              </div>

              <div class="row">
                <div class="col-md-2">
                  &emsp;&emsp;
                  <b>IVA: </b>
                </div>
                <div class="col-md-4">
                  <?php echo $clienteOK['iva'];?>
                </div>
                <div class="col-md-2">
                  &emsp;&emsp;
                  <b>Provincia: </b>
                </div>
                <div class="col-md-4">
                  <?php echo $provinciaOK['nombre'];?>
                </div>
              </div>

              <div class="row">
                <div class="col-md-2">
                  &emsp;&emsp;
                  <b>CUIT: </b>
                </div>
                <div class="col-md-4">
                  <?php echo $clienteOK['cuit'];?>
                </div>
                <div class="col-md-2">
                  &emsp;&emsp;
                  <b>Dirección: </b>
                </div>
                <div class="col-md-4">
                  <?php echo '"'.$clienteOK['direccion'].'"';?>
                </div>
              </div>

              <div class="row">
                <div class="col-md-2">
                  &emsp;&emsp;
                  <b>Teléfono: </b>
                </div>
                <div class="col-md-4">
                  <?php if (!empty($clienteOK['tel'])) 
                    echo $clienteOK['tel'];
                  else
                    echo "-"; ?>
                </div>
              </div>
            </div>
          </div>

  <!-- "seleccione un instrumento" -->
          <div class="rowInstru"></div>
          <hr>
          <div class="row">
            <div class="col-md-12">
              <label class="control-label">Instrumento utilizado en la medición:</label>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <select class="selectpicker" data-show-subtext="true" data-live-search="true" name="inst" id="inst" required="required" data-width="100%">
                <option value="">Seleccione un Instrumento</option>
                <?php  foreach ($all_instrumentos as $instrum): ?>
                  <option value="<?php echo (int) $instrum['nro_instrumento']?>">
                  <?php echo 'Instrumento Nº ', $instrum['nro_instrumento'] , ' - ',  $instrum['marca'], ' -  Modelo ',  $instrum['modelo'] , ' -  Nº Serie ',  $instrum['num_serie']?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <script>
              $('#inst').on('show.bs.select', function() {
                $('html,body').animate({
                    scrollTop: $(".rowInstru").offset().top},
                    'fast');
              });
            </script>
          </div>
          <div class="row">
            <div class="col-md-12">
              <span class="label label-danger" id='invalid_ins' style="display: none;">Debe seleccionar un instrumento</span>
            </div>
          </div>

  <!-- "seleccione un tecnico / profesional" -->

          <div class="rowTec"></div>
          <hr>
          <div class="row">
            <div class="col-md-6">
              <label for="op" class="control-label">Técnico que realizó la medición (opcional):</label>
            </div>
            <div class="col-md-6">
              <label for="op2" class="control-label">Profesional habilitado responsable:</label>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <select class="selectpicker" data-show-subtext="true" data-live-search="true" name="op" id="op" data-width="100%">
                <option value="">Seleccione un Técnico</option>
                <?php  foreach ($all_personal as $oper): 
                  if ($oper['responsable_servicios'] == '1' && $oper['baja'] == '0') {?>
                    <option value="<?php echo (int) $oper['id']?>">
                    <?php echo $oper['apellido'] , ' , ',  $oper['nombre'], ' -  DNI: ',  $oper['dni'];
                    if ($oper['tercero'] == '1')
                      echo " (Personal tercerizado)";
                  }?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <script>
              $('#op').on('show.bs.select', function() {
                $('html,body').animate({
                    scrollTop: $(".rowTec").offset().top},
                    'fast');
              });
            </script>

            <div class="col-md-6">
              <select class="selectpicker" data-show-subtext="true" data-live-search="true" name="op2" id="op2" required="required" data-width="100%">
                <option value="">Seleccione un Profesional habilitado</option>
                <?php foreach ($all_matriculados as $unaMat): 
                  $oper2 = find_by_id('personal', $unaMat['id_personal']);
                  if ($oper2['responsable_servicios'] == '1' && $oper2['baja'] == '0' && $provinciaOK['id_provincia'] == $unaMat['id_provincia']) {?>
                    <option value="<?php echo (int) $oper2['id']?>">
                    <?php 
                      echo $oper2['apellido'] , ' , ',  $oper2['nombre'], ' -  DNI: ',  $oper2['dni'], ' - Matrícula profesional: ', $unaMat['num_matricula'], ' (', $provinciaOK['nombre'],')';  
                  }?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <script>
              $('#op2').on('show.bs.select', function() {
                $('html,body').animate({
                    scrollTop: $(".rowTec").offset().top},
                    'fast');
              });
            </script>
          </div>
          <div class="row">
            <div class="col-md-6"></div>
            <div class="col-md-6">
              <span class="label label-danger" id='invalid_prof' style="display: none;">Debe seleccionar un responsable</span>
            </div>
          </div>

  <!-- "Conclusiones / Recomendaciones" -->     
          <hr>
          <div class="row">
            <div class="col-md-6">
              <label class="control-label">Conclusiones:</label>
            </div>
            <div class="col-md-6">
              <label class="control-label">Recomendaciones:</label>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <textarea type="text" class="form-control" placeholder="Conclusiones" id="conc" rows="4" required>Temperatura: N/D; Humedad: N/D; Los valores se encuentran comprendidos dentro del régimen legal.</textarea>
            </div>
            <div class="col-md-6">
              <textarea type="text" class="form-control" placeholder="Recomendaciones" id="recom" rows="4" required>Verificación visual de la puesta a tierra y cables de puesta a tierra de forma periódica y medición de valores de resistencia de puesta a tierra una vez por año.</textarea>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <span class="label label-danger" id='invalid_con' style="display: none;">El espacio para conclusiones no puede quedar vacío</span>
            </div>
            <div class="col-md-6">
              <span class="label label-danger" id='invalid_rec' style="display: none;">El espacio para recomendaciones no puede quedar vacío</span>
            </div>
          </div>

  <!-- "Fecha y horas" -->
          <hr>
          <div class="row">
            <div class="col-md-3">
              <label for="fecha" class="control-label">Fecha de medición:</label>
            </div>
            <div class="col-md-1"></div>
            <div class="col-md-2">
              <label for="hora_inicio" class="control-label">Hora de inicio:</label>
            </div>
            <div class="col-md-2">
              <label for="Hora_finalizacion2" class="control-label">Hora de fin</label>
            </div>
          </div>
          <div class="row">
            <div class="col-md-3">
              <input type="text" class="datepicker form-control" name="fecha" id="fecha" readonly required>
            </div>
            <div class="col-md-1"></div>
            <div class="col-md-2">
              <div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true" required>
                <input type="text" class="form-control" name="hora_ini" id="hora_ini" readonly>
                <span class="input-group-addon">
                  <span class="glyphicon glyphicon-time"></span>
                </span>
              </div>
              <script type="text/javascript">
                $('.clockpicker').clockpicker();
              </script>
            </div>
            <div class="col-md-2">
              <div class="input-group clockpicker" data-placement="left" data-align="top" data-autoclose="true" required>
                <input type="text" class="form-control" name="hora_fin" id="hora_fin" readonly>
                <span class="input-group-addon">
                  <span class="glyphicon glyphicon-time"></span>
                </span>
              </div>
              <script type="text/javascript">
                $('.clockpicker').clockpicker();
              </script>
            </div>
          </div>
          <div class="row">
            <div class="col-md-3">
              <span class="label label-danger" id='invalid_fecha' style="display: none;">Seleccione una fecha</span>
            </div>
            <div class="col-md-1"></div>
            <div class="col-md-2">
              <span class="label label-danger" id='invalid_horai' style="display: none;">Seleccione una hora de inicio</span>
            </div>
            <div class="col-md-2">
              <span class="label label-danger" id='invalid_horaf' style="display: none;">Seleccione una hora de fin</span>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4">
              <span class="label label-danger" id='invalid_horaf2' style="display: none;">La hora de fin debe ser superior a la hora de inicio</span>
            </div>   
          </div>
          <script>
            $('#fecha').datepicker( {
                format: 'dd-mm-yyyy',
                autoclose: true
              });
          </script>
        </div>
      </div>
    </div>
    
<!-- Detalle Medición -->
    <div class="col-md-12">
      <script type="text/javascript">
        $(document).ready(function(){
          $('[data-toggle="tooltip"]').tooltip();
          var actions = $("table td:last-child").html();
          
          $('#add_medicion').click(function(){
            
            var arrayCarac = {};
            var miTabla = [];
            if ($('#tabla_detalles tr').length>1){ // Controla que haya al menos una fila que no sea el encabezado de la tabla
              $('#tabla_detalles tr').each(function(index,element){
                if(index>0){    
                  var fila = $(element).find('td');
                  miTabla.push({
                    "num" : fila.eq(0).html(),
                    "sector" : fila.eq(1).html(),
                    "tipoTerreno" : fila.eq(2).html(),
                    "puestaTierra" : fila.eq(3).html(),
                    "esqConexion" : fila.eq(4).html(),
                    "valorObt" : fila.eq(5).html(),
                    "cumple" : fila.eq(6).html(),
                    "circCont" : fila.eq(7).html(),
                    "condCorr" : fila.eq(8).html(),
                    "protCont" : fila.eq(9).html()
                  });
                } 
              });
              if (controlCampos()){
                arrayCarac.cliente= <?php echo $clienteOK['id']?>;
                arrayCarac.ins=$("#inst").val();
                arrayCarac.tecnico=$("#op").val();
                arrayCarac.profesional=$("#op2").val();
                arrayCarac.fechMedicion=$('#fecha').val();
                arrayCarac.horaInicio=$('#hora_ini').val();
                arrayCarac.horaFin=$('#hora_fin').val();
                arrayCarac.conclusiones=$("#conc").val();
                arrayCarac.recomendaciones=$("#recom").val();
                arrayCarac.datos=miTabla;
                var miJson = JSON.stringify(arrayCarac);
                $.ajax({
                  data : {'datos':miJson},
                  method:'post',
                  success: function(response){
                    window.alert("¡Éxito!\nLa medición fue creada satisfactoriamente");
                  },
                  error: function(xhr, textStatus, error){
                    window.alert("error");
                    console.log(xhr.statusText);
                    console.log(textStatus);
                    console.log(error);
                  }
                });
                $(document).ajaxStop(function(){
                  window.location.replace("conAc.php");
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
            $("#inst").change(function(){
              if ($('#inst')[0].classList.contains("is-invalid"))
                  $("#inst")[0].classList.remove("is-invalid");
            });
            $("#op2").change(function(){
              if ($('#op2')[0].classList.contains("is-invalid"))
                  $("#op2")[0].classList.remove("is-invalid");
            });
            $("#inst").change(function(){
              if ($('#inst')[0].classList.contains("is-invalid"))
                  $("#inst")[0].classList.remove("is-invalid");
            });
            $("#conc").change(function(){
              if ($('#conc')[0].classList.contains("is-invalid"))
                  $("#conc")[0].classList.remove("is-invalid");
            });
            $("#recom").change(function(){
              if ($('#recom')[0].classList.contains("is-invalid"))
                  $("#recom")[0].classList.remove("is-invalid");
            });
            $("#fecha").change(function(){
              if ($('#fecha')[0].classList.contains("is-invalid"))
                  $("#fecha")[0].classList.remove("is-invalid");
            });
            $("#hora_ini").change(function(){
              if ($('#hora_ini')[0].classList.contains("is-invalid"))
                  $("#hora_ini")[0].classList.remove("is-invalid");
            });
            $("#hora_fin").change(function(){
              if ($('#hora_fin')[0].classList.contains("is-invalid"))
                  $("#hora_fin")[0].classList.remove("is-invalid");
            });
          });

          /*NUEVO: Controla que los campos no estén vacios, si es asi, agrega la clase is-invalid y fija camposCompletos en false, lo que 
          corta el proceso de armado del JSON.*/

          function controlCampos(){
            camposCompletos = true;

            var x2 = document.getElementById("invalid_ins");
            var x4 = document.getElementById("invalid_prof");
            var x5 = document.getElementById("invalid_con");
            var x6 = document.getElementById("invalid_rec");
            var x7 = document.getElementById("invalid_fecha");
            var x8 = document.getElementById("invalid_horai");
            var x9 = document.getElementById("invalid_horaf");
            var x10 = document.getElementById("invalid_horaf2");

            if(!$("#inst")[0].checkValidity()){
              $("#inst")[0].classList.add("is-invalid");
              x2.style.display = "block";
              camposCompletos = false;
            } else {
              x2.style.display = "none";
            }

            if(!$("#op2")[0].checkValidity()){
              $("#op2")[0].classList.add("is-invalid");
              x4.style.display = "block";
              camposCompletos = false;
            } else {
              x4.style.display = "none";
            }

            if(!$("#conc")[0].checkValidity()){
              $("#conc")[0].classList.add("is-invalid");
              x5.style.display = "block";
              camposCompletos = false;
            } else {
              x5.style.display = "none";
            }

            if(!$("#recom")[0].checkValidity()){
              $("#recom")[0].classList.add("is-invalid");
              x6.style.display = "block";
              camposCompletos = false;
            } else {
              x6.style.display = "none";
            }

            if($("#fecha").val() == ""){
              $("#fecha")[0].classList.add("is-invalid");
              x7.style.display = "block";
              camposCompletos = false;
            } else {
              x7.style.display = "none";
            }

            if($("#hora_ini").val() == ""){
              $("#hora_ini")[0].classList.add("is-invalid");
              x8.style.display = "block";
              camposCompletos = false;
            } else {
              x8.style.display = "none";
            }

            if($("#hora_fin").val() == ""){
              $("#hora_fin")[0].classList.add("is-invalid");
              x9.style.display = "block";
              camposCompletos = false;
            } else {
              x9.style.display = "none";

              // var hora_i = $("#hora_ini").val();
              var hora_i = document.getElementById("hora_ini").value.split(":");
              // var hora f = $("#hora_fin").val();
              var hora_f = document.getElementById("hora_fin").value.split(":");

              if(Number(hora_f[0]) < Number(hora_i[0])){
                  $("#hora_fin")[0].classList.add("is-invalid");
                  x10.style.display = "block";
                  camposCompletos = false;
              } else {
                if(Number(hora_f[0]) == Number(hora_i[0])) {
                  if(Number(hora_f[1]) <= Number(hora_i[1])){
                    $("#hora_fin")[0].classList.add("is-invalid");
                    x10.style.display = "block";
                    camposCompletos = false;
                  } else {
                    x10.style.display = "none";                    
                  } 
                } else {
                  x10.style.display = "none";                    
                }
              }
            }

            return camposCompletos
          }

        // Append table with add row form on add new button click
          $(".add-new").click(function(){
            $(this).attr("disabled", "disabled");
            document.getElementById("add_medicion").disabled = true;
            var index = $("table tbody tr:last-child").index();
            var row = '<tr>' +
                '<td><input id="numero_toma" type="text" name="numero_toma" class="form-control" readonly="readonly" value="asd"></td>' + 
                '<td><select class="form-control" id="sector" name="sector" onchange="javascript:valor()">' +
                <?php $allSectores = $db->while_loop($db->query("SELECT * FROM( SELECT * FROM `medicion_sectores` WHERE `importancia` <> 0 ORDER BY 2 DESC) AS `dummy1` UNION SELECT * FROM( SELECT * FROM `medicion_sectores` WHERE `importancia` = 0 ORDER BY 2 ASC) AS `dummy2`"));
                foreach ($allSectores as $unSector) : ?>
                '<option value="<?php echo $unSector['id'];?>"><?php echo $unSector['descripcion'];?></option>' +
                <?php if ($unSector['id'] != 1 && $unSector['importancia'] != 0) { ?>
                  '<option disabled style="font-style:italic">&nbsp;&nbsp;&nbsp;─────────────────────&nbsp;&nbsp;&nbsp;</option>' +
                <?php }
                endforeach; ?>
                '</select></td>' +
                '<td><select class="form-control" id="descripcion_terreno" name="descripcion_terreno">' +
                <?php $allTerrenos = $db->while_loop($db->query("SELECT * FROM `medicion_terrenos` ORDER BY 2 ASC"));
                foreach ($allTerrenos as $unTerreno) : ?>
                '<option value="<?php echo $unTerreno['id'];?>"><?php echo $unTerreno['descripcion'];?></option>' +              
              <?php endforeach; ?>
                '</select></td>' +
                '<td><select class="form-control" id="uso_puesta_tierra" name="uso_puesta_tierra" ><option value="">Cable de Tierra de Tablero Principal</option><option value="">Toma de Tierra del neutro de Transformador</option><option value="">Toma de Tierra de Seguridad de las Masas</option><option value="">Protección de equipos Electrónicos</option><option value="">Informática</option><option value="">Iluminación</option><option value="">Pararrayos</option><option value="">Otro</option></select></td>' +
                '<td><select class="form-control" id="esquema_conexion" name="esquema_conexion"><option value="">TT</option><option value="">TN-S</option><option value="">TN-C</option><option value="">TNC-S</option><option value="">IT</option></select></td>' +
                '<td><input id="valor_obtenido" type="text" name="valor_obtenido" class="form-control" placeholder="" onchange= "javascript:cumple_valor()"></td>' +
                '<td><input id="cumple" type="text" name="cumple" class="form-control" readonly="readonly" value="-"></td>' +
                '<td><select class="form-control" id="circuito_continuo" name="circuito_continuo"><option value="">Si</option><option value="">No</option></select></td>' +
                '<td><select class="form-control" id="conducir_corriente" name="conducir_corriente"><option value="">Si</option><option value="">No</option></select></td>' +
                '<td><select class="form-control" id="proteccion_contactos" name="proteccion_contactos"><option value="">DD</option><option value="">IA</option><option value="">DD / IA</option><option value="">Fus</option></select></td>' +
                '<td><a class="add" title="Add" data-toggle="tooltip"><i class="material-icons">&#xE03B;</i></a><a class="delete" title="Delete" data-toggle="tooltip"><i class="material-icons">&#xE872;</i></a></td>' + '</tr>';
            $("table").append(row);
            $("table tbody tr").eq(index + 1).find(".add, .edit").toggle();
            $('[data-toggle="tooltip"]').tooltip();
           
            var tbl  = document.getElementById('tabla_detalles');
            var rows = tbl.getElementsByTagName('tr');

            for (var row=1; row<$('#tabla_detalles tr').length-1;row++) {
              var cels = rows[row].getElementsByTagName('td');
              cels[10].style.display = 'none';
            }
           
            document.getElementById('numero_toma').value = document.getElementById('tabla_detalles').getElementsByTagName("tr").length-1;
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
                $(this).removeClass("error");
              }
            }); //Control de errores para "nro_toma" y "valor obtenido"

            if(!empty){
              var input_b = $(this).parents("tr").find('input[name="valor_obtenido"]');
              input_b.each(function(){
                if(!isNaN($(this).val()) || ($(this).val() == "-")){
                  $(this).removeClass("error");
                } else {
                  $(this).addClass("error");
                  empty = true;
                }
              });
            }

            var input2 = $(this).parents("tr").find('select[name="sector"]');
            var sel2 = document.getElementById('sector');
            var input3 = $(this).parents("tr").find('select[name="descripcion_terreno"]');
            var sel3 = document.getElementById('descripcion_terreno');
            var input4 = $(this).parents("tr").find('select[name="uso_puesta_tierra"]');
            var sel4 = document.getElementById('uso_puesta_tierra');
            var input5 = $(this).parents("tr").find('select[name="esquema_conexion"]');
            var sel5 = document.getElementById('esquema_conexion');
            var input6 = $(this).parents("tr").find('select[name="circuito_continuo"]');
            var sel6 = document.getElementById('circuito_continuo');
            var input7 = $(this).parents("tr").find('select[name="conducir_corriente"]');
            var sel7 = document.getElementById('conducir_corriente');
            var input8 = $(this).parents("tr").find('select[name="proteccion_contactos"]');
            var sel8 = document.getElementById('proteccion_contactos');

            $(this).parents("tr").find(".error").first().focus();
            if(!empty){

              input.each(function(){
                $(this).parent("td").html($(this).val());
              });  

              input2.parent("td").html(sel2.options[sel2.selectedIndex].text);
              input3.parent("td").html(sel3.options[sel3.selectedIndex].text);
              input4.parent("td").html(sel4.options[sel4.selectedIndex].text);
              input5.parent("td").html(sel5.options[sel5.selectedIndex].text);
              input6.parent("td").html(sel6.options[sel6.selectedIndex].text);
              input7.parent("td").html(sel7.options[sel7.selectedIndex].text);
              input8.parent("td").html(sel8.options[sel8.selectedIndex].text);

              $(this).parents("tr").find(".add, .edit").toggle();
              $(".add-new").removeAttr("disabled");
              document.getElementById("add_medicion").disabled = false;
            }

            var tbl  = document.getElementById('tabla_detalles');
            var rows = tbl.getElementsByTagName('tr');

            for (var row=1; row<$('#tabla_detalles tr').length-1;row++) {
              var cels = rows[row].getElementsByTagName('td');
              cels[10].style.display = '';
            }
          }); 

        
        // Delete row on delete button click
          $(document).on("click", ".delete", function(){
            $(this).parents("tr").remove();
            $(".add-new").removeAttr("disabled");
            document.getElementById("add_medicion").disabled = false;

            var tbl  = document.getElementById('tabla_detalles');
            var rows = tbl.getElementsByTagName('tr');

            for (var row=1; row<$('#tabla_detalles tr').length;row++) {
              var cels = rows[row].getElementsByTagName('td');
              cels[0].innerHTML = row;
            }

            for (var row=1; row<$('#tabla_detalles tr').length;row++) {
              var cels = rows[row].getElementsByTagName('td');
              cels[10].style.display = '';
            }

            if ($('#tabla_detalles tr').length < 2) {
              document.getElementById("add_medicion").disabled = true;
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

        function valor(){
          var sel2 = document.getElementById('sector').value;
          cumple.value = '-';
          
          if (sel2 == "0") {
            document.getElementById("valor_obtenido").disabled = false;
            valor_obtenido.value = '';    
            cumple.value = '-';
          } else {
            document.getElementById("valor_obtenido").disabled = true;
            valor_obtenido.value = '-';
            cumple.value = '-';
          }
        };

        function cumple_valor(){
          var plan = document.getElementById("valor_obtenido").value;
          var plan = plan.replace(",", ".");
          valor_obtenido.value = plan;
          if (plan == '' || plan == 'NULL' || plan == '0') {
            cumple.value = '-';    
          } else if (plan <= 40) {
            cumple.value = 'Si';
          } else {
            cumple.value = 'No';
          }
        };
      </script>

      <div class="table-wrapper">
        <div class="table-title">
          <div class="col">
            <button type="button" class="btn btn-info add-new"> Nuevo detalle</button>
            <h2><b>Medición - </b> Detalles</h2>
          </div>
        </div>
        <table id="tabla_detalles" name="tabla_detalles" class="table table-striped table-bordered">
          <thead class=thead-dark>
            <tr>
              <th style="width: 7%;" class="text-center" name="col1" id="col1">Nº</th>
              <th style="width: 14%;" class="text-center" name="col2" id="col2">Sector</th>
              <th style="width: 14%;" class="text-center" name="col3" id="col3">Tipo terreno</th>
              <th style="width: 14%;" class="text-center" name="col4" id="col4">Puesta a tierra</th>
              <th style="width: 10%;" class="text-center" name="col5" id="col5">Esquema de conex.</th>
              <th style="width: 9%;" class="text-center" name="col6" id="col6">Valor obtenido</th>
              <th style="width: 8%;" class="text-center" name="col7" id="col7">Cumple</th>
              <th style="width: 9%;" class="text-center" name="col8" id="col8">Circuito continuo</th>
              <th style="width: 9%;" class="text-center" name="col9" id="col9">Conduce corriente</th>
              <th style="width: 11%;" class="text-center" name="col10" id="col10">Protección contactos</th>
              <th style="width: 11%;" class="text-center"></th>
            </tr>
          </thead>
        </table>
        <div class="panel-body">
          <div class="form-group text-right">
            <a class="btn btn-primary" href="conAc.php" role=button>Volver a Mediciones</a>
            <button type="button" id="add_medicion" class="btn btn-danger" >Crear medición</button>
            <div id='response'> </div>
          </div>
        </div>
      </div>      
    </div>

<?php include_once('layouts/footer.php'); ?>