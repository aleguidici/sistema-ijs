<?php
  require_once('includes/load.php');

/*--------------------------------------------------------------*/
/* Function for find all database table rows by table name
/*--------------------------------------------------------------*/
function find_all($table) {
   global $db;
   if(tableExists($table))
   {
     return find_by_sql("SELECT * FROM ".$db->escape($table));
   }
}
function findModeloExiste($table,$idMarca,$idTipo,$codigo) {
   global $db;
   if(tableExists($table))
   {
     return find_by_sql("SELECT * FROM {$db->escape($table)} WHERE marca_id = {$db->escape($idMarca)} AND tipo_id = {$db->escape($idTipo)} AND codigo = '{$db->escape($codigo)}'");
   }
}

function find_all_modelo_order($table) {
  global $db;
  if (tableExists($table))
  {
    return find_by_sql("SELECT maquina_modelo.id, codigo, maquina_modelo.descripcion, inalambrico, anio, tipo_id,tamanio_id, marca_id FROM {$db->escape($table)} INNER JOIN maquina_tipo on maquina_modelo.tipo_id = maquina_tipo.id WHERE 1 ORDER BY maquina_tipo.descripcion ASC ");
  }
}

function find_all_tipos($table) {
   global $db;
   if(tableExists($table))
   {
     return find_by_sql("SELECT * FROM {$db->escape($table)} ORDER BY 2");
   }
}

function findProveedor2($idRepa,$idRepue){
   global $db;
   return find_by_sql("SELECT * FROM reparacion_repuesto WHERE reparacion_id = {$db->escape($idRepa)} AND repuesto_id = {$db->escape($idRepue)} AND id_proveedor = 2");
  }

 function findElegido($idRepa,$idRepue,$elegido){
   global $db;
   return find_by_sql("SELECT * FROM reparacion_repuesto WHERE reparacion_id = {$db->escape($idRepa)} AND repuesto_id = {$db->escape($idRepue)} AND elegido = 1");
  } 

function findProveedor1($idRepa,$idRepue){
   global $db;
   return find_by_sql("SELECT * FROM reparacion_repuesto WHERE reparacion_id = {$db->escape($idRepa)} AND repuesto_id = {$db->escape($idRepue)} AND id_proveedor = 1");
  }


function findRepaExist($idRepa,$idRepue){
  global $db;
  return find_by_sql("SELECT * FROM reparacion_repuesto WHERE reparacion_id = {$db->escape($idRepa)} AND repuesto_id = {$db->escape($idRepue)}");
}

function find_all_proveedores() {
   global $db;
   return find_by_sql("SELECT * FROM proveedor WHERE id <> 56 AND id <> 57");
}
function find_all_proveedores_me() {
   global $db;
   return find_by_sql("SELECT * FROM proveedormaquina WHERE id <> 1 AND id <> 2");
}

function find_all_maquinas_sin_reparacion_activa() {
global $db;
  return find_by_sql("SELECT * FROM maquina WHERE id NOT IN (SELECT id_maquina FROM reparacion_maquina WHERE id_estado <> 8)");
} 

function find_all_imagenes($tipo) {
  global $db;
  return find_by_sql("SELECT * FROM imagen WHERE tipo = {$db->escape($tipo)} ORDER BY id DESC");
}

function find_materiales_proyecto($id)
{
  global $db;
  return find_by_sql("SELECT * FROM proy_mats WHERE id_proyecto= {$db->escape($id)}");
}

function find_materiales_dispobibles()
{
  global $db;
  return find_by_sql("SELECT * FROM inv_materiales_insumos WHERE cant_disp>0");
}

function find_personal_proyecto($id)
{
  global $db;
  return find_by_sql("SELECT * FROM proy_personalafectado WHERE id_proyecto = {$db->escape($id)}");
}

function find_personal_noAfectado($id)
{
  global $db;
  return find_by_sql("SELECT * FROM personal WHERE id NOT IN (SELECT id_personal FROM proy_personalafectado WHERE id_proyecto = {$db->escape($id)})  AND tercero = 0 AND baja = 0 ORDER BY apellido");
}

