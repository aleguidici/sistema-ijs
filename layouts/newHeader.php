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
          else echo "Sistema IJS"; ?>
    </title>

<!-- LINKS -->
  <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bbootstrap 4 -->
    <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
    <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
    <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
    <!--<link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">-->
  <!-- summernote -->
    <link rel="stylesheet" href="plugins/summernote/summernote-bs4.css">
  <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">--> 
    <!--    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" />  -->
<!--/LINKS ADMINLTE -->    
  <!-- BOOTSTRAP -->
    <link rel="stylesheet" href="libs/bootstrap-3.4.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="libs/css/main.css" /> 
  <!--------------->
  <!-- ALERTIFY -->  
    <link rel="stylesheet" type="text/css" href="libs/alertifyjs/css/alertify.css">
    <link rel="stylesheet" type="text/css" href="libs/alertifyjs/css/themes/default.css">
  <!-------------->
  <!-- SELECT ---->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
  <!-------------->
  <!-- DATE PICKER -->
  <!--     <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css"> -->
    <link rel="stylesheet" href="libs/bootstrap-3.4.1/dist/css/bootstrap-datepicker3.min.css" />
  <!----------------->
  <!-- DATA TABLES -->
    <!--<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css">-->
    <!--<link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.15.3/dist/bootstrap-table.min.css">-->
    <link rel="stylesheet" type="text/css" href="libs/datatables/datatables.min.css"/>
  <!----------------->
  <!-- DATE RANGE -->
    <link rel="stylesheet" type="text/css" href="libs/daterangepicker-master/daterangepicker.css" />

    <link rel="stylesheet" href="plugins/customloader/loader.css" />
<!-- /LINKS  -->

