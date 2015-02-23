<?php
$_SESSION["adminProfileId"] = $adminProfile->getAdminProfileId();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Test Portal</title>
	</head>
	<body>
		<div id="adminProfile">
			<p> id="welcome">Welcome : <?php echo $_SESSION["adminProfileId"]; ?></p>
			<a href="../../admin-login/index.php">Log Out</a>
		</div>
	</body>
</html>