function find_actividades_diarias_personal($idProyecto, $idPersonal)
{
  global $db;
  return find_by_sql("SELECT * FROM proy_actdiaria_persafect WHERE id_actDiaria IN (SELECT id FROM proy_actividades_diarias WHERE id_proyecto = {$db->escape($idProyecto)}) AND id_pers = {$db->escape($idPersonal)}");
}

function find_actividades_diarias($idProyecto)
{
  global $db;
  return find_by_sql("SELECT * FROM proy_actividades_diarias WHERE id_proyecto = {$db->escape($idProyecto)}");
}

function find_actividades_diarias_segun_actividad_estatica($idActividadEstatica)
{
  global $db;
  return find_by_sql("SELECT * FROM proy_actividades_diarias WHERE id_actividad_estatica = {$db->escape($idActividadEstatica)}");
}

function find_porcentaje($id) {
   global $db;
   {
     return find_by_sql("SELECT * FROM prov_porcentajes WHERE id = {$db->escape($id)}");
   }
}

function find_prod($cod)
{
  global $db;
  $sql = $db->query("SELECT * FROM weg_lista_productos WHERE codigo='{$db->escape($cod)}' LIMIT 1");
  if($result = $db->fetch_assoc($sql))
    return $result;
  else
    return null;
}

function find_datos_imagen($id_proy, $id_imagen)
{
  global $db;
  $idPr = (int)$id_proy;
  $idIm = (int)$id_imagen;
  $sql = $db->query("SELECT * FROM proy_imagen WHERE id_proyecto='{$db->escape($idPr)}' AND id_imagen='{$db->escape($idIm)}'");
  if($result = $db->fetch_assoc($sql))
    return $result;
  else
    return null;
}

function find_last_medicion($table) {
   global $db;
   if(tableExists($table))
   {
    $sql = $db->query("SELECT * FROM {$db->escape($table)} ORDER BY id_medicion DESC LIMIT 1");
      if($result = $db->fetch_assoc($sql))
        return $result;
      else
        return null;
   }
}

function find_last($table) {
   global $db;
   if(tableExists($table))
   {
    $sql = $db->query("SELECT * FROM {$db->escape($table)} ORDER BY id DESC LIMIT 1");
      if($result = $db->fetch_assoc($sql))
        return $result;
      else
        return null;
   }
}

function find_imagen($id) {
  global $db;
  $sql = $db->query("SELECT * FROM imagen WHERE id='{$db->escape($id)}'");
  if($result = $db->fetch_assoc($sql))
    return $result;
  else
    return null;
}

function find_all_matriculados($table) {
   global $db;
   if(tableExists($table))
   {
     return find_by_sql("SELECT * FROM {$db->escape($table)}");
   }
}

function find_personal_actividad_diaria($id) {
  global $db;
  return find_by_sql("SELECT * FROM proy_actdiaria_persafect WHERE id_actDiaria = {$db->escape($id)}");
}

function find_matriculas_by_personal($id) {
  global $db;
  return find_by_sql("SELECT * FROM personal_matriculas WHERE id_personal = {$db->escape($id)}");
}

function find_mediciones_by_suc($table,$num)
{
  global $db;
  $id = (int)$num;
    // $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE nro_instrumento='{$db->escape($id)}' LIMIT 1");
    // return $sql;
    if(tableExists($table)){
          $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE num_suc='{$db->escape($id)}'");
          if($result = $db->fetch_assoc($sql))
            return $result;
          else
            return null;
      }
}

function find_maquinas_by_cliente($id)
{
  global $db;
  $id = (int)$id;
  $sql = $db->query("SELECT * FROM maquina WHERE id_cliente='{$db->escape($id)}'");
  if($result = $db->fetch_assoc($sql))
    return $result;
  else
    return null;
}

function find_matInsu_ajeno_by_proyecto($id_proy, $id_matInsu)
{
  global $db;
  $idP = (int)$id_proy;
  $idMI = (int)$id_matInsu;
  $sql = $db->query("SELECT * FROM proy_mats WHERE id_proyecto='{$db->escape($idP)}' AND id_materiales='{$db->escape($idMI)}' AND material_IJS=0");
  if($result = $db->fetch_assoc($sql))
    return $result;
  else
    return null;
}