<!-- SCRIPTS -->
  <!-- JQUERY --> 
    <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script> -->
    <!--<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script> -->
    <!--<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script> --> 
    <script src="libs/jquery-2.2.4/jquery-2.2.4.min.js"></script> 
    
  <!------------>
  <!-- DATEARANGE -->
    <script type="text/javascript" src="libs/daterangepicker-master/moment.min.js"></script>
    <script type="text/javascript" src="libs/daterangepicker-master/daterangepicker.js"></script>
  <!---------------->
  <!-- POOPER -->  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
  <!------------>
  <!-- BOOTSTRAP -->
  <script src="libs/bootstrap-3.4.1/dist/js/bootstrap.min.js"></script>
  <!--------------->
  <!-- DATEPICKER -->
    <!--<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>  -->
    <script src="libs/bootstrap-3.4.1/dist/js/bootstrap-datepicker.min.js"></script>
    <script src="libs/bootstrap-3.4.1/js/locales/bootstrap-datepicker.es.js"></script>
  <!---------------->
  <!-- YA ESTABAN -->
    <script type="text/javascript" src="libs/js/functions.js"></script>
  <!---------------->
  <!-- CHARTS -->
    <script src="plugins/chart.js/Chart.min.js"></script>   
  <!------------>
  <!-- ALERTIFY -->
    <script src="libs/alertifyjs/alertify.js"></script>
  <!-------------->
  <!-- SELECT -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>  
  <!------------>
    <!--<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>  -->
  <!-- DATATABLES -->
    <script type="text/javascript" src="libs/datatables/datatables.min.js"></script>
  <!---------------->
    <style type="text/css">
      body {
          color: #404E67;
          background: #F5F7FA;
          font-family: 'Open Sans', sans-serif;
          font-size: 12px;
      }
      .table-wrapper {
          width: fixed;
          margin: 30px auto;
          background: #fff;
          padding: 20px;  
          box-shadow: 0 1px 1px rgba(0,0,0,.05);
      }.table td.fit, 
        .table th.fit {
            white-space: nowrap;
            width: 1%;
        }
      .table-title {
          padding-bottom: 10px;
          margin: 0 0 10px;
      }
      .table-title h2 {
          margin: 6px 0 0;
          font-size: 22px;
      }
      .table-title .add-new {
          float: right;
          height: 30px;
          font-weight: bold;
          font-size: 12px;
          text-shadow: none;
          min-width: 100px;
          border-radius: 50px;
          line-height: 13px;
      }
      table.table {
          table-layout: fixed;
      }
      table.table tr th, table.table tr td {
          border-color: #e9e9e9;
      }
      table.table th i {
          font-size: 13px;
          margin: 0 5px;
          cursor: pointer;
      }
      table.table th:last-child {
          width: 100px;
      }
      table.table td a {
          cursor: pointer;
          display: inline-block;
          margin: 0 5px;
          min-width: 24px;
      }    
      table.table td a.add {
          color: #27C46B;
      }
      table.table td a.edit {
          color: #FFC107;
      }
      table.table td a.delete {
          color: #E34724;
      }
      table.table td i {
          font-size: 19px;
      }
      table.table td a.add i {
          font-size: 24px;
          margin-right: -1px;
          position: relative;
          top: 3px;
      }    
      table.table .form-control {
          height: 32px;
          line-height: 32px;
          box-shadow: none;
          border-radius: 2px;
      }
      table.table .form-control.error {
          border-color: #f50000;
      }
      table.table td .add {
          display: none;
      }

      .loader {
        position: fixed;
        left: 0px;
        top: 0px;
        width: 100%;
        height: 100%;
        z-index: 9999;
        background: url('includes/img/loaders/loading-79-unscreen.gif') 50% 50% no-repeat rgb(249, 249, 249);
        opacity: 1;
      }
      .loader2 {
        position: fixed;
        left: 0px;
        top: 0px;
        width: 100%;
        height: 100%;
        z-index: 9999;
        background: rgb(5, 5, 5);
        opacity: .5;
      }

      .msgwait {
        position: fixed;
        left: 0px;
        top: 0px;
        width: 100%;
        height: 100%;
        z-index: 9999;
        background: url('includes/img/loaders/loading-79-unscreen.gif') 50% 50% no-repeat rgb(249, 249, 249);
        opacity: .8;
      }

      .no-reparacion {
        background-color: rgb(192,192,192) !important;
      }
      .no-reparacion:hover{
        background-color: rgb(169,169,169) !important;
      }

      .no-registrada-en-reparacion {
        background-color: rgba(120,120,120,0.2) !important;
      }
      .no-registrada-en-reparacion:hover {
        background-color: rgba(120,120,120,0.4) !important;
      }   


   </style>

    <script>
      $( function() {
        var dateFormat = "dd/mm/yy",
          from = $( "#from" )
            .datepicker({
              defaultDate: "+1w",
              changeMonth: true,
              numberOfMonths: 3
            })
            .on( "change", function() {
              to.datepicker( "option", "minDate", getDate( this ) );
            }),
          to = $( "#to" ).datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            numberOfMonths: 3
          })
          .on( "change", function() {
            from.datepicker( "option", "maxDate", getDate( this ) );
          });
     
        function getDate( element ) {
          var date;
          try {
            date = $.datepicker.parseDate( dateFormat, element.value );
          } catch( error ) {
            date = null;
          }
     
          return date;
        }
    } );

    setInterval(myTimer, 1000);
    function myTimer() {
      const myTimerVar = new Date();
      var time = myTimerVar.toLocaleTimeString();
      var day = myTimerVar.toLocaleDateString('en-GB');
      document.getElementById("my_timer").innerHTML = day + " - " + time ;
    }

    $(window).load(function() {
      $('#myloader').fadeOut(1500);
    });
    </script>
  </head>
 
  <body>
    <?php if ($session->isUserLoggedIn(true)): ?>
      <div id="myloader" class="myloader">
        <span></span>
        <span></span>
        <span></span>
        <h3>Cargando...</h3>
      </div>
      <header id="header">
        <div class="logo pull-left">IJS INGENIERÍA ELÉCTRICA</div>
        <div class="header-content">
        <div class="header-date pull-left">
          <!--<strong><?php //echo date("d/m/Y")." - ".date("G:i:s");?></strong>-->
          <strong><p id="my_timer"></p></strong>  
        </div>
        <div class="pull-right clearfix">
          <ul class="info-menu list-inline list-unstyled">
            <li class="profile">
              <a href="#" data-toggle="dropdown" class="toggle" aria-expanded="false">
                <img src="uploads/users/<?php echo $user['image'];?>" alt="user-image" class="img-circle img-inline">
                <span><?php echo remove_junk(ucfirst($user['name'])); ?> <i class="caret"></i></span>
              </a>
              <ul class="dropdown-menu">
                <li>
                  <a href="profile.php?id=<?php echo (int)$user['id'];?>">
                    <i class="glyphicon glyphicon-user"></i>
                    Perfil
                  </a>
                </li>
                <li>
                  <a href="edit_account.php" title="edit account">
                    <i class="glyphicon glyphicon-cog"></i>
                    Configuración
                  </a>
                </li>
                <li class="last">
                  <a href="logout.php">
                    <i class="glyphicon glyphicon-off"></i>
                    Salir
                  </a>
               </li>
              </ul>
            </li>
          </ul>
        </div>
       </div>
      </header>
      <div class="sidebar" style="width: 251px;">
        <?php if($user['user_level'] === '1'): ?>
          <?php include_once('admin_menu.php');?>
        <?php elseif($user['user_level'] === '2'): ?>
          <?php include_once('special_menu.php');?>
        <?php elseif($user['user_level'] === '3'): ?>
          <?php include_once('user_menu.php');?>
        <?php endif;?>
      </div>
    <?php endif;?>
    <div class="page">
      <div class="container-fluid">