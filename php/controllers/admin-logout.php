<?php
	require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
	$_SESSION["adminProfileId"] = array();
	session_start();
	unset($_SESSION["adminProfileId"]);

	// destroy admin the session
	session_destroy();

	// redirect to login page
	header("Location: ../../admin-login/index.php"); // Redirecting To Admin Logout Message Page

?>


