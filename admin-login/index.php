<?php
$pageTitle = "Admin Login";
require_once("../php/lib/header.php");
?>

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

<?php
var_dump($_SESSION["adminProfileId"]);
require_once("../php/lib/footer.php");
?>