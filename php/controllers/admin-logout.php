<?php
	require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
	session_start();
	$_SESSION = array();
	unset($_SESSION);

	// destroy admin the session
	session_destroy();

	// redirect to login page
	header("Location: ../../admin-login/index.php"); // Redirecting To Admin Logout Message Page

?>


