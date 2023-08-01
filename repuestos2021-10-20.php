<?php
  $page_title = 'Buscar Repuestos';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
  
  
  $allProveedores = find_all('proveedormaquina');
  $all_maquina_repuesto = find_all('maquina_repuesto');
  $estaMaquina = find_by_id('maquina',$_GET['idMaquina']);
  $esteModelo = find_by_id('maquina_modelo',$estaMaquina['modelo_id']);
  $estaMarca = find_by_id('maquina_marca',$esteModelo['marca_id']);
  $esteTipo = find_by_id('maquina_tipo',$esteModelo['tipo_id']);
  $proveedorDisponible = find_by_id_maquina_marca_prov('maquina_marca_prov',$estaMarca['id']);
  $estaReparacion = find_by_id('reparacion_maquina',$_GET['idReparacion']);
  $esteRepuesto = find_by_id_reparacion('reparacion_repuesto',$estaReparacion['id']);
  //$repuestosDeEstaMaquina = find_all_id_modelo_repuesto('maquina_repuesto',$estaMaquina['modelo_id']);
  $sql_repuestosDeEstaMaquina = $db->query("SELECT * FROM `maquina_repuesto` WHERE ( `id_modelo` = '".$estaMaquina['modelo_id']."' OR `id_modelo` = 40 ) AND `id` NOT IN (SELECT `repuesto_id` FROM `reparacion_repuesto` WHERE `reparacion_id` = '".$estaReparacion['id']."') ORDER BY `maquina_repuesto`.`codigo` ASC");
  $repuestosDeEstaMaquina = $db->while_loop($sql_repuestosDeEstaMaquina);

  $allRepuestosDeEstaReparacion = find_all_reparacion_id_reparacion_repuesto('reparacion_repuesto',$_GET['idReparacion']);
  //$proveedorEnReparacion = find_all_proveedores_disponibles_a_reparacion($estaMarca['id'],$estaReparacion['id'],$esteRepuesto['repuesto_id']);
  //$proveedorDisponible = find_by_id_maquina_marca_prov('maquina_marca_prov',$esteModelo['marca_id']);


  if (isset($_POST['btn_set_nta'])) {
    $estaRazonNoReparacion = $_POST['detalle_nta'];
   
    if ($estaRazonNoReparacion != "") {
      $estaReparacion2 = $_GET['idReparacion'];
      $estaMaquina2 = $_GET['idMaquina'];

      $sql="UPDATE reparacion_maquina SET id_estado = 7 WHERE id = '{$estaReparacion2}'";
      echo $result=$db->query($sql);

      $sql2="UPDATE maquina SET sin_reparacion = 1, razon_noreparacion = '{$estaRazonNoReparacion}' WHERE id = '{$estaMaquina2}'";
      echo $result2=$db->query($sql2);
      $session->msg("s", "Estado de la orden: ".$estaReparacion2.", máquina: [IJS-ME: ".$estaMaquina2."] cambiado a LISTO PARA ENTREGAR, y se dió de baja a la máquina por no reparación: '".$estaRazonNoReparacion."'.");
      header("Location: conAc.php");
    }
  }

  /*if (isset($_POST['btn_set_limpieza'])) {
      $estaReparacion3 = $_GET['idReparacion'];
      $estaMaquina3 = $_GET['idMaquina'];
      $sql="UPDATE reparacion_maquina SET id_estado = 6 WHERE id = '{$estaReparacion3}'";
      echo $result=$db->query($sql);
      $session->msg("s", "Estado de la orden: ".$estaReparacion3.", máquina: [IJS-ME: ".$estaMaquina3."] cambiado a LIMPIEZA.");
      header("Location: conAc.php");
      header("Refresh: 0");
  }*/


?>

<?php include_once('layouts/header.php'); ?>

  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round|Open+Sans">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> 
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css">
  <link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.15.3/dist/bootstrap-table.min.css">
  <link rel="stylesheet" type="text/css" href="libs/alertifyjs/css/alertify.css">
  <link rel="stylesheet" type="text/css" href="libs/alertifyjs/css/themes/default.css">
  <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>
  <script src="libs/alertifyjs/alertify.js"></script>  
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css">
  <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>


  <style type="text/css">
    #verde{
      color:green;
      font-size: 20px;      
    }
    #amarillo{
      color:rgb(255,175,0);
      font-size: 20px;      
    }
    #rojo{
      color:red;
      font-size: 20px;      
    }
  </style>

<script>

  $(document).ready(function() {


    // </. reestablece los datos del modal repuestos
    $('#modalAgregarRepuesto').on('hide.bs.modal', function (event) {
      $(this).find('input,textarea,select').val('');
      $('#repuestoSel').val('');
      $('#repuestoSel').selectpicker('refresh');
      document.getElementById('btn_add-repuesto').disabled = false;
    });
    // </. hidden.bs.modal
    // </. reestablece los datos del modal add-modelo

    // </. reestablece los datos del modal add-repuestos 
    $('#modal_add-repuesto').on('hide.bs.modal', function (event) {
      $(this).find('input,textarea,select').val('');
      $('#repuestoSel').val('');
      $('#repuestoSel').selectpicker('refresh');
      document.getElementById('btn_add-repuesto').disabled = false;
    });

    $('#btn_cerrar-add-repuesto').click(function() {
      document.getElementById("openModalAgregarRepuesto").click(); 
    });

    //Crea un repuesto nuevo para el modelo de maquina
    /*$('#crearElRepuesto').click(function() {
      modeloOK = $('#modelo').val();
      codigoOK = $('#codigo').val();
      parteOK = $('#parte').val();
      descripcionOK=$('#descripcion').val();
      //descripcionOK= descripcionOK.charAt(0).toUpperCase() + descripcionOK.slice(1);
      descripcionOK= descripcionOK.toUpperCase();
      codigoOK = codigoOK.toUpperCase();
      parteOK = parteOK.toUpperCase();
      if (descripcionOK){
        cadena_p = "&modeloOK=" + modeloOK + "&codigoOK=" + codigoOK + "&descripcionOK=" + descripcionOK + "&parteOK=" + parteOK;

        $.ajax({
          type:"POST",
          url:"conAc_agregarMaquinaRepuesto.php",
          data:cadena_p,
          success:function(r) {
            if (r == 1) {
              alertify.success("Repuesto creado.");
              $("#modalCrearRepuesto").modal("hide"); 
              setInterval('location.reload()', 1000);
            } else {
              alertify.error("Error.");
            }
          }
        });
      } else {
          alertify.error("Complete todos los campos.");
      }
    });  
  */


    //Agregar un repuesto a la reparacion activa
    $('#agregarRepuestoSeleccionado').click(function() {
      //var aceptar = confirm("¿Está seguro que desea buscar un proveedor para éste repuesto?");
      //if (aceptar) {
        repuSelAgre = $('#repuestoSel').val();
        //trae datos del data del boton
        var repaSelAgre = $.parseJSON($(this).attr('data-repaSelId'));
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
        var yyyy = today.getFullYear();
        today = dd + '-' + mm + '-' + yyyy;

          if (repaSelAgre) {
            agregar_r = "&idRepuesto=" + repuSelAgre + "&idReparacion=" + repaSelAgre + "&idProveedor=" + 2 + "&precio=" + 0 + "&cantidad=" + 0 + "&date=" + today + "&elegido=" + 2 + "&idEstado=" + 0 + "&b=" + 1 + "&iva=" + 0 ;

              $.ajax({
                type:"POST",
                url:"proveedorAbmSeleccionado.php",
                data:agregar_r,
                success:function(r) {
                  if (r == 1) {
                    alertify.success("Repuesto Agregado.");
                    setInterval('location.reload()', 750);
                    //$("#modalAgregarRepuesto").modal("hide");                    
                    //location.reload();                    
                  } else {
                    alertify.error("El repuesto ya está agregado a la reparación.");
                  }
                }
              });
          } else {
            alertify.error("Complete todos los campos.");
          }
        //}else{
        //}
    });
//-----------

//AGREGAR REPUESTO CON STOCK IJS
    $('#btn_agregar_ijs').click(function() {
        repuSelAgre = $('#repuestoSel').val();
        var repaSelAgre = $.parseJSON($(this).attr('data-repaSelId'));
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
        var yyyy = today.getFullYear();
        today = dd + '-' + mm + '-' + yyyy;

          if (repaSelAgre) {
            agregar_r = "&idRepuesto=" + repuSelAgre + "&idReparacion=" + repaSelAgre + "&idProveedor=" + 1 + "&precio=" + 0 + "&cantidad=" + 1 + "&date=" + today + "&elegido=" + 2 + "&idEstado=" + 0 + "&b=" + 1 + "&iva=" + 0 ;

              $.ajax({
                type:"POST",
                url:"proveedorAbmSeleccionado.php",
                data:agregar_r,
                success:function(r) {
                  if (r == 1) {
                    alertify.success("Repuesto Agregado.");
                    setInterval('location.reload()', 750);                 
                  } else {
                    alertify.error("El repuesto ya está agregado a la reparación.");
                  }
                }
              });
          } else {
            alertify.error("Complete todos los campos.");
          }
    });

//-----

//Boton aceptar del modal agregar proveedor, hace el set en la bd con el proveedor en ese repuesto.
    $('#btn_aceptar_proveedor_seleccionado').click(function() {     
      var idProveedorSeleccionado = document.getElementById("proveedorSel").value;
      var idRepuestoARepaRepue = document.getElementById("id_repuesto_input").value;
      var idReparacionARepaRepue = document.getElementById("id_reparacion_input").value;
      var iva = document.getElementById("iva").value;
      var cantidad = 1;
      var precio = document.getElementById("precio").value;
      var dateGet = $("#fecha").datepicker({ dateFormat: 'dd,mm,yyyy' }).val();
      var today = new Date();
      var dd = String(today.getDate()).padStart(2, '0');
      var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
      var yyyy = today.getFullYear();
      today = dd + '-' + mm + '-' + yyyy;      
      if (dateGet != "") {
        date = dateGet;        
      } else {
        date = today;        
      }
      //var aceptar = confirm("¿Está seguro que desea agregar éste proveedor para éste repuesto?");
      if (idProveedorSeleccionado && precio) {
        aceptar_p = "&idReparacion=" + idReparacionARepaRepue + "&idRepuesto=" + idRepuestoARepaRepue + "&idProveedor=" + idProveedorSeleccionado + "&cantidad=" + cantidad + "&precio=" + precio + "&date=" + date + "&elegido=" + 2 + "&idEstado=" + 0 + "&b=" + 1 + "&iva=" + iva ;
        $.ajax({
          type:"POST",
          url:"proveedorAbmSeleccionado.php",
          data:aceptar_p,
          success:function(r) {
          //window.alert(r);
            if (r == 1) {
              alertify.success("Proveedor agregado correctamente.");
              setInterval('location.reload()', 750);
            } else {
              alertify.error("Ése proveedor ya está cargado en la reparación de éste repuesto.");           
            }
          }
        });

      } else {
        alertify.error("Error, por favor complete todos los campos.");
      }
    }); 

 });

