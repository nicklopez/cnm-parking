<?php
// first, require the SimpleTest framework <http://www.simpletest.org/>
// this path is *NOT* universal, but deployed on the bootcamp-coders server
require_once("/usr/lib/php5/simpletest/autorun.php");

// next, require the class from the project under scrutiny
require_once("../php/classes/parkingPass.php");

/**
 * Unit test for the ParkingPass class
 *
 * This is a SimpleTest test case for the CRUD methods of the ParkingPass class.
 *
 * @see ParkingPass
 * @author Kyle Dozier <kyle@kedlogic.com>
 **/

class ParkingPassTest extends UnitTestCase {
	/**
	 * Create placeholder objects for foreign keys: parkingSpotId, adminId, and vehicleId
	 */
	private $parkingSpot = null;
	private $admin = null;
	private $vehicle = null;

	/**
	 *
	 */


	/**
	 * mysqli object shared amongst all tests
	 **/
	private $mysqli = null;
	/**
	 * instance of the object we are testing with
	 **/
	private $parkingPass = null;

