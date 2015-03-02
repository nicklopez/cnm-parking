<?php
	require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
	session_start();
	$_SESSION["adminProfileId"] = array();
	unset($_SESSION["array()"]);

	// destroy admin the session
	session_destroy();

	// redirect to login page
	header("Location: ../../admin-login/index.php"); // Redirecting To Admin Logout Message Page

?>


