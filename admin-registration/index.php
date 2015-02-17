<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet">
		<link type="text/css" href="/lib/bootcamp-coders.css" rel="stylesheet">
		<link type="text/css" href="/images/favicon.ico" rel="shortcut icon">
		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.form/3.51/jquery.form.min.js"></script>
		<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/jquery.validate.min.js"></script>
		<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/additional-methods.min.js"></script>
		<script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>		<title>Controller: Admin Registration</title>
		<style type="text/css"></style></head>

	<?php
//
//	if (empty($_POST) === false) {
//		$requiredFields = array('adminUserName', 'adminLastName', 'adminEmail', 'password');
//		foreach($_POST as $key->$value) {
//			if (empty($value) && in_array($key, $requiredFields) === true) {
//				$errors[] = 'Fields cannot be empty';
//				break 1;
//			}
//		}
//
//		if (empty($erros) === true) {
//			if (userExists($_POST['adminFirstName']) === true) {
//
//			}
//		}
//	}



	?>
	<body>
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

			<button id="submit" type="submit" value="register">Register</button>
		</form>

	</body>
</html>