function find_matInsu_IJS_by_proyecto($id_proy, $id_matInsu)
{
  global $db;
  $idP = (int)$id_proy;
  $idMI = (int)$id_matInsu;
  $sql = $db->query("SELECT * FROM proy_mats WHERE id_proyecto='{$db->escape($idP)}' AND id_materiales='{$db->escape($idMI)}' AND material_IJS=1");
  if($result = $db->fetch_assoc($sql))
    return $result;
  else
    return null;
}

function find_ordenes_compra_by_prov($table,$num)
{
  global $db;
  $id = (int)$num;
    if(tableExists($table)){
          $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE id_proveedor='{$db->escape($id)}'");
          if($result = $db->fetch_assoc($sql))
            return $result;
          else
            return null;
     }
}

function find_mediciones_by_inst($table,$num)
{
  global $db;
  $id = (int)$num;
    // $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE nro_instrumento='{$db->escape($id)}' LIMIT 1");
    // return $sql;
    if(tableExists($table)){
          $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE nro_instrumento='{$db->escape($id)}'");
          if($result = $db->fetch_assoc($sql))
            return $result;
          else
            return null;
     }
}

function find_mediciones_by_tec($table,$num)
{
  global $db;
  $id = (int)$num;
    // $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE nro_instrumento='{$db->escape($id)}' LIMIT 1");
    // return $sql;
    if(tableExists($table)){
          $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE id_tecnico='{$db->escape($id)}'");
          if($result = $db->fetch_assoc($sql))
            return $result;
          else
            return null;
     }
}

function find_mediciones_by_prof($table,$num)
{
  global $db;
  $id = (int)$num;
    // $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE nro_instrumento='{$db->escape($id)}' LIMIT 1");
    // return $sql;
    if(tableExists($table)){
          $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE id_profesional='{$db->escape($id)}'");
          if($result = $db->fetch_assoc($sql))
            return $result;
          else
            return null;
     }
}
/*--------------------------------------------------------------*/
/* Function for Perform queries
/*--------------------------------------------------------------*/
function find_by_sql($sql)
{
  global $db;
  $result = $db->query($sql);
  $result_set = $db->while_loop($result);
 return $result_set;
}
/*--------------------------------------------------------------*/
/*  Function for Find data from table by id
/*--------------------------------------------------------------*/
function find_by_id($table,$id)
{
  global $db;
  $id = (int)$id;
  if(tableExists($table)){
        $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE id='{$db->escape($id)}' LIMIT 1");
        if($result = $db->fetch_assoc($sql))
          return $result;
        else
          return null;
   }
}

function find_by_cuit($table,$cuit)
{
  global $db;
  if(tableExists($table)){
        $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE cuit='{$db->escape($cuit)}' LIMIT 1");
        if($result = $db->fetch_assoc($sql))
          return $result;
        else
          return null;
   }
}

function find_all_id_modelo_repuesto($table,$id)
{
  global $db;
  return find_by_sql("SELECT * FROM {$db->escape($table)} WHERE id_modelo = {$db->escape($id)} ORDER BY codigo");
}

function find_all_maquinas_cliente($table,$id)
{
  global $db;
  return find_by_sql("SELECT * FROM {$db->escape($table)} WHERE id_cliente = {$db->escape($id)} ORDER BY id ASC");
}

function find_all_reparacion_id_reparacion_repuesto($table,$id)
{
  global $db;
  return find_by_sql("SELECT * FROM {$db->escape($table)} WHERE reparacion_id = {$db->escape($id)} ORDER BY reparacion_id ASC, repuesto_id ASC");
}


function find_all_proveedores_disponibles_a_reparacion($idMarc,$idRepar,$idRepue)
{
  global $db;
  return find_by_sql("SELECT * FROM proveedormaquina P INNER JOIN  maquina_marca_prov M ON P.id = M.id_proveedor AND M.id_maquina_marca = '{$db->escape($idMarc)}' LEFT JOIN reparacion_repuesto R ON P.id = R.id_proveedor AND R.reparacion_id = '{$db->escape($idRepar)}' AND R.repuesto_id = '{$db->escape($idRepue)}' WHERE R.id_proveedor IS NULL"); 
}

