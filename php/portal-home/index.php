<?php
session_start();

// assign url to session variable
$_SESSION['url'] = $_SERVER['REQUEST_URI'];

// checks for active session
if(!isset($_SESSION["adminProfileId"])) {
	header("location: ../../admin-login/index.php");
}

$title = "CNM Admin Portal";
$headerTitle = "CNM Admin Portal";
require_once("/home/cnmparki/etc/mysql/encrypted-config.php");
require_once("../classes/adminprofile.php");
require_once("../../php/lib/header.php");
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

<div id="fullCalendar" class="container-fluid"></div>

<!--<iframe src="http://stemuluscenter.org/" width="100%" height="800px"></iframe>-->
<?php
require_once("../../php/lib/footer.php");
?>
