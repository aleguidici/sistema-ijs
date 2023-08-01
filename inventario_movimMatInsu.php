<?php
  $page_title = 'Movimientos';
  require_once('includes/load.php');
  page_require_level(2);
  $all_movimientos = find_all('movimientos');
?>

<?php include_once('layouts/header.php'); ?>
  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> 
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css">
  <link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.15.3/dist/bootstrap-table.min.css">
  
  <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>

  <!-- datatable -->
  <script type="text/javascript">
      $(document).ready(function() {
          $('#movim').DataTable();
      } );
  </script>
  <h2><b>Movimientos de Materiales / Insumos</b></h2>

  <div class="table-responsive">
    <table id="movim" class="table table-striped" style="width:100%">
      <thead>
        <tr>
          <th style="width: 10px;">Nº</th> <!--visibility:hidden; display:none;-->
          <th style="width: 10px;"></th>
          <th style="width: 20px;">Fecha</th>
          <th style="width: 9px;">Hora</th>
          <th style="width: 120px;">Concepto</th>
          <th style="width: 205px;"><b>Marca</b> - <em>Tipo - [Cod.]</em></th> 
          <th style="width: 10px;">Cant.</th> 
          <th style="width: 60px;">Usuario</th>
          <th style="width: 20px;"></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach (array_reverse($all_movimientos) as $un_movimiento):
          $all_detalles = find_detalles4_by_id('movimientos_detalles', $un_movimiento['id']);
          foreach (array_reverse($all_detalles) as $un_detalle):
            $un_proveedor = find_by_id('proveedor', $un_movimiento['id_proveedor']);
            $una_provincia_prov = find_by_id_prov('provincia', $un_proveedor['provincia']);
            if ($un_movimiento['tipo'] == 0){
              $un_cliente = find_by_id('cliente', $un_movimiento['id_cliente']);
            } else {
              $un_proyecto = find_by_id('proyecto', $un_movimiento['id_proyecto']);
              $un_cliente = find_by_id('cliente', $un_proyecto['id_cliente']);
            }
            $una_provincia_cli = find_by_id_prov('provincia', $un_cliente['provincia']);
            switch ($un_movimiento['tipo']) {
              case 0: ?>
                <tr class="danger">
                <?php 
                break; 
              case 1: ?>
                <tr class="success">
                <?php 
                break;
              case 2: ?>
                <tr class="info">
                <?php 
                break; 
              } ?>
              <td class="text-center"> <!--style="visibility:hidden; display:none;"-->
                <?php echo $un_movimiento['id'];?>
              </td>
              <td class="text-center"><?php 
                switch ($un_movimiento['tipo']) {
                  case 0:
                    echo '<span class="label label-danger">Egreso</span>';
                    break;
                  case 1:
                    echo '<span class="label label-success">Ingreso</span>';
                    break;
                  case 2:
                    echo '<span class="label label-info">Interno</span>';
                    break;
                  }
              ?></td>
              <td class="text-center"><?php 
                list($año, $mes, $dia) = explode('-', $un_movimiento['fecha']);
                $fechaM = remove_junk($dia."/".$mes."/".$año);
                echo $fechaM;?></td>
              <td class="text-center"><?php 
                  list($H, $m, $s) = explode(':', $un_movimiento['hora']);
                  $horaM = remove_junk($H.":".$m);
                  echo $horaM;?></td>
              <td><?php 
                switch ($un_movimiento['tipo']) {
                  case 0:
                    switch ($un_movimiento['concepto']) {
                      case 1:
                        echo "Por Devolución a Proveedor";
                        break;
                      case 2:
                        echo "Por Venta a Cliente";
                        break;
                      case 3:
                        echo "Por Rotura, Faltante o Pérdida";
                        break;
                      case 4:
                        echo "Por Atención o Donación";
                        break;
                      case 5:
                        echo "Devolución a Cliente por cierre de Proyecto N° ".$un_proyecto["id"].": ".$un_proyecto["nombre_proyecto"];
                        break;
                      case 6:
                        echo "Uso interno";
                        break;
                    }
                    break;
                  case 1:
                    if (empty($un_movimiento['id_proveedor']))
                      echo 'De Cliente para Proyecto / Servicio';
                    else
                      echo 'De Proveedor por Compra';
                    break;
                  case 2:
                    echo 'Movimiento interno IJS';
                    break;
                }
              ?></td>
              <td>
                <b><?php 
                  $un_mat = find_by_id('inv_materiales_insumos', $un_detalle['id_matInsu']);
                  echo $un_mat['marca']; ?></b>
                <em><?php
                  if (!empty($un_mat['tipo']))
                    echo ' - '.$un_mat['tipo'];
                  if (!empty($un_mat['cod']))
                    echo ' - [Cod.: '.$un_mat['cod'].']';?>
                </em>
                  <?php
                  if (empty($un_mat['tipo']) && empty($un_mat['cod']))
                    echo ' ('.$un_mat['descripcion'].')'; ?>
                </td>
              <td class="text-center">
                <?php echo $un_detalle['cant'];?>
              </td>
              <td class="text-center"><?php 
              $usuario = find_by_id('users', $un_movimiento['id_user'])['name'];
              echo $usuario;?></td>
              <td class="text-center">
                <a data-toggle="modal" data-id="<?php echo $un_detalle['id'];?>" data-fecha="<?php echo $fechaM;?>" data-hora="<?php echo $horaM;?>" data-concepto="<?php echo $un_movimiento['tipo']."|".$un_movimiento['concepto'];?>" data-usuario="<?php echo $usuario;?>" data-cantidad="<?php echo $un_detalle['cant'];?>" data-unidad="<?php echo $un_mat['unidad'];?>" data-idMatInsu="<?php echo $un_detalle['id_matInsu'];?>" data-marca="<?php echo $un_mat['marca'];?>" data-tipo="<?php echo $un_mat['tipo'];?>" data-cod="<?php echo $un_mat['cod'];?>" data-descrip="<?php echo $un_movimiento['descripcion'];?>" data-descripm ="<?php echo $un_mat['descripcion'];?>" data-proveedor="<?php echo $un_proveedor['razon_social']."|".$un_proveedor['direccion']."|".$un_proveedor['localidad']."|".$una_provincia_prov['nombre'];?>" data-cliente="<?php echo $un_cliente['razon_social']."|".$un_cliente['direccion']."|".$un_cliente['localidad']."|".$una_provincia_cli['nombre'];?>" data-documentos="<?php echo $un_movimiento['num_factura']."|".$un_movimiento['letra_factura']."|".$un_movimiento['num_remito']."|".$un_movimiento['letra_remito']."|".$un_movimiento['num_nc'];?>" data-proyecto="<?php echo $un_proyecto['nombre_proyecto']."|".$un_proyecto['descripcion'];?>" title="Add this item" class="open-detallesMovim" href="#detallesMovim">(Detalles)</a>
              </td>
            </tr>
          <?php endforeach;
        endforeach; ?>
      </tbody>
      <tfoot>
        <tr>
          <th style="width: 10px;">Nº</th> <!--visibility:hidden; display:none;-->
          <th style="width: 10px;"></th>
          <th style="width: 20px;">Fecha</th>
          <th style="width: 9px;">Hora</th>
          <th style="width: 120px;">Concepto</th>
          <th style="width: 205px;"><b>Marca</b> - <em>Tipo - [Cod.]</em></th> 
          <th style="width: 10px;">Cant.</th> 
          <th style="width: 60px;">Usuario</th>
          <th style="width: 20px;"></th>
        </tr>
      </tfoot>
    </table>
  </div>

  <div class="modal fade bd-example-modal-lg" id="detallesMovim" tabindex="-1" role="dialog" aria-labelledby="modalMovim" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h3 class="modal-title" id="modalMovim">Detalles de movimiento</h3>
        </div>

        <div class="modal-body">
          <div class="row">
            <div class="col-xs-4">
              <h6><b>Concepto: </b>
              <span id="labelConcepto1" name="labelConcepto1"></span></h6>
              <span id="labelConcepto2" name="labelConcepto2"></span>
            </div>
            <div class="col-xs-2">
              <h6><b>Fecha: </b></h6>
              <span id="labelFecha" name="labelFecha"></span>
            </div>
            <div class="col-xs-2">
              <h6><b>Hora: </b></h6>
              <span id="labelHora" name="labelHora"></span>
            </div>
            <div class="col-xs-4">
              <h6><b>Responsable emisor: </b></h6>
              <span id="labelUsuario" name="labelUsuario"></span>
            </div>
          </div>
          <div class="row" id="rowProy">
            <br><br>
            <div class="col-xs-1">
              <b id="labelProy">Proyecto: </b>
            </div>
            <div class="col-xs-10">
              <span id="labelNombreProy" name="labelNombreProy"></span>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-xs-1">
              <b id="labelEntidad"> </b>
            </div>
            <div class="col-xs-10">
              <span id="labelNombreEntidad" name="labelNombreEntidad"></span>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-xs-6">
              <h4>Datos del Material / Insumo: </h4>
            </div>
            <div class="col-xs-6">
              <h4 id="tituloDocs">Documentos asociados: </h4>
            </div>
          </div>
          <div class="row" id="err" hidden>
            <div class="col-xs-6"></div>
            <div class="col-xs-6">
              <b id="labelSinDocs">No existen documentos asociados a este movimiento.</b>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-1">
              <b>Marca:</b>
            </div>
            <div class="col-xs-5">
              <span id="labelIdMatInsu" name="labelIdMatInsu"></span>
              <span id="labelMarca" name="labelMarca"></span>
            </div>
            <div class="col-xs-2" id="colFac1">
              <b id="labelFact"></b>
            </div>
            <div class="col-xs-4" id="colFac2">
              <span id="labelNumFact" name="labelNumFact"></span>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-1">
              <b>Tipo:</b>
            </div>
            <div class="col-xs-5">
              <span id="labelTipo" name="labelTipo"></span>
            </div>
            <div class="col-xs-2" id="colRem1">
              <b id="labelRemito"></b>
            </div>
            <div class="col-xs-4" id="colRem2">
              <span id="labelNumRemito" name="labelNumRemito"></span>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-1">
              <b>Código:</b>
            </div>
            <div class="col-xs-5">
              <span id="labelCod" name="labelCod"></span>
            </div>
            <div class="col-xs-2" id="colNC1">
              <b id="labelNC">Nº Nota de Crédito:</b>
            </div>
            <div class="col-xs-4" id="colNC2">
              <span id="labelNumNC" name="labelNumNC"></span>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-1">
              <b>Descrip.:</b>
            </div>
            <div class="col-xs-5">
              <span id="labelDesc" name="labelDesc"></span>
            </div>
          </div>

          <br>
          <div class="row">
            <div class="col-xs-2">
              <b id="labelCanti"></b>
            </div>
            <div class="col-xs-4">
              <span id="labelCant" name="labelCant"></span>
            </div>
          </div>
          <br>


        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        </div>
      </div>

      <script>
        $(document).on("click", ".open-detallesMovim", function () {
          var concepto = $(this).data('concepto').split("|");
          var proveedor = $(this).data('proveedor').split("|");
          var cliente = $(this).data('cliente').split("|");
          var descrip = $(this).data('descrip');
          var proyecto = $(this).data('proyecto').split("|");
          var cantidad = $(this).data('cantidad');
          var unidad = $(this).data('unidad');
          $(".modal-body #rowProy").hide();

          switch (parseInt(concepto[0])) {
            case 0:
              $(".modal-body #labelConcepto1").html('<span class="label label-danger">Egreso</span><br>');
              $(".modal-body #labelCanti").text('Cantidad egresada:');
              switch (parseInt(concepto[1])) {
                case 1:
                  $(".modal-body #labelConcepto2").text('- Por Devolución a Proveedor');
                  $(".modal-body #labelEntidad").text('Proveedor:');
                  $(".modal-body #labelNombreEntidad").text( proveedor[0]+' (Direc.: '+proveedor[1]+' - '+proveedor[2]+', '+proveedor[3]+')' );
                  break;
                case 2:
                  $(".modal-body #labelConcepto2").text('- Por Venta a Cliente');
                  $(".modal-body #labelEntidad").text('Cliente:');
                  $(".modal-body #labelNombreEntidad").text( cliente[0]+' (Direc.: '+cliente[1]+' - '+cliente[2]+', '+cliente[3]+')' );
                  break;
                case 3:
                  $(".modal-body #labelConcepto2").text('- Por Rotura, Faltante o Pérdida');
                  $(".modal-body #labelNombreEntidad").text( descrip );
                  $(".modal-body #labelEntidad").text('Descrip.:');
                  break;
                case 4:
                  $(".modal-body #labelConcepto2").text('- Por Atención o Donación');
                  $(".modal-body #labelNombreEntidad").text( descrip );
                  $(".modal-body #labelEntidad").text('Descrip.:');
                  break;
              }
              break;
            case 1:
              $(".modal-body #labelConcepto1").html('<span class="label label-success">Ingreso</span>');
              $(".modal-body #labelCanti").text('Cantidad ingresada:');
              if (parseInt(concepto[1]) == 1){
                $(".modal-body #labelConcepto2").text('- De Proveedor por Compra');
                $(".modal-body #labelEntidad").text('Proveedor:');
                $(".modal-body #labelNombreEntidad").text( proveedor[0]+' (Direc.: '+proveedor[1]+' - '+proveedor[2]+', '+proveedor[3]+')' );
              }
              else {
                $(".modal-body #labelConcepto2").text('- De Cliente para Proyecto / Servicio');
                $(".modal-body #labelEntidad").text('Cliente:');
                $(".modal-body #labelNombreEntidad").text( cliente[0]+' (Direc.: '+cliente[1]+' - '+cliente[2]+', '+cliente[3]+')' );
                $(".modal-body #rowProy").show();
                $(".modal-body #labelNombreProy").text( proyecto[0]+' ('+proyecto[1]+')' );
              }
              break;
            case 2:
              $(".modal-body #labelConcepto1").html('<span class="label label-warning">Interno</span>');
              $(".modal-body #labelCanti").text('Cantidad asociada:');

              $(".modal-body #labelConcepto2").text('- Movimiento interno (Asociado a un proyecto)');
              $(".modal-body #labelEntidad").text('Cliente:');
              $(".modal-body #labelNombreEntidad").text( cliente[0]+' (Direc.: '+cliente[1]+' - '+cliente[2]+', '+cliente[3]+')' );
              $(".modal-body #rowProy").show();
              $(".modal-body #labelNombreProy").text( proyecto[0]+' ('+proyecto[1]+')' );

              break;
          };
          

          var fecha = $(this).data('fecha');
          $(".modal-body #labelFecha").text( fecha );
          
          var hora = $(this).data('hora');
          $(".modal-body #labelHora").text( hora );
          
          var user = $(this).data('usuario');
          $(".modal-body #labelUsuario").text( user );
          
          var idMatIns = $(this).data('idMatInsu');
          $(".modal-body #labelIdMatInsu").text( idMatIns );

          var marca = $(this).data('marca');
          $(".modal-body #labelMarca").text( marca );
          $(".modal-body #labelCant").text( cantidad + ' '+ unidad );

          var descripMatInsu = $(this).data('descripm');
          $(".modal-body #labelDesc").text( descripMatInsu);
          
          var tipo = $(this).data('tipo');
          if (tipo == "")
            $(".modal-body #labelTipo").text( ' - ' );
          else
            $(".modal-body #labelTipo").text( tipo );
          
          var cod = $(this).data('cod');
          if (cod == "")
            $(".modal-body #labelCod").text( ' - ' );
          else
            $(".modal-body #labelCod").text( cod );

          var docs = $(this).data('documentos').split("|");
          if (docs[0] == "" && docs[2] == "" && docs[4] == ""){
            $(".modal-body #colFac1").hide();
            $(".modal-body #colFac2").hide();
            $(".modal-body #colRem1").hide();
            $(".modal-body #colRem2").hide();
            $(".modal-body #colNC1").hide();
            $(".modal-body #colNC2").hide();
            $(".modal-body #err").show();
            $(".modal-body #labelFact").text('No existen documentos asociados a este movimiento.');
          }
          else {
            if (docs[0] == "") {
              $(".modal-body #colFac1").hide();
              $(".modal-body #colFac2").hide();
            } else {
              $(".modal-body #labelFact").text( 'Nº Factura "'+docs[1]+'":' );
              $(".modal-body #labelNumFact").text( docs[0] );
            };

            if (docs[2] == "") {
              $(".modal-body #colRem1").hide();
              $(".modal-body #colRem2").hide();
            } else {
              $(".modal-body #labelRemito").text( 'Nº Remito "'+docs[3]+'":' );
              $(".modal-body #labelNumRemito").text( docs[2] );
            };

            if (docs[4] == "") {
              $(".modal-body #colNC1").hide();
              $(".modal-body #colNC2").hide();
            } else {
              $(".modal-body #labelNumNC").text( docs[4] );
            };
          }          
        });

        // $('#detallesMovim').on('hidden.bs.modal', function () {
        //   location.reload();
        // });

        $('#movim').DataTable({ "order": [[ 0, "desc" ]]});
      </script>
    </div>
  </div>                         

<?php include_once('layouts/footer.php'); ?>
