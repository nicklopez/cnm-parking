<!DOCTYPE html>
<html>
	<head>
		<?php // start a PHP session for CSRF protection
		session_start();
		// require CSRF protection
		require_once("../lib/csrf.php");
?>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet">
		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.form/3.51/jquery.form.min.js"></script>
		<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/jquery.validate.min.js"></script>
		<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/additional-methods.min.js"></script>
		<script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
		<title>Controller: Request Invite</title>
	</head>
	<body>
		<h1>Controller: Request a parking pass invite</h1>
		<form method="post" action="request-invite-post.php">
			<?php echo generateInputTags(); ?>
			<label for="emailAddress">Email:</label>
			<input type="text" id="emailAddress" name="emailAddress" size="128" maxlength="128" placeholder="name@example.com"><br>
			<input type="text" id="firstName" name="firstName" size="128" maxlength="128" placeholder="First Name"><br>
			<input type="text" id="lastName" name="lastName" size="128" maxlength="128" placeholder="Last Name"><br>
			<input type="text" id="phone" name="phone" size="128" maxlength="128" placeholder="Phone Number"><br>
			<button id="sendRequest" type="submit">Send Request</button>
		</form>
	</body>
</html>