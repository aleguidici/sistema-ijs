<?php
  ob_start();
  require_once('includes/load.php');
  if($session->isUserLoggedIn(true)) { redirect('home.php', false);}
?>

<?php $user = current_user(); ?>
<!DOCTYPE html>
  <html lang="en">
    <head>
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title><?php if (!empty($page_title))
            echo remove_junk($page_title);
            elseif(!empty($user))
            echo ucfirst($user['name']);
            else echo "Sistema de Mediciones";?>
      </title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="libs/css/main.css" /> 

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script type="text/javascript" src="libs/js/functions.js"></script> 


    <script src="particulas/particles.js"></script>
    <script src="particulas/demo/js/app.js"></script>
    <script src="particulas/demo/js/lib/stats.js"></script>
    <style type="text/css">
      .opacity {
        opacity: 0.5;
        filter: alpha(opacity=50);
        zoom: 1;
       }

/*      .opacity:hover {
         opacity: 1;
         filter: alpha(opacity=100);
         zoom: 1.1;
       }
*/
    </style> 

  </head>
  <script type="text/javascript">
    function onEnter() {
      var element = document.getElementById('log');
      element.style.opacity = "1";
      element.style.filter  = 'alpha(opacity=100)';
      element.style.zoom = "1.1";
    }
  </script>
 
  <body>
    <div class="container-fluid">

      <iframe src="particulas/demo/index.html" frameborder="0" scrolling="no" name="contenedor" style="height:100%;position:absolute;width:101%;top:0px;left:0px;right:0px;bottom:0px;margin-left: -15px;" height="100%" width="100%"></iframe>

      <div class="row" style="position: absolute;top: 20%;left: 41%;">
        <div class="col-2">

          <div class="login-page opacity" id="log" style="border-radius: 15px 45px;box-shadow: 4px 4px 4px 3px rgba(0, 0, 0, 0.2);" onmouseenter="javascript:onEnter();">
            <div class="text-center">
              <h1>Bienvenido</h1>
              <p>Iniciar sesión </p>
            </div>
            <?php echo display_msg($msg); ?>
            <form method="post" action="auth.php" class="clearfix">
              <div class="form-group">
                <label for="username" class="control-label">Usuario</label>
                <input type="name" class="form-control" name="username" placeholder="Usuario">
              </div>
            <div class="form-group">
              <label for="Password" class="control-label">Contraseña</label>
              <input type="password" name= "password" class="form-control" placeholder="Contraseña">
            </div>
            <div class="form-group">
              <button type="submit" class="btn btn-info  pull-right">Entrar</button>
              <br><br>
            </div>
            
            </form>
          </div>
        </div>
      </div>

  </div>
       
<?php include_once('layouts/footer.php'); ?>
