<?php
/**
 * controller
 *
 * @Author Kyle Dozier <kyle@kedlogic.com>
 */

/**
 * require the encrypted config functions
 */
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

/**
 * require the form
 */
require_once("../../visitor-search/index.php");

/**
 * verify the form values have been submitted
 */
if(@isset($_POST["email"]) === false) {
	echo"<p class=\"alert alert-danger\"> Search field empty. Please insert search criteria and try again.</p>";
}

try {
	/**
	 * connect to mySQL
	 */
	mysqli_report(MYSQLI_REPORT_STRICT);
	$config = readConfig("/etc/apache2/capstone-mysql/cnmparking.ini");
	$mysqli = new mysqli($config["hostname"], $config["username"], $config["password"], $config["database"]);

	/**
	 * sanitize input string
	 */
	$email = filter_var($email, FILTER_SANITIZE_STRING);
	if(empty($email) === true) {
		throw(new InvalidArgumentException("Input contains hostile code"));
	}

	/**
	 * populate array via get method
	 */
	$searchResults = array();

	$searchResults[] = Visitor::getVisitorByVisitorEmail($mysqli, $visitorEmail);

	/**
	 * echo results to html form
	 */
	// Preamble
	echo "<table class='table table-striped table-hover table-responsive'>";
	echo "<tr><th>First Name</th><th>Last Name</th><th>Email</th><th>Phone</th></tr>";
	// body
	foreach($searchResults as $searchResult) {
		echo "<tr><td>" . $searchResult->getVisitorFirstName() . "</td><td>" . $searchResult->getVisitorLastName() . "</td><td>" . $searchResult->getVisitorEmail() . "</td><td>" . $searchResult->getVisitorPhone() . "</td></tr>";
	}
	// postamble
	echo "</table>";

} catch(mysqli_sql_exception $mysqlException){
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
}

?>