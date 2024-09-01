<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create an account | VALORANT</title>
    <link href="css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
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
            <h4>Register | VALORANT</h4>
            <?php
              if(isset($_POST["register_btn"])){
                session_start();
                $username = $_POST["username"];
                $email = $_POST["email_address"];
                $password = $_POST["password"];
                $birthdate = $_POST["birthdate"];

                $servername = "localhost";
                $database = "cis1202";
                $db_username = "root";
                $db_password = "";

                $conn = mysqli_connect($servername, $db_username, $db_password, $database);

                if ($conn->connect_error) {
                  die("Connection failed: " . $conn->connect_error);
                }

                $sql = "SELECT * FROM accounts WHERE USERNAME = '$username' AND PASSWORD = '$password' OR EMAIL_ADDRESS = '$email' AND PASSWORD = '$password'";
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($result);

                if ($row == null){
                  $sql = "INSERT INTO accounts (USERNAME,EMAIL_ADDRESS,PASSWORD,BIRTHDATE,ACCOUNT_TYPE,STATUS)
                  VALUES ('$username', '$email', '$password', '$birthdate', 'PLAYER', 'ACTIVE')";

                  if (mysqli_query($conn, $sql)) {
                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                      Welcome to <b>VALORANT!</b>
                      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
                    echo '<script>window.setTimeout(function(){
                          window.location.href = "index.php";
                      }, 2000);</script>';
                  } else {
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                      Something went wrong!
                      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
                  }
                } else {
                  echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <b>Username</b> or <b>email address</b> is already taken.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
                }

                mysqli_close($conn);
              }

            ?>
            <hr>
            <div class="mb-3">
              <label class="form-label">Username</label>
              <input type="text" class="form-control" placeholder="Enter username" name="username" autofocus required>
            </div>
            <div class="mb-3">
              <label class="form-label">Email Address</label>
              <input type="email" class="form-control" placeholder="Enter email" name="email_address" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Password</label>
              <input type="password" class="form-control" placeholder="Enter password" name="password" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Birthdate</label>
              <input type="date" class="form-control" name="birthdate" required>
            </div>
            <div class="d-grid gap-2">
              <button class="btn btn-dark" type="submit" name="register_btn">REGISTER</button>
              <button onclick="window.location='index.php'" class="btn btn-light" type="button">I already have an account</button>
            </div>
          </form>
        </div>
        <div class="col-md-4">
        </div>
      </div>
    </div>
    <script src="js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
  </body>
</html>