function find_all_proveedores_disponibles_a_reparacion2($table,$idProvee)
{
  global $db;
  return find_by_sql("SELECT * FROM {$db->escape($table)} WHERE reparacion_id = 1 AND repuesto_id = 1 AND id_proveedor <> '{$db->escape($idProvee)}'"); 
}


function find_by_id_maquina_marca_prov($table,$id)
{
  global $db;
  return find_by_sql("SELECT * FROM {$db->escape($table)} WHERE id_maquina_marca = '{$db->escape($id)}' OR id_maquina_marca = 37");
}
   function find_by_id_proveedor_maquina_marca_prov($table,$id)
  {
  global $db;
  //$id = (int)$id;
  return find_by_sql("SELECT * FROM {$db->escape($table)} WHERE id_proveedor = '{$db->escape($id)}'");
   }


function find_by_id_reparacion($table,$id)
{
  global $db;
  $id = (int)$id;
  if(tableExists($table)){
        $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE reparacion_id='{$db->escape($id)}' LIMIT 1");
        if($result = $db->fetch_assoc($sql))
          return $result;
        else
          return null;
   }
}

function find_last_by_id($table) {
   global $db;
   if(tableExists($table))
   {
    $sql = $db->query("SELECT * FROM {$db->escape($table)} ORDER BY id DESC LIMIT 1");
      if($result = $db->fetch_assoc($sql))
        return $result;
      else
        return null;
   }
}

function find_last_historial($id) {
  global $db;
  $id = (int)$id;
  $sql = $db->query("SELECT * FROM inv_maq_historial WHERE id_maq='{$db->escape($id)}' ORDER BY id DESC LIMIT 1");
  if($result = $db->fetch_assoc($sql))
    return $result;
  else
    return null;
}

function find_prov_by_pais($id)
{
  global $db;
  $id = (int)$id;
  return find_by_sql("SELECT * FROM provincia WHERE pais=".$db->escape($id));
}

function find_actEstat_by_proy($id)
{
  global $db;
  $id = (int)$id;
  return find_by_sql("SELECT * FROM proy_actividades_estaticas WHERE id_proy='{$db->escape($id)}'");
}

function find_medicion_by_id($table,$id)
{
  global $db;
  $id = (int)$id;
    if(tableExists($table)){
          $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE id_medicion='{$db->escape($id)}' LIMIT 1");
          if($result = $db->fetch_assoc($sql))
            return $result;
          else
            return null;
     }
}
/*--------------------------------------------------------------*/
/*  Function for Find data from table by num_ins
/*--------------------------------------------------------------*/
function find_by_num_ins($table,$num)
{
  global $db;
  $id = (int)$num;
    // $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE nro_instrumento='{$db->escape($id)}' LIMIT 1");
    // return $sql;
    if(tableExists($table)){
          $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE nro_instrumento='{$db->escape($id)}' LIMIT 1");
          if($result = $db->fetch_assoc($sql))
            return $result;
          else
            return null;
     }
}
/*--------------------------------------------------------------*/
/*  Function for Find data from table by num_ins
/*--------------------------------------------------------------*/
function find_by_num_matInsu($table,$num)
{
  global $db;
  $id = (int)$num;
    // $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE nro_instrumento='{$db->escape($id)}' LIMIT 1");
    // return $sql;
    if(tableExists($table)){
          $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE id='{$db->escape($id)}' LIMIT 1");
          if($result = $db->fetch_assoc($sql))
            return $result;
          else
            return null;
     }
}
/*--------------------------------------------------------------*/
/*  Function for Find data from table by dni
/*--------------------------------------------------------------*/
function find_by_num_dni($table,$num)
{
  global $db;
  $id = (int)$num;
    // $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE nro_instrumento='{$db->escape($id)}' LIMIT 1");
    // return $sql;
    if(tableExists($table)){
          $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE dni='{$db->escape($id)}' LIMIT 1");
          if($result = $db->fetch_assoc($sql))
            return $result;
          else
            return null;
     }
}
/*--------------------------------------------------------------*/
/*  Function for Find data from table by num_ins
/*--------------------------------------------------------------*/
function find_by_id_suc($table,$num)
{
  global $db;
  $id = (int)$num;
    // $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE nro_instrumento='{$db->escape($id)}' LIMIT 1");
    // return $sql;
    if(tableExists($table)){
          $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE id='{$db->escape($id)}' LIMIT 1");
          if($result = $db->fetch_assoc($sql))
            return $result;
          else
            return null;
     }
}

