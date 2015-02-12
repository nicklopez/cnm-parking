<?php
/**
 * controller
 *
 * @Author Kyle Dozier <kyle@kedlogic.com>
 */

// require the encrypted config functions
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

// require the form
require_once("pass-search-by-visitor-name-post.php");

// verify the form values have been submitted
if(@isset($_POST["fullName"]) === false) {
	echo"<p class=\"alert alert-danger\"> Search field empty. Please insert search criteria and try again.</p>";
}

try {
	// connect to mySQL
	mysqli_report(MYSQLI_REPORT_STRICT);
	$config = readConfig("/etc/apache2/capstone-mysql/cnmparking.ini");
	$this->mysqli = new mysqli($config["hostname"], $config["username"], $config["password"], $config["database"]);

}


?>