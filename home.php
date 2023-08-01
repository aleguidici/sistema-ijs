<?php
  $page_title = 'Home Page';
  require_once('includes/load.php');
  if (!$session->isUserLoggedIn(true)) { redirect('index.php', false);}
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
<?php include_once('layouts/header.php'); ?>

<head>
  

<meta name="description" content="particles.js is a lightweight JavaScript library for creating particles.">
<meta name="author" content="Vincent Garreau" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<link rel="stylesheet" media="screen" href="particulas/demo/css/style.css">
<link rel="stylesheet" media="screen" href="particulas/demo/css/estilos.css">
<script src="particulas/particles.js"></script>
<script src="particulas/demo/js/app.js"></script>
<script src="particulas/demo/js/lib/stats.js"></script>


<style>
  img.opacity {
   opacity: 0.5;
   filter: alpha(opacity=50);
   zoom: 1;
 }

img.opacity:hover {
   opacity: 1;
   filter: alpha(opacity=100);
   zoom: 1;
 }
/*
.carousel-indicators li {
    position: relative;
    -webkit-box-flex: 0;
    -ms-flex: 0 1 auto;
    flex: 0 1 auto;
    width: 30px;
    height: 3px;
    margin-right: 3px;
    margin-left: 3px;
    text-indent: -999px;
    background-color: rgba(255,255,255,.5);
}

.carousel-indicators li::before {
    position: absolute;
    top: -10px;
    left: 0;
    display: inline-block;
    width: 100%;
    height: 10px;
    content: "\2212";
}
.carousel-indicators li::after {
    position: absolute;
    bottom: -10px;
    left: 0;
    display: inline-block;
    width: 100%;
    height: 10px;
    content: "\2212";
}

.carousel-indicators .active {
    background-color: #fff;
}
*/
</style>
<script>
  $(document).ready(function(){
    $('.carousel').carousel({
      interval: 15000
    });
  });
</script>

</head>
<div class="row" style="height: 75px;"></div>
<div class="container-fluid">
  <iframe src="particulas/demo/index.html" frameborder="0" scrolling="no" name="contenedor" style="height:100%;position:absolute;width:87.3%;top:16px;left:0px;right:0px;bottom:0px; margin-left: 248px;" height="100%" width="100%"></iframe>
  <div class="row">
    <div class="col-auto">
      <?php echo display_msg($msg); ?>
    </div>
    <div class="col-auto">
      <div class="panel">
        <div id="particles-js" name="particles-js">
  			<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
			  <ol class="carousel-indicators">
			    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
			    <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
          <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
			  </ol>
			  <div class="carousel-inner">
			    <div class="carousel-item active">
			      <img class="d-block w-100 opacity" src="libs\images\elec.png" alt="First slide">
			    </div>
			    <div class="carousel-item">
			      <img class="d-block w-100 opacity" src="libs\images\auto.png" alt="Second slide">
			    </div>
          <div class="carousel-item">
            <img class="d-block w-100 opacity" src="libs\images\maquinas.png" alt="Third slide">
          </div>
			  </div>
			  <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
			    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
			    <span class="sr-only">Previous</span>
			  </a>
			  <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
			    <span class="carousel-control-next-icon" aria-hidden="true"></span>
			    <span class="sr-only">Next</span>
			  </a>
			</div>
        </div>  
      </div>
    </div>
  </div>
</div>
<div class="row" style="height: 75px;"></div>



<?php include_once('layouts/footer.php'); ?>
