<?php
	/*ini_set("display_errors", 0);
	error_reporting(0);*/

	$base_path		= "https://store.kottykosmetik.com/fp/";
	$db_name		= "fingerprint";
	$db_user		= "fprint";
	$db_pass		= "fzTzVjZvE4oVdTAW";
	$db_host		= "127.0.0.1";
	$time_limit_reg = "15";
	$time_limit_ver = "10";

	$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

	if (!$conn) die("Connection for user $db_user refused!");
?>
