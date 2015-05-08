<?php
$title = "Log";
$headerTitle = "Parking Pass Log";

// start a PHP session
session_start();

require_once("../php/lib/header.php");

require_once("../php/classes/log.php");

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
			<a id="logout" href="//cnmparking.com/php/controllers/admin-logout.php" class="btn btn-primary navbar-btn pull-right">Log Out</a>
			<p id="welcome" class="navbar-text pull-right">Welcome back, <?php echo $_SESSION["adminFirstName"]; ?></p>
			<ul class="nav navbar-nav">
				<li role="presentation" class="active"><a class="navbar-brand" href="//cnmparking.com/php/portal-home/index.php">Home</a></li>
				<li role="presentation"><a href="//cnmparking.com/create-pass">Create Parking Pass</a></li>
				<li role="presentation"><a href="//cnmparking.com/send-invite">Manage Invites</a></li>
				<li role="presentation"><a href="//cnmparking.com/manage-parking">Manage Parking</a></li>
				<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Reports <span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu">
						<li><a href="//cnmparking.com/reports/vp.php">Visitor Parking Data</a></li>
						<li><a href="//cnmparking.com/reports/log.php">Parking Pass Log</a></li>
					</ul>
				</li>
			</ul>
		</div>
	</nav>

	<div class="container-fluid">
		<table id="log" class="hover row-border">
			<thead>
				<th>Log Date</th>
				<th>End Requestor</th>
				<th>Log Message</th>
			</thead>
			<tbody>

			<?php

			$logs = Log::getParkingPassLog($pdo);

			if (count($logs) === 0) {
				echo "</tbody>";
				echo "</table>";
				echo "</div>";
				require_once("../php/lib/footer.php");

			} else {
				foreach($logs as $log) {
					$visitor = $log["visitor"];
					$admin = $log["admin"];

					if($visitor === null) {
						$requestor = $admin;
					} else {
						$requestor = $visitor;
					}

					$message = $log["message"];
					$logDate = new DateTime($log["logDateTime"]);
					$formattedLogDate = $logDate->format("m-d-Y g:iA");
					$row = <<< EOF
				<tr><td>$formattedLogDate</td><td>$requestor</td><td>$message</td></tr>
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

require_once("../php/lib/footer.php");
?>