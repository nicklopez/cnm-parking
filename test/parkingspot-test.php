<?php
// require the encrypted config functions
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");


// require the SimpleTest framework <http://www.simpletest.org/>
// this path is *NOT* universal, but deployed on the bootcamp-coders server
require_once("/usr/lib/php5/simpletest/autorun.php");

// require the class from the project under scrutiny
require_once("../php/classes/parkingspot.php");

/**
 * Unit test for the ParkingSpot class
 *
 * This is a SimpleTest test case for the CRUD methods of the ParkingSpot class.
 *
 * @see ParkingSpot
 * @author Kyle Dozier <kyle@kedlogic.com>
 **/

class ParkingSpotTest extends UnitTestCase {
	/**
	 * mysqli object shared amongst all tests
	 */
	private $mysqli = null;

	/**
	 * first instance of the object parkingSpot
	 */
	private $parkingSpot1 = null;
	/**
	 * second instance of the object parkingSpot
	 */
	private $parkingSpot2 = null;

	/**
	 * integer of location id
	 */
	private $locationId1 = 17;

	/**
	 * integer of location id
	 */
	private $locationId2 = 17;

	/**
	 * char(16) of placardNumber
	 */
	private $placardNumber1 = "202";

	/**
	 * char(16) of placardNumber
	 */
	private $placardNumber2 = "203";

	/**
	 * sets up the mySQL connection for this test
	 */
	public function setUp() {
		// tell mysqli to throw exceptions
		mysqli_report(MYSQLI_REPORT_STRICT);

		// now connect to mySQL
		$config = readConfig("/etc/apache2/capstone-mysql/cnmparking.ini");
		$this->mysqli = new mysqli($config["hostname"], $config["username"], $config["password"], $config["database"]);

		// second, create an instance of the object under scrutiny
		$this->parkingSpot1 = new ParkingSpot(null, $this->locationId1, $this->placardNumber2);
		$this->parkingSpot2 = new ParkingSpot(null, $this->locationId1, $this->placardNumber2);
	}

	/**
	 * tears down he connection to mySQL and deletes the test instance object
	 **/
	public function tearDown() {
		// destroy the object if it was created
		if($this->parkingSpot1 !== null && $this->parkingSpot1->getParkingSpotId() !== null) {
			$this->parkingSpot1->delete($this->mysqli);
			$this->parkingSpot1 = null;
		}

		if($this->parkingSpot2 !== null && $this->parkingSpot2->getParkingSpotId() !== null) {
			$this->parkingSpot2->delete($this->mysqli);
			$this->parkingSpot2 = null;
		}

		//disconnect from mySQL
		if($this->mysqli !== null) {
			$this->mysqli->close();
			$this->mysqli = null;
		}
	}
	/**
	 * test inserting a valid parkingSpot into mySQL
	 */
	public function testInsertValidParkingSpot() {
		//zeroth, ensure the parkingSpot and mySQL class are sane
		$this->assertNotNull($this->parkingSpot1);
		$this->assertNotNull($this->mysqli);

		//first, insert the parkingSpot into mySQL
		$this->parkingSpot1->insert($this->mysqli);

		//second, grab an parkingSpot from mySQL
		$mysqlParkingSpot = ParkingSpot::getParkingSpotByParkingSpotId($this->mysqli, $this->parkingSpot1->getParkingSpotId());

		//third, assert the parkingSpot created and mySQL's parkingSpot are the same object
		$this->assertIdentical($this->parkingSpot1->getParkingSpotId(), $mysqlParkingSpot->getParkingSpotId());
		$this->assertIdentical($this->parkingSpot1->getLocationId(), $mysqlParkingSpot->getLocationId());
		$this->assertIdentical($this->parkingSpot1->getPlacardNumber(), $mysqlParkingSpot->getPlacardNumber());
	}

	/**
	 * test inserting an invalid parkingSpot into mySQL
	 */
	public function testInsertInvalidParkingSpot() {
		//zeroth, ensure the parkingSpot and mySQL class are the same
		$this->assertNotNull($this->parkingSpot1);
		$this->assertNotNull($this->mysqli);

		//first, set the parkingSpot id to an invented value that should never insert in the first place
		$this->parkingSpot1->setParkingSpotId(666);

		//second, try to insert the parkingSpot and ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$this->parkingSpot1->insert($this->mysqli);

		//third, set the parkingSpot to null to prevent the tearDown() from deleting an parkingSpot that never existed
		$this->parkingSpot1 = null;
	}