function find_detalles4_by_id($table,$num)
{
  global $db;
  $id = (int)$num;
   if(tableExists($table))
   {
     return find_by_sql("SELECT * FROM {$db->escape($table)} WHERE id_movimiento='{$db->escape($id)}'");
   }
}

function find_by_id_instrumento($table,$num)
{
  global $db;
  $id = (int)$num;
    // $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE nro_instrumento='{$db->escape($id)}' LIMIT 1");
    // return $sql;
    if(tableExists($table)){
          $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE nro_instrumento='{$db->escape($id)}' LIMIT 1");
          if($result = $db->fetch_assoc($sql))
            return $result;
          else
            return null;
     }
}

function find_by_id_prov($table,$num)
{
  global $db;
  $id = (int)$num;
    // $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE nro_instrumento='{$db->escape($id)}' LIMIT 1");
    // return $sql;
    if(tableExists($table)){
          $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE id_provincia='{$db->escape($id)}'");
          if($result = $db->fetch_assoc($sql))
            return $result;
          else
            return null;
     }
}

function find_by_id_cli($table,$num)
{
  global $db;
  $id = (int)$num;
    // $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE nro_instrumento='{$db->escape($id)}' LIMIT 1");
    // return $sql;
    if(tableExists($table)){
          $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE id_cliente='{$db->escape($id)}'");
          if($result = $db->fetch_assoc($sql))
            return $result;
          else
            return null;
     }
}

function find_detalles_by_id($table,$num)
{
  global $db;
  $id = (int)$num;
   if(tableExists($table))
   {
     return find_by_sql("SELECT * FROM {$db->escape($table)} WHERE id_medicion='{$db->escape($id)}'");
   }
}

function find_historial_maquin_by_id($table,$id)
{
  global $db;
  $id = (int)$id;
   if(tableExists($table))
   {
     return find_by_sql("SELECT * FROM {$db->escape($table)} WHERE id_maq='{$db->escape($id)}'");
   }
}

function find_detalles2_by_id($table,$num)
{
  global $db;
  $id = (int)$num;
   if(tableExists($table))
   {
     return find_by_sql("SELECT * FROM {$db->escape($table)} WHERE id_orden='{$db->escape($id)}'");
   }
}

function find_detalles3_by_id($table,$num)
{
  global $db;
  $id = (int)$num;
   if(tableExists($table))
   {
     return find_by_sql("SELECT * FROM {$db->escape($table)} WHERE id_presupuesto='{$db->escape($id)}'");
   }
}

