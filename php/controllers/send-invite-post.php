<?php

// verify the form values have been submitted
if(isset($_POST["emailAddress"]) == FALSE) {
	echo "<p class=\"alert alert-danger\">Please enter or verify the email address.</p>";
}

try {
// headers for HTML content
	$headers = "MIME-Version: 1.0 \r\n";
	$headers = $headers . "Content-type: text/html; charset=iso-8859-1 \r\n";

// additional email headers
	$headers = $headers . "From: CNM Parking <noreply@bootcamp-coders.cnm.edu>" . "\r\n";

// variables for email message
	$to = $_POST["emailAddress"];
	$subject = "CNM Parking";
	$message = "
	<!DOCTYPE html>
<html>
	<head>
	</head>
	<body>
		<h1>Hello</h1>
			<p>Here is your link</p>
	</body>
</html>";

// send the email
	mail($to, $subject, $message, $headers);

	// display success message or catch thrown exception
} catch(Exception $exception) {
	throw (new Exception($exception->getMessage(),0,$exception));
}
//echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";

echo "<p class=\"alert alert-success\">Invite sent!</p>";

?>