<?php
$title = "Results";
$headerTitle = "Results";

// start a PHP session
session_start();

require_once("../lib/header.php");

require_once("../classes/parkingpass.php");

// require the encrypted configuration functions
require_once("/home/cnmparki/etc/mysql/encrypted-config.php");

try {
	// now retrieve the configuration parameters
	$configFile = "/home/cnmparki/etc/mysql/cnmparking.ini";
	$configArray = readConfig($configFile);

	// Connect to mySQL
	$host = $configArray["hostname"];
	$db = $configArray["database"];
	$dsn = "mysql:host=$host;dbname=$db";
	$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
	$pdo = new PDO($dsn, $configArray["username"], $configArray["password"], $options);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

	?>
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<a id="logout" href="../../php/controllers/admin-logout.php" class="btn btn-primary navbar-btn pull-right">Log Out</a>
			<p id="welcome" class="navbar-text pull-right">Welcome back, <?php echo $_SESSION["adminFirstName"]; ?></p>
			<ul class="nav navbar-nav">
				<li role="presentation" class="active"><a class="navbar-brand" href="../portal-home/index.php">Home</a></li>
				<li role="presentation"><a href="../../create-pass">Create Parking Pass</a></li>
				<li role="presentation"><a href="../../send-invite">Manage Invites</a></li>
				<li role="presentation"><a href="../../manage-parking">Manage Parking</a></li>
				<li role="presentation"><a href="../../reports">Reports</a></li>
			</ul>
		</div>
	</nav>
	<div class="container-fluid">
		<table id="reports" class="hover row-border">
			<thead>
				<th>Location</th>
				<th>Placard #</th>
				<th>Visitor Name</th>
				<th>Vehicle Plate #</th>
				<th>Date / Time</th>
				<th>Approved By</th>
			</thead>
			<tbody>

			<?php

			$begin = $_POST["reportStartDate"];
			$end = $_POST["reportEndDate"];

			$parkingPass = ParkingPass::getVisitorParkingDataByDateRange($pdo, $begin, $end);

			if (count($parkingPass) === 0) {
				echo "</tbody>";
				echo "</table>";
				echo "</div>";
				require_once("../lib/footer.php");

			} else {
				foreach($parkingPass as $pass) {
					$locationDesc = $pass["locationDescription"] . " - " . $pass["locationNote"];
					$visitor = $pass["visitorFirstName"] . " " . $pass["visitorLastName"];
					$admin = $pass["adminFirstName"] . " " . $pass["adminLastName"];
					$plateNumber = $pass["vehiclePlateNumber"];
					$placardNumber = $pass["placardNumber"];
					$startDate = new DateTime($pass["startDateTime"]);
					$formattedStartDate = $startDate->format("m-d-Y (g:iA");
					$endDate = new DateTime($pass["endDateTime"]);
					$formattedEndTime = $endDate->format("g:iA");
					$dateTimeRange = $formattedStartDate . " - " . $formattedEndTime . ")";
					$row = <<< EOF
				<tr><td>$locationDesc</td><td>$placardNumber</td><td>$visitor</td><td>$plateNumber</td><td>$dateTimeRange</td>
				<td>$admin</td></tr>
EOF;
					echo $row;
				}
			}

	echo "</tbody>";
	echo "</table>";
	echo "</div>";

} catch(Exception $exception) {
	echo "<td><tr class=\"alert alert-danger\" colspan=\"3\">Exception: " . $exception->getMessage() . "</td></tr>";
}

require_once("../lib/footer.php");
?>