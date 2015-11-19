<?php
include 'init.php';

if(isset($_SESSION['userid']) && $_SESSION['userid'] != ''){ // Redirect to secured user page if user logged in
	echo '<script type="text/javascript">window.location = "dashboard.php"; </script>';
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>MedChart-Login</title>
        <link rel="stylesheet" href="css/style.css"/>
        <link href='http://fonts.googleapis.com/css?family=Lora' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>
        <script type="text/javascript" src="js/jquery-1.9.1.js"></script>
        <script src="js/jquery.min.js"></script>
        <script src="js/jquery.ui.shake.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
        <style type="text/css">
            .modal-alert{
                margin: 20px;
            }
        </style>

        <script type="text/javascript" src="js/scripts.js"></script>
    </head>
    <body>
    <div class="outer_wrapper">
      <div class="top_nav">
        <img src="img/heart_white.png"/>
        <h1>Welcome to MedChart!</h1>
        <h3>A PRACTICE ELECTRONIC HEALTH RECORD</h3>
      </div>
      <div class="divider"></div>
      <div id="main">
          <?php
          if(isset($_GET['register_success']))
          {
              echo '<div class="modal-alert">
        <div class="alert alert-success">
            <a href="#" class="close" data-dismiss="alert">&times;</a>
            <strong>Success!</strong> Your account has been created.
        </div>';

          }
          ?>
        <div id="box">
          <form action="" method="post">
            <label style="visibility: hidden;">Username</label>
            <input type="text" name="username" id="username" class="input" autocomplete="off" required  placeholder="Username"/>
            <label style="visibility: hidden;">Password </label>
            <input type="password" name="password" id="password" class="input" autocomplete="off"  required placeholder="Password"/><br/>
            <input type="submit" class="button button-primary" value="Log In" name="login" id="login" value="Login &raquo;"/>
            <span class='msg'></span>
              <div class="login_result" id="login_result" ></div>
          <div id="error"></div>
        </div>
				<a class="register_account" style="color: #fff;" href="accountRegister.html">Register Account</a>
        </form><br>

      </div>
    </div>
  </body>
</html>
