<!DOCTYPE html>
<html>
	<head>
		<title>Logout CNM Parking Admin</title>
	</head>
	   
	<body>
		<?php
		if(!isset($_SESSION["adminProfileId"])) {
			echo '<div class="alert alert-success" role="alert" id="message">You have been logged out</div>';
		}
		?>
		<form method="post" action="../admin-login/index.php">
			<input type="submit" id="logout" value="Return to Login Page">
		</form>
	</body>
</html>
