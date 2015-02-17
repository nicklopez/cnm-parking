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
	if(@isset($_POST["fullName"]) === false) {
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
	$fullName = filter_var($fullName, FILTER_SANITIZE_STRING);
	if(empty($fullName) === true) {
		throw(new InvalidArgumentException("Input contains hostile code"));
	}

	/**
	 * split the phrase by any number of commas or spaces, which include " ", \r, \t, \n and \v
	 */
	$keywords = preg_split("/[\s,]+/", $_POST["fullName"]);
	// check if a comma was used
	$hasComma = strpos($fullName, ',');
	if($hasComma >= 0) {
		$hasComma = true;
	}

	/**
	 * case 1: assign First and Last Name values from Keywords assuming (First Last)
	 * else
	 * case 2: assign First and Last Name values from Keywords assuming (Last, First)
	 */
	if($hasComma === false) {
		$visitorFirstName = $keywords[0];
		$visitorLastName  = end($keywords);
	} else {
		$visitorFirstName = $keywords[1];
		$visitorLastName  = $keywords[0];
	}

	/**
	 * preform the search. End result will be $searchResults[]
	 */
	$searchResults = array();
	/**
	 * if input is only (Name) -> assume (First) -> if null then -> assume (Last)
	 */
	if($visitorFirstName === $visitorLastName) {
		$results = Visitor::getVisitorByVisitorLastName($mysqli, $visitorFirstName);
		if($results === null) {
			$results = Visitor::getVisitorByVisitorFirstName($mysqli, $visitorFirstName);
		}
		$searchResults = $results;
	} else {
		/**
		 * assume case 1 (First Last)
		 */
		$results = Visitor::getVisitorByVisitorLastName($mysqli, $visitorLastName);
		if($results !== null) {
			foreach($results as $result) {
				if(stripos($result->getVisitorFirstName(), $visitorFirstName) >= 0) {
					$searchResults[] = $result;
				}
			}
		}
		/**
		 * if case 1 returns null then assume case 2 (Last, First)
		 */
		if(empty($searchResults) === true && $visitorFirstName !== $visitorLastName) {
			$results = Visitor::getVisitorByVisitorLastName($mysqli, $visitorFirstName);
			if($results !== null) {
				foreach($results as $result) {
					if(stripos($result->getVisitorFirstName(), $visitorLastNameName) >= 0) {
						$searchResults[] = $result;
					}
				}
			}
		}
	}

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