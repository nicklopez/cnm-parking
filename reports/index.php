<?php
$pageTitle = "Visitor Parking Data";
require_once("../php/lib/header.php");
?>

<header>
	<h1>Visitor Parking Data</h1>
</header>
<form method="post" action="../php/controllers/reports-post.php">
	<div class="form-group">
		<label for="startDate">Start Date</label>
		<input type="date" class="form-control" id="startDate" name="startDate">
	</div>
	<div class="form-group">
		<label for="endDate">End Date</label>
		<input type="date" class="form-control" id="endDate" name="endDate">
	</div>
	<button type="submit" class="btn btn-default">Run Report</button>
</form>

<?php require_once("../php/lib/footer.php"); ?>
