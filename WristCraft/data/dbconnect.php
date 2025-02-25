<?php

	header("Cache-Control: no-cache, no-store, must-revalidate");
	header("Pragma: no-cache");
	header("Expires: 0");

	require_once("configure.php");
	$con = mysqli_connect($host, $username, $password, $database) or die("Cannot connect to database.");
	mysqli_set_charset($con, "utf8");
        
?>