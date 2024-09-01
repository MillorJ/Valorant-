<?php
  session_start();
  ob_start();
  if (isset($_SESSION["account"])){
    header("Location: lobby.php");
  }
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VALORANT | Sign In</title>
    <link href="css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">
    <link rel="stylesheet" href="css/styles.css">
  </head>
  <body>
    <div class="container">
      <div class="row">
        <div class="col-md-4">
        </div>
        <div class="col-md-4 valorant-form">
          <form action="" method="POST">
            <h4>Sign In | VALORANT</h4>
            <?php
              if(isset($_POST["login_btn"])){
                $identification = $_POST["identification"];
                $password = $_POST["password"];

                $servername = "localhost";
                $database = "cis1202";
                $db_username = "root";
                $db_password = "";

                $conn = mysqli_connect($servername, $db_username, $db_password, $database);

                if ($conn->connect_error) {
                  die("Connection failed: " . $conn->connect_error);
                }

                $sql = "SELECT * FROM accounts WHERE USERNAME = '$identification' AND PASSWORD = '$password' OR EMAIL_ADDRESS = '$identification' AND PASSWORD = '$password'";
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($result);

                if ($row == null){
                  echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    The account does not exist! <a href="register.php" style="color: darkred;"><b>SIGN UP</b></a>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
                } else {
                  $_SESSION["account"] = $row;
                  header("Location: lobby.php");
                }

                mysqli_close($conn);
              }

            ?>
            <hr>
            <div class="mb-3">
              <label class="form-label">Username or Email address</label>
              <input type="text" class="form-control" placeholder="Enter username or email" name="identification" autofocus required>
            </div>
            <div class="mb-3">
              <label class="form-label">Password</label>
              <input type="password" class="form-control" placeholder="Enter password" name="password" required>
            </div>
            <div class="d-grid gap-2">
              <button class="btn btn-dark" type="submit" name="login_btn">LOG IN</button>
              <button onclick="window.location='register.php'" class="btn btn-light" type="button" name="login_btn">CREATE AN ACCOUNT</button>
            </div>
          </form>
        </div>
        <div class="col-md-4">
        </div>
      </div>
    </div>
    <script src="js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  </body>
</html>