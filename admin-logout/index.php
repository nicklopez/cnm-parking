<?php
$title = "CNM Parking Admin Portal";
$headerTitle = "CNM Parking Admin Portal";
require_once("../php/lib/header.php");

if(!isset($_SESSION["adminProfileId"])) {
	echo '<div class="alert alert-success" role="alert" id="message">You have logged out successfully.</div>';
}
?>
	<div class="container-fluid">
	<a class="btn btn-primary" href="../admin-login/index.php">Return to Log In</a>
		</div>

<?php
require_once("../php/lib/footer.php");
?>