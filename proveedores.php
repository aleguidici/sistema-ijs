<?php
  $page_title = 'Proveedores';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
   $all_proveedores = find_all_proveedores();
   $all_provincias = find_all('provincia');
   $current_user_ok = current_user();
   $all_proveedores_me=find_all_proveedores_me();

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
          $('#proveedor').DataTable();
          var tabsel = <?php echo $_GET['tabprovme'];?>;
     
            if(tabsel == 2){
               activaTab('menu2');
            } else {
              activaTab('menu1');
            }
        
          //var hash = document.location.hash;
          //if (hash) {
         //   $('.nav-tabs a[href='+hash+']').tab('show');
          //} 
          // Change hash for page-reload
         // $('.nav-tabs a').on('shown.bs.tab', function (e) {
          //    window.location.hash = e.target.hash;
          //});
      });

      function activaTab(tab){
        $('.nav-tabs a[href="#' + tab + '"]').tab('show');
      } 

  </script>

  <h2><b>Proveedores</b></h2>

  <ul class="nav nav-tabs" id="myTab">
    <li class="active">
      <a data-toggle="tab" role="tab" href="#menu1">Proveedores</a>
    </li>
    <li>
      <a data-toggle="tab" role="tab" href="#menu2"¨>Proveedores - Máquinas Eléctricas</a>
    </li>
  </ul>

  <div class="tab-content">
  <!-- Solapa de Proveedores -->
    <div id="menu1" class="tab-pane fade in active">

      <div class="panel-heading clearfix">
        <div class="pull-right">
          <a href="proveedor.php" class="btn btn-primary">Agregar proveedor</a>
        </div>
      </div>

      <div class="table-responsive">
        <table id="proveedor" class="table table-striped table-bordered" style="width:100%">
          <thead>
            <tr>
              <th class="text-center" style="width: 15%;"> Razon Social </th>
              <th class="text-center" style="width: 13%;"> Cuit - (Condición IVA)</th>
              <th class="text-center" style="width: 20%;"> Dirección </th>
              <th class="text-center" style="width: 12%;"> Localidad </th>
              <th class="text-center" style="width: 10%;"> Provincia / Estado </th>
              <th class="text-center" style="width: 10%;"> Código postal </th>
              <th class="text-center" style="width: 72px;"> Teléfono 1 </th>
              <th class="text-center" style="width: 72px;"> Teléfono 2 </th>
              <th class="text-center" style="width: 60px;"></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($all_proveedores as $proveedor):?>
              <tr>
                <td> <?php echo remove_junk($proveedor['razon_social']); ?></td>
                <td class="text-center"> <?php echo remove_junk($proveedor['cuit']), " (",remove_junk($proveedor['iva']),")"; ?></td>
                <td> <?php echo $proveedor['direccion']; ?></td>
                <td class="text-center"> <?php echo remove_junk($proveedor['localidad']); ?></td>
                <td class="text-center" id="prov2">
                  <?php 
                    foreach ($all_provincias as $prov2): 
                      if($prov2['id_provincia'] == $proveedor['provincia']){
                        $prov = $prov2['nombre'];
                        break;
                      }
                    endforeach;
                    echo $prov;
                  ?>
                </td>
                
                <td class="text-center">
                  <?php 
                    if($proveedor['cp'] === NULL || $proveedor['cp'] === ''){ echo '-'; }
                    else { echo remove_junk($proveedor['cp']); }
                  ?>  
                </td>
                <td class="text-center"> 
                  <?php 
                    if($proveedor['telefono1'] === NULL || $proveedor['telefono1'] === ''){ echo '-'; }
                    else { echo remove_junk(ucfirst($proveedor['telefono1'])); }
                  ?>
                </td>
                <td class="text-center"> 
                  <?php 
                    if($proveedor['telefono2'] === NULL || $proveedor['telefono2'] === ''){ echo '-'; }
                    else { echo remove_junk(ucfirst($proveedor['telefono2'])); }
                  ?>
                </td>

                <td class="text-center">
                  <div class="btn-group">
                     <a href="edit_proveedor.php?id=<?php echo (int)$proveedor['id'];?>" class="btn btn-info btn-xs"  title="Editar" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-edit"></span>
                    </a>
                    <?php if ($current_user_ok['user_level'] <= 1) {?>
                      <a href="delete_proveedor.php?id=<?php echo (int)$proveedor['id'];?>" class="btn btn-xs btn-danger"  title="Eliminar" data-toggle="tooltip" onclick="return confirm('¿Seguro que desea eliminar este proveedor?')">
                        <span class="glyphicon glyphicon-trash"></span>
                      </a>
                    <?php } ?>
                  </div>
                </td>
              </tr>
             <?php endforeach; ?>
          </tbody>
        </table>
        <script>
          $('#proveedor').DataTable({ "order": [[ 0, "asc" ],[ 3, "asc" ]] });
          $('.dataTables_length').addClass('bs-select');
        </script>


      </div>
    </div>
