
<!DOCTYPE html> 
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>ZANECO WMS | Log in</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="../dist/img/logo2-1.2x1.2.png"> 
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="../dist/css/ionicons.min.css">
  <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
  <link rel="stylesheet" href="../plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <link rel="stylesheet" href="../plugins/toastr/toastr.min.css">  
</head>
<body class="hold-transition login-page">  

  <?php  session_start();  ?>

  <div class="login-box">
    <div class="login-logo">
      <div class="text-center">
        <img class="profile-user-img img-fluid img-circle" src="../dist/img/logo2-2x2.png" alt="User profile picture">
      </div>
      <a href="#"><b>ZANECO</b>WMS</a>
    </div>
    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg">Select Transaction</p>        
        <hr style="margin-top: -5px">          
        <button onclick="selected_transaction(0)" class="btn btn-primary btn-block">As Requistor!</button>          
        <button onclick="selected_transaction(1)" class="btn btn-warning btn-block">Warehouseman</button>                    
        <div class="social-auth-links text-center mb-3">
          <?php 
          if(isset($_SESSION['error'])) {
            echo '<hr><br><label style="color: red">'.$_SESSION["error"].'</label>';
          }  
          ?>
        </div>
        <p class="mb-1">
          <!--a href="">I forgot my password</a-->
        </p>
        <p class="mb-0">
          <!--a href="" class="text-center">Register a new membership</a-->
        </p>
      </div>
    </div>
  </div>

  <script src="../plugins/jquery/jquery.min.js"></script>
  <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../dist/js/adminlte.min.js"></script>
  <script src="../plugins/sweetalert2/sweetalert2.min.js"></script>
  <script src="../plugins/toastr/toastr.min.js"></script> 

  <script type="text/javascript">

    function selected_transaction(e) {
      if (e == 0) {
        window.open("index2.php?id=0","_self");              
      } else {             
        window.open("index.php?id="+ e,"_self")              
      }
    }  

  </script>

</body>
</html>
