<?php
$title = "CNM Parking Admin Portal";
$headerTitle = "CNM Parking Admin Portal";
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

session_start();
$_SESSION = array();

// destroy admin the session
session_destroy();

// redirect to login page
header("Location: ../../admin-logout/index.php"); // Redirecting To Admin Logout Message Page

?>


