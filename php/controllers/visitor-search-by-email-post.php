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
 * create function for meta file
 */
function searchByEmail($mysqli, $email) {

	/**
	 * ensure string length is 128 char or less
	 */
	if	(strlen($email) > 128) {
		throw(new RangeException("Email cannot be more than 128 characters in length."));
	}

	$searchResults = Visitor::getVisitorByVisitorEmail($mysqli, $email);
	if($searchResults !== null) {
		$searchResults = array($searchResults);
	}

	return($searchResults);
}
?>