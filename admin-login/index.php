<!DOCTYPE html>
<html>
	   <head>
			<title>Login to CNM Parking Admin</title>
		</head>
	   <body>
			<form method="post" action="../php/controllers/admin-login-post.php">
				<fieldset>
					<legend>CNM Parking Admin Credential</legend>
						<p>
					   	<label for="adminEmail">Email: </label>
					   	<input type="text" name="adminEmail" id="adminEmail" placeholder="name@example.com"  />
						</p>
						<p>
							<label for="password">Password: </label>
							<input type="password" name="password" id="password" placeholder="******"  />
						</p>
				</fieldset>
					<p>
						<input id="submit" type="submit" value="submit" />
					</p>
			</form>
		</body>
</html>