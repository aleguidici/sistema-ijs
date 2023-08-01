<?php
  $page_title = 'Agregar Cliente - Máquina';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);

   $cuit = remove_junk($db->escape($_POST['cuit']));
   $cond_IVA = remove_junk($db->escape($_POST['condicion_iva']));
   $razon_social = remove_junk($db->escape($_POST['razon_social']));
   $razon_social = strtoupper($razon_social);
   $direccion = remove_junk($db->escape($_POST['direccion']));
   $localidad = remove_junk($db->escape($_POST['localidad']));
   $localidad = ucfirst(strtolower($localidad));
   $provincia = remove_junk($db->escape($_POST['provin']));
   $cp = remove_junk($db->escape($_POST['codigo_postal']));
   $tel = remove_junk($db->escape($_POST['telefono']));
   $cel = remove_junk($db->escape($_POST['celular']));
   $email = remove_junk($db->escape($_POST['email']));

 
   $cuitExiste = find_by_cuit('clientemaquina', $cuit);

   if ($cuitExiste) {
    echo $result = 2;
    $session->msg("w", "Error. Al parecer ya existe un cliente con el mismo número de DNI o CUIT, por favor verifique e intente nuevamente.");
    //redirect('conAc_agregarMaquinaCli.php',false);
   } else {
    $query  = "INSERT INTO clientemaquina (`cuit`, `razon_social`, `direccion`, `localidad`, `provincia`, `cp`, `email`, `tel` , `cel`, `iva`) VALUES ('{$cuit}', '{$razon_social}', '{$direccion}', '{$localidad}', '{$provincia}', '{$cp}', '{$email}', '{$tel}', '{$cel}', '{$cond_IVA}')";
    $result = $db->query($query);
      if ($result == 1) {        
        $session->msg("s", "Cliente agregado exitosamente.");
        echo $result.".".$db->insert_id(); 
      } else {
        $session->msg("d", "Lo siento, el registro falló.");
        echo $result;
      }    
   }
 //}

?>



  
