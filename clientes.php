<?php
  $page_title = 'Clientes';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
   $all_clientes = find_all('cliente');
   $all_clientesMaq = find_all('clientemaquina');
   $all_provincias = find_all('provincia');
   $current_user_ok = current_user();
   $tab = $_GET['tabclieme'];
?>
<?php include_once('layouts/header.php'); ?>
  <style>
    .thumb { 
      height: auto; 
      cursor: pointer;
      max-height:140px;/* using max-height property*/
      max-width:250px;
    }
    
    #thumbnails ul{
      margin: 0 auto;
      display: block;
    }
    
    #thumbnails li{
      display: inline-block;
      padding:10px;
      margin:10px;
    }
  </style>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> 
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css">
  <link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.15.3/dist/bootstrap-table.min.css">

  <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>

  <!-- datatable -->
  <script type="text/javascript">
      $(document).ready(function() {

          $('#clientes').DataTable();

          var tabsel = <?php if ($tab) { echo $tab; } else { echo 1; } ?>;   

          if (tabsel == 1) {
            activaTab('menu1');
          }
          if (tabsel == 2) {
            activaTab('menu2');
          } 

      });

      function activaTab(tab){
        $('.nav-tabs a[href="#' + tab + '"]').tab('show');
      } 

  </script>


  <h2><b>Clientes</b></h2>

  <ul class="nav nav-tabs" id="myTab">
    <li class="active">
      <a data-toggle="tab" role="tab" href="#menu1">Clientes</a>
    </li>
    <li>
      <a data-toggle="tab" role="tab" href="#menu2">Clientes - Máquinas Eléctricas</a>
    </li>
  </ul>

  <div class="tab-content">
  <!-- Solapa de Proyectos -->
    <div id="menu1" class="tab-pane fade in active">
      <div class="panel-heading clearfix">
        <div class="pull-right">
          <a href="cliente.php" class="btn btn-primary">Agregar Cliente</a>
        </div>
      </div>

      <div class="table-responsive">
        <table id="clientes" class="table table-striped table-bordered" style="width:100%">
          <thead>
            <tr>
              <th class="text-center" style="width: 12%;"> Razon Social</th>
              <th class="text-center" style="width: 13%;"> Cuit - (Condición IVA)</th>
              <th class="text-center" style="width: 10%;"> Localidad - Provincia / Estado </th>
              <th class="text-center" style="width: 10%;"> Nº y Nombre de suc. </th>
              <th class="text-center" style="width: 16%;"> Dirección </th>
              <th class="text-center" style="width: 12%;"> Teléfono </th>
              <th class="text-center" style="width: 110px;"> Croquis </th>
              <th class="text-center" style="width: 110px;"> Logo </th>
              <th class="text-center" style="width: 50px;"></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($all_clientes as $cliente):?>
              <tr>
                <td> <?php echo remove_junk($cliente['razon_social']); ?></td>
                <td class="text-center"> <?php echo remove_junk($cliente['cuit']), " (",remove_junk($cliente['iva']),")"; ?></td>
                
                <td class="text-center"> <?php echo remove_junk($cliente['localidad']); ?>
                  <?php 
                    foreach ($all_provincias as $prov2): 
                      if($prov2['id_provincia'] == $cliente['provincia']){
                        $ban = $prov2['nombre'];
                        break;
                      }
                    endforeach;
                    echo remove_junk(' - '.$ban);
                  ?>
                </td>
                <td class="text-center"> <?php 
                  if (!$cliente['num_suc'] == "")
                    echo remove_junk($cliente['num_suc']);
                  else
                    echo "S/Num.";
                  echo " - ";
                  if (!$cliente['nombre_suc'] == "")
                    echo remove_junk($cliente['nombre_suc']);
                  else
                    echo "S/Nombre";
                  echo ' [Cod. IJS: '.$cliente['id'].']'?></td>
                <td> <?php echo remove_junk($cliente['direccion']); ?></td>
                
                
                <td class="text-center"> 
                  <?php 
                    if($cliente['tel'] === NULL || $cliente['tel'] === ''){ echo '-'; }
                    else { echo remove_junk(ucfirst($cliente['tel'])); }
                  ?>
                </td>
                <td class="text-center">
                  <?php 
                    if($cliente['croquis'] === '0'){ echo '(Sin croquis)'; }
                    else {  
                      $croquis = find_imagen($cliente['croquis']);?>
                          <img src="uploads/imagenes/<?php echo $croquis['file_name'];?>" class="img-thumbnail" /> 
                  <?php } ?>
                </td>
                <td class="text-center">
                  <?php 
                    if($cliente['logo'] === '0'){ echo '(Sin logo)'; }
                    else {  
                      $logo = find_imagen($cliente['logo']);?>
                          <img src="uploads/imagenes/<?php echo $logo['file_name'];?>" class="img-thumbnail" /> 
                  <?php } ?>
                </td>
                <td class="text-center">
                  <div class="btn-group">
                     <a href="edit_cliente.php?id=<?php echo (int)$cliente['id'];?>" class="btn btn-info btn-xs"  title="Editar" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-edit"></span>
                    </a>
                    <?php if ($current_user_ok['user_level'] <= 1) {?>
                      <a href="delete_cliente.php?id=<?php echo (int)$cliente['id'];?>" class="btn btn-xs btn-danger"  title="Eliminar" data-toggle="tooltip" onclick="return confirm('¿Seguro que desea eliminar este cliente?')">
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
          $('#clientes').DataTable({ "order": [[ 0, "asc" ],[ 3, "asc" ]] });
          $('.dataTables_length').addClass('bs-select');
        </script>
      </div>

    </div>

    <div id="menu2" class="tab-pane fade">
      <div class="panel-heading clearfix">
        <div class="pull-right">          
          <a href="clienteMaquina.php" class="btn btn-primary">Agregar Cliente - Máquina</a>
          <a href="conAc.php" class="btn btn-danger">Volver a conAc</a>
        </div>
      </div>
      
      <div class="table-responsive" style="width:100%">
        <table id="clientesMaq" class="table table-striped stable-condensed table-bordered table-hover">
          <thead>
            <tr>
              <th class="text-center" style="width: 20% !important;">Razon Social</th>
              <th class="text-center" style="width: 15% !important;">Cuit - (Condición IVA)</th>
              <th class="text-center" style="width: 15% !important;">Localidad - Provincia</th>
              <th class="text-center" style="width: 15% !important;">Dirección</th>
              <th class="text-center" style="width: 10% !important;">Email</th>
              <th class="text-center" style="width: 10% !important;">Teléfono</th>
              <th class="text-center" style="width: 10% !important;">Celular</th>
              <th class="text-center" style="width: 5% !important;"></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($all_clientesMaq as $clienteMaq):
              $ivaCond = find_by_id('iva_condiciones',$clienteMaq['iva']);
            ?>
              <tr>
                <td > <?php echo remove_junk($clienteMaq['razon_social']); ?></td>
                <td class="text-center" > <?php echo remove_junk($clienteMaq['cuit']), " (",remove_junk($ivaCond['descripcion']),")"; ?></td>
                
                <td class="text-center" > <?php echo remove_junk($clienteMaq['localidad']); ?>
                  <?php 
                    foreach ($all_provincias as $prov2): 
                      if($prov2['id_provincia'] == $clienteMaq['provincia']){
                        $ban = $prov2['nombre'];
                        break;
                      }
                    endforeach;
                    echo remove_junk(' - '.$ban);
                  ?>
                </td>
                <td class="text-center" > <?php if ($clienteMaq['direccion'] === NULL || $clienteMaq['direccion'] === ''){
                  echo '-'; } else {
                    echo remove_junk($clienteMaq['direccion']); } ?></td>
                  
                


                
                <td class="text-center" > 
                  <?php 
                    if($clienteMaq['email'] === NULL || $clienteMaq['email'] === ''){ echo '-'; }
                    else { echo remove_junk(ucfirst($clienteMaq['email'])); }
                  ?>
                </td>
                <td class="text-center" > 
                  <?php 
                    if($clienteMaq['tel'] === NULL || $clienteMaq['tel'] === ''){ echo '-'; }
                    else { echo remove_junk(ucfirst($clienteMaq['tel'])); }
                  ?>
                </td>
                <td class="text-center" > 
                  <?php 
                    if($clienteMaq['cel'] === NULL || $clienteMaq['cel'] === ''){ echo '-'; }
                    else { echo remove_junk(ucfirst($clienteMaq['cel'])); }
                  ?>
                </td>
                <td class="text-center" >
                  <div class="btn-group">
                    <a href="edit_clienteMaq.php?id=<?php echo (int)$clienteMaq['id'];?>" name="btn_editar_<?php echo (int)$clienteMaq['id'];?>" id="btn_editar_<?php echo (int)$clienteMaq['id'];?>" class="btn btn-info btn-xs"  title="Editar" data-toggle="tooltip"><span class="glyphicon glyphicon-edit"></span>
                    </a>                    
                    <a href="clienteMaquinaMaquinas.php?id=<?php echo (int)$clienteMaq['id'];?>" class="btn btn-xs btn-warning"  title="Ver máquinas del cliente" data-toggle="tooltip" onclick="return confirm('¿Seguro que desea editar las máquinas de éste cliente?')"><span class="glyphicon glyphicon-th-list"></span>
                    </a>
                    <a href="delete_clienteMaq.php?id=<?php echo (int)$clienteMaq['id'];?>" class="btn btn-xs btn-danger"  title="Eliminar" data-toggle="tooltip" onclick="return confirm('¿Seguro que desea eliminar este cliente?')"><span class="glyphicon glyphicon-trash"></span>
                    </a>                 
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>

        <script>
          $('#clientesMaq').DataTable({ "order": [[ 0, "asc" ],[ 3, "asc" ]] });
          $('.dataTables_length').addClass('bs-select');
        </script>
      </div>
    </div>
  </div>

  <?php include_once('layouts/footer.php'); ?>
