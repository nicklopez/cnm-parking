<?php
// require the encrypted config functions
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");


// require the SimpleTest framework <http://www.simpletest.org/>
// this path is *NOT* universal, but deployed on the bootcamp-coders server
require_once("/usr/lib/php5/simpletest/autorun.php");

// require the class from the project under scrutiny
require_once("../php/classes/parkingpass.php");

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
	 * integer of adminId
	 */
	private $adminId = 72;

	/**
	 * int of parkingSpotId
	 */
	private $parkingSpotId = 11;

	/**
	 * int of vehicleId
	 */
	private $vehicleId = 2;

	/**
	 * int of endDateTime
	 */
	private $endDateTime = "2015-01-01 01:59:59";

	/**
	 * datetime of issuedDateTime
	 */
	private $issuedDateTime = "2015-01-01 00:00:00";

	/**
	 * datetime of startDateTime
	 */
	private $startDateTime = "2015-01-01 12:00:00";

	/**
	 * char(36) of uuId
	 */
	private $uuId = null;

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
		$this->parkingPass = new ParkingPass(null, $this->adminId, $this->parkingSpotId, $this->vehicleId, $this->endDateTime, $this->issuedDateTime, $this->startDateTime, $this->uuId);
	}

	/**
	 * tears down he connection to mySQL and deletes the test instance object
	 **/
	public function tearDown() {
		// destroy the object if it was created
		if($this->parkingPass !== null) {
			$this->parkingPass->delete($this->mysqli);
			$this->parkingPass = null;
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
		$this->assertIdentical($this->parkingPass->getAdminId(), $mysqlParkingPass->getAdminId());
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
		$this->assertIdentical($this->parkingPass->getAdminId(), $mysqlParkingPass->getAdminId());
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
}