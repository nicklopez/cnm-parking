<script type="text/javascript" src="../js/request-invite.js"></script>
<?php
// require the header file
$pageTitle = "Controller - Request Parking Pass Invite";
require_once("../php/lib/header.php");

// start a PHP session for CSRF protection
session_start();

// require CSRF protection
require_once("../php/lib/csrf.php");
?>
	<h1>Controller: Request a parking pass invite</h1>
	<form id="request-invite" method="post" action="../php/controllers/request-invite-post.php" novalidate="novalidate">
		<?php echo generateInputTags(); ?>
		<label for="emailAddress">Email:</label>
		<input type="text" id="emailAddress" name="emailAddress" size="128" maxlength="128" placeholder="name@example.com"><br>
		<label for="firstName">First Name:</label>
		<input type="text" id="firstName" name="firstName" size="128" maxlength="128" placeholder="First Name"><br>
		<label for="lastName">Last Name:</label>
		<input type="text" id="lastName" name="lastName" size="128" maxlength="128" placeholder="Last Name"><br>
		<label for="phone">Phone:</label>
		<input type="text" id="phone" name="phone" size="128" maxlength="128" placeholder="Phone Number"><br>
		<button id="sendRequest" type="submit">Send Request</button>
	</form>
	<p id="outputArea"></p>

	<!--	require the footer file-->
	<?php require_once("../php/lib/footer.php"); ?>