<!-- Solapa de proveedores maquinas electricas-->
<div id="menu2" class="tab-pane fade">

      <div class="panel-heading clearfix">
        <div class="pull-right">
          <a href="proveedorMaquina.php" class="btn btn-primary">Agregar proveedor M.E.</a>
        </div>
      </div>

      <div class="table-responsive">
        <table id="proveedor_me" class="table table-striped table-bordered" style="width:100%">
          <thead>
            <tr>
              <th class="text-center" style="width: 15%;"> Razon Social </th>
              <th class="text-center" style="width: 13%;"> Cuit - (Condición IVA)</th>
              <th class="text-center" style="width: 20%;"> Dirección </th>
              <th class="text-center" style="width: 8%;"> Localidad </th>
              <th class="text-center" style="width: 8%;"> Provincia / Estado </th>
              <th class="text-center" style="width: 8%;"> Código postal </th>
              <th class="text-center" style="width: 10%;"> Teléfono 1 </th>
              <th class="text-center" style="width: 10%;"> Teléfono 2 </th>
              <th class="text-center" style="width: 8%;"></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($all_proveedores_me as $proveedor_me):?>
              <tr>
                <td> <?php echo remove_junk($proveedor_me['razon_social']); ?></td>
                <td class="text-center"> <?php echo remove_junk($proveedor_me['cuit']), " (",remove_junk($proveedor_me['iva']),")"; ?></td>
                <td> <?php echo $proveedor_me['direccion']; ?></td>
                <td class="text-center"> <?php echo remove_junk($proveedor_me['localidad']); ?></td>
                <td class="text-center" id="prov2">
                  <?php 
                    foreach ($all_provincias as $prov2): 
                      if($prov2['id_provincia'] == $proveedor_me['provincia']){
                        $prov = $prov2['nombre'];
                        break;
                      }
                    endforeach;
                    echo $prov;
                  ?>
                </td>
                
                <td class="text-center">
                  <?php 
                    if($proveedor_me['cp'] === NULL || $proveedor_me['cp'] === ''){ echo '-'; }
                    else { echo remove_junk($proveedor_me['cp']); }
                  ?>  
                </td>
                <td class="text-center"> 
                  <?php 
                    if($proveedor_me['telefono1'] === NULL || $proveedor_me['telefono1'] === ''){ echo '-'; }
                    else { echo remove_junk(ucfirst($proveedor_me['telefono1'])); }
                  ?>
                </td>
                <td class="text-center"> 
                  <?php 
                    if($proveedor_me['telefono2'] === NULL || $proveedor_me['telefono2'] === ''){ echo '-'; }
                    else { echo remove_junk(ucfirst($proveedor_me['telefono2'])); }
                  ?>
                </td>

                <td class="text-center">
                  <div class="btn-group">
                     <a href="edit_proveedorMaquina.php?id=<?php echo (int)$proveedor_me['id'];?>" class="btn btn-info btn-xs"  title="Editar proveedor" data-toggle="tooltip" onclick="return confirm('¿Seguro que desea editar éste proveedor?')">
                      <span class="glyphicon glyphicon-edit"></span>
                    </a>
                    <?php if ($current_user_ok['user_level'] <= 1) {?>
                      <a href="proveedorMaquinaMarca.php?id=<?php echo (int)$proveedor_me['id'];?>" class="btn btn-xs btn-warning"  title="Editar marcas admitidas" data-toggle="tooltip" onclick="return confirm('¿Seguro que desea editar marcas admitidas por éste proveedor?')"><span class="glyphicon glyphicon-th-list"></span>
                      </a> 
                      <a href="delete_proveedor.php?id=<?php echo (int)$proveedor_me['id'];?>" class="btn btn-xs btn-danger"  title="Eliminar proveedor" data-toggle="tooltip" onclick="return confirm('¿Seguro que desea eliminar éste proveedor?')">
                        <span class="glyphicon glyphicon-trash"></span>                                             
                      </a>
                    <?php } ?>
                  </div>
                </td>
              </tr>
             <?php endforeach; ?>
          </tbody>
        </table>
        <script>
          $('#proveedor_me').DataTable({ "order": [[ 0, "asc" ],[ 3, "asc" ]] });
          $('.dataTables_length').addClass('bs-select');
        </script>
      </div>
    </div>
  </div>


<?php include_once('layouts/footer.php'); ?>
