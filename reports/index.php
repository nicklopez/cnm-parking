<?php
$title = "Visitor Parking Data";
$headerTitle = "Visitor Parking Data";
require_once("../php/lib/header.php");
require_once("../php/lib/csrf.php");
// start a PHP session
session_start();

// set url to session variable
$_SESSION['url'] = $_SERVER['REQUEST_URI'];

// check for active session
if(!isset($_SESSION["adminProfileId"])) {
	header("location: ../admin-login/index.php");
}
?>

<nav class="navbar navbar-default">
	<div class="container-fluid">
		<a id="logout" href="../php/controllers/admin-logout.php" class="btn btn-primary navbar-btn pull-right">Log Out</a>
		<p id="welcome" class="navbar-text pull-right">Welcome back, <?php echo $_SESSION["adminFirstName"]; ?></p>
		<ul class="nav navbar-nav">
			<li role="presentation" class="active"><a class="navbar-brand" href="../php/portal-home/index.php">Home</a></li>
			<li role="presentation"><a href="../create-pass">Create Parking Pass</a></li>
			<li role="presentation"><a href="../send-invite">Manage Invites</a></li>
			<li role="presentation"><a href="../manage-parking">Manage Parking</a></li>
			<li role="presentation"><a href="../reports">Reports</a></li>
		</ul>
	</div>
</nav>
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
				<form id="reports-form" method="post" action="../php/controllers/reports-post.php">
				<?php echo generateInputTags(); ?>
				<div class="form-group">
					<label for="reportStartDate">Start Date</label>
					<input type="date" class="form-control date" id="reportStartDate" name="reportStartDate" placeholder="mm/dd/yyyy">
				</div>
				<div class="form-group">
					<label for="reportEndDate">End Date</label>
					<input type="date" class="form-control date" id="reportEndDate" name="reportEndDate" placeholder="mm/dd/yyyy">
				</div>
				<button type="submit" class="btn btn-primary">Run Report</button>
			</form>
		</div>
	</div>
</div>

<?php require_once("../php/lib/footer.php"); ?>
