<?php
// start admin restart session
session_start();

// set session to an empty array
$_SESSION = array();

// destroy the cookie
$params = session_get_cookie_params();
setcookie(session_name(), "", 1, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);

// unset sesstion
session_unset();

// destroy admin the session
session_destroy();

var_dump($_SESSION['login user']);

// redirect to login page
header("Location: index.php"); // Redirecting To Home Page
?>