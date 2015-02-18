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
 * require the form and class
 */
require_once("../classes/visitor.php");
require_once("../classes/vehicle.php");


/**
 * split fullName into First and Last names. Then search
 */
function searchByName($mysqli, $fullName) {
	/**
	 * ensure string length is 270 char or less
	 */
	if	(strlen($fullName) > 270) {
		throw(new RangeException("Visitor name cannot be more than 270 characters in length."));
	}

	/**
	 * split the phrase by any number of commas or spaces, which include " ", \r, \t, \n and \v
	 */
	$keywords = preg_split("/[\s,]+/", $fullName, -1, PREG_SPLIT_NO_EMPTY);
	// check if a comma was used
	$hasComma = strpos($fullName, ',');
	if($hasComma !==false) {
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
	} else if(count($keywords) === 1) {
		$visitorFirstName = $keywords[0];
		$visitorLastName = $keywords[0];
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
				if(stripos($result->getVisitorFirstName(), $visitorFirstName) !== false) {
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
					if(stripos($result->getVisitorFirstName(), $visitorLastName) !== false) {
						$searchResults[] = $result;
					}
				}
			}
		}
	}
	return($searchResults);
}
?>