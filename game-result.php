<?php
session_start();
ob_start();

if (!isset($_SESSION["account"])) {
    header("Location: logout.php");
    exit();
}

include("dbconnect.php");

// Validate and sanitize input
$game_id = filter_input(INPUT_GET, 'game_id', FILTER_SANITIZE_NUMBER_INT);
$user_agent = filter_input(INPUT_GET, 'user_agent', FILTER_SANITIZE_NUMBER_INT);
$opponent_agent = filter_input(INPUT_GET, 'opponent_agent', FILTER_SANITIZE_NUMBER_INT);

if (!$game_id || !$user_agent || !$opponent_agent) {
    header("Location: waiting-room.php");
    exit();
}

// Prepare and execute statements
$stmt = $conn->prepare("SELECT * FROM agents WHERE id = ?");
$stmt->bind_param('i', $user_agent);
$stmt->execute();
$user_agent_information = $stmt->get_result()->fetch_assoc();

$stmt->bind_param('i', $opponent_agent);
$stmt->execute();
$opponent_agent_information = $stmt->get_result()->fetch_assoc();

$stmt->bind_param('i', $game_id);
$stmt->execute();
$game = $stmt->get_result()->fetch_assoc();

$map_id = $game["MAP_ID"];
$mode = $game["MODE"];
$attacker_id = $game["ATTACKER_ID"];
$defender_id = $game["DEFENDER_ID"];
$winner = $game["WINNER"];
$status = $game["STATUS"];

$stmt->bind_param('i', $attacker_id);
$stmt->execute();
$attacker = $stmt->get_result()->fetch_assoc();

$stmt->bind_param('i', $defender_id);
$stmt->execute();
$defender = $stmt->get_result()->fetch_assoc();

if ($mode == "COMPETITIVE" && $status == "PENDING") {
    if ($winner == "ATTACKER") {
        $attacker["LEVEL"]++;
        $attacker["WINS"]++;
        $stmt = $conn->prepare("UPDATE accounts SET LEVEL = ?, WINS = ? WHERE id = ?");
        $stmt->bind_param('iii', $attacker["LEVEL"], $attacker["WINS"], $attacker_id);
        $stmt->execute();

        $defender["LOSSES"]++;
        $stmt = $conn->prepare("UPDATE accounts SET LOSSES = ? WHERE id = ?");
        $stmt->bind_param('ii', $defender["LOSSES"], $defender_id);
        $stmt->execute();
    } else if ($winner == "DEFENDER") {
        $defender["LEVEL"]++;
        $defender["WINS"]++;
        $stmt = $conn->prepare("UPDATE accounts SET LEVEL = ?, WINS = ? WHERE id = ?");
        $stmt->bind_param('iii', $defender["LEVEL"], $defender["WINS"], $defender_id);
        $stmt->execute();

        $attacker["LOSSES"]++;
        $stmt = $conn->prepare("UPDATE accounts SET LOSSES = ? WHERE id = ?");
        $stmt->bind_param('ii', $attacker["LOSSES"], $attacker_id);
        $stmt->execute();
    } else if ($winner == "DRAW") {
        $attacker["DRAWS"]++;
        $defender["DRAWS"]++;
        $stmt = $conn->prepare("UPDATE accounts SET DRAWS = ? WHERE id = ?");
        $stmt->bind_param('ii', $attacker["DRAWS"], $attacker_id);
        $stmt->execute();
        $stmt->bind_param('ii', $defender["DRAWS"], $defender_id);
        $stmt->execute();
    }

    $stmt = $conn->prepare("UPDATE games SET STATUS = 'PROCESSED' WHERE id = ?");
    $stmt->bind_param('i', $game_id);
    $stmt->execute();
}

$stmt->bind_param('i', $attacker_id);
$stmt->execute();
$attacker = $stmt->get_result()->fetch_assoc();

$stmt->bind_param('i', $defender_id);
$stmt->execute();
$defender = $stmt->get_result()->fetch_assoc();

if ($winner == "ATTACKER") {
    $victory = $attacker;
    $defeat = $defender;
} else if ($winner == "DEFENDER") {
    $victory = $defender;
    $defeat = $attacker;
}

$stmt = $conn->prepare("SELECT * FROM maps WHERE id = ?");
$stmt->bind_param('i', $map_id);
$stmt->execute();
$map = $stmt->get_result()->fetch_assoc();
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
            background-color: black;
        }
    </style>
