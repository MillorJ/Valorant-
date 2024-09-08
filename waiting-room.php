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
    <title>Waiting Room | VALORANT</title>
    <link href="css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">
    <style>
     body {
        background-image: url("img/waiting-room.webp");
        background-repeat: no-repeat;
        background-size: cover;
        background-color: #000;
        color: #fff;
      }
      .valorant-form {
        background: rgba(204, 204, 204, 0.8);
        padding: 30px;
        border-radius: 8px;
        margin-top: 100px;
        color: #000;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
      }

	  .form-label {
        font-weight: bold;
      }


    </style>
  </head>
  <body>
    <?php 
      include("navbar.php");
    ?>
    <div class="container text-center">
      <div class="row">
        <div class="col-md-4">
        </div>
        <div class="col-md-4 valorant-form">
        	<form action="" method="POST">
        		<h4>FIND MATCH</h4>
        		<?php
        			if(isset($_POST["find_match"])){
        				$mode = $_POST["mode"];
        				$map = $_POST["map"];
        				$agent = $_POST["agent"];
        				$user_team = $_POST["team"];

        				if ($mode == "-- Select --" || $map == "-- Select --" || $agent == "-- Select --" || $user_team == "-- Select --"){

	        				echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">Selection is <b>incomplete!</b><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';

        				} else {

        					$sql = "SELECT * FROM accounts";
									$result = $conn->query($sql);
									if($result->num_rows == 1){
										echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">You are the <b>only account</b> in this server.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
									} else {

										$user_id = $_SESSION["account"]["id"];
										$players = array(); $i=0;
										while($account = $result->fetch_assoc()) {
											if ($account["ACCOUNT_TYPE"] == "PLAYER" && $account["STATUS"] == "ACTIVE" && $account["id"] != $user_id){
												$players[$i] = $account; $i++;
											}
									  }

									  $opponent_key = array_rand($players,1);
									  $coin = array("heads","tails","side")[random_int(0,2)];

									  if ($coin == "heads"){
									  	if ($user_team == "ATTACKER"){
									  		$winner = "ATTACKER";
									  		$attacker = $_SESSION["account"];
									  		$defender = $players[$opponent_key];
									  	} else if ($user_team == "DEFENDER"){
									  		$winner = "DEFENDER";
									  		$attacker = $players[$opponent_key];
									  		$defender = $_SESSION["account"];
									  	}
									  } else if ($coin == "tails"){
									  	if ($user_team == "ATTACKER"){
									  		$winner = "DEFENDER";
									  		$attacker = $_SESSION["account"];
									  		$defender = $players[$opponent_key];
									  	} else if ($user_team == "DEFENDER"){
									  		$winner = "ATTACKER";
									  		$attacker = $players[$opponent_key];
									  		$defender = $_SESSION["account"];
									  	}
									  } else if ($coin == "side"){
									  	$winner = "DRAW";
									  	if ($user_team == "ATTACKER"){
									  		$attacker = $_SESSION["account"];
									  		$defender = $players[$opponent_key];
									  	} else if ($user_team == "DEFENDER"){
									  		$attacker = $players[$opponent_key];
									  		$defender = $_SESSION["account"];
									  	}
									  }

									  $sql = "INSERT INTO games (MAP_ID,MODE,ATTACKER_ID,DEFENDER_ID,WINNER,STATUS)
										VALUES ('$map', '$mode', '".$attacker["id"]."', '".$defender["id"]."', '$winner', 'PENDING')";

										if (mysqli_query($conn, $sql)) {
											$game_id = $conn->insert_id;
											$sql = "SELECT * FROM agents ORDER BY RAND() LIMIT 1";
									    $result = $conn->query($sql);
									    $opponent_agent = $result->fetch_assoc();
									    $opponent_agent_id = $opponent_agent["id"];

										  echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
	                      <b>MATCH FOUND!</b>
	                      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
	                    </div>';
	                    echo '<script>window.setTimeout(function(){
	                          window.location.href = "game-result.php?game_id='.$game_id.'&user_agent='.$agent.'&opponent_agent='.$opponent_agent_id.'";
	                      }, 1000);</script>';
										} else {
										  echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
	                      Something went wrong!
	                      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
	                    </div>';
										}

									}
        				}
        			}
        		?>
        		<hr>
        		<div class="mb-3">
	        		<label class="form-label">Mode</label>
	        		<select class="form-select" aria-label="Default select example" name="mode">
							  <option selected>-- Select --</option>
							  <option value="UNRATED" disabled>UNRATED</option>
							  <option value="COMPETITIVE">COMPETITIVE</option>
							  <option value="SWIFTPLAY" disabled>SWIFTPLAY</option>
							  <option value="SPIKE RUSH" disabled>SPIKE RUSH</option>
							  <option value="DEATHMATCH" disabled>DEATHMATCH</option>
							  <option value="ESCALATION" disabled>ESCALATION</option>
							  <option value="CUSTOM GAME" disabled>CUSTOM GAME</option>
							</select>
						</div>
						<div class="mb-3">
			        		<label class="form-label">Map</label>
			        		<select class="form-select" aria-label="Default select example" name="map">
							  <option selected>-- Select --</option>
							  <?php
								  $sql = "SELECT * FROM maps";
			        			  $result = mysqli_query($conn, $sql);
			        			   while($map = $result->fetch_assoc()) {
								  ?>
								  <option value="<?php echo $map["id"]; ?>"><?php echo $map["NAME"]; ?></option>
								  <?php
								  }
							  ?>
							</select>
						</div>
						<div class="mb-3">
			        		<label class="form-label">Agent</label>
			        		<select class="form-select" aria-label="Default select example" name="agent">
							  <option selected>-- Select --</option>
							  <?php
								  $sql = "SELECT * FROM agents";
			        			$result = mysqli_query($conn, $sql);
			        			while($agent = $result->fetch_assoc()) {
								  ?>
								  	<option value="<?php echo $agent["id"]; ?>"><?php echo $agent["NAME"]; ?></option>
									<?php } ?>
							</select>
						</div>
						<div class="mb-3">
	        		<label class="form-label">Team</label>
	        		<select class="form-select" aria-label="Default select example" name="team">
							  <option selected>-- Select --</option>
							  <option value="ATTACKER">ATTACKER</option>
							  <option value="DEFENDER">DEFENDER</option>
							</select>
						</div>
				<div class="d-grid gap-2">
				  <button class="btn btn-danger" type="submit" name="find_match">FIND MATCH</button>
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