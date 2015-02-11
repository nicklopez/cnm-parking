<?php
// verify the form values have been submitted
if(empty($_POST["emailAddress"])) {
	echo "<p class=\"alert alert-danger\">Please enter or verify the email address.</p>";
	return;
}

try {
// headers for HTML content
	$headers = "MIME-Version: 1.0 \r\n";
	$headers = $headers . "Content-type: text/html; charset=iso-8859-1 \r\n";

// additional email headers
	$headers = $headers."From: ".$_POST["emailAddress"] . "\r\n";

// variables for email message
	$to = "CNM Parking <nicklopez702@gmail.com>";
	$subject = "CNM Parking";
	$message = "
	<!DOCTYPE html>
<html>
	<head>
	</head>
	<body>
		<h1>Hello</h1>
			<p>A visitor has requested a parking pass invite</p>
	</body>
</html>";

// send the email
	mail($to, $subject, $message, $headers);

	// display success message or catch thrown exception
} catch(Exception $exception) {
	throw (new Exception($exception->getMessage(),0,$exception));
}

echo "<p class=\"alert alert-success\">Your request has been sent!</p>";

?>