<?php
require_once("../php/lib/header.php");
?>


		<h1>CNM Parking Admin Registration</h1>
		<form method="post" action="../php/controllers/admin-registration-post.php">

			<label for="adminFirstName">First Name</label>
			<input type="text" id="adminFirstName" name="adminFirstName"><br>

			<label for="adminLastName">Last Name</label>
			<input type="text" id="adminLastName" name="adminLastName"><br>

			<label for="adminEmail">Email</label>
			<input type="text" id="adminEmail" name="adminEmail"><br>

			<label for="password">Password</label>
			<input type="password" id="password" name="password"><br>

			<button id="submit" type="submit" value="submit">Register</button>
		</form>

<?php
require_once("../php/lib/footer.php");
?>