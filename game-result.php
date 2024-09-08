<?php
  session_start();
  ob_start();
  if (!isset($_SESSION["account"])){
    header("Location: logout.php");
  }
  include("dbconnect.php");

  $game_id = $_GET["game_id"];
  $user_agent = $_GET["user_agent"];
  $opponent_agent = $_GET["opponent_agent"];

  if ($game_id == null || $user_agent == null || $opponent_agent == null){
    header("Location: waiting-room.php");
  } else {
    $sql = "SELECT * FROM agents WHERE id ='$user_agent'";
    $result = $conn->query($sql);
    $user_agent_information = $result->fetch_assoc();

    $sql = "SELECT * FROM agents WHERE id ='$opponent_agent'";
    $result = $conn->query($sql);
    $opponent_agent_information = $result->fetch_assoc();

    $sql = "SELECT * FROM games WHERE id ='$game_id'";
    $result = $conn->query($sql);
    $game = $result->fetch_assoc();

    $map_id = $game["MAP_ID"];
    $mode = $game["MODE"];
    $attacker_id = $game["ATTACKER_ID"];
    $defender_id = $game["DEFENDER_ID"];
    $winner = $game["WINNER"];
    $status = $game["STATUS"];

    $sql = "SELECT * FROM accounts WHERE id ='$attacker_id'";
    $result = $conn->query($sql);
    $attacker = $result->fetch_assoc();

    $sql = "SELECT * FROM accounts WHERE id ='$defender_id'";
    $result = $conn->query($sql);
    $defender = $result->fetch_assoc();

    if ($mode == "COMPETITIVE" && $status == "PENDING"){
      if ($winner == "ATTACKER"){
        $attacker_level = $attacker["LEVEL"];
        $attacker_level++;
        $attacker_wins = $attacker["WINS"];
        $attacker_wins++;

        $sql = "UPDATE accounts SET LEVEL='$attacker_level', WINS='$attacker_wins' WHERE id='$attacker_id'";
        $conn->query($sql);

        $defender_losses = $defender["LOSSES"];
        $defender_losses++;

        $sql = "UPDATE accounts SET LOSSES='$defender_losses' WHERE id='$defender_id'";
        $conn->query($sql);
      } else if ($winner == "DEFENDER"){
        $defender_level = $defender["LEVEL"];
        $defender_level++;
        $defender_wins = $defender["WINS"];
        $defender_wins++;

        $sql = "UPDATE accounts SET LEVEL='$defender_level', WINS='$defender_wins' WHERE id='$defender_id'";
        $conn->query($sql);

        $attacker_losses = $attacker["LOSSES"];
        $attacker_losses++;

        $sql = "UPDATE accounts SET LOSSES='$attacker_losses' WHERE id='$attacker_id'";
        $conn->query($sql);
      } else if ($winner == "DRAW"){
        $attacker_draws = $attacker["DRAWS"];
        $defender_draws = $defender["DRAWS"];
        $attacker_draws++;
        $defender_draws++;
        $sql = "UPDATE accounts SET DRAWS='$attacker_draws' WHERE id='$attacker_id'";
        $conn->query($sql);
        $sql = "UPDATE accounts SET DRAWS='$defender_draws' WHERE id='$defender_id'";
        $conn->query($sql);
      }
      $sql = "UPDATE games SET STATUS='PROCESSED' WHERE id='$game_id'";
      $conn->query($sql);
    }

    $sql = "SELECT * FROM accounts WHERE id ='$attacker_id'";
    $result = $conn->query($sql);
    $attacker = $result->fetch_assoc();

    $sql = "SELECT * FROM accounts WHERE id ='$defender_id'";
    $result = $conn->query($sql);
    $defender = $result->fetch_assoc();

    if ($winner == "ATTACKER"){
      $victory = $attacker;
      $defeat = $defender;
    } else if ($winner == "DEFENDER"){
      $victory = $defender;
      $defeat = $attacker;
    }

    $sql = "SELECT * FROM maps WHERE id ='$map_id'";
    $result = $conn->query($sql);
    $map = $result->fetch_assoc();
  }
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Game Result | VALORANT</title>
    <link href="css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">
    <style>
       body {
            background-image: url(<?php echo htmlspecialchars($map["IMG_LOC"], ENT_QUOTES, 'UTF-8'); ?>);
            background-repeat: no-repeat;
            background-size: cover;
            background-attachment: fixed;
            background-color: #000;
            color: #fff;
        }
        .container {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-radius: 8px;
        }
        .alert {
            border-radius: 8px;
            padding: 20px;
        }
        .alert-success {
            background-color: #28a745;
            color: #fff;
        }
    </style>
  </head>
  <body>
    <?php 
      include("navbar.php");
    ?>
    <div class="container text-center" style="margin-top: 80px;">
      <div class="alert alert-dark" role="alert">
        <h4>• <?php echo $mode; ?> •</h4>
        <hr>
        <h2><b><?php echo $map["NAME"]; ?></b></h2>
        <?php echo $map["DESCRIPTION"]; ?>
      </div>
    </div>
    <div class="container text-center" style="margin-top: 10px;">
      <div class="row">
        <?php
          if ($winner == "DRAW"){
        ?>
          <div class="alert alert-warning" role="alert">
            <h4>DRAW</h4>
          </div>
          <div class="col-md-6">
            <div class="alert alert-warning" role="alert">
              <h2><?php echo $attacker["USERNAME"]; ?> [ATTACKER]</h2>
              <?php 
                if ($attacker["USERNAME"] == $_SESSION["account"]["USERNAME"]){
              ?>
                <img src="<?php echo $user_agent_information["IMG_LOC"]; ?>" style="max-width: 100%; margin-bottom: 10px;">
                <h4>LEVEL: <?php echo $attacker["LEVEL"]; ?></h4>
                <h4>WINS: <?php echo $attacker["WINS"]; ?></h4>
                <h4>LOSSES: <?php echo $attacker["LOSSES"]; ?></h4>
                <h4>DRAWS: <?php echo $attacker["DRAWS"]; ?></h4>
              <?php
                } else {
              ?>
                <img src="<?php echo $opponent_agent_information["IMG_LOC"]; ?>" style="max-width: 100%; margin-bottom: 10px;">
                <h4>LEVEL: <?php echo $attacker["LEVEL"]; ?></h4>
                <h4>WINS: <?php echo $attacker["WINS"]; ?></h4>
                <h4>LOSSES: <?php echo $attacker["LOSSES"]; ?></h4>
                <h4>DRAWS: <?php echo $attacker["DRAWS"]; ?></h4>
              <?php } ?>
            </div>
          </div>
          <div class="col-md-6">
            <div class="alert alert-warning" role="alert">
              <h2><?php echo $defender["USERNAME"]; ?> [DEFENDER]</h2>
              <?php 
                if ($defender["USERNAME"] == $_SESSION["account"]["USERNAME"]){
              ?>
                <img src="<?php echo $user_agent_information["IMG_LOC"]; ?>" style="max-width: 100%; margin-bottom: 10px;">
                <h4>LEVEL: <?php echo $defender["LEVEL"]; ?></h4>
                <h4>WINS: <?php echo $defender["WINS"]; ?></h4>
                <h4>LOSSES: <?php echo $defender["LOSSES"]; ?></h4>
                <h4>DRAWS: <?php echo $defender["DRAWS"]; ?></h4>
              <?php
                } else {
              ?>
                <img src="<?php echo $opponent_agent_information["IMG_LOC"]; ?>" style="max-width: 100%; margin-bottom: 10px;">
                <h4>LEVEL: <?php echo $defender["LEVEL"]; ?></h4>
                <h4>WINS: <?php echo $defender["WINS"]; ?></h4>
                <h4>LOSSES: <?php echo $defender["LOSSES"]; ?></h4>
                <h4>DRAWS: <?php echo $defender["DRAWS"]; ?></h4>
              <?php } ?>
            </div>
          </div>
        <?php
        } else {
        ?>
        <div class="col-md-6">
          <div class="alert alert-success" role="alert">
            <h4>VICTORY</h4>
            <hr>
            <h2><?php echo $victory["USERNAME"]; ?>
              [<?php 
                if ($winner == "ATTACKER"){
                  echo "ATTACKER";
                } else if ($winner == "DEFENDER"){
                  echo "DEFENDER";
                }
              ?>]
            </h2>
            <?php
              if ($victory["USERNAME"] == $_SESSION["account"]["USERNAME"]){
            ?>
              <img src="<?php echo $user_agent_information["IMG_LOC"]; ?>" style="max-width: 100%; margin-bottom: 10px;">
              <h4>LEVEL: <?php echo $victory["LEVEL"]; ?></h4>
              <h4>WINS: <?php echo $victory["WINS"]; ?></h4>
              <h4>LOSSES: <?php echo $victory["LOSSES"]; ?></h4>
              <h4>DRAWS: <?php echo $victory["DRAWS"]; ?></h4>
            <?php
              } else {
            ?>
              <img src="<?php echo $opponent_agent_information["IMG_LOC"]; ?>" style="max-width: 100%; margin-bottom: 10px;">
              <h4>LEVEL: <?php echo $victory["LEVEL"]; ?></h4>
              <h4>WINS: <?php echo $victory["WINS"]; ?></h4>
              <h4>LOSSES: <?php echo $victory["LOSSES"]; ?></h4>
              <h4>DRAWS: <?php echo $victory["DRAWS"]; ?></h4>
            <?php } ?>
          </div>
        </div>
        <div class="col-md-6">
          <div class="alert alert-danger" role="alert">
            <h4>DEFEAT</h4>
            <hr>
            <h2><?php echo $defeat["USERNAME"]; ?>
              [<?php 
                if ($winner == "ATTACKER"){
                  echo "DEFENDER";
                } else if ($winner == "DEFENDER"){
                  echo "ATTACKER";
                }
              ?>]
            </h2>
            <?php
              if ($defender["USERNAME"] == $_SESSION["account"]["USERNAME"]){
            ?>
              <img src="<?php echo $user_agent_information["IMG_LOC"]; ?>" style="max-width: 100%; margin-bottom: 10px;">
              <h4>LEVEL: <?php echo $defeat["LEVEL"]; ?></h4>
              <h4>WINS: <?php echo $defeat["WINS"]; ?></h4>
              <h4>LOSSES: <?php echo $defeat["LOSSES"]; ?></h4>
              <h4>DRAWS: <?php echo $defeat["DRAWS"]; ?></h4>
            <?php
              } else {
            ?>
              <img src="<?php echo $opponent_agent_information["IMG_LOC"]; ?>" style="max-width: 100%; margin-bottom: 10px;">
              <h4>LEVEL: <?php echo $defeat["LEVEL"]; ?></h4>
              <h4>WINS: <?php echo $defeat["WINS"]; ?></h4>
              <h4>LOSSES: <?php echo $defeat["LOSSES"]; ?></h4>
              <h4>DRAWS: <?php echo $defeat["DRAWS"]; ?></h4>
            <?php } ?>
          </div>
        </div>
        <?php } ?>
      </div>
      <div class="d-grid gap-2" style="margin-bottom: 10px;">
        <button class="btn btn-danger" type="button" onclick="window.location='waiting-room.php'">PLAY AGAIN</button>
        <button class="btn btn-light" type="button" onclick="window.location='lobby.php'">EXIT</button>
      </div>
    </div>
    <script src="js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  </body>
</html>