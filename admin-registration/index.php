<?php
$pageTitle = "Admin Registration";
require_once("../php/lib/header.php");
?>

<!--		<h1>CNM Parking Admin Registration</h1>-->
<!--		<form method="post" action="../php/controllers/admin-registration-post.php">-->
<!---->
<!--			<label for="adminFirstName">First Name</label>-->
<!--			<input type="text" id="adminFirstName" name="adminFirstName"><br>-->
<!---->
<!--			<label for="adminLastName">Last Name</label>-->
<!--			<input type="text" id="adminLastName" name="adminLastName"><br>-->
<!---->
<!--			<label for="adminEmail">Email</label>-->
<!--			<input type="text" id="adminEmail" name="adminEmail"><br>-->
<!---->
<!--			<label for="password">Password</label>-->
<!--			<input type="password" id="password" name="password"><br>-->
<!---->
<!--			<button id="register" type="submit">Register</button>-->
<!--		</form>-->

	<header>
		<h1>CNM Parking Admin Registration</h1>
	</header>
	<form id="admin-registration" method="post" action="../php/controllers/admin-registration-post.php" novalidate="novalidate">
<!--		--><?php //echo generateInputTags(); ?>
		<label for="adminFirstName">First Name:</label>
		<input type="text" id="adminFirstName" name="adminFirstName" size="128" maxlength="128" placeholder="First Name"><br>
		<label for="AdminLastName">Last Name:</label>
		<input type="text" id="adminLastName" name="adminLastName" size="128" maxlength="128" placeholder="Last Name"><br>
		<label for="adminEmail">Email:</label>
		<input type="text" id="adminEmail" name="adminEmail" size="128" maxlength="128" placeholder="name@example.com"><br>
		<label for="password">Password:</label>
		<input type="password" id="password" name="password" size="128" maxlength="128" placeholder="********"><br>

		<button id="register" type="submit">Register</button>
	</form>
	<p id="outputArea"></p>

<?php
require_once("../php/lib/footer.php");
?>