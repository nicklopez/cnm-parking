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

<nav class="navbar navbar-default ">
	<div id="adminProfile">
		<p id ="welcome" class="navbar-text pull-right">Welcome back, <?php echo $adminProfile->getAdminFirstName(); ?></p>
	</div>
	<div class="container-fluid">
		<ul class="nav nav-tabs">
			<li role="presentation" class="active"><a href="#">Home</a></li>
			<li role="presentation"><a href="#">Create Parking Pass</a></li>
			<li role="presentation"><a href="#">Manage Parking Spots</a></li>
			<li role="presentation"><a href="#">Reports</a></li>
		</ul>
	</div>
	<form action="../controllers/admin-logout.php" method="post">
		    <input id="logout" type="submit" class="btn btn-primary pull-right" value="logout" />
	</form>

</nav>
	<body>



		</div>
	</body>

<?php
require_once("../../php/lib/footer.php");
?>
