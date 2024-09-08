<?php
  session_start();
  ob_start();
  if (!isset($_SESSION["account"])){
    header("Location: logout.php");
  }
  include("dbconnect.php");
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Agents | VALORANT</title>
    <link href="css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">
    <style>
      body {
        background-image: url("img/agents.webp");
        background-repeat: no-repeat;
        background-size: cover;
        background-attachment: fixed;
        background-color: black;
        font-family: Arial, sans-serif;
        color: #fff;
        margin: 0;
        padding: 0;
  }
      .container {
        margin-top: 30px;
  }
  .valorant-form {
    background: rgba(204, 204, 204, 0.7);
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    margin-bottom: 20px;
    color: #333;
  }

    </style>
  </head>
  <body>
    <?php 
      include("navbar.php");
    ?>
    <div class="container" style="margin-bottom: 20px;">
      <br><br><br>
      <div class="valorant-form">
        <center><h2>AGENTS</h2></center>
      </div>
      <?php
        $sql = "SELECT * FROM agents";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
          while($agent = mysqli_fetch_assoc($result)) {
      ?>
      <div class="valorant-form">
        <div class="row">
          <div class="col-md-4">
            <img src="<?php echo $agent["IMG_LOC"]; ?>" style="max-width: 100%;">
          </div>
          <div class="col-md-8">
            <br>
            <b>NAME:</b> <?php echo $agent["NAME"]; ?>
            <br>
            <b>TYPE:</b> <?php echo $agent["TYPE"]; ?>
            <hr>
            <b>SKILLS:</b>
            <br>
            - <?php echo $agent["1ST_SKILL"]; ?>
            <br>
            - <?php echo $agent["2ND_SKILL"]; ?>
            <br>
            - <?php echo $agent["3RD_SKILL"]; ?>
            <br>
            - <?php echo $agent["ULTIMATE_SKILL"]; ?>
          </div>
        </div>
      </div>
      <?php
          }
        }
      ?>
    </div>
    <script src="js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
  </body>
</html>