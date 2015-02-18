<!DOCTYPE html>
<html>
	<head>
		<?php
		// start a PHP session for CSRF protection
		session_start();

		// require CSRF protection
		require_once("../php/lib/csrf.php");

		// require the encrypted configuration functions
		require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

		// require the mySQL enabled Invite class
		require_once("../php/classes/invite.php");

		?>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet">
		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.form/3.51/jquery.form.min.js"></script>
		<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/jquery.validate.min.js"></script>
		<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/additional-methods.min.js"></script>
		<script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="../js/send-invite.js"></script>
		<title>Controller: Send Invite</title>
	</head>
	<body>
		<h1>Controller: Send a parking pass invite</h1>
		<table class="table-bordered table-responsive table-striped">
			<tr><th>Select</th><th>Name</th><th>Email</th></tr>
		<form id="send-invite" method="post" action="../php/controllers/send-invite-post.php" novalidate="novalidate">
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
				if(count($invites) !== 0) {
					foreach($invites as $invite)
			echo "<tr><td><input type=Checkbox></td><td>" . $invite["fullName"] . "</td><td>" . $invite["visitorEmail"] . "</td></tr>";
				} else {
					echo "<h2>No pending invites at this time</h2>";
					return;
				}
				$mysqli->close();
			} catch(Exception $exception) {
				echo "<td><tr class=\"alert alert-danger\" colspan=\"3\">Exception: " . $exception->getMessage() . "</td></tr>";
			}
			?>
		</table>
			<button id=accept class="btn btn-default">
				<span class="glyphicon glyphicon-ok">Accept</span>
			</button>
			<button id=decline class="btn btn-default">
				<span class="glyphicon glyphicon-remove">Decline</span>
			</button>
		</form>
		<p id="outputArea"></p>
	</body>
</html>