function find_by_id_personal($table,$num)
{
  global $db;
  $id = (int)$num;
    // $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE nro_instrumento='{$db->escape($id)}' LIMIT 1");
    // return $sql;
    if(tableExists($table)){
          $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE id='{$db->escape($id)}'");
          if($result = $db->fetch_assoc($sql))
            return $result;
          else
            return null;
     }
}
// /*--------------------------------------------------------------*/
// /*  Function for Find data from table by num_suc
// /*--------------------------------------------------------------*/
// function find_by_num_suc($table,$num_suc)
// {
//   global $db;
//   $id = (int)$num_suc;
//     if(tableExists($table)){
//           $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE num_suc='{$db->escape($id)}' LIMIT 1");
//           if($result = $db->fetch_assoc($sql))
//             return $result;
//           else
//             return null;
//      }
// }
/*--------------------------------------------------------------*/
/* Function for Delete data from table by id
/*--------------------------------------------------------------*/
function delete_by_id($table,$id)
{
  global $db;
  if(tableExists($table))
   {
    $sql = "DELETE FROM ".$db->escape($table);
    $sql .= " WHERE id=". $db->escape($id);
    $sql .= " LIMIT 1";
    $db->query($sql);
    return ($db->affected_rows() === 1) ? true : false;
   }
}
/*--------------------------------------------------------------*/
/* Function for Delete data from table by numero instrumento
/*--------------------------------------------------------------*/
function delete_by_num_ins($table,$num)
{
  global $db;
  if(tableExists($table))
   {
    //$sql = "DELETE FROM '$table' WHERE nro_instrumento='$num'";
    $sql = "DELETE FROM ".$db->escape($table);
    $sql .= " WHERE nro_instrumento=". $db->escape($num);
    $sql .= " LIMIT 1";
    $db->query($sql);
    return ($db->affected_rows() === 1) ? true : false;
   }
}
/*--------------------------------------------------------------*/
/* Function for Delete data from table by numero instrumento
/*--------------------------------------------------------------*/
function delete_by_num_matInsu($table,$num)
{
  global $db;
  if(tableExists($table))
   {
    //$sql = "DELETE FROM '$table' WHERE nro_instrumento='$num'";
    $sql = "DELETE FROM ".$db->escape($table);
    $sql .= " WHERE id=". $db->escape($num);
    $sql .= " LIMIT 1";
    $db->query($sql);
    return ($db->affected_rows() === 1) ? true : false;
   }
}
/*--------------------------------------------------------------*/
/* Function for Delete data from table by dni
/*--------------------------------------------------------------*/
function delete_by_num_dni($table,$num)
{
  global $db;
  if(tableExists($table))
   {
    //$sql = "DELETE FROM '$table' WHERE nro_instrumento='$num'";
    $sql = "DELETE FROM ".$db->escape($table);
    $sql .= " WHERE dni=". $db->escape($num);
    $sql .= " LIMIT 1";
    $db->query($sql);
    return ($db->affected_rows() === 1) ? true : false;
   }
}
/*--------------------------------------------------------------*/
/* Function for Delete data from table by num_suc
/*--------------------------------------------------------------*/
function delete_by_id_suc($table,$num_suc)
{
  global $db;
  if(tableExists($table))
   {
    $sql = "DELETE FROM ".$db->escape($table);
    $sql .= " WHERE id=". $db->escape($num_suc);
    $db->query($sql);
    return ($db->affected_rows() === 1) ? true : false;
   }
}

function delete_img_proyecto($proy,$img)
{
  global $db;
  $sql = "DELETE FROM proy_imagen WHERE id_proyecto='{$db->escape($proy)}' AND id_imagen='{$db->escape($img)}'";
  $db->query($sql);
  return ($db->affected_rows() === 1) ? true : false;
}
/*--------------------------------------------------------------*/
/*--------------------------------------------------------------*/
/* Function for Count id  By table name
/*--------------------------------------------------------------*/

