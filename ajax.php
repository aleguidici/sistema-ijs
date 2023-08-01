<?php
  require_once('includes/load.php');
  if (!$session->isUserLoggedIn(true)) { redirect('index.php', false);}
?>

<?php
 // Auto suggetion
    $html = '';
   if(isset($_POST['num_suc']) && strlen($_POST['num_suc']))
   {
     $products = find_product_by_title($_POST['num_sc']);
     if($products){
        foreach ($products as $product):
           $html .= "<li class=\"list-group-item\">";
           $html .= $product['num_suc'];
           $html .= "</li>";
         endforeach;
      } else {

        $html .= '<li onClick=\"fill(\''.addslashes().'\')\" class=\"list-group-item\">';
        $html .= 'No encontrado';
        $html .= "</li>";

      }

      echo json_encode($html);
   }
 ?>
 <?php
 // find all product
  if(isset($_POST['num_suc']) && strlen($_POST['num_suc']))
  {
    $product_title = remove_junk($db->escape($_POST['num_suc']));
    if($results = find_all_product_info_by_title($product_title)){
        foreach ($results as $result) {

          $html .= "<tr>";

          $html .= "<td id=\"s_name\">".$result['num_suc']."</td>";
          $html .= "<input type=\"hidden\" name=\"s_id\" value=\"{$result['num_suc']}\">";
          $html  .= "<td>";
          $html  .= "<input type=\"text\" class=\"form-control\" name=\"price\" value=\"{$result['razon_social']}\">";
          $html  .= "</td>";
          $html .= "<td id=\"s_qty\">";
          $html .= "<input type=\"text\" class=\"form-control\" name=\"quantity\" value=\"1\">";
          $html  .= "</td>";
          $html  .= "<td>";
          $html  .= "<input type=\"text\" class=\"form-control\" name=\"total\" value=\"{$result['sale_price']}\">";
          $html  .= "</td>";
          $html  .= "<td>";
          $html  .= "<input type=\"date\" class=\"form-control datePicker\" name=\"date\" data-date data-date-format=\"yyyy-mm-dd\">";
          $html  .= "</td>";
          $html  .= "<td>";
          $html  .= "<button type=\"submit\" name=\"add_sale\" class=\"btn btn-primary\">Agregar</button>";
          $html  .= "</td>";
          $html  .= "</tr>";

        }
    } else {
        $html ='<tr><td>El cliente no se encuentra registrado en la base de datos</td></tr>';
    }

    echo json_encode($html);
  }
 ?>