//NEW ADD REPUESTO
function addRepuesto(idModelo) {
    var parte = document.getElementById('inp_num-parte-tbl-repuestos').value;
    var codigo = document.getElementById('inp_codigo-repuesto-tbl-repuestos').value;
    var descripcion = document.getElementById('text_descripcion-repuesto-tbl-repuestos').value;
    if (parte && codigo && descripcion) {
      $('#btn_add-repuesto').prop('disabled', true);
      datosRepuesto = "&idModelo=" + idModelo + "&parte=" + parte + "&codigo=" + codigo + "&descripcion=" + descripcion;
      $.ajax({
        type:"POST",
        url:"add_repuestoMaquina.php",
        data:datosRepuesto,
        success:function(r) {
          if (r == 1) {
            alertify.success("Repuesto agregado correctamente y actualizando datos. Por favor espere...");
            setTimeout(function() {   
              location.reload();
            },1000);       
          } else {
            if (r == 2) {
              alertify.warning("Error. Al parecer ya existe un repuesto con ése #Pieza y #Parte, para éste modelo");
              $('#btn_add-repuesto').prop('disabled', false);
            } else {
              alertify.error("Error.");
              $('#btn_add-repuesto').prop('disabled', false);
            }
          }
        }
      });     
    } else {
      alertify.warning("¡Ups! Al parecer hay campos requeridos sin completar.");
    }
  }


//

  //borra el repuesto de la lista de repuestos en la reparacion activa
    function borrarRepuesto(idRepue,idRepa,idProvee,cantidad) {
      var result = confirm("¿Está seguro que desea eliminar éste repuesto?");
      var today = new Date();
      var dd = String(today.getDate()).padStart(2, '0');
      var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
      var yyyy = today.getFullYear();
      today = dd + '-' + mm + '-' + yyyy;
      if (result){
        if (idRepa) {
              borrar_r = "&idRepuesto=" + idRepue + "&idReparacion=" + idRepa + "&idProveedor=" + idProvee + "&cantidad=" + cantidad + "&precio=" + 0 + "&elegido=" + 2 + "&date=" + today + "&idEstado=" + 0 + "&b=" + 2 + "&iva=" + 0 ;

                $.ajax({
                  type:"POST",
                  url:"proveedorAbmSeleccionado.php",
                  data:borrar_r,
                  success:function(r){
                    if(r){
                      alertify.success("Repuesto eliminado correctamente.");
                      setInterval('location.reload()', 750);                       
                    }
                  }
                });

        } else {
          alertify.error("Error.");
        }
      }
    }

//Abre el modal agregar proveedores si no hay 3 elegidos y de ahi selecciona uno.
    function agregarProveedorAlRepuesto(idReparacionPro,idRepuestoPro) {
      var today = new Date();
      var dd = String(today.getDate()).padStart(2, '0');
      var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
      var yyyy = today.getFullYear();
      today = dd + '-' + mm + '-' + yyyy;
      var idReparacionPro2 = idReparacionPro;
      var idRepuestoPro2 = idRepuestoPro;
      if (idRepuestoPro2) {
            cadena_p = "&idReparacion=" + idReparacionPro2 + "&idRepuesto=" + idRepuestoPro2;

              $.ajax({
                type:"POST",
                url:"conAc_selectCountProveedores.php",
                data:cadena_p,
                success:function(r){
                  if(r < 3){
                    $(".modal-body #id_repuesto_input").val(idRepuestoPro);
                    $(".modal-body #id_reparacion_input").val(idReparacionPro);
                    $("#modalAgregarProveedor").modal("show");
                  }else{
                    window.alert("Ya hay 3 proveedores elegidos, por favor elimine 1 para seleccionar uno nuevo.");
                  }
                }
              });      
      }
    }
