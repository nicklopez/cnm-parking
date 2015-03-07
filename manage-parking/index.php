<?php
session_start();
$title = "Manage Parking";
$headerTitle = "Manage locations & parking spots";
require_once("../php/lib/header.php");
require_once("../php/classes/location.php");
require_once("../php/classes/parkingspot.php");
?>

<nav class="navbar navbar-default">
	<div class="container-fluid">
		<a id="logout" href="../php/controllers/admin-logout.php" class="btn btn-primary navbar-btn pull-right">Log Out</a>
		<p id="welcome" class="navbar-text pull-right">Welcome back, <?php echo $_SESSION["adminFirstName"]; ?></p>
		<ul class="nav navbar-nav">
			<li role="presentation" class="active"><a class="navbar-brand" href="../php/test-portal/test-portal.php">Home</a></li>
			<li role="presentation"><a href="../personal-vehicle">Create Parking Pass</a></li>
			<li role="presentation"><a href="../send-invite">Manage Invites</a></li>
			<li role="presentation"><a href="../manage-parking">Manage Parking</a></li>
			<li role="presentation"><a href="../reports">Reports</a></li>
		</ul>
	</div>
</nav>

<?php
// require the encrypted configuration functions
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

try {
// now retrieve the configuration parameters
$configFile = "/etc/apache2/capstone-mysql/cnmparking.ini";
$configArray = readConfig($configFile);

// first, connect to mysqli
mysqli_report(MYSQLI_REPORT_STRICT);
$mysqli = new mysqli($configArray["hostname"], $configArray["username"], $configArray["password"], $configArray["database"]);

$objects = Location::getAllLocationsAndParkingSpots($mysqli);

?>

<table id="example" class="hover row-border">
	<thead>
	<th>Location</th>
		<th></th>

	</thead>
	<tbody>
	<?php

	foreach($objects as $object) {
		$locationId = $object["locationId"];
		$locationNote = $object["locationNote"];
		$locationDesc = $object["locationDescription"];
		$placard = $object["placardNumber"];
		$row = <<< EOF
		<tr><td class="info" hidden="hidden">$locationDesc</td><td>$placard</td><td>$locationDesc - $locationNote</td></tr>
EOF;
			echo $row;
		}
	echo "</tbody>";
	echo "</table>";
	$mysqli->close();

	} catch(Exception $exception) {
		echo "<td><tr class=\"alert alert-danger\" colspan=\"3\">Exception: " . $exception->getMessage() . "</td></tr>";
	}

	require_once("../php/lib/footer.php");
	?>
