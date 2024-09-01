<?php
	$servername = "localhost";
    $database = "cis1202";
    $db_username = "root";
    $db_password = "";

	$conn = mysqli_connect($servername, $db_username, $db_password, $database);

	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

?>