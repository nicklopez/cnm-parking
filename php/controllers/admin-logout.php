<?php
	require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

	session_start();
	unset($_SESSION["adminProfileId"]);

	// destroy admin the session
	session_destroy();

	// redirect to login page
	header("Location: https://bootcamp-coders.cnm.edu/~dfevig/cnm-parking/admin-logout/index.php"); // Redirecting To Admin Logout Message Page

?>


