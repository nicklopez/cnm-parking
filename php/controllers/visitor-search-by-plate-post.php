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
 * require the form and classes
 */
require_once("../../visitor-search/index.php");
require_once("../classes/visitor.php");
require_once("../classes/vehicle.php");



/**
 * create function for meta file
 */
function searchByPlate($mysqli, $plate) {
	/**
	 * ensure string length is 8 char or less
	 */
	if	(strlen($plate) > 8) {
		throw(new RangeException("License plate number cannot be more than 8 characters in length."));
	}

	/**
	 * populate array via get method
	 */
	$searchResults[] = Visitor::getParkingSpotByPlacardNumber($mysqli, $plate);
}
?>