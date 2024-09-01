<?php
	session_start();
	ob_start();
	ob_flush();
	session_destroy();
	header("Location: index.php");
?>