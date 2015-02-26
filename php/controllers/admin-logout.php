<?php

	session_start();
	unset($_SESSION["adminProfileId"]);

	// destroy admin the session
	session_destroy();

// redirect to login page
header("Location: https://bootcamp-coders.cnm.edu/~dfevig/cnm-parking/admin-login/index.php"); // Redirecting To Admin Login Page
echo "You have been logged out.";
var_dump($_SESSION["adminProfileId"]);
?>

