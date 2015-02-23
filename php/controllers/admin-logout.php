<?php
// start admin restart session
session_start();

// destroy admin the session
session_destroy();

var_dump($_SESSION['login user']);

// redirect to login page
header("Location: /login/index.php"); // Redirecting To Admin Login Page
?>