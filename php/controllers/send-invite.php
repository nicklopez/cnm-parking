<!DOCTYPE html>
<html>
	<head>
		<?php
		// start a PHP session for CSRF protection
		session_start();

		// require CSRF protection
		require_once("../lib/csrf.php");

		// require the encrypted configuration functions
		require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

		// require the mySQL enabled Invite class
		require_once("../classes/invite.php");

		?>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet">
		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.form/3.51/jquery.form.min.js"></script>
		<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/jquery.validate.min.js"></script>
		<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/additional-methods.min.js"></script>
		<script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="../js/request-invite.js"></script>
		<title>Controller: Send Invite</title>
	</head>
	<body>
		<h1>Controller: Send a parking pass invite</h1>
		<form id="send-invite" method="post" action="send-invite-post.php" novalidate="novalidate">
			<?php echo generateInputTags();

			try {
				// now retrieve the configuration parameters
				$configFile = "/etc/apache2/capstone-mysql/cnmparking.ini";
				$configArray = readConfig($configFile);

				// first, connect to mysqli
				mysqli_report(MYSQLI_REPORT_STRICT);
				$mysqli = new mysqli($configArray["hostname"], $configArray["username"], $configArray["password"], $configArray["database"]);

				// retrieve all pending invites
				$invites = Invite::getPendingInvite($mysqli);
				foreach($invites as $invite) {
					echo "<tr><td>" . $invite->getInviteId() . "</td><br/><td>" . $invite->getActivation() . "</td><br/><td>" . $invite->getVisitorId() . "</td></tr>";
				}
				$mysqli->close();
			} catch(Exception $exception) {
				echo "<td><tr class=\"alert alert-danger\" colspan=\"4\">Exception: " . $exception->getMessage() . "</td></tr>";
			}
			?>
			<button id="sendInvite" type="submit">Send Invite</button>
		</form>
		<p id="outputArea"></p>
	</body>
</html>