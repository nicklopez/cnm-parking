<?php
	require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

	session_start();
	unset($_SESSION["adminProfileId"]);

	// destroy admin the session
	session_destroy();

	// redirect to login page
	echo "You have been logged out.";
	header("Location: https://bootcamp-coders.cnm.edu/~dfevig/cnm-parking/admin-login/index.php"); // Redirecting To Admin Login Page

?>


