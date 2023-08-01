<?php
  $page_title = 'Cotizador de productos WEG';
  require_once('includes/load.php');
  page_require_level(2);
  $all_productos = find_all('weg_lista_productos');
  $all_porcentajes = find_porcentaje('1');
  $tabla = "";
?>

<?php
  
  if(isset($_POST['guardar1'])){
    $v1 = str_replace(',', '.',remove_junk($db->escape($_POST['desc'])));
    $v2 = str_replace(',', '.',remove_junk($db->escape($_POST['tran'])));
    $v3 = str_replace(',', '.',remove_junk($db->escape($_POST['ing_br'])));
    $v4 = str_replace(',', '.',remove_junk($db->escape($_POST['gas_adm'])));
    $v5 = str_replace(',', '.',remove_junk($db->escape($_POST['ganan'])));
    $v6 = str_replace(',', '.',remove_junk($db->escape($_POST['imp_ganan'])));
    $v7 = str_replace(',', '.',remove_junk($db->escape($_POST['alic_iva'])));

    if(empty($errors)){
      $sql  = "UPDATE prov_porcentajes SET descuento='{$v1}', transporte='{$v2}', ing_bruto='{$v3}', gastos_adm='{$v4}', ganancia='{$v5}', imp_ganancia='{$v6}', iva='{$v7}' WHERE id = '1'";
      
      if($db->query($sql)){
        $session->msg("s", "Porcentajes actualizados exitosamente.");
        redirect('weg_cotizador.php',false);
      } else {
        $session->msg("d", "Lo siento, la actualización falló");
        redirect('actualizar_porcentajes.php',false);
      }
    } else {
      $session->msg("d", $errors);
      redirect('actualizar_porcentajes.php',false);
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
          <form method="post" action="" id="form1">
            <h4><b><u>Porcentajes:</u></b></h4>
            
            <div class="row">
              <div class="col-md-2">
                <label for="prod" class="control-label">Descuento:</label>
              </div>
              <div class="col-md-3">
                <input type="text" class="form-control" placeholder="Desc." id="desc" name="desc" value="<?php echo str_replace('.', ',',number_format($all_porcentajes[0][2],2));?>" onchange="javascript:descue()"></input>
              </div>
            </div>
            <script>
              function descue(){
                var descu = document.getElementById("desc").value;
                descu = descu.replace(",", ".");
                descu = parseFloat(descu).toFixed(2);
                if (descu == "NaN")
                  descu = 0;
                desc.value = descu.replace(".", ",");
              };
            </script>
            <br>

            <div class="row">
              <div class="col-md-2">
                <label for="prod" class="control-label">Transporte:</label>
              </div>
              <div class="col-md-3">
                <input type="text" class="form-control" placeholder="Transp." id="tran" name="tran" required value="<?php echo str_replace('.', ',',number_format($all_porcentajes[0][3],2));?>" onchange="javascript:transp()"></input>
              </div>
            </div>
            <script>
              function transp(){
                var trans = document.getElementById("tran").value;
                trans = trans.replace(",", ".");
                trans = parseFloat(trans).toFixed(2);
                if (trans == "NaN")
                  trans = 0;
                tran.value = trans.replace(".", ",");
              };
            </script>
            <br>

            <div class="row">
              <div class="col-md-2">
                <label for="prod" class="control-label">Ingreso bruto:</label>
              </div>
              <div class="col-md-3">
                <input type="text" class="form-control" placeholder="Ing. bruto" id="ing_br" name="ing_br" required value="<?php echo str_replace('.', ',',number_format($all_porcentajes[0][4],2));?>" onchange="javascript:ing_bruto()"></input>
              </div>
            </div>
            <script>
              function ing_bruto(){
                var ing_bru = document.getElementById("ing_br").value;
                ing_bru = ing_bru.replace(",", ".");
                ing_bru = parseFloat(ing_bru).toFixed(2);
                if (ing_bru == "NaN")
                  ing_bru = 0;
                ing_br.value = ing_bru.replace(".", ",");
              };
            </script>
            <br>

            <div class="row">
              <div class="col-md-2">
                <label for="prod" class="control-label">Gastos administ.:</label>
              </div>
              <div class="col-md-3">
                <input type="text" class="form-control" placeholder="Gastos adm." id="gas_adm" name="gas_adm" required value="<?php echo str_replace('.', ',',number_format($all_porcentajes[0][5],2));?>" onchange="javascript:gas_admin()"></input>
              </div>
            </div>
            <script>
              function gas_admin(){
                var gas_admi = document.getElementById("gas_adm").value;
                gas_admi = gas_admi.replace(",", ".");
                gas_admi = parseFloat(gas_admi).toFixed(2);
                if (gas_admi == "NaN")
                  gas_admi = 0;
                gas_adm.value = gas_admi.replace(".", ",");
              };
            </script>
            <br>

            <div class="row">
              <div class="col-md-2">
                <label for="prod" class="control-label">Ganancia:</label>
              </div>
              <div class="col-md-3">
                <input type="text" class="form-control" placeholder="Ganancia" id="ganan" name="ganan" required value="<?php echo str_replace('.', ',',number_format($all_porcentajes[0][6],2));?>" onchange="javascript:ganancias()"></input>
              </div>
            </div>
            <script>
              function ganancias(){
                var gananc = document.getElementById("ganan").value;
                gananc = gananc.replace(",", ".");
                gananc = parseFloat(gananc).toFixed(2);
                if (gananc == "NaN")
                  gananc = 0;
                ganan.value = gananc.replace(".", ",");
              };
            </script>
            <br>

            <div class="row">
              <div class="col-md-2">
                <label for="alic_iva" class="control-label">Alícuota IVA:</label>
              </div>
              <div class="col-md-3">
                <input type="text" class="form-control" placeholder="Alícuota IVA" id="alic_iva" name="alic_iva" required value="<?php echo str_replace('.', ',',number_format($all_porcentajes[0][8],2));?>" onchange="javascript:alicuota()"></input>
              </div>
            </div>
            <script>
              function alicuota(){
                var alicuota_iva = document.getElementById("alic_iva").value;
                alicuota_iva = alicuota_iva.replace(",", ".");
                alicuota_iva = parseFloat(alicuota_iva).toFixed(2);
                if (alicuota_iva == "NaN")
                  alicuota_iva = 0;
                alic_iva.value = alicuota_iva.replace(".", ",");
              };
            </script>
            <br>

            <div class="row">
              <div class="col-md-2">
                <label for="prod" class="control-label">Imp. Ganancia:</label>
              </div>
              <div class="col-md-3">
                <input type="text" class="form-control" placeholder="Imp. Ganancia" id="imp_ganan" name="imp_ganan" required value="<?php echo str_replace('.', ',',number_format($all_porcentajes[0][7],2));?>" onchange="javascript:imp_ganancias()"></input>
              </div>
              <div class="col-md-2" align="right">
                <button type="submit" class="btn btn-success" name="guardar1" id="guardar1">Actualizar</button>
              </div>
            </div>
            <script>
              function imp_ganancias(){
                var imp_gananci = document.getElementById("imp_ganan").value;
                imp_gananci = imp_gananci.replace(",", ".");
                imp_gananci = parseFloat(imp_gananci).toFixed(2);
                if (imp_gananci == "NaN")
                  imp_gananci = 0;
                imp_ganan.value = imp_gananci.replace(".", ",");
              };
            </script>
            <br>



            <div class="row">
              <!-- <div class="col-md-2">
                <label for="prod" class="control-label"> - </label>
              </div>
              <div class="col-md-2">
                <label for="prod" class="control-label"> - </label>
              </div> -->
            </div>

            <div class="row">
              <!-- <div class="col-md-2">
                <input type="text" class="form-control" placeholder="Desc." id="desc" name="desc" value="43.19" onchange="javascript:descue()"></input>
              </div>
              <script>
                function descue(){
                  var descu = document.getElementById("desc").value;
                  descu = descu.replace(",", ".");
                  descu = parseFloat(descu).toFixed(2);
                  if (descu == "NaN")
                    descu = 0;
                  desc.value = descu;

                  actualizarTabla();
                  calcular_totales();
                };
              </script>

              <div class="col-md-2">
                <input type="text" class="form-control" placeholder="Desc." id="desc" name="desc" value="43.19" onchange="javascript:descue()"></input>
              </div>
              <script>
                function descue(){
                  var descu = document.getElementById("desc").value;
                  descu = descu.replace(",", ".");
                  descu = parseFloat(descu).toFixed(2);
                  if (descu == "NaN")
                    descu = 0;
                  desc.value = descu;

                  actualizarTabla();
                  calcular_totales();
                };
              </script> -->
            </div> 
          </form>
        </div>

<?php include_once('layouts/footer.php'); ?>