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
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous"> -->
    <!-- <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous"> -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"> -->
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> 
    
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" />

    <link rel="stylesheet" href="libs/css/main.css" /> 

<!-- SCRIPTS -->
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script> -->
    <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>   -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    
    <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script> -->
    <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script> -->
<!--     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> -->
<!-- 
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script> -->
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>

    <script type="text/javascript" src="libs/js/functions.js"></script>  

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
    </script>
  </head>
 
  <body>
    <?php if ($session->isUserLoggedIn(true)): ?>
      <header id="header">
        <div class="logo pull-left">IJS INGENIERÍA ELÉCTRICA</div>
        <div class="header-content">
        <div class="header-date pull-left">
          <strong><?php echo date("d/m/Y");?></strong>
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
