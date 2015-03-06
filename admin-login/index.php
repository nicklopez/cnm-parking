<?php
// required label variables
$title = "Admin Login";
$headerTitle = "CNM Parking Admin Credentials";

// require header file
require_once("../php/lib/header.php");

// require CSRF protection
require_once("../php/lib/csrf.php");

//start a session
session_start();

?>
	<form id="admin-login" method="post" action="../php/controllers/admin-login-post.php" novalidate="novalidate">
		<?php echo generateInputTags(); ?>
		<div class="control-group">
			<label for="adminEmail">Email:</label>
			<input type="text" class="form-control email" id="adminEmail" name="adminEmail" size="128" maxlength="128" placeholder="name@example.com"><br>
		</div>
		<div class="form-group">
			<label for="password">Password:</label>
			<input type="password" class="form-control name" id="password" name="password" size="128" maxlength="128" placeholder="******"><br>
		</div>
		<button id="submit" type="submit" class="btn btn-primary" value="submit">Log In</button>
	</form>
	<p id="outputArea"></p>

<?php
require_once("../php/lib/footer.php");
?>