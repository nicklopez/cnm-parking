<?php
$title = "Visitor Parking Data";
$headerTitle = "Visitor Parking Data";
require_once("../php/lib/header.php");

// start a PHP session for CSRF protection
session_start();

// require CSRF protection
require_once("../php/lib/csrf.php");
?>

<form method="post" action="../php/controllers/reports-post.php">
	<?php echo generateInputTags(); ?>
	<div class="form-group">
		<label for="startDate">Start Date</label>
		<input type="date" class="form-control date" id="startDate" name="startDate">
	</div>
	<div class="form-group">
		<label for="endDate">End Date</label>
		<input type="date" class="form-control date" id="endDate" name="endDate">
	</div>
	<button type="submit" class="btn btn-primary">Run Report</button>
</form>

<?php require_once("../php/lib/footer.php"); ?>