</head>
<body>
    <?php include("navbar.php"); ?>
    <div class="container text-center" style="margin-top: 80px;">
        <div class="alert alert-dark" role="alert">
            <h4>• <?php echo htmlspecialchars($mode, ENT_QUOTES, 'UTF-8'); ?> •</h4>
            <hr>
            <h2><b><?php echo htmlspecialchars($map["NAME"], ENT_QUOTES, 'UTF-8'); ?></b></h2>
            <?php echo htmlspecialchars($map["DESCRIPTION"], ENT_QUOTES, 'UTF-8'); ?>
        </div>
    </div>
    <div class="container text-center" style="margin-top: 10px;">
        <div class="row">
            <?php if ($winner == "DRAW"): ?>
                <div class="alert alert-warning" role="alert">
                    <h4>DRAW</h4>
                </div>
                <div class="col-md-6">
                    <div class="alert alert-warning" role="alert">
                        <h2><?php echo htmlspecialchars($attacker["USERNAME"], ENT_QUOTES, 'UTF-8'); ?> [ATTACKER]</h2>
                        <img src="<?php echo htmlspecialchars($attacker["USERNAME"] == $_SESSION["account"]["USERNAME"] ? $user_agent_information["IMG_LOC"] : $opponent_agent_information["IMG_LOC"], ENT_QUOTES, 'UTF-8'); ?>" style="max-width: 100%; margin-bottom: 10px;">
                        <h4>LEVEL: <?php echo htmlspecialchars($attacker["LEVEL"], ENT_QUOTES, 'UTF-8'); ?></h4>
                        <h4>WINS: <?php echo htmlspecialchars($attacker["WINS"], ENT_QUOTES, 'UTF-8'); ?></h4>
                        <h4>LOSSES: <?php echo htmlspecialchars($attacker["LOSSES"], ENT_QUOTES, 'UTF-8'); ?></h4>
                        <h4>DRAWS: <?php echo htmlspecialchars($attacker["DRAWS"], ENT_QUOTES, 'UTF-8'); ?></h4>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="alert alert-warning" role="alert">
                        <h2><?php echo htmlspecialchars($defender["USERNAME"], ENT_QUOTES, 'UTF-8'); ?> [DEFENDER]</h2>
                        <img src="<?php echo htmlspecialchars($defender["USERNAME"] == $_SESSION["account"]["USERNAME"] ? $user_agent_information["IMG_LOC"] : $opponent_agent_information["IMG_LOC"], ENT_QUOTES, 'UTF-8'); ?>" style="max-width: 100%; margin-bottom: 10px;">
                        <h4>LEVEL: <?php echo htmlspecialchars($defender["LEVEL"], ENT_QUOTES, 'UTF-8'); ?></h4>
                        <h4>WINS: <?php echo htmlspecialchars($defender["WINS"], ENT_QUOTES, 'UTF-8'); ?></h4>
                        <h4>LOSSES: <?php echo htmlspecialchars($defender["LOSSES"], ENT_QUOTES, 'UTF-8'); ?></h4>
                        <h4>DRAWS: <?php echo htmlspecialchars($defender["DRAWS"], ENT_QUOTES, 'UTF-8'); ?></h4>
                    </div>
                </div>
            <?php else: ?>
                <div class="col-md-6">
                    <div class="alert alert-success" role="alert">
                        <h4>VICTORY</h4>
                        <hr>
                        <h2><?php echo htmlspecialchars($victory["USERNAME"], ENT_QUOTES, 'UTF-8'); ?> [<?php echo htmlspecialchars($winner == "ATTACKER" ? "ATTACKER" : "DEFENDER", ENT_QUOTES, 'UTF-8'); ?>]</h2>
                        <img src="<?php echo htmlspecialchars($victory["USERNAME"] == $_SESSION["account"]["USERNAME"] ? $user_agent_information["IMG_LOC"] : $opponent_agent_information["IMG_LOC"], ENT_QUOTES, 'UTF-8'); ?>" style="max-width: 100%; margin-bottom: 10px;">
                        <h4>LEVEL: <?php echo htmlspecialchars($victory["LEVEL"], ENT_QUOTES, 'UTF-8'); ?></h4>
                        <h4>WINS: <?php echo htmlspecialchars($victory["WINS"], ENT_QUOTES, 'UTF-8'); ?></h4>
                        <h4>LOSSES: <?php echo htmlspecialchars($victory["LOSSES"], ENT_QUOTES, 'UTF-8'); ?></h4>
                        <h4>DRAWS: <?php echo htmlspecialchars($victory["DRAWS"], ENT_QUOTES, 'UTF-8'); ?></h4>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="alert alert-danger" role="alert">
                        <h4>DEFEAT</h4>
                        <hr>
                        <h2><?php echo htmlspecialchars($defeat["USERNAME"], ENT_QUOTES, 'UTF-8'); ?> [<?php echo htmlspecialchars($winner == "ATTACKER" ? "DEFENDER" : "ATTACKER", ENT_QUOTES, 'UTF-8'); ?>]</h2>
                        <img src="<?php echo htmlspecialchars($defeat["USERNAME"] == $_SESSION["account"]["USERNAME"] ? $user_agent_information["IMG_LOC"] : $opponent_agent_information["IMG_LOC"], ENT_QUOTES, 'UTF-8'); ?>" style="max-width: 100%; margin-bottom: 10px;">
                        <h4>LEVEL: <?php echo htmlspecialchars($defeat["LEVEL"], ENT_QUOTES, 'UTF-8'); ?></h4>
                        <h4>WINS: <?php echo htmlspecialchars($defeat["WINS"], ENT_QUOTES, 'UTF-8'); ?></h4>
                        <h4>LOSSES: <?php echo htmlspecialchars($defeat["LOSSES"], ENT_QUOTES, 'UTF-8'); ?></h4>
                        <h4>DRAWS: <?php echo htmlspecialchars($defeat["DRAWS"], ENT_QUOTES, 'UTF-8'); ?></h4>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <script src="js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
