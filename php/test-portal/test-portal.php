<?php
session_start();
$title = "CNM Admin Portal";
$headerTitle = "CNM Admin Portal";
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
require_once("../classes/adminprofile.php");
require_once("../../php/lib/header.php");

mysqli_report(MYSQLI_REPORT_STRICT);
$configArray = readConfig("/etc/apache2/capstone-mysql/cnmparking.ini");
$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);
$adminProfile = AdminProfile::getAdminProfileByAdminProfileId($mysqli, $_SESSION['adminProfileId']);
$adminProfileId = $adminProfile->getAdminProfileId();
?>

<form action="../controllers/admin-logout.php" method="post">
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<button id="logout" type="submit" class="btn btn-primary navbar-btn pull-right">Log Out</button>
			<p id ="welcome" class="navbar-text pull-right">Welcome back, <?php echo $adminProfile->getAdminFirstName(); ?></p>
			<ul class="nav navbar-nav">
				<a class="active navbar-brand" href="test-portal.php">Home</a>
				<li role="presentation"><a href="../../personal-vehicle">Create Parking Pass</a></li>
				<li role="presentation"><a href="../../manage-parking">Manage Parking</a></li>
				<li role="presentation"><a href="../../reports">Reports</a></li>
			</ul>
		</div>
	</nav>
</form>

<?php
require_once("../../php/lib/footer.php");
?>
