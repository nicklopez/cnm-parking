<?php
include("../../php/controllers/admin-login-post.php");
$_SESSION["adminProfileId"] = $adminProfile->getAdminProfileId();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Test Portal</title>
	</head>
	<body>
		<div id="adminProfile">
			<p id="welcome">Welcome : <?php echo $_SESSION["adminProfileId"]; ?></p>
			<form method="post" action="../../admin-login/index.php">
				<input type="submit" id="logout" value="Logout">
			</form>
		</div>
	</body>
</html>