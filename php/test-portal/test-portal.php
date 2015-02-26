<?php
session_start();
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
require_once("../classes/adminprofile.php");


mysqli_report(MYSQLI_REPORT_STRICT);
$configArray = readConfig("/etc/apache2/capstone-mysql/cnmparking.ini");
$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);
$adminProfile = AdminProfile::getAdminProfileByAdminProfileId($mysqli, $_SESSION['adminProfileId']);

$adminProfileId = $adminProfile->getAdminProfileId();


?>
<!DOCTYPE html>
<html>
	<head>
		<title>Test Portal</title>
	</head>
	<body>
		<div id="adminProfile">
			<p id="welcome">Welcome Admin: <?php echo $adminProfileId; ?>
			<form action="../controllers/admin-logout.php" method="post">
				    <input id="logout" type="submit" value="logout" />
			</form>

		</div>
	</body>
</html>

