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

try {
	/**
	 * verify the form values have been submitted
	 */
	if(@isset($_POST["placard"]) === false) {
		throw(new InvalidArgumentException("Search field empty."));
}

	/**
	 * connect to mySQL
	 */
	mysqli_report(MYSQLI_REPORT_STRICT);
	$config = readConfig("/etc/apache2/capstone-mysql/cnmparking.ini");
	$mysqli = new mysqli($config["hostname"], $config["username"], $config["password"], $config["database"]);

	/**
	 * sanitize input string
	 */
	$placard = filter_var($placard, FILTER_SANITIZE_STRING);
	if(empty($placard) === true) {
		throw(new InvalidArgumentException("Input contains hostile code"));
	}

	/**
	 * populate array via get method
	 */
	$searchResults = array();

	$searchResults[] = Visitor::getParkingSpotByPlacardNumber($mysqli, $PlacardNumber);

	/**
	 * echo results to html form
	 */
	// Preamble
	echo "<table class='table table-striped table-hover table-responsive'>";
	echo "<tr><th>First Name</th><th>Last Name</th><th>placardNumber</th><th>Phone</th></tr>";
	// body
	foreach($searchResults as $searchResult) {
		echo "<tr><td>" . $searchResult->getVisitorFirstName() . "</td><td>" . $searchResult->getVisitorLastName() . "</td><td>" . $searchResult->getVisitorplacardNumber() . "</td><td>" . $searchResult->getVisitorPhone() . "</td></tr>";
	}
	// postamble
	echo "</table>";

} catch(mysqli_sql_exception $mysqlException){
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
}

?>