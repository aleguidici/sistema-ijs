<?php
  $page_title = 'Personal';
  require_once('includes/load.php');
  page_require_level(2);
  $all_provincias = find_prov_by_pais('1');
  $all_personal = find_all('personal');
  $current_user_ok = current_user();
?>

<?php
  if(isset($_POST['add_personal'])){
    $name = remove_junk($db->escape($_POST['Nombre']));
    $subname = remove_junk($db->escape($_POST['Apellido']));
    $num_doc = remove_junk($db->escape($_POST['Documento']));
    $cargo = remove_junk($db->escape($_POST['Cargo']));
    $num_mat = remove_junk($db->escape($_POST['Matricula']));
    $provincia = remove_junk($db->escape($_POST['provin']));
    if ($num_mat === '0')
      $num_mat = "";

    if(empty($errors)){
      $sql  = "INSERT INTO personal ( `dni`, `apellido`, `nombre`, `cargo`, `num_matricula`, `provincia`) VALUES ( '{$num_doc}','{$subname}','{$name}','{$cargo}','{$num_mat}','{$provincia}')";
    
      if($db->query($sql)){
        $session->msg("s", "Personal agregado exitosamente.");
        redirect('entidades_personal.php.php',false);
      } else {
        $session->msg("d", "Lo siento, el registro falló");
        redirect('entidades_personal.php',false);
      }
    } else {
      $session->msg("d", $errors);
      redirect('entidades_personal.php',false);
    }
  }
?>

<?php include_once('layouts/header.php'); ?>

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

<script type="text/javascript">
    $(document).ready(function() {
        $('#myTable').DataTable();
        $('#myTable2').DataTable();
    } );
</script>

<h2><b>Personal</b></h2>

<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>

<ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#menu1">Interno</a></li>
  <li><a data-toggle="tab" href="#menu2">Terceros</a></li>
</ul>