//borra el proveedor del repuesto
    function borrarProveedorRepuesto(idRepar,idRepue,idProvee,cantidad) {
      var result = confirm("¿Está seguro que desea eliminar éste proveedor?");
      var today = new Date();
      var dd = String(today.getDate()).padStart(2, '0');
      var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
      var yyyy = today.getFullYear();
      today = dd + '-' + mm + '-' + yyyy;
      if (result) {
        if (idProvee) {
              eliminar_p = "&idReparacion=" + idRepar + "&idRepuesto=" + idRepue + "&idProveedor=" + idProvee + "&cantidad=" + cantidad + "&precio=" + 0 + "&date=" + today + "&elegido=" + 2 + "&idEstado=" + 0 + "&b=" + 2 + "&iva=" + 0 ;

                $.ajax({
                  type:"POST",
                  url:"proveedorAbmSeleccionado.php",
                  data:eliminar_p,
                  success:function(r){
                    if (r) {
                      alertify.success("Proveedor eliminado correctamente.");
                      setInterval('location.reload()', 750);                       
                    }
                  }
                });
        } else {
          alertify.error("Error.");
        }
      }
    }

    function sumarCantidad(idRepar,idRepue,idProvee,cantidad) {
      var today = new Date();
      var dd = String(today.getDate()).padStart(2, '0');
      var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
      var yyyy = today.getFullYear();
      today = dd + '-' + mm + '-' + yyyy;
      if (cantidad) {
        cantidad ++;
                sumar_p = "&idReparacion=" + idRepar + "&idRepuesto=" + idRepue + "&idProveedor=" + idProvee + "&cantidad=" + cantidad + "&precio=" + 0 + "&date=" + today + "&elegido=" + 2 + "&idEstado=" + 0 + "&b=" + 4 + "&iva=" + 0 ;
                  $.ajax({
                    type:"POST",
                    url:"proveedorAbmSeleccionado.php",
                    data:sumar_p,
                    dataType: "text",
                    success:function(r){
                      if(r){
                        alertify.success("Agregado 1 ud.");
                        setInterval('location.reload()', 250);                       
                      }
                    }                    
                  });
      } else {
        alertify.error("Error.");
      }        
    }

    function restarCantidad(idRepar,idRepue,idProvee,cantidad) {
      var today = new Date();
      var dd = String(today.getDate()).padStart(2, '0');
      var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
      var yyyy = today.getFullYear();
      today = dd + '-' + mm + '-' + yyyy;
      if (cantidad > 1) {
        cantidad --;
                restar_p = "&idReparacion=" + idRepar + "&idRepuesto=" + idRepue + "&idProveedor=" + idProvee + "&cantidad=" + cantidad + "&precio=" + 0 + "&date=" + today + "&elegido=" + 2 + "&idEstado=" + 0 + "&b=" + 5 + "&iva=" + 0 ;
                  $.ajax({
                    type:"POST",
                    url:"proveedorAbmSeleccionado.php",
                    data:restar_p,
                    success:function(r){
                      if(r == 1){
                        alertify.success("Restado 1 ud.");
                        setInterval('location.reload()', 250);                       
                      }
                    }
                  });
      } else {
        alertify.error("Error, no se puede restar más.");
      }        
    }

    function editarProveedorRepuesto(idRepar,idRepue,idProvee) {
      document.getElementById("btn_editar_proveedor_repuesto_"+idProvee).disabled = true;
      document.getElementById("btn_editar_proveedor_repuesto_"+idProvee).style.display = "none";

    }

  //Elige el proveedor para el set elegido 1 en bd

    function elegirProveedorRepuesto(idRepar,idRepue,idProvee) {
      var today = new Date();
      var dd = String(today.getDate()).padStart(2, '0');
      var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
      var yyyy = today.getFullYear();
      today = dd + '-' + mm + '-' + yyyy;
      if (idProvee) {
              elegir_p = "&idReparacion=" + idRepar + "&idRepuesto=" + idRepue + "&idProveedor=" + idProvee + "&cantidad=" + 0 + "&precio=" + 0 + "&date=" + today + "&elegido=" + 1 + "&idEstado=" + 0 + "&b=" + 6 + "&iva=" + 0 ;
                $.ajax({
                  type:"POST",
                  url:"proveedorAbmSeleccionado.php",
                  data:elegir_p,
                  dataType:"text",
                  success:function(r){
                    if(r == 1){
                      alertify.success("Proveedor Elegido.");
                      setInterval('location.reload()', 500);                       
                    }
                  }                    
                });
      } else {
      alertify.error("Error.");
      }
    }

  // setea 0 en elegido BD

    function cancelarProveedorRepuesto(idRepar,idRepue,idProvee) {
      var today = new Date();
      var dd = String(today.getDate()).padStart(2, '0');
      var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
      var yyyy = today.getFullYear();
      today = dd + '-' + mm + '-' + yyyy;
      if (idProvee) {
                elegir_p = "&idReparacion=" + idRepar + "&idRepuesto=" + idRepue + "&idProveedor=" + idProvee + "&cantidad=" + 0 + "&precio=" + 0 + "&date=" + today + "&elegido=" + 0 + "&idEstado=" + 0 + "&b=" + 6 + "&iva=" + 0 ;
                  $.ajax({
                    type:"POST",
                    url:"proveedorAbmSeleccionado.php",
                    data:elegir_p,
                    dataType:"text",
                    success:function(r){
                      if(r == 1){
                        alertify.success("Proveedor Cancelado.");
                        setInterval('location.reload()', 500);                       
                      }
                    }                    
                  });
      } else {
        alertify.error("Error.");
      } 
    }


  //SET EN BUSQUEDA DE REPUESTO
    function setBusquedaRepuestos(idRepar) {
      var today = new Date();
      var dd = String(today.getDate()).padStart(2, '0');
      var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
      var yyyy = today.getFullYear();
      today = dd + '-' + mm + '-' + yyyy;
      if (idRepar) {
        set_busqueda_r = "&idReparacion=" + idRepar + "&idRepuesto=" + 0 + "&idProveedor=" + 0 + "&elegido=" + 0 + "&precio=" + 0 + "&cantidad=" + 0 + "&date=" + today + "&idEstado=" + 3 + "&b=" + 7 + "&iva=" + 0 ;
        $.ajax({
          type:"POST",
          url:"proveedorAbmSeleccionado.php",
          data:set_busqueda_r,
          dataType:"text",
          success:function(r){
            if (r == 1){
              alertify.success("Estado actualizado.");
              //setInterval('location.replace("conAc.php")', 500); 
              setInterval('location.reload()', 500);                      
            }
          }                    
        });
      } else {
        alertify.error("Error.");
      }    
    }

    function setEsperaEncomienda(idRepar) {
      var today = new Date();
      var dd = String(today.getDate()).padStart(2, '0');
      var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
      var yyyy = today.getFullYear();
      today = dd + '-' + mm + '-' + yyyy;
      if (idRepar) {
        set_espera_e = "&idReparacion=" + idRepar + "&idRepuesto=" + 0 + "&idProveedor=" + 0 + "&elegido=" + 0 + "&precio=" + 0 + "&cantidad=" + 0 + "&date=" + today + "&idEstado=" + 4 + "&b=" + 7 + "&iva=" + 0 ;
        $.ajax({
          type:"POST",
          url:"proveedorAbmSeleccionado.php",
          data:set_espera_e,
          dataType:"text",
          success:function(r){
            if (r == 1){
              alertify.success("Estado actualizado.");
              //setInterval('location.replace("conAc.php")', 500);
              setInterval('location.reload()', 500);                        
            }
          }                    
        });
      } else {
        alertify.error("Error.");
      }
    }

    function setColocacion(idRepar) {
      var today = new Date();
      var dd = String(today.getDate()).padStart(2, '0');
      var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
      var yyyy = today.getFullYear();
      today = dd + '-' + mm + '-' + yyyy;
      if (idRepar) {
        set_espera_e = "&idReparacion=" + idRepar + "&idRepuesto=" + 0 + "&idProveedor=" + 0 + "&elegido=" + 0 + "&precio=" + 0 + "&cantidad=" + 0 + "&date=" + today + "&idEstado=" + 5 + "&b=" + 7 + "&iva=" + 0 ;
        $.ajax({
          type:"POST",
          url:"proveedorAbmSeleccionado.php",
          data:set_espera_e,
          dataType:"text",
          success:function(r) {
            if (r == 1){
              alertify.success("Estado actualizado.");
              //setInterval('location.replace("conAc.php")', 500);
              setInterval('location.reload()', 500);                       
            }
          }                    
        });
      } else {
        alertify.error("Error.");
      }
    }

    function setLimpieza(idRepar) {
      var today = new Date();
      var dd = String(today.getDate()).padStart(2, '0');
      var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
      var yyyy = today.getFullYear();
      today = dd + '-' + mm + '-' + yyyy;
      if (idRepar) {
        set_limpieza = "&idReparacion=" + idRepar + "&idRepuesto=" + 0 + "&idProveedor=" + 0 + "&elegido=" + 0 + "&precio=" + 0 + "&cantidad=" + 0 + "&date=" + today + "&idEstado=" + 6 + "&b=" + 7 + "&iva=" + 0 ;
        $.ajax({
          type:"POST",
          url:"proveedorAbmSeleccionado.php",
          data:set_limpieza,
          dataType:"text",
          success:function(r) {
            if (r == 1){
              alertify.success("Estado actualizado.");
              
              //setInterval('location.replace("conAc.php")', 500);
              setInterval('location.reload()', 500);                       
            }
          }                    
        });
      } else {
        alertify.error("Error.");
      }
    }

    function setListo(idRepar) {
      var result = confirm("¿Está seguro que desea cambiar el estado a Listo para entregar?");
      if (result) {
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
        var yyyy = today.getFullYear();
        today = dd + '-' + mm + '-' + yyyy;
        if (idRepar) {
          set_listo = "&idReparacion=" + idRepar + "&idRepuesto=" + 0 + "&idProveedor=" + 0 + "&elegido=" + 0 + "&precio=" + 0 + "&cantidad=" + 0 + "&date=" + today + "&idEstado=" + 7 + "&b=" + 7 + "&iva=" + 0 ;
          $.ajax({
            type:"POST",
            url:"proveedorAbmSeleccionado.php",
            data:set_listo,
            dataType:"text",
            success:function(r) {
              if (r == 1){
                alertify.success("Estado actualizado.");
                setInterval('location.replace("conAc.php")', 500);
                //setInterval('location.reload()', 500);                       
              }
            }                    
          });
        } else {
          alertify.error("Error.");
        }
      }
    }

    function setEntrega(idRepar,hayCotizacionReparacion) {
      if (hayCotizacionReparacion == 1) { 
        var result = confirm("¿Está seguro que desea entregar ésta máquina?");
        if (result) {
          
          var today = new Date();
          var dd = String(today.getDate()).padStart(2, '0');
          var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
          var yyyy = today.getFullYear();
          today = dd + '-' + mm + '-' + yyyy;
          if (idRepar) {
            set_entrega = "&idReparacion=" + idRepar + "&idRepuesto=" + 0 + "&idProveedor=" + 0 + "&elegido=" + 0 + "&precio=" + 0 + "&cantidad=" + 0 + "&date=" + today + "&idEstado=" + 8 + "&b=" + 7 + "&iva=" + 0 ;
            $.ajax({
              type:"POST",
              url:"proveedorAbmSeleccionado.php",
              data:set_entrega,
              dataType:"text",
              success:function(r) {
                if (r == 1){
                  alertify.success("Estado actualizado.");
                  setInterval('location.replace("conAc.php")', 500);
                  //setInterval('location.reload()', 500);                       
                }
              }                    
            });
          } else {
            alertify.error("Error.");
          }
        }
      } else {
        alertify.error("Error. Ésta reparación no tiene registrado ningún dato de cotización, por favor haga un registro.");
      }
    }

    function setNoArreglo() {
        var razon = prompt("Por favor, especifique por qué no tiene más arreglo:", "");
        if (razon != null){
          //window.alert (typeof(razon));
          if (razon != "") {
            document.getElementById("detalle_nta").value = razon;
          } else {
            document.getElementById("detalle_nta").value  = "Sin razón";
          }
        } else {
          document.getElementById("detalle_nta").value = "";
        }
      }

    function sumarTotal(idReparacion,sumaTotal,senia) {
      if (sumaTotal >= 0) {
        var mano_obra = document.getElementById('mano_obra').value;
        var recargo = document.getElementById('recargo').value;
        var grasa = document.getElementById('grasa').value;
        var flete = document.getElementById('flete').value;
        var totales = 0 ;
        var parcial = 0 ;
        var acumRecargo = 0 ;
        var completo = 0;
        if (recargo != "" && grasa != "" && flete != "") {
          completo = 1;
        }
        if (recargo <= 1000) {
          if (completo == 1) {
            parcial = parseFloat(sumaTotal) + parseFloat(flete);
            acumRecargo = (parseFloat(parcial) * parseFloat(recargo)) / 100 ;
            totales = parseFloat(parcial) + parseFloat(acumRecargo) + parseFloat(grasa) + parseFloat(mano_obra) - parseFloat(senia);
            document.getElementById("input_totales").innerHTML = "$ " + totales ;
            document.getElementById("mano_obra").disabled = true ;
            document.getElementById("recargo").disabled = true ;
            document.getElementById("grasa").disabled = true ;
            document.getElementById("flete").disabled = true ;
            document.getElementById("btn_crear_presupuesto").disabled = false ;
            //window.location.href = window.location.href + "&par=" + parcial + "&sen=" + senia + "&man=" + mano_obra + "&rec=" + acumRecargo + "&gra=" + grasa + "&fle=" + flete + "&tot=" + totales ;
            send_cotizacion = "&idReparacion=" + idReparacion + "&repuestos=" + sumaTotal + "&manoObra=" + mano_obra + "&grasa=" + grasa + "&flete=" + flete + "&recargo=" + recargo + "&acumRecargo=" + acumRecargo + "&senia=" + senia + "&total=" + totales ;
            $.ajax({
              type:"POST",
              url:"cotizacionReparacionME.php",
              data:send_cotizacion,
              dataType:"text",
              success:function(r) {
                if (r == 1) {
                  alertify.success("Cotización registrada, puede generar el PDF.");
                  //setInterval('location.replace("conAc.php")', 500);                       
                }
              }                    
            });
          } else {
            alertify.error("Error, hay campos vacios.");
          }
        } else {
          alertify.error("El recargo no puede exceder al 100%.");
        }
      } else {
        alertify.error("Debe elegir al menos un repuesto.");
      }
    }

    function verPresupuesto(sumaTotal,senia) {
      if (sumaTotal >= 0) {
        var mano_obra = document.getElementById('mano_obra').value;
        var recargo = document.getElementById('recargo').value;
        var grasa = document.getElementById('grasa').value;
        var flete = document.getElementById('flete').value;
        var totales = 0 ;
        var parcial = 0 ;
        var acumRecargo = 0 ;
        if (recargo <= 100) {
          parcial = parseFloat(sumaTotal) + parseFloat(flete);
          acumRecargo = (parseFloat(parcial) * parseFloat(recargo)) / 100 ;
          totales = parseFloat(parcial) + parseFloat(acumRecargo) + parseFloat(grasa) + parseFloat(mano_obra) - parseFloat(senia);
          document.getElementById("input_totales").innerHTML = "$ " + totales ;
          document.getElementById("mano_obra").disabled = true ;
          document.getElementById("recargo").disabled = true ;
          document.getElementById("grasa").disabled = true ;
          document.getElementById("flete").disabled = true ;
          window.open('pdf_presupuestoME.php?idReparacion=<?php echo $estaReparacion['id'];?>' + "&par=" + parcial + "&sen=" + senia + "&man=" + mano_obra + "&rec=" + acumRecargo + "&gra=" + grasa + "&fle=" + flete + "&tot=" + totales, '_blank');
        } else {
          alertify.error("El recargo no puede exceder al 100%.");
        }
      } else {
        alertify.error("Debe elegir al menos un repuesto.");
      }
    }
   

  //////////////VISUALES    

  //selecciona proveedor en el modal de proveedores
    function proveedorSeleccionado() {   
      document.getElementById("btn_aceptar_proveedor_seleccionado").disabled = false;    
      //document.getElementById("btnCrearProveedor").disabled = true;
    }

    function sinDespiece() {
      alertify.error("Éste modelo todavía no tiene asignado ningún despiece.");
    }

    function editarTotales() {
      document.getElementById("recargo").disabled = false ;
      document.getElementById("mano_obra").disabled = false ;
      document.getElementById("grasa").disabled = false ;
      document.getElementById("flete").disabled = false ;
      document.getElementById("btn_crear_presupuesto").disabled = true ;
    }

  //selecciona repuesto en el modal seleccionarrepuesto
    function repuestoSeleccionado() {      
      document.getElementById("agregarRepuestoSeleccionado").disabled = false;      
      var idRepuestoSeleccionado = document.getElementById("repuestoSel").value;
      var sel = document.getElementById('repuestoSel');
      var selected = sel.options[sel.selectedIndex];
      var stockGuardado = selected.getAttribute('data-stock');     
      //var stockGuardado2 =
      //stockGuardado2=$('#stockGuardado').val();
      //window.alert(stockGuardado);
      document.getElementById("stockDisponible").innerHTML = "(" + stockGuardado + " Uds. en stock IJS.)";
      var stockDisponibleShow = document.getElementById("stockDisponible");
      stockDisponibleShow.style.display = "block";
      if ( stockGuardado > 0){
        document.getElementById("btn_agregar_ijs").disabled = false;
      } else {
        document.getElementById("btn_agregar_ijs").disabled = true;
      }
    }

    function irProveedoresMe() {
      location.href = "proveedores.php?tabprovme=2";
    }


