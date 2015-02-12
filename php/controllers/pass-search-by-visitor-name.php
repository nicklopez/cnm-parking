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
	require_once("pass-search-by-visitor-name-post.php");

	/**
	 * verify the form values have been submitted
 	 */
	if(@isset($_POST["fullName"]) === false) {
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
		 * assign First and Last Name values from Keywords assuming (First Last)
		 */
		if($hasComma === false) {
			$visitorFirstName = $keywords[0];
			$visitorLastName  = end($keywords);
		}

		/**
		 * assign First and Last Name values from Keywords assuming (Last, First)
		 */
		if($hasComma === true) {
			$visitorFirstName = $keywords[1];
			$visitorLastName  = $keywords[0];
		}


		$results = Visitor::getVisitorByVisitorLastName($mysqli, $visitorLastName);
		if($results !== null) {
			foreach($results as $result) {
				if(stripos($result->getVisitorFirstName(), $visitorFirstName) >= 0){

				}
			}
		}
		if($results === null) {
			$results = Visitor::getVisitorByVisitorLastName($mysqli, $visitorFirstName);

		}









	}

?>