function count_by_id($table){
  global $db;
  if(tableExists($table))
  {
    $sql    = "SELECT COUNT(id) AS total FROM ".$db->escape($table);
    $result = $db->query($sql);
     return($db->fetch_assoc($result));
  }
}
/*--------------------------------------------------------------*/
/* Determine if database table exists
/*--------------------------------------------------------------*/
function tableExists($table){
  global $db;
  $table_exit = $db->query('SHOW TABLES FROM '.DB_NAME.' LIKE "'.$db->escape($table).'"');
      if($table_exit) {
        if($db->num_rows($table_exit) > 0)
              return true;
         else
              return false;
      }
  }
 /*--------------------------------------------------------------*/
 /* Login with the data provided in $_POST,
 /* coming from the login form.
/*--------------------------------------------------------------*/
  function authenticate($username='', $password='') {
    global $db;
    $username = $db->escape($username);
    $password = $db->escape($password);
    $sql  = sprintf("SELECT id,username,password,user_level FROM users WHERE username ='%s' LIMIT 1", $username);
    $result = $db->query($sql);
    if($db->num_rows($result)){
      $user = $db->fetch_assoc($result);
      $password_request = sha1($password);
      if($password_request === $user['password'] ){
        return $user['id'];
      }
    }
   return false;
  }
  /*--------------------------------------------------------------*/
  /* Login with the data provided in $_POST,
  /* coming from the login_v2.php form.
  /* If you used this method then remove authenticate function.
 /*--------------------------------------------------------------*/
   function authenticate_v2($username='', $password='') {
     global $db;
     $username = $db->escape($username);
     $password = $db->escape($password);
     $sql  = sprintf("SELECT id,username,password,user_level FROM users WHERE username ='%s' LIMIT 1", $username);
     $result = $db->query($sql);
     if($db->num_rows($result)){
       $user = $db->fetch_assoc($result);
       $password_request = sha1($password);
       if($password_request === $user['password'] ){
         return $user;
       }
     }
    return false;
   }


  /*--------------------------------------------------------------*/
  /* Find current log in user by session id
  /*--------------------------------------------------------------*/
  function current_user(){
      static $current_user;
      global $db;
      if(!$current_user){
         if(isset($_SESSION['user_id'])):
             $user_id = intval($_SESSION['user_id']);
             $current_user = find_by_id('users',$user_id);
        endif;
      }
    return $current_user;
  }
  /*--------------------------------------------------------------*/
  /* Find all user by
  /* Joining users table and user gropus table
  /*--------------------------------------------------------------*/
  function find_all_user(){
      global $db;
      $results = array();
      $sql = "SELECT u.id,u.name,u.username,u.user_level,u.status,u.last_login,";
      $sql .="g.group_name ";
      $sql .="FROM users u ";
      $sql .="LEFT JOIN user_groups g ";
      $sql .="ON g.group_level=u.user_level ORDER BY u.name ASC";
      $result = find_by_sql($sql);
      return $result;
  }
  /*--------------------------------------------------------------*/
  /* Function to update the last log in of a user
  /*--------------------------------------------------------------*/

 function updateLastLogIn($user_id)
  {
    global $db;
    $date = make_date();
    $sql = "UPDATE users SET last_login='{$date}' WHERE id ='{$user_id}' LIMIT 1";
    $result = $db->query($sql);
    return ($result && $db->affected_rows() === 1 ? true : false);
  }

  /*--------------------------------------------------------------*/
  /* Find all Group name
  /*--------------------------------------------------------------*/
  function find_by_groupName($val)
  {
    global $db;
    $sql = "SELECT group_name FROM user_groups WHERE group_name = '{$db->escape($val)}' LIMIT 1 ";
    $result = $db->query($sql);
    return($db->num_rows($result) === 0 ? true : false);
  }
  /*--------------------------------------------------------------*/
  /* Find group level
  /*--------------------------------------------------------------*/
  function find_by_groupLevel($level)
  {
    global $db;
    $sql = "SELECT group_level FROM user_groups WHERE group_level = '{$db->escape($level)}' LIMIT 1 ";
    $result = $db->query($sql);
    return($db->num_rows($result) === 0 ? true : false);
  }

  function find_by_groupLevel2($level)
  {
    global $db;
    $sql = $db->query("SELECT * FROM user_groups WHERE group_level = '{$db->escape($level)}'");
    if($result = $db->fetch_assoc($sql))
      return $result;
    else
      return null;
  }
  /*--------------------------------------------------------------*/
  /* Function for cheaking which user level has access to page
  /*--------------------------------------------------------------*/
   function page_require_level($require_level){
     global $session;
     $current_user = current_user();
     $login_level = find_by_groupLevel($current_user['user_level']);
     //if user not login
     if (!$session->isUserLoggedIn(true)):
            $session->msg('d','Por favor Iniciar sesión...');
            redirect('index.php', false);
      //if Group status Deactive
     elseif($login_level['group_status'] === '0'):
           $session->msg('d','Este nivel de usuario esta inactivo.');
           redirect('home.php',false);
      //cheackin log in User level and Require level is Less than or equal to
     elseif($current_user['user_level'] <= (int)$require_level):
              return true;
      else:
            $session->msg("d", "¡Lo siento!  no tienes permiso para ver la página.");
            redirect('home.php', false);
        endif;

     }
   /*--------------------------------------------------------------*/
   /* Function for Finding all product name
   /* JOIN with categorie  and media database table
   /*--------------------------------------------------------------*/
  function join_product_table(){
     global $db;
     $sql  =" SELECT p.id,p.name,p.quantity,p.buy_price,p.sale_price,p.media_id,p.date,c.name";
    $sql  .=" AS categorie,m.file_name AS image";
    $sql  .=" FROM products p";
    $sql  .=" LEFT JOIN categories c ON c.id = p.categorie_id";
    $sql  .=" LEFT JOIN media m ON m.id = p.media_id";
    $sql  .=" ORDER BY p.id ASC";
    return find_by_sql($sql);

   }
  /*--------------------------------------------------------------*/
  /* Function for Finding all product name
  /* Request coming from ajax.php for auto suggest
  /*--------------------------------------------------------------*/

   function find_product_by_title($product_name){
     global $db;
     $p_name = remove_junk($db->escape($product_name));
     $sql = "SELECT num_suc FROM cliente WHERE num_suc like '%$p_name%' LIMIT 5";
     $result = find_by_sql($sql);
     return $result;
   }

  /*--------------------------------------------------------------*/
  /* Function for Finding all product info by product title
  /* Request coming from ajax.php
  /*--------------------------------------------------------------*/
  function find_all_product_info_by_title($title){
    global $db;
    $sql  = "SELECT * FROM products ";
    $sql .= " WHERE name ='{$title}'";
    $sql .=" LIMIT 1";
    return find_by_sql($sql);
  }

  /*--------------------------------------------------------------*/
  /* Function for Update product quantity
  /*--------------------------------------------------------------*/
  function update_product_qty($qty,$p_id){
    global $db;
    $qty = (int) $qty;
    $id  = (int)$p_id;
    $sql = "UPDATE products SET quantity=quantity -'{$qty}' WHERE id = '{$id}'";
    $result = $db->query($sql);
    return($db->affected_rows() === 1 ? true : false);
  }
  /*--------------------------------------------------------------*/
  /* Function for Display Recent product Added
  /*--------------------------------------------------------------*/
 function find_recent_product_added($limit){
   global $db;
   $sql   = " SELECT p.id,p.name,p.sale_price,p.media_id,c.name AS categorie,";
   $sql  .= "m.file_name AS image FROM products p";
   $sql  .= " LEFT JOIN categories c ON c.id = p.categorie_id";
   $sql  .= " LEFT JOIN media m ON m.id = p.media_id";
   $sql  .= " ORDER BY p.id DESC LIMIT ".$db->escape((int)$limit);
   return find_by_sql($sql);
 }
 /*--------------------------------------------------------------*/
 /* Function for Find Highest saleing Product
 /*--------------------------------------------------------------*/
 function find_higest_saleing_product($limit){
   global $db;
   $sql  = "SELECT p.name, COUNT(s.product_id) AS totalSold, SUM(s.qty) AS totalQty";
   $sql .= " FROM sales s";
   $sql .= " LEFT JOIN products p ON p.id = s.product_id ";
   $sql .= " GROUP BY s.product_id";
   $sql .= " ORDER BY SUM(s.qty) DESC LIMIT ".$db->escape((int)$limit);
   return $db->query($sql);
 }
 /*--------------------------------------------------------------*/
 /* Function for find all sales
 /*--------------------------------------------------------------*/
 function find_all_sale(){
   global $db;
   $sql  = "SELECT s.id,s.qty,s.price,s.date,p.name";
   $sql .= " FROM sales s";
   $sql .= " LEFT JOIN products p ON s.product_id = p.id";
   $sql .= " ORDER BY s.date DESC";
   return find_by_sql($sql);
 }
 /*--------------------------------------------------------------*/
 /* Function for Display Recent sale
 /*--------------------------------------------------------------*/
function find_recent_sale_added($limit){
  global $db;
  $sql  = "SELECT s.id,s.qty,s.price,s.date,p.name";
  $sql .= " FROM sales s";
  $sql .= " LEFT JOIN products p ON s.product_id = p.id";
  $sql .= " ORDER BY s.date DESC LIMIT ".$db->escape((int)$limit);
  return find_by_sql($sql);
}

?>