</script> 
<form method="post"> 
  <div class="row">    
    <div class="col-md-12">
      <div class="row">
        <div class="col-md-3" style="text-align: left;">
          <h4>
            <?php $clienteMaquina = find_by_id('clientemaquina',$estaMaquina['id_cliente']); ?>
            <strong><span><?php echo "Cliente: ".$clienteMaquina['razon_social'];?></span></strong>
            <br>
            <strong><span><?php echo "Maquina: IJS-ME ".$estaMaquina['id'];?></span></strong>
            <hr>
            <strong><span><?php echo $esteTipo['descripcion'];?></span></strong>
            <br>                     
            <strong><span><?php echo "Marca: ".$estaMarca['descripcion'];?></span></strong>
            <br>
            <strong><span><?php echo "Modelo: ".$esteModelo['codigo'];?></span></strong>
            <br>
            <strong><span><?php echo "Serie N°: ".$estaMaquina['num_serie'];?></span></strong>
          </h4>          
        </div>
        <?php 
          $sqlHayCotizacionReparacion = $db->query("SELECT * FROM reparacion_cotizacion WHERE reparacion_id = ".$estaReparacion['id']."");
          $hayCotizacionReparacion = $db->fetch_assoc($sqlHayCotizacionReparacion);
          if ($hayCotizacionReparacion) {
            $hayCotizacionReparacionSend = 1;
          } else {
            $hayCotizacionReparacionSend = 0;
          }
        ?>
        <div class="col-md-9">
          <input type="text" name="detalle_nta" hidden id="detalle_nta" maxlength="3">
          <br><br><br>
          <div class="pull-left">
          <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
              <div class="btn-group mr-2" role="group" aria-label="First group">
                <?php
                $despiece = find_by_id('maquina_despieces',$esteModelo['despiece_id']); 
                if ($esteModelo['despiece_id'] != 0) { 
                ?>
                <a class="btn btn-info" target="_blank" href="uploads/despieces/<?php echo $despiece['file_name'];?>">Despiece
                </a>
                <?php } else { ?>
                <a class="btn btn-warning" href="#" onclick="javascript:sinDespiece();">Despiece
                </a>
                <?php } ?>
                <a class="btn btn-warning" target='_blank' href="pdf_ComprobanteME.php?idReparacion=<?php echo $estaReparacion['id']?>">Comprobante
                </a>
              </div>
              <div class="btn-group mr-2" role="group" aria-label="Second group">
                <?php if ($estaReparacion['id_estado'] != 8) { ?>
                <button type="button" class="btn btn-lg btn-success" data-toggle="modal" data-target="#modalAgregarRepuesto">
                  <span class="glyphicon glyphicon-plus" id="openModalAgregarRepuesto"></span> AGREGAR REPUESTO
                </button>
                <?php } else { ?>
                <button disabled type="button" class="btn btn-lg btn-success" data-toggle="modal" data-target="#modalAgregarRepuesto">
                  <span class="glyphicon glyphicon-plus" id="openModalAgregarRepuesto"></span> AGREGAR REPUESTO
                </button>
                <?php } ?>
              </div>
              <div class="btn-group" role="group" aria-label="Third group">
                <a class="btn btn-danger" href="conAc.php">Volver
                </a>
                <div class="btn-group">
                  <?php if ($estaReparacion['id_estado'] == 2) { ?>
                  <a class="btn btn-warning" data-toggle="tootip" title="Estado actual">En revisión
                  </a>
                  <a class="btn btn-warning dropdown-toggle" data-toggle="dropdown" href="#">
                    <span class="caret"></span>
                  </a>               
                    <ul class="dropdown-menu">
                      <button href="#" disabled class="list-group-item list-group-item-action list-group-item-info" style="width: 190px;">Cambiar estado a:</button>
                      <a href="#" class="list-group-item list-group-item-action list-group-item-warning" style="width: 190px;" id="btn_set_busqueda_repuestos" name="btn_set_busqueda_repuestos" onclick="javascript:setBusquedaRepuestos('<?php echo $_GET['idReparacion'];?>')"><span>Busqueda de repuestos</span>
                      </a>
                      <a href="#" class="list-group-item list-group-item-action list-group-item-warning" style="width: 190px;" id="btn_set_colocacion" name="btn_set_colocacion" onclick="javascript:setColocacion('<?php echo $_GET['idReparacion'];?>')"><span>Colocación</span>
                      </a>
                      <a href="#" class="list-group-item list-group-item-action list-group-item-warning" style="width: 190px;" id="btn_set_limpieza" name="btn_set_limpieza" onclick="javascript:setLimpieza('<?php echo $_GET['idReparacion'];?>')"><span>Limpieza</span>
                      </a>
                      <br>
                      <a href="#" class="list-group-item list-group-item-action list-group-item-success" style="width: 190px;" id="btn_set_listo" name="btn_set_listo" onclick="javascript:setListo('<?php echo $_GET['idReparacion'];?>')"><span>Listo para entregar</span>
                      </a>
                      <br>
                      <button class="list-group-item list-group-item-action list-group-item-danger" style="width: 190px;" id="btn_set_nta" name="btn_set_nta" onclick="javascript:setNoArreglo()"><span>No tiene arreglo</span>
                      </button>                             
                      </div>

                    </ul>

                  <?php 
                  }
                  if ($estaReparacion['id_estado'] == 3) { ?>
                  <a class="btn btn-warning" data-toggle="tootip" title="Estado actual">En busqueda de repuestos
                  </a>
                  <a class="btn btn-warning dropdown-toggle" data-toggle="dropdown" href="#">
                    <span class="caret"></span>
                  </a>               
                    <ul class="dropdown-menu">
                      <button href="#" disabled class="list-group-item list-group-item-action list-group-item-info" style="width: 190px;">Cambiar estado a:</button>
                      <a href="#" class="list-group-item list-group-item-action list-group-item-warning" style="width: 190px;" id="btn_set_espera_encomienda" name="btn_set_espera_encomienda" onclick="javascript:setEsperaEncomienda('<?php echo $_GET['idReparacion'];?>')"><span>Espera de encomienda</span>
                      </a>
                      <a href="#" class="list-group-item list-group-item-action list-group-item-warning" style="width: 190px;" id="btn_set_colocacion" name="btn_set_colocacion" onclick="javascript:setColocacion('<?php echo $_GET['idReparacion'];?>')"><span>Colocación</span>
                      </a>
                      <a href="#" class="list-group-item list-group-item-action list-group-item-warning" style="width: 190px;" id="btn_set_limpieza" name="btn_set_limpieza" onclick="javascript:setLimpieza('<?php echo $_GET['idReparacion'];?>')"><span>Limpieza</span>
                      </a>
                      <br>
                      <a href="#" class="list-group-item list-group-item-action list-group-item-success" style="width: 190px;" id="btn_set_listo" name="btn_set_listo" onclick="javascript:setListo('<?php echo $_GET['idReparacion'];?>')"><span>Listo para entregar</span>
                      </a>
                      <br>
                      <button class="list-group-item list-group-item-action list-group-item-danger" style="width: 190px;" id="btn_set_nta" name="btn_set_nta" onclick="javascript:setNoArreglo()"><span>No tiene arreglo</span>
                      </button>                             
                      </div>
                    </ul>
                  <?php 
                  } 
                  if ($estaReparacion['id_estado'] == 4) { ?>
                  <a class="btn btn-warning" data-toggle="tootip" title="Estado actual">En espera de encomienda 
                  </a>
                  <a class="btn btn-warning dropdown-toggle" data-toggle="dropdown" href="#">
                    <span class="caret"></span>
                  </a>               
                    <ul class="dropdown-menu">
                      <button href="#" disabled class="list-group-item list-group-item-action list-group-item-info" style="width: 190px;">Cambiar estado a:</button>
                      <a href="#" class="list-group-item list-group-item-action list-group-item-warning" style="width: 190px;" id="btn_set_busqueda_repuestos" name="btn_set_busqueda_repuestos" onclick="javascript:setBusquedaRepuestos('<?php echo $_GET['idReparacion'];?>')"><span>Busqueda de repuestos</span>
                      </a>
                      <a href="#" class="list-group-item list-group-item-action list-group-item-warning" style="width: 190px;" id="btn_set_colocacion" name="btn_set_colocacion" onclick="javascript:setColocacion('<?php echo $_GET['idReparacion'];?>')"><span>Colocación</span>
                      </a>
                      <br>
                      <a href="#" class="list-group-item list-group-item-action list-group-item-success" style="width: 190px;" id="btn_set_listo" name="btn_set_listo" onclick="javascript:setListo('<?php echo $_GET['idReparacion'];?>')"><span>Listo para entregar</span>
                      </a>                        
                      </div>
                    </ul>
                  <?php 
                  }
                  if ($estaReparacion['id_estado'] == 5) { ?>
                  <a class="btn btn-warning" data-toggle="tootip" title="Estado actual">En colocación de repuestos 
                  </a>
                  <a class="btn btn-warning dropdown-toggle" data-toggle="dropdown" href="#">
                    <span class="caret"></span>
                  </a>               
                    <ul class="dropdown-menu">
                      <button href="#" disabled class="list-group-item list-group-item-action list-group-item-info" style="width: 190px;">Cambiar estado a:</button>
                      <a href="#" class="list-group-item list-group-item-action list-group-item-warning" style="width: 190px;" id="btn_set_busqueda_repuestos" name="btn_set_busqueda_repuestos" onclick="javascript:setBusquedaRepuestos('<?php echo $_GET['idReparacion'];?>')"><span>Busqueda de repuestos</span>
                      </a>
                      <a href="#" class="list-group-item list-group-item-action list-group-item-warning" style="width: 190px;" id="btn_set_limpieza" name="btn_set_limpieza" onclick="javascript:setLimpieza('<?php echo $_GET['idReparacion'];?>')"><span>Limpieza</span>
                      </a>
                      <br>
                      <a href="#" class="list-group-item list-group-item-action list-group-item-success" style="width: 190px;" id="btn_set_listo" name="btn_set_listo" onclick="javascript:setListo('<?php echo $_GET['idReparacion'];?>')"><span>Listo para entregar</span>
                      </a>                                              
                      </div>
                    </ul>
                    <?php }
                    if ($estaReparacion['id_estado'] == 6) { ?>
                      <a class="btn btn-warning" data-toggle="tootip" title="Estado actual">En limpieza 
                      </a>
                      <a class="btn btn-warning dropdown-toggle" data-toggle="dropdown" href="#">
                        <span class="caret"></span>
                      </a>               
                        <ul class="dropdown-menu">
                          <button href="#" disabled class="list-group-item list-group-item-action list-group-item-info" style="width: 190px;">Cambiar estado a:</button>
                          <a href="#" class="list-group-item list-group-item-action list-group-item-warning" style="width: 190px;" id="btn_set_busqueda_repuestos" name="btn_set_busqueda_repuestos" onclick="javascript:setBusquedaRepuestos('<?php echo $_GET['idReparacion'];?>')"><span>Busqueda de repuestos</span>
                          </a>
                          <br>
                          <a href="#" class="list-group-item list-group-item-action list-group-item-success" style="width: 190px;" id="btn_set_listo" name="btn_set_listo" onclick="javascript:setListo('<?php echo $_GET['idReparacion'];?>')"><span>Listo para entregar</span>
                          </a>
                          <br>
                          <button class="list-group-item list-group-item-action list-group-item-danger" style="width: 190px;" id="btn_set_nta" name="btn_set_nta" onclick="javascript:setNoArreglo()"><span>No tiene arreglo</span>
                          </button>                                              
                          </div>
                        </ul>
                    <?php }
                    if ($estaReparacion['id_estado'] == 7) { ?>
                      <a class="btn btn-warning" data-toggle="tootip" title="Estado actual">Lista para entregar 
                      </a>
                      <a class="btn btn-warning dropdown-toggle" data-toggle="dropdown" href="#">
                        <span class="caret"></span>
                      </a>               
                        <ul class="dropdown-menu">
                          <button href="#" disabled class="list-group-item list-group-item-action list-group-item-info" style="width: 190px;">Cambiar estado a:</button>
                          <a href="#" class="list-group-item list-group-item-action list-group-item-warning" style="width: 190px;" id="btn_set_busqueda_repuestos" name="btn_set_busqueda_repuestos" onclick="javascript:setBusquedaRepuestos('<?php echo $_GET['idReparacion'];?>')"><span>Busqueda de repuestos</span>
                          </a>
                          <a href="#" class="list-group-item list-group-item-action list-group-item-success" style="width: 190px;" id="btn_set_entrega" name="btn_set_entrega" onclick="javascript:setEntrega('<?php echo $_GET['idReparacion'];?>','<?php echo $hayCotizacionReparacionSend;?>')"><span>Entregar máquina</span>
                          </a>
                          <br>
                          <button class="list-group-item list-group-item-action list-group-item-danger" style="width: 190px;" id="btn_set_nta" name="btn_set_nta" onclick="javascript:setNoArreglo()"><span>No tiene arreglo</span>
                          </button>                                              
                          </div>
                        </ul>
                      <?php }
                    if ($estaReparacion['id_estado'] == 8) { ?>                   
                      <a disabled class="btn btn-warning" data-toggle="tootip" title="Estado actual">Entregada 
                      </a>
                      <a disabled class="btn btn-warning dropdown-toggle" data-toggle="dropdown" href="#">
                        <span class="caret"></span>
                      </a>                                                                           
                      </div>
                      <br>

                    <?php } ?>                     
              </div>
          </div>
          </div>          
        </div>
      </div>
    </div>   
  </div>
