<?php
$pageTitle = "Admin Login";
require_once("../php/lib/header.php");

// require CSRF protection
require_once("../php/lib/csrf.php");
?>


<!--<form method="post" action="../php/controllers/admin-login-post.php">-->
<!--	<fieldset>-->
<!--		<legend>CNM Parking Admin Credential</legend>-->
<!--			<p>-->
<!--				<label for="adminEmail">Email: </label>-->
<!--				<input type="text" name="adminEmail" id="adminEmail" placeholder="name@example.com"/>-->
<!--			</p>-->
<!--			<p>-->
<!--				<label for="password">Password: </label>-->
<!--				<input type="password" name="password" id="password" placeholder="******"/>-->
<!--			</p>-->
<!--	</fieldset>-->
<!--		<p>-->
<!--			<input id="submit" type="submit" value="submit" />-->
<!--		</p>-->
<!--</form>-->

	<header>
		<h1>CNM Parking Admin Credentials </h1>
	</header>
	<form id="admin-login" method="post" action="../php/controllers/admin-login-post.php" novalidate="novalidate">
<!--		--><?php //echo generateInputTags(); ?>
		<label for="adminEmail">Email:</label>
		<input type="text" id="adminEmail" name="adminEmail" size="128" maxlength="128" placeholder="name@example.com"><br>
		<label for="password">Password:</label>
		<input type="password" id="password" name="password" size="128" maxlength="128" placeholder="******"><br>
		<button id="submit" type="submit" value="submit">Log In</button>
	</form>
	<p id="outputArea"></p>

<?php
require_once("../php/lib/footer.php");
?>