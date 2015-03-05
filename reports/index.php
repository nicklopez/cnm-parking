<?php
$pageTitle = "Reports";
require_once("../php/lib/header.php");
?>
<form method="post" action="../php/controllers/reports-post.php" novalidate="novalidate">
<header>
	<h1>Reports</h1>
</header>
<table>
	<tbody>
		<tr><td>Start Date:</td><td><input id="startDate" name="startDate" type="date"></td></tr>
		<tr><td>End Date:</td><td><input id="endDate" name="endDate" type="date"></td></tr>
	</tbody>
</table>
<button type="submit">Run Report</button>
	</form>

<?php require_once("../php/lib/footer.php"); ?>