</form>
  
  <hr>
  <div class="row">
    <?php 
      $cant = 0 ;
      $prec = 0 ;
      $tempSuma = 0 ;
      $sumaTotal = 0 ;
      $tempSumaFinal = 0 ;
      $iva = 0 ; 
      $iva2 = 0 ;   
      foreach ($allRepuestosDeEstaReparacion as $unRepuestoDeEstaReparacion) :
        if ($unRepuestoDeEstaReparacion['elegido'] == 1) {
          $cant = $unRepuestoDeEstaReparacion['cantidad'] ;
          $prec = $unRepuestoDeEstaReparacion['precio'] ;
          $tempSuma = $cant * $prec ;
          if ($unRepuestoDeEstaReparacion['iva'] == 1) {
            $iva = ($tempSuma * 21 ) / 100 ;                    
          } else {
            $iva = 0 ;
          }
          $tempSumaFinal = $tempSuma + $iva ;
          //$tempSumaFinal = $tempSumaFinal + $tempSuma + $iva;
          $sumaTotal = $sumaTotal + $tempSumaFinal;
        }        
      endforeach ; ?>
    <?php 
    $sqlCotizacionBase = $db->query("SELECT * FROM reparacion_cotizacion_base");
    $cotizacionBase = $db->fetch_assoc($sqlCotizacionBase);
    $sqlCotizacionReparacion = $db->query("SELECT * FROM reparacion_cotizacion WHERE reparacion_id = ".$estaReparacion['id']."");
    $cotizacionReparacion = $db->fetch_assoc($sqlCotizacionReparacion);
    ?>
    <div class="col-md-1"></div>
    <div class="col-md-10">
      <div class="panel panel-default">
        <div class="panel-heading">
          <div class="row">
            <div class="col-md-10">
              <div style="position: absolute;left: 45%;"><strong><p style="font-size: 25px;">C O T I Z A C I Ó N</p></strong></div>
            </div>
            <div class="col-md-2" style="vertical-align: middle; text-align: right;">
              <div class="btn-group">
                <?php if ($estaReparacion['id_estado'] != 8) { ?>
                <button type="button" class="btn btn-warning btn-xs" id="btn_editar_total" name="btn_editar_total" onclick="javascript:editarTotales()" style="width: 35px;height: 35px;"><span class="glyphicon glyphicon-pencil"></span></button>
                <button type="button" class="btn btn-primary btn-xs" id="btn_sumar_total" name="btn_sumar_total" onclick="javascript:sumarTotal('<?php echo $estaReparacion['id']; ?>','<?php echo $sumaTotal;?>','<?php echo $estaReparacion['senia']; ?>')" style="width: 35px;height: 35px;"><span class="glyphicon glyphicon-ok"></span></button>
                <?php } else { ?>
                <button disabled type="button" class="btn btn-warning btn-xs" id="btn_editar_total" name="btn_editar_total" onclick="javascript:editarTotales()" style="width: 35px;height: 35px;"><span class="glyphicon glyphicon-pencil"></span></button>
                <button disabled type="button" class="btn btn-primary btn-xs" id="btn_sumar_total" name="btn_sumar_total" onclick="javascript:sumarTotal('<?php echo $estaReparacion['id']; ?>','<?php echo $sumaTotal;?>','<?php echo $estaReparacion['senia']; ?>')" style="width: 35px;height: 35px;"><span class="glyphicon glyphicon-ok"></span></button>
                <?php } ?>
                <?php if ($cotizacionReparacion) { ?>
                <button type="button" class="btn btn-success btn-xs" id="btn_crear_presupuesto" name="btn_crear_presupuesto" onclick="javascript:verPresupuesto('<?php echo $sumaTotal;?>','<?php echo $estaReparacion['senia']; ?>')" style="width: 35px;height: 35px;"><span class="glyphicon glyphicon-usd"></span></button>
              <?php } else { ?>
                <button type="button" class="btn btn-success btn-xs" id="btn_crear_presupuesto" name="btn_crear_presupuesto" onclick="javascript:verPresupuesto('<?php echo $sumaTotal;?>','<?php echo $estaReparacion['senia']; ?>')" disabled style="width: 35px;height: 35px;"><span class="glyphicon glyphicon-usd"></span></button>
              <?php } ?>
                <!--<a type="button" target = '_blank' href="pdf_presupuestoME.php?idReparacion=<?php// echo $estaReparacion['id'];?>&suTo=<?php// echo $sumaTotal;?>" class="btn btn-success btn-xs" title="Crear Presupuesto" data-toggle="tooltip" id="btn_crear_presupuesto" name="btn_crear_presupuesto" onclick="javascript:pruebaEnviar()"><span class="glyphicon glyphicon-usd"></span></a> -->          
              </div>
            </div>
          </div> 
        </div>
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover" style="width: 100% !important;">
              <thead>
                <tr>
                  <th style="vertical-align:middle; width: 14.25%;" class="text-center">Repuestos</th>
                  <th style="vertical-align:middle; width: 14.25%;" class="text-center">Mano de Obra</th>
                  <th style="vertical-align:middle; width: 14.25%;" class="text-center">Grasa</th>
                  <th style="vertical-align:middle; width: 14.25%;" class="text-center">Flete</th>                  
                  <th style="vertical-align:middle; width: 14.25%;" class="text-center">Recargo</th>
                  <th style="vertical-align:middle; width: 14.25%;" class="text-center">Seña</th>
                  <th style="vertical-align:middle; width: 14.5%;" class="text-center">TOTAL</th>                 
                </tr>
              </thead>
              <tbody>
                <tr>
                  <?php
                    if ($cotizacionReparacion) { ?>                      
                      <td style="vertical-align:middle; width: 14.25%;" class="text-center"><?php echo "$ ".$sumaTotal ?></td>
                      <td style="vertical-align:middle; width: 14.25%;" class="text-center"><?php echo "$ "?><input type="number" name="mano_obra" id="mano_obra" value="<?php echo $cotizacionReparacion['mano_obra'];?>" maxlength="8" style="width: 75px !important;" disabled></td>
                      <td style="vertical-align:middle; width: 14.25%;" class="text-center"><?php echo "$ "?><input type="number" min="0" name="grasa" id="grasa" value="<?php echo $cotizacionReparacion['grasa'];?>" maxlength="10" style="width: 75px !important;" disabled></td>
                      <td style="vertical-align:middle; width: 14.25%;" class="text-center"><?php echo "$ "?><input type="number" min="0" name="flete" id="flete" value="<?php echo $cotizacionReparacion['flete'];?>" maxlength="10" style="width: 75px !important;" disabled></td>
                      <td style="vertical-align:middle; width: 14.25%" class="text-center"><input type="number" min="0" max="1000" name="recargo" id="recargo" value="<?php echo $cotizacionReparacion['recargo'];?>" style="width: 50px !important;" disabled=""><?php echo " %";?></td>
                      <td style="vertical-align:middle; width: 14.25%;" class="text-center"><?php echo "$ ".$estaReparacion['senia']; ?></td>
                      <td style="vertical-align:middle; width: 14.5%;" class="text-center"><label name="input_totales" id="input_totales" style="width: 75px !important;"></label></td>
                      <script> document.getElementById('input_totales').innerHTML = "<?php echo '$ '.$cotizacionReparacion['total'] ;?>" ; </script>
                    <?php } else { ?>
                      <td style="vertical-align:middle; width: 14.25%;" class="text-center"><?php echo "$ ".$sumaTotal ?></td>
                      <td style="vertical-align:middle; width: 14.25%;" class="text-center"><?php echo "$ "?><input type="number" name="mano_obra" id="mano_obra" value="<?php echo $cotizacionBase['mano_obra'];?>" maxlength="8" style="width: 75px !important;" disabled></td>
                      <td style="vertical-align:middle; width: 14.25%;" class="text-center"><?php echo "$ "?><input type="number" min="0" name="grasa" id="grasa" value="<?php echo $cotizacionBase['grasa'];?>" maxlength="10" style="width: 75px !important;" disabled></td>
                      <td style="vertical-align:middle; width: 14.25%;" class="text-center"><?php echo "$ "?><input type="number" min="0" name="flete" id="flete" value="<?php echo $cotizacionBase['flete'];?>" maxlength="10" style="width: 75px !important;" disabled></td>
                      <td style="vertical-align:middle; width: 14.25%" class="text-center"><input type="number" min="0" max="1000" name="recargo" id="recargo" value="<?php echo $cotizacionBase['recargo'];?>" style="width: 50px !important;" disabled=""><?php echo " %";?></td>
                      <td style="vertical-align:middle; width: 14.25%;" class="text-center"><?php echo "$ ".$estaReparacion['senia']; ?></td>
                      <td style="vertical-align:middle; width: 14.5%;" class="text-center"><label name="input_totales" id="input_totales" style="width: 75px !important;"></label></td>
                  <?php } ?>
                </tr>                             
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="col-md-1"></div>            
  </div>

    <!-- INICIO MODAL AGREGAR REPUESTOS -->
  <div class="modal bd-example-modal-lg" id="modalAgregarRepuesto" tabindex="-1" role="dialog" aria-labelledby="modalRepuestos" data-backdrop="static" data-keyboard="false" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title">Seleccionar un repuesto</h4>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6">
                <label>Repuestos:</label>
              </div>
            </div>
            <div class="row">
              <div class="col-md-8">
                <select class="selectpicker" data-show-subtext="true" data-live-search="true" name="repuestoSel" id="repuestoSel" required="required" data-width="100%" onchange="javascript:repuestoSeleccionado()">
                  <option value="" disabled selected >Seleccione un repuesto</option>
                  <?php  foreach ($repuestosDeEstaMaquina as $unRepuestoDeEstaMaquina): ?>                                    
                    <option value="<?php echo $unRepuestoDeEstaMaquina['id'];?>" data-stock="<?php echo $unRepuestoDeEstaMaquina['stock'];?>">
                     <?php echo $unRepuestoDeEstaMaquina['codigo']." - ".$unRepuestoDeEstaMaquina['descripcion']." - ( ".$unRepuestoDeEstaMaquina['parte']." )";?>
                    </option>                    
                  <?php endforeach; ?>                   
                </select>
              </div>
              <div class="col-md-4" style="text-align: center-left;">                              
                <button class="btn btn-primary" data-dismiss="modal" title="Crear un repuesto" data-toggle="modal" data-target="#modal_add-repuesto"><span class="glyphicon glyphicon-plus"></span> Crear un repuesto</button>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <div class="row">
              <div class="col-md-12" style="text-align: right;">
                <label vertical-align="middle" id="stockDisponible" style="display: none">(Stock Disponible)</label>
              </div>              
            </div>
            <div class="row">
              <br>
              <div class="col-md-4" style="text-align: left">            
                <button type="button" class="btn btn-danger"  data-dismiss="modal">Cancelar</button>
              </div>
              <div class="col-md-8" style="text-align: right">                         
                <button type="button" class="btn btn-warning" id="btn_agregar_ijs" data-repaSelId="<?php echo $estaReparacion['id'];?>"disabled="">Agregar IJS</button>                
                <button type="button" class="btn btn-primary" id="agregarRepuestoSeleccionado" data-repaSelId="<?php echo $estaReparacion['id'];?>" disabled="">Buscar proveedor</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- MODAL ADD REPUESTO-->
  <div class="modal bd-example-modal-xs" id="modal_add-repuesto" tabindex="-1" role="dialog" aria-labelledby="modal_add-repuesto-label" data-backdrop="static" data-keyboard="false" aria-hidden="true" style="justify-content: center;align-content: center;">
  <div class="modal-dialog modal-xs" role="document"  style="width: 350px !important; margin: 35px auto;">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title">Agregar repuesto</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <label class="control-label" for="inp_codigo-repuesto-tbl-repuestos"># Pieza / Código: <sup>(No es necesario escribir en mayúsculas)</sup></label>
            <input type="text" name="inp_codigo-repuesto-tbl-repuestos" id="inp_codigo-repuesto-tbl-repuestos" class="form-control" placeholder="# Pieza o Código para proveedor">
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-md-12">
            <label class="control-label" for="inp_num-parte-tbl-repuestos"># Parte / Referencia: <sup>(No es necesario escribir en mayúsculas)</sup></label>
            <input type="text" name="inp_num-parte-tbl-repuestos" id="inp_num-parte-tbl-repuestos" class="form-control" placeholder="# Parte o Referencia">
          </div>
        </div>
        <br>
        <!--
        <div class="row">
          <div class="col-md-12">
            <label class="control-label" for="inp_stock-inicial-tbl-repuestos">Código de repuesto:</label>
            <input type="text" name="inp_stock-inicial-tbl-repuestos" id="inp_stock-inicial-tbl-repuestos" class="form-control" placeholder="Código de repuesto según proveedor">
          </div>
        </div>
        -->
        <div class="row">
          <div class="col-md-12">
            <label for="text_descripcion-repuesto-tbl-repuestos" class="control-label">Descripción: <sup>(No es necesario escribir en mayúsculas)</sup></label>
            <textarea type="text" class="form-control" placeholder="Descripción o Nombre del repuesto" name="text_descripcion-repuesto-tbl-repuestos" id="text_descripcion-repuesto-tbl-repuestos" maxlength="250" style="resize: none;"></textarea>
          </div>
        </div>        
      </div>
      <div class="modal-footer">
      <br>
        <div class="row">          
          <div class="col-md-6">            
            <button type="button" class="btn btn-danger" style="border: 1px solid white;" id="btn_cerrar-add-repuesto" data-dismiss="modal">Cancelar</button>
          </div>
          <div class="col-md-6">                                        
            <button type="button" class="btn btn-success" style="border: 1px solid white;" id="btn_add-repuesto" onclick="javascript:addRepuesto('<?php echo $esteModelo['id']; ?>');">Aceptar</button>
          </div>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<!-- END MODAL ADD REPUESTO -->
