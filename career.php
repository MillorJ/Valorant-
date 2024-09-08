<?php
  session_start();
  ob_start();
  if (!isset($_SESSION["account"])){
    header("Location: logout.php");
  }
  include("dbconnect.php");
  $user_id = $_SESSION["account"]["id"];
  $sql = "SELECT * FROM accounts WHERE id ='$user_id'";
  $result = $conn->query($sql);
  $_SESSION["account"] = $result->fetch_assoc();
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Career | VALORANT</title>
    <link href="css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">
    <style>
     body {
        background-image: url("img/career.webp"); /* Background image */
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center; /* Center background image */
        font-family: Arial, sans-serif; /* Modern font */
        color: #fff; /* White text color for better contrast */
        margin: 0;
}
        .valorant-form {
          background: rgba(0, 0, 0, 0.7); /* Darker background for better readability */
          padding: 20px;
          border-radius: 10px; /* Rounded corners */
          box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5); /* Shadow for depth */
          color: #fff;
          margin-top: 20px;
}

        .valorant-form h2 {
          margin: 0;
          font-size: 2rem;
}

        .container {
          padding: 20px;
}

        .text-center {
          text-align: center;
        }

        .row {
  margin-bottom: 20px;
}

      .col {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
      }

      .col img {
  margin-top: 10px;
  border-radius: 50%; /* Circular profile images */
}

      h4 {
        margin-top: 10px;
        font-size: 1.25rem; /* Adjusted font size for headings */
      }

        b {
    font-size: 1rem;
  }

    @media (max-width: 768px) {
    .valorant-form {
      padding: 15px;
    }
    
    .col {
    margin-bottom: 10px;
  }
  .row {
    margin-bottom: 15px;
  }
}

    </style>
  </head>
  <body>
    <?php include("navbar.php"); ?>
    <div class="container" style="margin-bottom: 20px;">
      <br><br><br>
      <div class="valorant-form">
        <center><h2>CAREER</h2></center>
      </div>
      <div class="container text-center">
        <div class="row">
          <div class="col valorant-form">
            <b>RANK</b>
            <br><?php
                  $rank = null;
                  $rank_id = $_SESSION["account"]["RANK_ID"];
                  $wins = $_SESSION["account"]["WINS"];
                  $losses = $_SESSION["account"]["LOSSES"];
                  $draws = $_SESSION["account"]["DRAWS"];
                  $level = $_SESSION["account"]["LEVEL"];
                  if ($losses > 0){
                    $MMR = number_format((float)(($draws * 0.5) + $wins)/$losses, 2, '.', '');
                    $sql = "SELECT * FROM ranks";
                    $result = mysqli_query($conn, $sql);
                    while($rank = $result->fetch_assoc()) {
                      if ($rank["MATCHMAKING_RATING"] <= $MMR){
                        $rank_id = $rank["id"];
                      }
                    }
                    $sql = "UPDATE accounts SET RANK_ID='$rank_id' WHERE id='$user_id'";
                    $conn->query($sql);
                    $_SESSION["account"]["RANK_ID"] = $rank_id;

                    $sql = "SELECT * FROM ranks WHERE id = '$rank_id'";
                    $result = mysqli_query($conn, $sql);
                    $rank = mysqli_fetch_assoc($result);
                  }
                  if ($rank != null){
                  ?>
                    <img src="<?php echo $rank["IMG_LOC"]; ?>" width="100" height="100" style="margin-top: 10px;">
                    <br><h4 style="margin-top: 10px;"><?php echo $rank["NAME"]; ?></h4>
                  <?php
                  } else {
                  ?>
                <img src="img/ranks/unranked.jpeg" width="100" height="100" style="margin-top: 10px;">
                <br><h4 style="margin-top: 10px;">UNRANKED</h4>
              <?php } ?>
          </div>
          <div class="col valorant-form">
            <b>MMR</b>
            <br><?php
              if ($losses > 0 && $level >= 20){
                echo $MMR;
              } else {
              ?>
                <br><h4>LOCKED</h4>
              <?php
              }
            ?>
          </div>
          <div class="col valorant-form">
            <b>LEVEL</b>
            <br><h4><?php echo $level; ?></h4>
          </div>
        </div>
      </div>
      <div class="container text-center">
        <div class="row">
          <div class="col valorant-form">
            <b>WINS</b>
            <br><h4><?php echo $wins; ?></h4>
          </div>
          <div class="col valorant-form">
            <b>LOSSES</b>
            <br><h4><?php echo $losses; ?></h4>
          </div>
          <div class="col valorant-form">
            <b>DRAWS</b>
            <br><h4><?php echo $draws; ?></h4>
          </div>
        </div>
      </div>
    </div>
    <script src="js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
  </body>
</html>