<div class="tab-content">
<!-- Solapa de personal INTERNO -->
  <div id="menu1" class="tab-pane fade in active">
    <br>
    <div class="col-xs-12" />
      <div class="row">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalNuevoPersInt">
          <span class="glyphicon glyphicon-plus-sign"></span> Nuevo Personal Interno
        </button>

        <br><br>

        <!-- Modal nuevo Personal interno -->
        <div class="modal fade bd-example-modal-lg" id="modalNuevoPersInt" tabindex="-1" role="dialog" aria-labelledby="modalNuevoPersInttt" data-backdrop="static" data-keyboard="false" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                  <h4 class="modal-title" id="modalNuevoPersInttt">Nuevo Personal Interno</h4>
              </div>
              <div class="modal-body">
                <h4>Campos obligatorios:</h4>
                <div class="row">
                  <div class="col-md-6">
                    <label>Nombre:</label>

                    <input style="border:1px solid #000000" type="name" id="nombre_nuevoPersInt" class="form-control" placeholder="Nombre" required maxlength="100" onkeypress="return blockSpecialChar(event)">
                  </div>
                  <div class="col-md-6">
                    <label>Apellido:</label>
                    <input style="border:1px solid #000000" type="name" id="apellido_nuevoPersInt" class="form-control" placeholder="Apellido" required maxlength="50" onkeypress="return blockSpecialChar(event)">
                  </div>
                </div>
                <br>
                <div class="row">
                  <div class="col-md-3">
                    <label>DNI:</label>
                    <input style="border:1px solid #000000" type="name" id="dni_nuevoPersInt" class="form-control" minlength="7" maxlength="8" placeholder="DNI" required onkeypress="return blockSpecialChar(event)">
                  </div>
                  <div class="col-md-3">
                    <label>Es responsable de servicios:</label>
                    <select style="border:1px solid #000000" required class="form-control" name="respServ_nuevoPersInt" id="respServ_nuevoPersInt">
                      <option value="0" selected> NO </option>
                      <option value="1"> SI </option>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <label>Cargo:</label>
                    <input style="border:1px solid #000000" type="name" id="cargo_nuevoPersInt" class="form-control" maxlength="100" placeholder="Cargo" required onkeypress="return blockSpecialChar(event)">
                  </div>
                </div>
                <hr style="border-color:black;">

                <h4>Otros datos (Opcionales):</h4>
                <div class="row">
                  <div class="col-md-4">
                    <label>Teléfono de contacto:</label>
                    <input type="name" class="form-control" id="tel1_nuevoPersInt" placeholder="Teléfono" maxlength="50" onkeypress="return blockSpecialChar(event)">
                  </div>
                </div>          
              </div>

                          
              <script>
                function blockSpecialChar(e) {
                  var k = e.keyCode;
                  return (!(k == 34 || k == 39));
                }
              </script>

              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="guardar_nuevoPersInt">Agregar</button>
              </div>
            </div>
          </div>
        </div>

        <script>
          $(document).ready(function(){
            $('#guardar_nuevoPersInt').click(function(){
              nombre_p = $('#nombre_nuevoPersInt').val();
              nombre_p = nombre_p.charAt(0).toUpperCase() + nombre_p.slice(1);
              apellido_p = $('#apellido_nuevoPersInt').val();
              apellido_p = apellido_p.charAt(0).toUpperCase() + apellido_p.slice(1);
              dni_p = $('#dni_nuevoPersInt').val();

              cargo_p = $('#cargo_nuevoPersInt').val();
              tel1_p = $('#tel1_nuevoPersInt').val();
              esResp_p = $('#respServ_nuevoPersInt').val();
              
              if (nombre_p && apellido_p && dni_p && cargo_p) {
                cadena_p = "nombre_p=" + nombre_p + "&apellido_p=" + apellido_p + "&dni_p=" + dni_p + "&cargo_p=" + cargo_p + "&tel1_p=" + tel1_p + "&esResp_p=" + esResp_p;

                $.ajax({
                  type:"POST",
                  url:"entidades_personalInternoNuevo.php",
                  data:cadena_p,
                  success:function(r){
                    if(r==1){
                      location.reload();
                    } else {
                      alertify.error("ERROR.");
                    }
                  }
                });
              } else {
                alertify.error("Complete los campos obligatorios.");
              }
            });
          });

          $('#modalNuevoPersInt').on('hidden.bs.modal', function (e) {
            $(this)
              .find("input,textarea, name, text")
                 .val('')
                 .end()
              .find("select")
                 .val('0')
                 .end()
              .find("input[type=checkbox], input[type=radio]")
                 .prop("checked", "")
                 .end();
          });
        </script>

        <div class="row">
          <div class="col-md-12">
            <div class="table-responsive">
              <table id="myTable" class="table table-hover table-condensed table-bordered">
                <thead>
                  <tr>
                    <th class="text-center" style="width: 11%;">Apellido</th>
                    <th class="text-center" style="width: 15%;">Nombre</th>
                    <th class="text-center" style="width: 13%;">DNI</th>
                    <th class="text-center" style="width: 18%;">Cargo</th>
                    <th class="text-center" style="width: 14%;">Es responsable de servicios</th>
                    <th class="text-center" style="width: 14%;">Teléfono</th>
                    <?php if ($current_user_ok['user_level'] <= 2) {?>
                      <th class="text-center" style="width: 8%;">Matrículas</th>
                    <?php } 
                    if ($current_user_ok['user_level'] <= 1) {?>
                      <th class="text-center" style="width: 8%;">Acciones</th>
                    <?php } ?>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($all_personal as $op):
                    if($op['baja'] == '0' && $op['tercero'] == '0') {?>
                    <tr>
                      <td class="text-center"><?php echo remove_junk(ucfirst($op['apellido'])); ?></td>
                      <td class="text-center"><?php echo remove_junk(ucfirst($op['nombre'])); ?></td>
                      <td class="text-center"><?php echo remove_junk(ucfirst($op['dni'])); ?></td>
                      <td class="text-center">
                        <?php 
                          if(empty($op['cargo'])){ echo '-'; }
                          else { echo remove_junk(ucfirst($op['cargo'])); }
                        ?>
                      </td>
                      <td class="text-center"><?php 
                        if ($op['responsable_servicios'])
                          echo "SI";
                        else
                          echo "NO"; ?>
                      </td>
                      <td class="text-center"><?php 
                        if($op['tel1'] === ''){ 
                          echo '-'; 
                        } else { 
                          echo $op['tel1']; } ?>
                      </td>
                      
                      <?php if ($current_user_ok['user_level'] <= 2) { ?>
                        <td class="text-center">
                          <div class="btn-group">
                            <?php 
                            $matriculas = find_matriculas_by_personal($op['id']);
                            if ($matriculas) {?>
                              <a href="entidades_matriculas.php?id=<?php echo (int)$op['id'];?>" class="btn btn-xs btn-success" data-toggle="tooltip" title="Ver matrículas">
                                <span class="glyphicon glyphicon-eye-open"></span>
                              </a>
                            <?php } else {?>
                              <a href="entidades_matriculas.php?id=<?php echo (int)$op['id'];?>" class="btn btn-xs btn-default" data-toggle="tooltip" title="Ver matrículas">
                                <span class="glyphicon glyphicon-eye-open"></span>
                              </a>
                            <?php }?>
                          </div>
                        </td>
                      <?php } 

                      if ($current_user_ok['user_level'] <= 1) {?>
                        <td class="text-center">
                          <div class="btn-group">
                            <a href="edit_personal.php?id=<?php echo (int)$op['id'];?>"  class="btn btn-xs btn-warning" data-toggle="tooltip" title="Editar">
                              <span class="glyphicon glyphicon-edit"></span>
                            </a>
                            <a href="delete_personal.php?id=<?php echo (int)$op['id'];?>" class="btn btn-xs btn-danger"  title="Eliminar" data-toggle="tooltip" onclick="return confirm('¿Seguro que desea eliminar este personal?')">
                                <span class="glyphicon glyphicon-trash"></span>
                            </a>
                          </div>
                        </td>
                      <?php } ?>
                    </tr>
                  <?php }
                  endforeach; ?>
                </tbody>
                <tfoot>
                  <tr>
                    <th class="text-center" style="width: 11%;">Apellido</th>
                    <th class="text-center" style="width: 15%;">Nombre</th>
                    <th class="text-center" style="width: 13%;">DNI</th>
                    <th class="text-center" style="width: 18%;">Cargo</th>
                    <th class="text-center" style="width: 14%;">Es responsable de servicios</th>
                    <th class="text-center" style="width: 14%;">Teléfono</th>
                    <?php if ($current_user_ok['user_level'] <= 2) {?>
                      <th class="text-center" style="width: 8%;">Matrículas</th>
                    <?php } 
                    if ($current_user_ok['user_level'] <= 1) {?>
                      <th class="text-center" style="width: 8%;">Acciones</th>
                    <?php } ?>
                  </tr>
                </tfoot>
              </table>
            </div>
            <script>
              //Ordena la tabla alfabeticamente
              var table, rows, switching, i, x, y, shouldSwitch;
              table = document.getElementById("myTable");
              switching = true;
              while (switching) {
                switching = false;
                rows = table.rows;
                for (i = 1; i < (rows.length - 1); i++) {
                  shouldSwitch = false;
                  x = rows[i].getElementsByTagName("TD")[0];
                  y = rows[i + 1].getElementsByTagName("TD")[0];
                  if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                    shouldSwitch = true;
                    break;
                  }
                }
                if (shouldSwitch) {
                  rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                  switching = true;
                }
              }
            </script>
          </div>
        </div>
      </div>
    </div>
  </div>


  <div id="menu2" class="tab-pane fade">
  <!-- Solapa de personal EXTERNO -->
    <br>
    <div class="col-xs-12" />
      <div class="row">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalNuevoPersExt">
          <span class="glyphicon glyphicon-plus-sign"></span> Nuevo Personal Externo
        </button>

        <br><br>

        <!-- Modal nuevo Personal Externo -->
        <div class="modal fade bd-example-modal-lg" id="modalNuevoPersExt" tabindex="-1" role="dialog" aria-labelledby="modalNuevoPersExttt" data-backdrop="static" data-keyboard="false" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                  <h4 class="modal-title" id="modalNuevoPersExttt">Nuevo Personal Externo</h4>
              </div>
              <div class="modal-body">
                <h4>Campos obligatorios:</h4>
                <div class="row">
                  <div class="col-md-6">
                    <label>Nombre:</label>

                    <input style="border:1px solid #000000" type="name" id="nombre_nuevoPersExt" class="form-control" placeholder="Nombre" required maxlength="100" onkeypress="return blockSpecialChar(event)">
                  </div>
                  <div class="col-md-6">
                    <label>Apellido:</label>
                    <input style="border:1px solid #000000" type="name" id="apellido_nuevoPersExt" class="form-control" placeholder="Apellido" required maxlength="50" onkeypress="return blockSpecialChar(event)">
                  </div>
                </div>
                <br>
                <div class="row">
                  <div class="col-md-3">
                    <label>DNI:</label>
                    <input style="border:1px solid #000000" type="name" id="dni_nuevoPersExt" class="form-control" minlength="7" maxlength="8" placeholder="DNI" required onkeypress="return blockSpecialChar(event)">
                  </div>
                  <div class="col-md-3">
                    <label>Es responsable de servicios:</label>
                    <select style="border:1px solid #000000" required class="form-control" name="respServ_nuevoPersExt" id="respServ_nuevoPersExt">
                      <option value="0" selected> NO </option>
                      <option value="1"> SI </option>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <label>Cargo:</label>
                    <input style="border:1px solid #000000" type="name" id="cargo_nuevoPersExt" class="form-control" maxlength="100" placeholder="Cargo" required onkeypress="return blockSpecialChar(event)">
                  </div>
                </div>
                <hr style="border-color:black;">

                <h4>Otros datos (Opcionales):</h4>
                <div class="row">
                  <div class="col-md-4">
                    <label>Teléfono de contacto:</label>
                    <input type="name" class="form-control" id="tel1_nuevoPersExt" placeholder="Teléfono" maxlength="50" onkeypress="return blockSpecialChar(event)">
                  </div>
                </div>          
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="guardar_nuevoPersExt">Agregar</button>
              </div>
            </div>
          </div>
        </div>

        <script>

          $(document).ready(function(){
            $('#guardar_nuevoPersExt').click(function(){
              nombre_e = $('#nombre_nuevoPersExt').val();
              nombre_e = nombre_e.charAt(0).toUpperCase() + nombre_e.slice(1);
              apellido_e = $('#apellido_nuevoPersExt').val();
              apellido_e = apellido_e.charAt(0).toUpperCase() + apellido_e.slice(1);
              dni_e = $('#dni_nuevoPersExt').val();

              cargo_e = $('#cargo_nuevoPersExt').val();
              tel1_e = $('#tel1_nuevoPersExt').val();
              esResp_e = $('#respServ_nuevoPersExt').val();
              
              if (nombre_e && apellido_e && dni_e && cargo_e) {
                cadena_e = "nombre_e=" + nombre_e + "&apellido_e=" + apellido_e + "&dni_e=" + dni_e + "&cargo_e=" + cargo_e + "&tel1_e=" + tel1_e + "&esResp_e=" + esResp_e;

                $.ajax({
                  type:"POST",
                  url:"entidades_personalExternoNuevo.php",
                  data:cadena_e,
                  success:function(r){
                    if(r==1){
                      location.reload();
                    } else {
                      alertify.error("ERROR.");
                    }
                  }
                });
              } else {
                alertify.error("Complete los campos obligatorios.");
              }
            });
          });

          $('#modalNuevoPersExt').on('hidden.bs.modal', function (e) {
            $(this)
              .find("input,textarea, name, text")
                 .val('')
                 .end()
              .find("select")
                 .val('0')
                 .end()
              .find("input[type=checkbox], input[type=radio]")
                 .prop("checked", "")
                 .end();
          });
        </script>

        <div class="row">
          <div class="col-md-12">
            <div class="table-responsive">
              <table id="myTable2" class="table table-hover table-condensed table-bordered">
                <thead>
                  <tr>
                    <th class="text-center" style="width: 11%;">Apellido</th>
                    <th class="text-center" style="width: 15%;">Nombre</th>
                    <th class="text-center" style="width: 13%;">DNI</th>
                    <th class="text-center" style="width: 18%;">Cargo</th>
                    <th class="text-center" style="width: 14%;">Es responsable de servicios</th>
                    <th class="text-center" style="width: 14%;">Teléfono</th>
                    <?php if ($current_user_ok['user_level'] <= 2) {?>
                      <th class="text-center" style="width: 8%;">Matrículas</th>
                    <?php } 
                    if ($current_user_ok['user_level'] <= 1) {?>
                      <th class="text-center" style="width: 8%;">Acciones</th>
                    <?php } ?>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($all_personal as $op2):
                    if($op2['baja'] == '0' && $op2['tercero'] == '1') {?>
                    <tr>
                      <td class="text-center"><?php echo remove_junk(ucfirst($op2['apellido'])); ?></td>
                      <td class="text-center"><?php echo remove_junk(ucfirst($op2['nombre'])); ?></td>
                      <td class="text-center"><?php echo remove_junk(ucfirst($op2['dni'])); ?></td>
                      <td class="text-center">
                        <?php 
                          if(empty($op2['cargo'])){ echo '-'; }
                          else { echo remove_junk(ucfirst($op2['cargo'])); }
                        ?>
                      </td>
                      <td class="text-center"><?php 
                        if ($op2['responsable_servicios'])
                          echo "SI";
                        else
                          echo "NO"; ?>
                      </td>
                      <td class="text-center"><?php 
                        if($op2['tel1'] === ''){ 
                          echo '-'; 
                        } else { 
                          echo $op2['tel1']; } ?>
                      </td>
                      
                      <?php if ($current_user_ok['user_level'] <= 2) { ?>
                        <td class="text-center">
                          <div class="btn-group">
                            <?php 
                            $matriculas2 = find_matriculas_by_personal($op2['id']);
                            if ($matriculas2) {?>
                              <a href="entidades_matriculas.php?id=<?php echo (int)$op2['id'];?>" class="btn btn-xs btn-success" data-toggle="tooltip" title="Ver matrículas">
                                <span class="glyphicon glyphicon-eye-open"></span>
                              </a>
                            <?php } else {?>
                              <a href="entidades_matriculas.php?id=<?php echo (int)$op2['id'];?>" class="btn btn-xs btn-default" data-toggle="tooltip" title="Ver matrículas">
                                <span class="glyphicon glyphicon-eye-open"></span>
                              </a>
                            <?php }?>
                          </div>
                        </td>
                      <?php } 
                      if ($current_user_ok['user_level'] <= 1) {?>
                        <td class="text-center">
                          <div class="btn-group">
                            <a href="edit_personal.php?id=<?php echo (int)$op2['id'];?>"  class="btn btn-xs btn-warning" data-toggle="tooltip" title="Editar">
                              <span class="glyphicon glyphicon-edit"></span>
                            </a>
                            <a href="delete_personal.php?id=<?php echo (int)$op2['id'];?>" class="btn btn-xs btn-danger"  title="Eliminar" data-toggle="tooltip" onclick="return confirm('¿Seguro que desea eliminar este personal?')">
                                <span class="glyphicon glyphicon-trash"></span>
                            </a>
                          </div>
                        </td>
                      <?php } ?>
                    </tr>
                  <?php }
                  endforeach; ?>
                </tbody>
                <tfoot>
                  <tr>
                    <th class="text-center" style="width: 11%;">Apellido</th>
                    <th class="text-center" style="width: 15%;">Nombre</th>
                    <th class="text-center" style="width: 13%;">DNI</th>
                    <th class="text-center" style="width: 18%;">Cargo</th>
                    <th class="text-center" style="width: 14%;">Es responsable de servicios</th>
                    <th class="text-center" style="width: 14%;">Teléfono</th>
                    <?php if ($current_user_ok['user_level'] <= 2) {?>
                      <th class="text-center" style="width: 8%;">Matrículas</th>
                    <?php } 
                    if ($current_user_ok['user_level'] <= 1) {?>
                      <th class="text-center" style="width: 8%;">Acciones</th>
                    <?php } ?>
                  </tr>
                </tfoot>
              </table>
            </div>
            <script>
              //Ordena la tabla alfabeticamente
              var table2, rows2, switching2, j, x2, y2, shouldSwitch2;
              table2 = document.getElementById("myTable2");
              switching2 = true;
              while (switching2) {
                switching2 = false;
                rows2 = table2.rows2;
                for (j = 1; j < (rows2.length - 1); j++) {
                  shouldSwitch2 = false;
                  x2 = rows2[j].getElementsByTagName("TD")[0];
                  y2 = rows2[j + 1].getElementsByTagName("TD")[0];
                  if (x2.innerHTML.toLowerCase() > y2.innerHTML.toLowerCase()) {
                    shouldSwitch2 = true;
                    break;
                  }
                }
                if (shouldSwitch2) {
                  rows2[j].parentNode.insertBefore(rows2[j + 1], rows2[j]);
                  switching2 = true;
                }
              }
            </script>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>