<!-- MODAL EDIT REPUESTO-->




















    <!-- FIN MODAL-->
    <!-- INICIO MODAL CREAR UN REPUESTO -->
    <!--<div class="modal bd-example-modal-lg" id="modalCrearRepuesto" tabindex="-1" role="dialog" aria-labelledby="modalCrearRep" data-backdrop="static" data-keyboard="false" aria-hidden="true">
      <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title" id="modalCrearRep">Crear repuesto</h4>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12">
                <div>
                  <label>Código:</label>
                </div>
                <div>
                  <input type="name" class="form-control" name="codigo" id="codigo" placeholder="Código" maxlength="100">
                  <input type="hidden" class="form-control" name="modelo" id="modelo" value="<?php// echo $esteModelo['id']?>">
                </div>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-12">
                <div>
                  <label>N° de Parte:</label>
                </div>
                <div>
                  <input type="name" class="form-control" name="parte" id="parte" placeholder="N° de parte" maxlength="100">
                </div>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-12">
                <div>
                  <label>Descripción:</label>
                </div> 
                <div>             
                  <textarea type="text" class="form-control" placeholder="Descripción" id="descripcion" name="descripcion" rows="5" required></textarea>
                </div>
              </div>
              </div>
              <br>
            </div>          
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal" id="cerrarCreacion">Atrás</button>
            <button type="button" class="btn btn-success" id="crearElRepuesto">Crear</button>
          </div>
          </div>
        </div>
      </div>
      <hr>     
      --> 
      <!-- FIN MODAL-->
      <!-- INICIO MODAL AGREGAR UN PROVEEDOR -->
      <div class="modal bd-example-modal-lg" id="modalAgregarProveedor" name="modalAgregarProveedor" tabindex="-1" role="dialog" aria-labelledby="modalProveedores" data-backdrop="static" data-keyboard="false" data-repuestoid="" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title">Seleccionar un proveedor</h4>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6">
                <label>Proveedores:</label>
              </div>              
            </div>
              <div class="row">
              <div class="col-md-8">                
                <select class="selectpicker" data-show-subtext="true" data-live-search="true" name="proveedorSel" id="proveedorSel" required="required" data-width="100%" onchange="javascript:proveedorSeleccionado()">
                  <option value="" disabled selected>Seleccione un proveedor</option>                  
                    <?php foreach ($proveedorDisponible as $unProveedorDisponible): ?>                                    
                      <option value="<?php echo $unProveedorDisponible['id_proveedor'];?>">
                        <?php 
                          $elProveedor = find_by_id('proveedormaquina',$unProveedorDisponible['id_proveedor']);
                          $laProvincia = find_by_id_prov('provincia',$elProveedor['provincia']);
                        ?>
                       <?php echo $elProveedor['razon_social']." - ".$elProveedor['cuit']." - (".$elProveedor['localidad']." - ".$laProvincia['nombre'].") - Tel.: ".$elProveedor['telefono1'];
                       ?>
                      </option>
                      <?php endforeach; ?>                    

                </select>
              </div>
              <div class="col-md-4" style="text-align: center-left;">                
                <button class="btn btn-primary" id="btn_ir_proveedorme" data-dismiss="modal" data-toggle="modal" title="Ir a proveedores de máquinas eléctricas" onclick="javascript:irProveedoresMe()">Ir a proveedores M.E.</button>        
              </div>
              <input type="name" disabled hidden id="id_repuesto_input">
              <input type="name" disabled hidden id="id_reparacion_input">
            </div>
            <br>

            <div class="row">
              <div class="col-md-4">
                <div>
                  <label>Precio:</label>
                </div>
                <div>
                  <input type="number" min="0" class="form-control" name="precio" id="precio" placeholder="Precio" maxlength="100" required="">               
                </div>
              </div>
               <div class="col-md-4">
                <div>
                  <label>IVA:</label>
                </div>
                <select class="form-control" name="iva" id="iva">
                  <option value="0" selected>NO</option>                                                      
                  <option value="1">SI</option>                          
                </select>
              </div>
              <div class="col-md-4">
                <div>
                  <label for="fecha" class="control-label">Fecha:</label>
                </div>
                <div>
                  <input type="text" class="datepicker form-control" name="fecha" id="fecha" readonly required>
                  <script>
                    $('#fecha').datepicker({
                      format: 'dd-mm-yyyy',
                      autoclose: true
                    });
                  </script>               
                </div>                
              </div>
            </div>
            <br>

          </div>
          <div class="modal-footer">
            <div class="row">
              <br>
              <div class="col-md-4" style="text-align: left">            
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
              </div>
              <div class="col-md-8" style="text-align: right">                                         
                <button type="button" class="btn btn-success" id="btn_aceptar_proveedor_seleccionado" disabled>Aceptar</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- FIN MODAL -->
