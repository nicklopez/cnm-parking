<!DOCTYPE html>
<html>
	   <head>
			<title>Login to CNM Parking Admin</title>
		</head>
	   <body>
			<form method="post" action="admin-login-post.php">
				<fieldset>
					<legend>CNM Parking Admin Credential</legend>
						<p>
					   	<label for="adminEmail">Email: </label>
					   	<input type="text" name="adminEmail" id="adminEmail" placeholder="name@example.com" value="" />
						</p>
						<p>
							<label for="password">Password: </label>
							<input type="password" name="password" id="password" placeholder="******" value="" />
						</p>
				</fieldset>
					<p>
						<input type="submit" value="Submit" />
					</p>
			</form>
		</body>
</html>