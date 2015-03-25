<?php
session_start();
$title = "CNM Admin Portal";
$headerTitle = "CNM Admin Portal";
require_once("/home/cnmparki/etc/mysql/encrypted-config.php");
require_once("../classes/adminprofile.php");
require_once("../../php/lib/header.php");
?>

<nav class="navbar navbar-default">
	<div class="container-fluid">
		<a id="logout" href="../../php/controllers/admin-logout.php" class="btn btn-primary navbar-btn pull-right">Log Out</a>
		<p id="welcome" class="navbar-text pull-right">Welcome, <?php echo $_SESSION["adminFirstName"]; ?></p>
		<ul class="nav navbar-nav">
			<li role="presentation" class="active"><a class="navbar-brand" href="../../php/test-portal/test-portal.php">Home</a></li>
			<li role="presentation"><a href="../../create-pass">Create Parking Pass</a></li>
			<li role="presentation"><a href="../../send-invite">Manage Invites</a></li>
			<li role="presentation"><a href="../../manage-parking">Manage Parking</a></li>
			<li role="presentation"><a href="../../reports">Reports</a></li>
		</ul>
	</div>
</nav>

<?php
require_once("../../php/lib/footer.php");
?>