<?php 
$cont_veces = 0;
$banderita = 0;
for ($i = 0; $i < count($allRepuestosDeEstaReparacion); $i++) {
  $idRepuestoReparacion = $allRepuestosDeEstaReparacion[$i]['repuesto_id'];
  $idMaquinaRepuesto = find_by_id('maquina_repuesto',$idRepuestoReparacion);
  $idProveedorReparacion = $allRepuestosDeEstaReparacion[$i]['id_proveedor'];
  if ($banderita == 1) {
    if ($i != count($allRepuestosDeEstaReparacion)-1) {
    if ($allRepuestosDeEstaReparacion[$i]['repuesto_id'] != $allRepuestosDeEstaReparacion[$i+1]['repuesto_id']) {
      $banderita = 0;
    }
  }
    goto a;
  }
  if ($i != count($allRepuestosDeEstaReparacion)-1) {
    if ($allRepuestosDeEstaReparacion[$i]['repuesto_id'] == $allRepuestosDeEstaReparacion[$i+1]['repuesto_id']) {
      $banderita = 1;
    }
  }
  if ($cont_veces % 2 == 0) { ?>
    </div>
    <div class="row">
  <?php 
  }
  $cont_veces = $cont_veces + 1;
  ?>
        <div class="col-md-6">          
          <div class="panel panel-default" style="box-shadow: 2px 2px 10px 2px rgba(0, 0, 0, 0.4);">
            <div class="panel-heading">
              <div class="row">
                <div class="col-md-10">
                  <?php
                  $siHayElegido = 0; 
                  $siHayProve2 = 0;
                  $hayElegido = findElegido($allRepuestosDeEstaReparacion[$i]['reparacion_id'],$allRepuestosDeEstaReparacion[$i]['repuesto_id'],$allRepuestosDeEstaReparacion[$i]['elegido']);
                  $hayProve2 = findProveedor2($allRepuestosDeEstaReparacion[$i]['reparacion_id'],$allRepuestosDeEstaReparacion[$i]['repuesto_id']);
                  if($hayElegido){
                    $siHayElegido = 1;
                  } else {
                    $siHayElegido = 2;
                  } 
                  if ($hayProve2) {
                    $siHayProve2 = 1;                    
                  } ?>
                <strong>
                  <?php if ($siHayProve2 == 1) { ?>
                  <div id="rojo">
                  <?php } elseif ($siHayElegido == 1) { ?>
                  <div id="verde">
                  <?php } elseif ($siHayElegido == 2) { ?>
                  <div id="amarillo">                    
                  <?php } ?>
                    <span class="glyphicon glyphicon-cog"></span>
                    <span><?php echo $idMaquinaRepuesto['codigo']." - ".$idMaquinaRepuesto['descripcion']." - ( ".$idMaquinaRepuesto['parte']." )"?></span>
                  </div>
                </strong>
                </div>                
                <div class="col-md-2" style="text-align: left">
                <?php if ($estaReparacion['id_estado'] != 8) { ?>  
                <?php if ($allRepuestosDeEstaReparacion[$i]['id_proveedor'] != 1) { 
                  if ($siHayElegido == 1) { ?>
                  <button id="botonAgregarProveedor_<?php echo $allRepuestosDeEstaReparacion[$i]['repuesto_id'];?>" class="btn btn-xs btn-primary" data-toggle="tooltip" title="Agregar Proveedor" onclick="javascript:agregarProveedorAlRepuesto('<?php echo $allRepuestosDeEstaReparacion[$i]['reparacion_id'];?>','<?php echo $allRepuestosDeEstaReparacion[$i]['repuesto_id'];?>')" disabled><span class="glyphicon glyphicon-plus"></span></button>

                  <button id="boton_borrado_<?php echo $allRepuestosDeEstaReparacion[$i]['repuesto_id'];?>" type="submit" class="btn btn-xs btn-danger" data-toggle="tooltip" title="Borrar Repuesto" data-repuestoIdOk="<?php echo $allRepuestosDeEstaReparacion[$i]['repuesto_id'];?>" onclick="javascript:borrarRepuesto('<?php echo $allRepuestosDeEstaReparacion[$i]['repuesto_id'];?>','<?php echo $allRepuestosDeEstaReparacion[$i]['reparacion_id'];?>','<?php echo $allRepuestosDeEstaReparacion[$i]['id_proveedor'];?>','<?php echo $allRepuestosDeEstaReparacion[$i]['cantidad'];?>')" disabled><span class="glyphicon glyphicon-trash"></span></button>

                <?php } else { ?>
                  <button id="botonAgregarProveedor_<?php echo $allRepuestosDeEstaReparacion[$i]['repuesto_id'];?>" class="btn btn-xs btn-primary" data-toggle="tooltip" title="Agregar Proveedor" onclick="javascript:agregarProveedorAlRepuesto('<?php echo $allRepuestosDeEstaReparacion[$i]['reparacion_id'];?>','<?php echo $allRepuestosDeEstaReparacion[$i]['repuesto_id'];?>')"><span class="glyphicon glyphicon-plus"></span></button>

                  <button id="boton_borrado_<?php echo $allRepuestosDeEstaReparacion[$i]['repuesto_id'];?>" type="submit" class="btn btn-xs btn-danger" data-toggle="tooltip" title="Borrar Repuesto" data-repuestoIdOk="<?php echo $allRepuestosDeEstaReparacion[$i]['repuesto_id'];?>" onclick="javascript:borrarRepuesto('<?php echo $allRepuestosDeEstaReparacion[$i]['repuesto_id'];?>','<?php echo $allRepuestosDeEstaReparacion[$i]['reparacion_id'];?>','<?php echo $allRepuestosDeEstaReparacion[$i]['id_proveedor'];?>','<?php echo $allRepuestosDeEstaReparacion[$i]['cantidad'];?>')" ><span class="glyphicon glyphicon-trash"></span></button> 

                <?php } } ?>
                <?php } else { ?>
                  <!-- btn disableado si la maquina se entrego -->
                  <button disabled id="botonAgregarProveedor_<?php echo $allRepuestosDeEstaReparacion[$i]['repuesto_id'];?>" class="btn btn-xs btn-primary" data-toggle="tooltip" title="Agregar Proveedor" onclick="javascript:agregarProveedorAlRepuesto('<?php echo $allRepuestosDeEstaReparacion[$i]['reparacion_id'];?>','<?php echo $allRepuestosDeEstaReparacion[$i]['repuesto_id'];?>')"><span class="glyphicon glyphicon-plus"></span></button>
                  <button disabled id="boton_borrado_<?php echo $allRepuestosDeEstaReparacion[$i]['repuesto_id'];?>" type="submit" class="btn btn-xs btn-danger" data-toggle="tooltip" title="Borrar Repuesto" data-repuestoIdOk="<?php echo $allRepuestosDeEstaReparacion[$i]['repuesto_id'];?>" onclick="javascript:borrarRepuesto('<?php echo $allRepuestosDeEstaReparacion[$i]['repuesto_id'];?>','<?php echo $allRepuestosDeEstaReparacion[$i]['reparacion_id'];?>','<?php echo $allRepuestosDeEstaReparacion[$i]['id_proveedor'];?>','<?php echo $allRepuestosDeEstaReparacion[$i]['cantidad'];?>')" ><span class="glyphicon glyphicon-trash"></span></button> 
                <?php } ?>
                </div>              
              </div>
            </div>
            <div class="table-responsive">
              <table class="table table-bordered table-striped table-hover">
                <thead>
                  <tr>
                      <!-- <th class="text-center" style="width: 50px;">#</th> -->
                    <th style="vertical-align:middle; width: 25%;" class="text-center">Proveedor</th>
                    <th style="vertical-align:middle; width: 10%;" class="text-center">Precio</th>
                    <th style="vertical-align:middle; width: 8%;" class="text-center">Iva</th>
                    <th style="vertical-align:middle; width: 12%;" class="text-center">Fecha</th>                                        
                    <th colspan="2" style="vertical-align:middle; width: 15%;" class="text-center">Cantidad</th>
                    <th style="vertical-align:middle; width: 18%;"></th>                  
                  </tr>
                </thead>              
                <tbody>
                <?php  
                  a:
                  if($allRepuestosDeEstaReparacion[$i]['id_proveedor'] != 2){
                    $prove = find_by_id('proveedormaquina',$idProveedorReparacion);
                    if ($allRepuestosDeEstaReparacion[$i]['elegido'] == 1) { ?>
                    <tr class="success">
                    <?php } else { ?>
                    <tr>
                    <?php } ?>
                      <td style="vertical-align:middle; width: 25%;"><?php echo utf8_encode($prove['razon_social']);?></td>
                      <td class="text-center" style="vertical-align:middle; width: 10%;"><?php echo "$ ".$allRepuestosDeEstaReparacion[$i]['precio'];?></td>
                      <td class="text-center" style="vertical-align:middle; width: 8%;"><?php 
                      if ($allRepuestosDeEstaReparacion[$i]['iva'] == 1) {
                        echo "SI";
                      } else {
                        echo "NO";
                      } ?> 
                        </td>                                  
                      <td class="text-center" style="vertical-align:middle; width: 12%;"><?php
                        if($allRepuestosDeEstaReparacion[$i]['fecha'] != null){
                          list($año, $mes, $dia) = explode('-', $allRepuestosDeEstaReparacion[$i]['fecha']); echo remove_junk($dia."/".$mes."/".$año);
                        }else{
                          echo "--";
                        } ?>                          
                      </td>
                      <td class="text-center" style="vertical-align:middle; width: 5%;"><?php echo $allRepuestosDeEstaReparacion[$i]['cantidad'];?></td>
                      <td class="text-center" style="vertical-align:middle; width: 10%;">

                       <!-- DISABLEAD BUTTONS SI LA MAQUINA YA SE ENTREGO -->
                       <?php if ($estaReparacion['id_estado'] != 8) { ?> 
                        <?php if ($siHayElegido == 1) { ?>                        
                      
                          <button id="btn_sumar_cantidad_<?php echo $allRepuestosDeEstaReparacion[$i]['id_proveedor'];?>" type="submit" class="btn btn-xs btn-primary" data-toggle="tooltip" title="Agregar" onclick="javascript:sumarCantidad('<?php echo $allRepuestosDeEstaReparacion[$i]['reparacion_id'];?>','<?php echo $allRepuestosDeEstaReparacion[$i]['repuesto_id'];?>','<?php echo $allRepuestosDeEstaReparacion[$i]['id_proveedor'];?>','<?php echo $allRepuestosDeEstaReparacion[$i]['cantidad'];?>')" disabled="disabled">
                            <span class="glyphicon glyphicon-plus"></span>
                          </button>
                          <button id="btn_restar_cantidad_<?php echo $allRepuestosDeEstaReparacion[$i]['id_proveedor'];?>" type="submit" class="btn btn-xs btn-primary" data-toggle="tooltip" title="Restar" onclick="javascript:restarCantidad('<?php echo $allRepuestosDeEstaReparacion[$i]['reparacion_id'];?>','<?php echo $allRepuestosDeEstaReparacion[$i]['repuesto_id'];?>','<?php echo $allRepuestosDeEstaReparacion[$i]['id_proveedor'];?>','<?php echo $allRepuestosDeEstaReparacion[$i]['cantidad'];?>')" disabled="disabled">
                            <span class="glyphicon glyphicon-minus"></span>
                          </button>
                        <?php } else { ?>
                          <button id="btn_sumar_cantidad_<?php echo $allRepuestosDeEstaReparacion[$i]['id_proveedor'];?>" type="submit" class="btn btn-xs btn-primary" data-toggle="tooltip" title="Agregar" onclick="javascript:sumarCantidad('<?php echo $allRepuestosDeEstaReparacion[$i]['reparacion_id'];?>','<?php echo $allRepuestosDeEstaReparacion[$i]['repuesto_id'];?>','<?php echo $allRepuestosDeEstaReparacion[$i]['id_proveedor'];?>','<?php echo $allRepuestosDeEstaReparacion[$i]['cantidad'];?>')">
                            <span class="glyphicon glyphicon-plus"></span>
                          </button>
                          <button id="btn_restar_cantidad_<?php echo $allRepuestosDeEstaReparacion[$i]['id_proveedor'];?>" type="submit" class="btn btn-xs btn-primary" data-toggle="tooltip" title="Restar" onclick="javascript:restarCantidad('<?php echo $allRepuestosDeEstaReparacion[$i]['reparacion_id'];?>','<?php echo $allRepuestosDeEstaReparacion[$i]['repuesto_id'];?>','<?php echo $allRepuestosDeEstaReparacion[$i]['id_proveedor'];?>','<?php echo $allRepuestosDeEstaReparacion[$i]['cantidad'];?>')">
                            <span class="glyphicon glyphicon-minus"></span>
                          </button>
                        <?php } ?>
                        <?php } else { ?>
                        <button disabled id="btn_sumar_cantidad_<?php echo $allRepuestosDeEstaReparacion[$i]['id_proveedor'];?>" type="submit" class="btn btn-xs btn-primary" data-toggle="tooltip" title="Agregar" onclick="javascript:sumarCantidad('<?php echo $allRepuestosDeEstaReparacion[$i]['reparacion_id'];?>','<?php echo $allRepuestosDeEstaReparacion[$i]['repuesto_id'];?>','<?php echo $allRepuestosDeEstaReparacion[$i]['id_proveedor'];?>','<?php echo $allRepuestosDeEstaReparacion[$i]['cantidad'];?>')">
                            <span class="glyphicon glyphicon-plus"></span>
                        </button>
                        <button disabled id="btn_restar_cantidad_<?php echo $allRepuestosDeEstaReparacion[$i]['id_proveedor'];?>" type="submit" class="btn btn-xs btn-primary" data-toggle="tooltip" title="Restar" onclick="javascript:restarCantidad('<?php echo $allRepuestosDeEstaReparacion[$i]['reparacion_id'];?>','<?php echo $allRepuestosDeEstaReparacion[$i]['repuesto_id'];?>','<?php echo $allRepuestosDeEstaReparacion[$i]['id_proveedor'];?>','<?php echo $allRepuestosDeEstaReparacion[$i]['cantidad'];?>')">
                          <span class="glyphicon glyphicon-minus"></span>
                        </button> 
                        <?php } ?>                    
                      </td>
                      <td class="text-center" style="vertical-align:middle; width: 18%;">

                      <?php if ($estaReparacion['id_estado'] != 8) {  ?>

                        <?php if ($siHayElegido == 1) { 
                          if ($allRepuestosDeEstaReparacion[$i]['elegido'] == 1) { ?>

                            <button id="btn_cancelar_proveedor_repuesto_<?php echo $allRepuestosDeEstaReparacion[$i]['id_proveedor'];?>" type="submit" class="btn btn-xs btn-danger" data-toggle="tooltip" title="Cancelar Proveedor" onclick="javascript:cancelarProveedorRepuesto('<?php echo $allRepuestosDeEstaReparacion[$i]['reparacion_id'];?>','<?php echo $allRepuestosDeEstaReparacion[$i]['repuesto_id'];?>','<?php echo $allRepuestosDeEstaReparacion[$i]['id_proveedor'];?>')">
                            <span class="glyphicon glyphicon-ban-circle"></span>
                          </button>

                          <button id="btn_editar_proveedor_repuesto_<?php echo $allRepuestosDeEstaReparacion[$i]['id_proveedor'];?>" type="submit" class="btn btn-xs btn-warning" data-toggle="tooltip" title="Editar Proveedor" onclick="javascript:editarProveedorRepuesto('<?php echo $allRepuestosDeEstaReparacion[$i]['reparacion_id'];?>','<?php echo $allRepuestosDeEstaReparacion[$i]['repuesto_id'];?>','<?php echo $allRepuestosDeEstaReparacion[$i]['id_proveedor'];?>')" disabled>
                            <span class="glyphicon glyphicon-pencil"></span>
                          </button>

                          <button id="btn_borrar_proveedor_repuesto_<?php echo $allRepuestosDeEstaReparacion[$i]['id_proveedor'];?>" type="submit" class="btn btn-xs btn-danger" data-toggle="tooltip" title="Borrar Proveedor" onclick="javascript:borrarProveedorRepuesto('<?php echo $allRepuestosDeEstaReparacion[$i]['reparacion_id'];?>','<?php echo $allRepuestosDeEstaReparacion[$i]['repuesto_id'];?>','<?php echo $allRepuestosDeEstaReparacion[$i]['id_proveedor'];?>','<?php echo $allRepuestosDeEstaReparacion[$i]['cantidad'];?>')" disabled>
                            <span class="glyphicon glyphicon-trash"></span>
                          </button>

                        <?php } else { ?>

                          <button id="btn_elegir_proveedor_repuesto_<?php echo $allRepuestosDeEstaReparacion[$i]['id_proveedor'];?>" type="submit" class="btn btn-xs btn-success" data-toggle="tooltip" title="Elegir Proveedor" onclick="javascript:elegirProveedorRepuesto('<?php echo $allRepuestosDeEstaReparacion[$i]['reparacion_id'];?>','<?php echo $allRepuestosDeEstaReparacion[$i]['repuesto_id'];?>','<?php echo $allRepuestosDeEstaReparacion[$i]['id_proveedor'];?>')" disabled="disabled">
                            <span class="glyphicon glyphicon-ok"></span>
                          </button>

                          <button id="btn_editar_proveedor_repuesto_<?php echo $allRepuestosDeEstaReparacion[$i]['id_proveedor'];?>" type="submit" class="btn btn-xs btn-warning" data-toggle="tooltip" title="Editar Proveedor" onclick="javascript:editarProveedorRepuesto('<?php echo $allRepuestosDeEstaReparacion[$i]['reparacion_id'];?>','<?php echo $allRepuestosDeEstaReparacion[$i]['repuesto_id'];?>','<?php echo $allRepuestosDeEstaReparacion[$i]['id_proveedor'];?>')" disabled>
                            <span class="glyphicon glyphicon-pencil"></span>
                          </button>

                          <button id="btn_borrar_proveedor_repuesto_<?php echo $allRepuestosDeEstaReparacion[$i]['id_proveedor'];?>" type="submit" class="btn btn-xs btn-danger" data-toggle="tooltip" title="Borrar Proveedor" onclick="javascript:borrarProveedorRepuesto('<?php echo $allRepuestosDeEstaReparacion[$i]['reparacion_id'];?>','<?php echo $allRepuestosDeEstaReparacion[$i]['repuesto_id'];?>','<?php echo $allRepuestosDeEstaReparacion[$i]['id_proveedor'];?>','<?php echo $allRepuestosDeEstaReparacion[$i]['cantidad'];?>')" disabled>
                            <span class="glyphicon glyphicon-trash"></span>
                          </button>


                        <?php }} else { ?>

                          <button id="btn_elegir_proveedor_repuesto_<?php echo $allRepuestosDeEstaReparacion[$i]['id_proveedor'];?>" type="submit" class="btn btn-xs btn-success" data-toggle="tooltip" title="Elegir Proveedor" onclick="javascript:elegirProveedorRepuesto('<?php echo $allRepuestosDeEstaReparacion[$i]['reparacion_id'];?>','<?php echo $allRepuestosDeEstaReparacion[$i]['repuesto_id'];?>','<?php echo $allRepuestosDeEstaReparacion[$i]['id_proveedor'];?>')" >
                            <span class="glyphicon glyphicon-ok"></span>
                          </button>

                          <button id="btn_editar_proveedor_repuesto_<?php echo $allRepuestosDeEstaReparacion[$i]['id_proveedor'];?>" type="submit" class="btn btn-xs btn-warning" data-toggle="tooltip" title="Editar Proveedor" onclick="javascript:editarProveedorRepuesto('<?php echo $allRepuestosDeEstaReparacion[$i]['reparacion_id'];?>','<?php echo $allRepuestosDeEstaReparacion[$i]['repuesto_id'];?>','<?php echo $allRepuestosDeEstaReparacion[$i]['id_proveedor'];?>')" >
                            <span class="glyphicon glyphicon-pencil"></span>
                          </button>

                          <button id="btn_borrar_proveedor_repuesto_<?php echo $allRepuestosDeEstaReparacion[$i]['id_proveedor'];?>" type="submit" class="btn btn-xs btn-danger" data-toggle="tooltip" title="Borrar Proveedor" onclick="javascript:borrarProveedorRepuesto('<?php echo $allRepuestosDeEstaReparacion[$i]['reparacion_id'];?>','<?php echo $allRepuestosDeEstaReparacion[$i]['repuesto_id'];?>','<?php echo $allRepuestosDeEstaReparacion[$i]['id_proveedor'];?>','<?php echo $allRepuestosDeEstaReparacion[$i]['cantidad'];?>')" >
                            <span class="glyphicon glyphicon-trash"></span>
                          </button>

                        <?php } ?>                          
                      <?php  } else { ?>
                        <!-- DISABLEA BUTTONS SI LA MAQUINA SE ENTREGO -->
                        <button disabled id="btn_elegir_proveedor_repuesto_<?php echo $allRepuestosDeEstaReparacion[$i]['id_proveedor'];?>" type="submit" class="btn btn-xs btn-success" data-toggle="tooltip" title="Elegir Proveedor" onclick="javascript:elegirProveedorRepuesto('<?php echo $allRepuestosDeEstaReparacion[$i]['reparacion_id'];?>','<?php echo $allRepuestosDeEstaReparacion[$i]['repuesto_id'];?>','<?php echo $allRepuestosDeEstaReparacion[$i]['id_proveedor'];?>')" >
                            <span class="glyphicon glyphicon-ok"></span>
                          </button>

                          <button disabled id="btn_editar_proveedor_repuesto_<?php echo $allRepuestosDeEstaReparacion[$i]['id_proveedor'];?>" type="submit" class="btn btn-xs btn-warning" data-toggle="tooltip" title="Editar Proveedor" onclick="javascript:editarProveedorRepuesto('<?php echo $allRepuestosDeEstaReparacion[$i]['reparacion_id'];?>','<?php echo $allRepuestosDeEstaReparacion[$i]['repuesto_id'];?>','<?php echo $allRepuestosDeEstaReparacion[$i]['id_proveedor'];?>')" >
                            <span class="glyphicon glyphicon-pencil"></span>
                          </button>

                          <button disabled id="btn_borrar_proveedor_repuesto_<?php echo $allRepuestosDeEstaReparacion[$i]['id_proveedor'];?>" type="submit" class="btn btn-xs btn-danger" data-toggle="tooltip" title="Borrar Proveedor" onclick="javascript:borrarProveedorRepuesto('<?php echo $allRepuestosDeEstaReparacion[$i]['reparacion_id'];?>','<?php echo $allRepuestosDeEstaReparacion[$i]['repuesto_id'];?>','<?php echo $allRepuestosDeEstaReparacion[$i]['id_proveedor'];?>','<?php echo $allRepuestosDeEstaReparacion[$i]['cantidad'];?>')" >
                            <span class="glyphicon glyphicon-trash"></span>
                          </button>
                      <?php } ?>
                      <!-- END DISABLES -->
                      </td>                      
                    </tr>
                <?php } 
                  if ($banderita == 1) {
                    goto b;
                  } 
                ?>
                </tbody>
              </table>
            </div>
          </div>
        </div> 
        <?php 
          b:
        } ?>       
        

  <?php include_once('layouts/footer.php'); ?>