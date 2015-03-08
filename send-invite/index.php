<?php
// require the header file
$title = "Send Parking Pass Invite";
$headerTitle = "Send a parking pass invite";
require_once("../php/lib/header.php");

// start a PHP session
session_start();
?>

<nav class="navbar navbar-default">
	<div class="container-fluid">
		<a id="logout" href="../php/controllers/admin-logout.php" class="btn btn-primary navbar-btn pull-right">Log Out</a>
		<p id="welcome" class="navbar-text pull-right">Welcome back, <?php echo $_SESSION["adminFirstName"]; ?></p>
		<ul class="nav navbar-nav">
			<li role="presentation" class="active"><a class="navbar-brand" href="../php/test-portal/test-portal.php">Home</a></li>
			<li role="presentation"><a href="../create-pass">Create Parking Pass</a></li>
			<li role="presentation"><a href="../send-invite">Manage Invites</a></li>
			<li role="presentation"><a href="../manage-parking">Manage Parking</a></li>
			<li role="presentation"><a href="../reports">Reports</a></li>
		</ul>
	</div>
</nav>

<?php
// require the encrypted configuration functions
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

// require the mySQL enabled Invite class
require_once("../php/classes/invite.php");
?>

<?php
try {
	// now retrieve the configuration parameters
	$configFile = "/etc/apache2/capstone-mysql/cnmparking.ini";
	$configArray = readConfig($configFile);

	// first, connect to mysqli
	mysqli_report(MYSQLI_REPORT_STRICT);
	$mysqli = new mysqli($configArray["hostname"], $configArray["username"], $configArray["password"], $configArray["database"]);
	// retrieve all pending invites
	$invites = Invite::getPendingInvite($mysqli);

	if(count($invites) !== 0) {
		echo '<table id="invite" class="hover row-border" style="width:80%">';
		echo "<tr><th>Name</th><th>Email</th><th>Action</th></tr>";

		foreach($invites as $invite) {
			$activation = $invite["activation"];
			$email = $invite["visitorEmail"];
			$visitorName = $invite["fullName"];
			$row = <<< EOF
			<tr><td id="name">$visitorName</td><td>$email</td><td>
			<button id="accept" class="btn btn-success" onclick="acceptInvite('$activation');">
			<span class="glyphicon glyphicon-ok">Accept</span>
			</button>
			<button id="decline" class="btn btn-danger" onclick="declineInvite('$activation');">
			<span class="glyphicon glyphicon-remove">Decline</span>
			</button></td></tr>
EOF;
			echo $row;
		}
	} else {
		echo "<div class=\"alert alert-success\" role=\"alert\"><strong>No pending invites at this time</strong></div>";
		require_once("../php/lib/footer.php");
		return;

	}
	$mysqli->close();
} catch(Exception $exception) {
	echo "<td><tr class=\"alert alert-danger\" colspan=\"3\">Exception: " . $exception->getMessage() . "</td></tr>";
}
?>
</table>
<p id="outputArea"></p>

<!--require the footer file-->
<?php require_once("../php/lib/footer.php"); ?>