	/**
	 * test deleting an parkingSpot from mySQL
	 */
	public function testDeleteValidParkingSpot() {
		//zeroth, ensure the parkingSpot and mySQL class are the sane
		$this->assertNotNull($this->parkingSpot1);
		$this->assertNotNull($this->mysqli);

		//first, assert the parkingSpot is inserted into mySQL by grabbing it from mySQL and asserting the primary key
		$this->parkingSpot1->insert($this->mysqli);
		$mysqlParkingSpot = ParkingSpot::getParkingSpotByParkingSpotId($this->mysqli, $this->parkingSpot1->getParkingSpotId());
		$this->assertIdentical($this->parkingSpot1->getParkingSpotId(), $mysqlParkingSpot->getParkingSpotId());

		//second, delete the parkingSpot from mySQL and re-grab it from mySQL and assert id does not exist
		$this->parkingSpot1->delete($this->mysqli);
		$mysqlParkingSpot= ParkingSpot::getParkingSpotByParkingSpotId($this->mysqli, $this->parkingSpot1->getParkingSpotId());
		$this->assertNull($mysqlParkingSpot);

		//third, set the parkingSpot to null to prevent tearDown() from deleting a parkingSpot that has already been deleted
		$this->parkingSpot1 = null;
	}

	/**
	 * test deleting an parkingSpot from mySQL that does not exist
	 */
	public function testDeleteInvalidParkingSpot() {
		//zeroth, ensure the parkingSpot and mySQL class are the sane
		$this->assertNotNull($this->parkingSpot1);
		$this->assertNotNull($this->mysqli);

		//first, try to delete the parkingSpot before inserting it and ensure the exception is thrown
		$this->expectException('mysqli_sql_exception');
		$this->parkingSpot1->delete($this->mysqli);

		//second, set the parkingSpot to null to prevent tearDown() from deleting an parkingSpot that has already been deleted
		$this->parkingSpot1 = null;
	}

	/**
	 * test updating an parkingSpot from mySQL
	 */
	public function testUpdateValidParkingSpot() {
		//zeroth, ensure the parkingSpot and mySQL class are the sane
		$this->assertNotNull($this->parkingSpot1);
		$this->assertNotNull($this->mysqli);

		//first, assert the parkingSpot is inserted into mySQL by grabbing it from mySQL and asserting the primary key
		$this->parkingSpot1->insert($this->mysqli);
		$mysqlParkingSpot = ParkingSpot::getParkingSpotByParkingSpotId($this->mysqli, $this->parkingSpot1->getParkingSpotId());
		$this->assertIdentical($this->parkingSpot1->getParkingSpotId(), $mysqlParkingSpot->getParkingSpotId());

		//second, grab an parkingSpot from mySQL
		$mysqlParkingSpot = ParkingSpot::getParkingSpotByParkingSpotId($this->mysqli, $this->parkingSpot1->getParkingSpotId());

		//third, assert the parkingSpot created and mySQL's parkingSpot are the same object
		$this->assertIdentical($this->parkingSpot1->getParkingSpotId(), $mysqlParkingSpot->getParkingSpotId());
		$this->assertIdentical($this->parkingSpot1->getLocationId(), $mysqlParkingSpot->getLocationId());
		$this->assertIdentical($this->parkingSpot1->getPlacardNumber(), $mysqlParkingSpot->getPlacardNumber());
	}


	/**
	 * test updating an parkingSpot from mySQL that does not exist
	 */
	public function testUpdateInvalidParkingSpot() {
		//zeroth, ensure the parkingSpot and mySQL class are the sane
		$this->assertNotNull($this->parkingSpot1);
		$this->assertNotNull($this->mysqli);

		//first, try to update the parkingSpot before inserting it and ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$this->parkingSpot1->update($this->mysqli);

		//second, set the parkingSpot to null to prevent tearDown() from deleting an parkingSpot that has already been deleted
		$this->parkingSpot1 = null;
	}

	/**
	 * test selecting a parking spot by parkingSpotId from mySQL
	 **/
	public function testSelectValidVehicleByVehicleId() {
		// zeroth, ensure the parking spot and mySQL class are sane
		$this->assertNotNull($this->parkingSpot1);
		$this->assertNotNull($this->mysqli);

		// first, insert the parking spot into mySQL
		$this->parkingSpot1->insert($this->mysqli);
		$mysqlParkingSpot = ParkingSpot::getParkingSpotByParkingSpotId($this->mysqli, $this->parkingSpot1->getParkingSpotId());

		// second, assert the query returned a result
		$this->assertNotNull($mysqlParkingSpot);
	}

	/**
	 * test selecting a parking spot that does not exist in mySQL
	 **/
	public function testSelectInvalidVehicleByVehicleId() {
		// zeroth, ensure the vehicle and mySQL class are sane
		$this->assertNotNull($this->parkingSpot1);
		$this->assertNotNull($this->mysqli);

		// first, try to selecting a vehicle by vehicleId
		$parkingSpot = 0;

		// ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$mysqlParkingSpot = ParkingSpot::getParkingSpotByParkingSpotId($this->mysqli, $parkingSpot);

		// second, assert the query returned no results
		$this->assertNull($mysqlParkingSpot);
	}


}