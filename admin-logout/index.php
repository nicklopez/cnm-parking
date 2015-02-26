<?php
$pageTitle = "Logout CNM Parking Admin";
require_once("../php/lib/header.php");

if(!isset($_SESSION["adminProfileId"])) {
			echo '<div class="alert alert-success" role="alert" id="message">You have been logged out</div>';
		}
		?>
		<form method="post" action="../admin-login/index.php">
			<input type="submit" id="logout" value="Return to Login Page">
		</form>

<?php
require_once("../php/lib/footer.php");
?>n