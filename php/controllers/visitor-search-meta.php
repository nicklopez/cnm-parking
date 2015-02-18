<?php
/**
 *
 */
require_once("visitor-search-by-name-post.php");
require_once("visitor-search-by-email-post.php");
require_once("visitor-search-by-plate-post.php");

/**
 * create the whiteList
 */
$whiteList = array();
$whiteList["name"]  = "searchByName";
$whiteList["email"] = "searchByEmail";
$whiteList["plate"] = "searchByPlate";
/**
 * try to process search and post results. catch any errors that occur
 */
try {
	/**
	 * input sanitize via whiteList
	 */
	if(@isset($_POST["visitorSearchOptions"]) === false || array_key_exists($_POST["visitorSearchOptions"], $whiteList) === false) {
		throw(new InvalidArgumentException("Please select an option to search by."));
	}

	/**
	 * verify the form values have been submitted
	 */
	if(@isset($_POST["textVisitorSearchInput"]) === false) {
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
	$searchInput = filter_input(INPUT_POST, "textVisitorSearchInput", FILTER_SANITIZE_STRING);
	if(empty($searchInput) === true) {
		throw(new InvalidArgumentException("Input contains hostile code"));
	}

	/**
	 * utilise functions to preform search
	 */
	$function = $whiteList[$_POST["visitorSearchOptions"]];
	$argv = array($mysqli, $searchInput);
	$searchResults = call_user_func_array($function, $argv);


	/**
	 * echo results to html form
	 */
	if(empty($searchResults) === false) {
			/**
			 * preamble
			 */
			echo "<table class='table table-striped table-hover table-responsive'>";
		echo "<tr><th>First Name</th><th>Last Name</th><th>Email</th><th>Phone</th></tr>";

		/**
		 * table body
		 */
		foreach($searchResults as $searchResult) {
			echo "<tr><td>" . $searchResult->getVisitorFirstName() . "</td><td>" . $searchResult->getVisitorLastName() . "</td><td>" . $searchResult->getVisitorEmail() . "</td><td>" . $searchResult->getVisitorPhone() . "</td></tr>";
		}

		/**
		 * postamble
		 */
		echo "</table>";
	} else {
		echo "<p>No results</p>";
	}
} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
}
?>