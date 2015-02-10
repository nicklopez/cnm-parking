<?php
// require the encrypted config functions
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");


// require the SimpleTest framework <http://www.simpletest.org/>
// this path is *NOT* universal, but deployed on the bootcamp-coders server
require_once("/usr/lib/php5/simpletest/autorun.php");

// require the class from the project under scrutiny
require_once("../php/classes/parkingpass.php");
require_once("../php/classes/admin.php");
require_once("../php/classes/adminprofile.php");
require_once("../php/classes/visitor.php");
require_once("../php/classes/vehicle.php");
require_once("../php/classes/location.php");
require_once("../php/classes/parkingspot.php");

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
	 * mysqli object shared amongst all tests
	 */
	private $mysqli = null;

	/**
	 * instance of the object parkingPass
	 */
	private $parkingPass = null;

	/**
	 * instance of each dependent entity(a.k.a all of them X{ )
	 */
	private $admin = null;
	private $adminProfile = null;
	private $visitor = null;
	private $vehicle = null;
	private $location = null;
	private $parkingSpot = null;


	/**
	 * sets up the mySQL connection for this test
	 */
	public function setUp() {
		// tell mysqli to throw exceptions
		mysqli_report(MYSQLI_REPORT_STRICT);

		// connect to mySQL
		$config = readConfig("/etc/apache2/capstone-mysql/cnmparking.ini");
		$this->mysqli = new mysqli($config["hostname"], $config["username"], $config["password"], $config["database"]);

		// create an instance of the objects under scrutiny
		$this->admin = new Admin(null, "1234567890abcdef1234567890abcdef", "abc@123.com", "1234567890abcdef1234567890abcdef1234567890abcdef1234567890abcdef1234567890abcdef1234567890abcdef1234567890abcdef1234567890abcdef", "1234567890abcdef1234567890abcdef1234567890abcdef1234567890abcdef");
		$this->admin->insert($this->mysqli);
		$this->adminProfile = new AdminProfile(null, $this->admin->getAdminId(), "John", "Smith");
		$this->adminProfile->insert($this->mysqli);
		$this->visitor = new Visitor(null, '1234@abcd.com', "Joe", "Bob", 5051234567);
		$this->visitor->insert($this->mysqli);
		$this->vehicle = new Vehicle(null, $this->visitor->getVisitorId(), "Black", "Ford", "F150", "abc-123", "NM", 2015);
		$this->vehicle->insert($this->mysqli);
		$this->location = new Location(null, 12.34, "Here", "Right Here",43.21);
		$this->location->insert($this->mysqli);
		$this->parkingSpot = new ParkingSpot(null, $this->location->getLocationId(), "101");
		$this->parkingSpot->insert($this->mysqli);

		// create main object
		$this->parkingPass = new ParkingPass(null, $this->adminProfile->getAdminProfileId(), $this->parkingSpot->getParkingSpotId(), $this->vehicle->getVehicleId(), $this->getEndDateTime(), $this->getIssuedDateTime(), $this->getStartDateTime(), null);
	}


	/**
	 * tears down he connection to mySQL and deletes the test instance object
	 **/
	public function tearDown() {
		// destroy created objects
		if($this->parkingPass !== null && $this->parkingPass->isInserted()) {
			$this->parkingPass->delete($this->mysqli);
			$this->parkingPass = null;
		}
		
		if($this->admin !== null) {
			$this->admin->delete($this->mysqli);
			$this->admin = null;
		}

		if($this->adminProfile !== null) {
			$this->adminProfile->delete($this->mysqli);
			$this->adminProfile = null;
		}

		if($this->visitor !== null) {
			$this->visitor->delete($this->mysqli);
			$this->visitor = null;
		}

		if($this->vehicle !== null) {
			$this->vehicle->delete($this->mysqli);
			$this->vehicle = null;
		}

		if($this->location !== null) {
			$this->location->delete($this->mysqli);
			$this->location = null;
		}

		if($this->parkingSpot !== null) {
			$this->parkingSpot->delete($this->mysqli);
			$this->parkingSpot = null;
		}

		//disconnect from mySQL
		if($this->mysqli !== null) {
			$this->mysqli->close();
			$this->mysqli = null;
		}
	}
	/**
	 * test inserting a valid parkingPass into mySQL
	 */
	public function testInsertValidParkingPass() {
		//zeroth, ensure the parkingPass and mySQL class are sane
		$this->assertNotNull($this->parkingPass);
		$this->assertNotNull($this->mysqli);

		//first, insert the parkingPass into mySQL
		$this->parkingPass->insert($this->mysqli);

		//second, grab an parkingPass from mySQL
		$mysqlParkingPass = ParkingPass::getParkingPassByParkingPassId($this->mysqli, $this->parkingPass->getParkingPassId());

		//third, assert the parkingPass created and mySQL's parkingPass are the same object
		$this->assertIdentical($this->parkingPass->getParkingPassId(), $mysqlParkingPass->getParkingPassId());
		$this->assertIdentical($this->parkingPass->getAdminProfileId(), $mysqlParkingPass->getAdminProfileId());
		$this->assertIdentical($this->parkingPass->getParkingSpotId(), $mysqlParkingPass->getParkingSpotId());
		$this->assertIdentical($this->parkingPass->getVehicleId(), $mysqlParkingPass->getVehicleId());
		$this->assertIdentical($this->parkingPass->getEndDateTime(), $mysqlParkingPass->getEndDateTime());
		$this->assertIdentical($this->parkingPass->getIssuedDateTime(), $mysqlParkingPass->getIssuedDateTime());
		$this->assertIdentical($this->parkingPass->getStartDateTime(), $mysqlParkingPass->getStartDateTime());
		$this->assertIdentical($this->parkingPass->getUuId(), $mysqlParkingPass->getUuId());
	}

	/**
	 * test inserting an invalid parkingPass into mySQL
	 */
	public function testInsertInvalidParkingPass() {
		//zeroth, ensure the parkingPass and mySQL class are the same
		$this->assertNotNull($this->parkingPass);
		$this->assertNotNull($this->mysqli);

		//first, set the parkingPass id to an invented value that should never insert in the first place
		$this->parkingPass->setParkingPassId(666);

		//second, try to insert the parkingPass and ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$this->parkingPass->insert($this->mysqli);

		//third, set the parkingPass to null to prevent the tearDown() from deleting an parkingPass that never existed
		$this->parkingPass = null;
	}

	/**
	 * test deleting an parkingPass from mySQL
	 */
	public function testDeleteValidParkingPass() {
		//zeroth, ensure the parkingPass and mySQL class are the sane
		$this->assertNotNull($this->parkingPass);
		$this->assertNotNull($this->mysqli);

		//first, assert the parkingPass is inserted into mySQL by grabbing it from mySQL and asserting the primary key
		$this->parkingPass->insert($this->mysqli);
		$mysqlParkingPass = ParkingPass::getParkingPassByParkingPassId($this->mysqli, $this->parkingPass->getParkingPassId());
		$this->assertIdentical($this->parkingPass->getParkingPassId(), $mysqlParkingPass->getParkingPassId());

		//second, delete the parkingPass from mySQL and re-grab it from mySQL and assert id does not exist
		$this->parkingPass->delete($this->mysqli);
		$mysqlParkingPass = ParkingPass::getParkingPassByParkingPassId($this->mysqli, $this->parkingPass->getParkingPassId());
		$this->assertNull($mysqlParkingPass);

		//third, set the parkingPass to null to prevent tearDown() from deleting a parkingPass that has already been deleted
		$this->parkingPass = null;
	}

	/**
	 * test deleting an parkingPass from mySQL that does not exist
	 */
	public function testDeleteInvalidParkingPass() {
		//zeroth, ensure the parkingPass and mySQL class are the sane
		$this->assertNotNull($this->parkingPass);
		$this->assertNotNull($this->mysqli);

		//first, try to delete the parkingPass before inserting it and ensure the exception is thrown
		$this->expectException('mysqli_sql_exception');
		$this->parkingPass->delete($this->mysqli);

		//second, set the parkingPass to null to prevent tearDown() from deleting an parkingPass that has already been deleted
		$this->parkingPass = null;
	}

	/**
	 * test updating an parkingPass from mySQL
	 */
	public function testUpdateValidParkingPass() {
		//zeroth, ensure the parkingPass and mySQL class are the sane
		$this->assertNotNull($this->parkingPass);
		$this->assertNotNull($this->mysqli);

		//first, assert the parkingPass is inserted into mySQL by grabbing it from mySQL and asserting the primary key
		$this->parkingPass->insert($this->mysqli);
		$mysqlParkingPass = ParkingPass::getParkingPassByParkingPassId($this->mysqli, $this->parkingPass->getParkingPassId());
		$this->assertIdentical($this->parkingPass->getParkingPassId(), $mysqlParkingPass->getParkingPassId());

		//second, grab an parkingPass from mySQL
		$mysqlParkingPass = ParkingPass::getParkingPassByParkingPassId($this->mysqli, $this->parkingPass->getParkingPassId());

		//third, assert the parkingPass created and mySQL's parkingPass are the same object
		$this->assertIdentical($this->parkingPass->getParkingPassId(), $mysqlParkingPass->getParkingPassId());
		$this->assertIdentical($this->parkingPass->getAdminProfileId(), $mysqlParkingPass->getAdminProfileId());
		$this->assertIdentical($this->parkingPass->getParkingSpotId(), $mysqlParkingPass->getParkingSpotId());
		$this->assertIdentical($this->parkingPass->getVehicleId(), $mysqlParkingPass->getVehicleId());
		$this->assertIdentical($this->parkingPass->getEndDateTime(), $mysqlParkingPass->getEndDateTime());
		$this->assertIdentical($this->parkingPass->getIssuedDateTime(), $mysqlParkingPass->getIssuedDateTime());
		$this->assertIdentical($this->parkingPass->getStartDateTime(), $mysqlParkingPass->getStartDateTime());
		$this->assertIdentical($this->parkingPass->getUuId(), $mysqlParkingPass->getUuId());
	}


	/**
	 * test updating an parkingPass from mySQL that does not exist
	 */
	public function testUpdateInvalidParkingPass() {
		//zeroth, ensure the parkingPass and mySQL class are the sane
		$this->assertNotNull($this->parkingPass);
		$this->assertNotNull($this->mysqli);

		//first, try to update the parkingPass before inserting it and ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$this->parkingPass->update($this->mysqli);

		//second, set the parkingPass to null to prevent tearDown() from deleting an parkingPass that has already been deleted
		$this->parkingPass = null;
	}

	/**
	 * test grabbing a valid parkingPass from mySQL by parkingPassId
	 **/
	public function testSelectValidParkingPassByParkingPassId() {
		// zeroth, ensure the ParkingPass and mySQL class are sane
		$this->assertNotNull($this->parkingPass);
		$this->assertNotNull($this->mysqli);

		// first, assert the ParkingPass is inserted into mySQL by grabbing it from mySQL and asserting the primary key
		$this->parkingPass->insert($this->mysqli);
		$mysqlParkingPass = ParkingPass::getParkingPassByParkingPassId($this->mysqli, $this->parkingPass->getParkingPassId());
		$this->assertIdentical($this->parkingPass->getParkingPassId(), $mysqlParkingPass->getParkingPassId());
	}

	/**
	 * test grabbing an invalid parkingPass from mySQL by parkingPassId
	 **/
	public function testSelectInvalidParkingPassByParkingPassId() {
		// zeroth, ensure that mySQL is sane
		$this->assertNotNull($this->mysqli);

		// attempt to grab an invalid parkingPassId from mySQL
		$mysqlParkingPass = ParkingPass::getParkingPassByParkingPassId($this->mysqli, PHP_INT_MAX);
		$this->assertNull($mysqlParkingPass);
	}

	/**
	 * test grabbing valid parkingPasses from mySQL by parkingSpotId
	 **/
	public function testSelectValidParkingPassesByParkingSpotId() {
		// zeroth, ensure the ParkingPass and mySQL class are sane
		$this->assertNotNull($this->parkingPass);
		$this->assertNotNull($this->parkingPass2);
		$this->assertNotNull($this->mysqli);

		// first, assert the ParkingPass is inserted into mySQL by grabbing it from mySQL and asserting the primary key
		$this->parkingPass->insert($this->mysqli);
		$this->parkingPass2->insert($this->mysqli);
		$parkingSpotId = 1;
		$mysqlParkingPassArray = ParkingPass::getParkingPassByParkingSpotId($this->mysqli, $this->parkingPass->getParkingSpotId());
		$this->assertIsA($mysqlParkingPassArray, "array");
		$this->assertIdentical(count($mysqlParkingPassArray), 2);

		// test each object in the array
		foreach($mysqlParkingPassArray as $parkingPass) {
			$this->assertTrue($parkingPass->getParkingPassId() > 0);
			$this->assertTrue(strpos($parkingPass->getParkingSpotId(), $parkingSpotId) >= 0);
		}
	}

	/**
	 * test grabbing invalid parkingPass from mySQL by parkingSpotId
	 */
	public function testSelectInvalidParkingPassesByParkingSpotId() {
		// zeroth, ensure that mySQL is sane
		$this->assertNotNull($this->mysqli);

		//attempt to grab invalid parkingPasses form mySQL
		$mysqlParkingPass = ParkingPass::getParkingPassByParkingSpotId($this->mysqli, PHP_INT_MAX);
		$this->assertNull($mysqlParkingPass);
	}


	/**
	 * test grabbing valid parkingPasses from mySQL by vehicleId
	 **/
	public function testSelectValidParkingPassesByVehicleId() {
		// zeroth, ensure the ParkingPass and mySQL class are sane
		$this->assertNotNull($this->parkingPass);
		$this->assertNotNull($this->parkingPass2);
		$this->assertNotNull($this->mysqli);

		// first, assert the ParkingPass is inserted into mySQL by grabbing it from mySQL and asserting the primary key
		$this->parkingPass->insert($this->mysqli);
		$this->parkingPass2->insert($this->mysqli);
		$vehicleId = 1;
		$mysqlParkingPassArray = ParkingPass::getParkingPassByVehicleId($this->mysqli, $this->parkingPass->getVehicleId());
		$this->assertIsA($mysqlParkingPassArray, "array");
		$this->assertIdentical(count($mysqlParkingPassArray), 2);

		// test each object in the array
		foreach($mysqlParkingPassArray as $parkingPass) {
			$this->assertTrue($parkingPass->getParkingPassId() > 0);
			$this->assertTrue(strpos($parkingPass->getVehicleId(), $vehicleId) >= 0);
		}
	}

	/**
	 * test grabbing invalid parkingPass from mySQL by vehicleId
	 */
	public function testSelectInvalidParkingPassesByVehicleId() {
		// zeroth, ensure that mySQL is sane
		$this->assertNotNull($this->mysqli);

		//attempt to grab invalid parkingPasses form mySQL
		$mysqlParkingPass = ParkingPass::getParkingPassByVehicleId($this->mysqli, PHP_INT_MAX);
		$this->assertNull($mysqlParkingPass);
	}



}