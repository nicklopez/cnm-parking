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
	 * First instance of the object parkingPass
	 */
	private $parkingPass1 = null;

	/**
	 * integer of adminProfileId
	 */
	private $adminProfileId1 = 1;

	/**
	 * int of parkingSpotId
	 */
	private $parkingSpot1Id = 1;

	/**
	 * int of vehicleId
	 */
	private $vehicleId1 = 1;

	/**
	 * int of endDateTime
	 */
	private $endDateTime1 = "2015-01-01 01:59:59";

	/**
	 * datetime of issuedDateTime
	 */
	private $issuedDateTime1 = "2015-01-01 00:00:00";

	/**
	 * datetime of startDateTime
	 */
	private $startDateTime1 = "2015-01-01 12:00:00";

	/**
	 * char(36) of uuId
	 */
	private $uuId1 = null;


	/**
	 * Second instance of the object parkingPass
	 */
	private $parkingPass2 = null;

	/**
	 * integer of adminProfileId
	 */
	private $adminProfileId2 = 2;

	/**
	 * int of parkingSpotId
	 */
	private $parkingSpot2Id = 2;

	/**
	 * int of vehicleId
	 */
	private $vehicleId2 = 2;

	/**
	 * int of endDateTime
	 */
	private $endDateTime2 = "2015-01-02 01:59:59";

	/**
	 * datetime of issuedDateTime
	 */
	private $issuedDateTime2 = "2015-01-02 00:00:00";

	/**
	 * datetime of startDateTime
	 */
	private $startDateTime2 = "2015-01-02 12:00:00";

	/**
	 * char(36) of uuId
	 */
	private $uuId2 = null;

	/**
	 * sets up the mySQL connection for this test
	 */
	public function setUp() {
		// tell mysqli to throw exceptions
		mysqli_report(MYSQLI_REPORT_STRICT);

		// now connect to mySQL
		$config = readConfig("/etc/apache2/capstone-mysql/cnmparking.ini");
		$this->mysqli = new mysqli($config["hostname"], $config["username"], $config["password"], $config["database"]);

		// second, create an instance of the objects under scrutiny
		$this->parkingPass1 = new ParkingPass(null, $this->adminProfileId1, $this->parkingSpot1Id, $this->vehicleId1, $this->endDateTime1, $this->issuedDateTime1, $this->startDateTime1, null);
		$this->parkingPass2 = new ParkingPass(null, $this->adminProfileId2, $this->parkingSpot2Id, $this->vehicleId2, $this->endDateTime2, $this->issuedDateTime2, $this->startDateTime2, null);
	}

	/**
	 * tears down he connection to mySQL and deletes the test instance object
	 **/
	public function tearDown() {
		// destroy the object if it was created
		if($this->parkingPass1 !== null && $this->parkingPass1->getParkingPassId() !== null) {
			$this->parkingPass1->delete($this->mysqli);
			$this->parkingPass1 = null;
		}
		if($this->parkingPass2 !== null && $this->parkingPass2->getParkingPassId() !== null) {
			$this->parkingPass2->delete($this->mysqli);
			$this->parkingPass2 = null;
		}

		//disconnect from mySQL
		if($this->mysqli !== null) {
			$this->mysqli->close();
			$this->mysqli = null;
		}
	}
	/**
	 * test inserting a valid parkingPass1 into mySQL
	 */
	public function testInsertValidParkingPass1() {
		//zeroth, ensure the parkingPass1 and mySQL class are sane
		$this->assertNotNull($this->parkingPass1);
		$this->assertNotNull($this->mysqli);

		//first, insert the parkingPass1 into mySQL
		$this->parkingPass1->insert($this->mysqli);

		//second, grab an parkingPass1 from mySQL
		$mysqlParkingPass1 = ParkingPass::getParkingPassByParkingPassId($this->mysqli, $this->parkingPass1->getParkingPassId());

		//third, assert the parkingPass1 created and mySQL's parkingPass1 are the same object
		$this->assertIdentical($this->parkingPass1->getParkingPassId(), $mysqlParkingPass1->getParkingPassId());
		$this->assertIdentical($this->parkingPass1->getAdminProfileId(), $mysqlParkingPass1->getAdminProfileId());
		$this->assertIdentical($this->parkingPass1->getParkingSpotId(), $mysqlParkingPass1->getParkingSpotId());
		$this->assertIdentical($this->parkingPass1->getVehicleId(), $mysqlParkingPass1->getVehicleId());
		$this->assertIdentical($this->parkingPass1->getEndDateTime(), $mysqlParkingPass1->getEndDateTime());
		$this->assertIdentical($this->parkingPass1->getIssuedDateTime(), $mysqlParkingPass1->getIssuedDateTime());
		$this->assertIdentical($this->parkingPass1->getStartDateTime(), $mysqlParkingPass1->getStartDateTime());
		$this->assertIdentical($this->parkingPass1->getUuId(), $mysqlParkingPass1->getUuId());
	}

	/**
	 * test inserting an invalid parkingPass1 into mySQL
	 */
	public function testInsertInvalidParkingPass1() {
		//zeroth, ensure the parkingPass1 and mySQL class are the same
		$this->assertNotNull($this->parkingPass1);
		$this->assertNotNull($this->mysqli);

		//first, set the parkingPass1 id to an invented value that should never insert in the first place
		$this->parkingPass1->setParkingPassId(666);

		//second, try to insert the parkingPass1 and ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$this->parkingPass1->insert($this->mysqli);

		//third, set the parkingPass1 to null to prevent the tearDown() from deleting an parkingPass1 that never existed
		$this->parkingPass1 = null;
	}

	/**
	 * test deleting an parkingPass1 from mySQL
	 */
	public function testDeleteValidParkingPass1() {
		//zeroth, ensure the parkingPass1 and mySQL class are the sane
		$this->assertNotNull($this->parkingPass1);
		$this->assertNotNull($this->mysqli);

		//first, assert the parkingPass1 is inserted into mySQL by grabbing it from mySQL and asserting the primary key
		$this->parkingPass1->insert($this->mysqli);
		$mysqlParkingPass1 = ParkingPass::getParkingPassByParkingPassId($this->mysqli, $this->parkingPass1->getParkingPassId());
		$this->assertIdentical($this->parkingPass1->getParkingPassId(), $mysqlParkingPass1->getParkingPassId());

		//second, delete the parkingPass1 from mySQL and re-grab it from mySQL and assert id does not exist
		$this->parkingPass1->delete($this->mysqli);
		$mysqlParkingPass1 = ParkingPass::getParkingPassByParkingPassId($this->mysqli, $this->parkingPass1->getParkingPassId());
		$this->assertNull($mysqlParkingPass1);

		//third, set the parkingPass1 to null to prevent tearDown() from deleting a parkingPass1 that has already been deleted
		$this->parkingPass1 = null;
	}

	/**
	 * test deleting an parkingPass1 from mySQL that does not exist
	 */
	public function testDeleteInvalidParkingPass1() {
		//zeroth, ensure the parkingPass1 and mySQL class are the sane
		$this->assertNotNull($this->parkingPass1);
		$this->assertNotNull($this->mysqli);

		//first, try to delete the parkingPass1 before inserting it and ensure the exception is thrown
		$this->expectException('mysqli_sql_exception');
		$this->parkingPass1->delete($this->mysqli);

		//second, set the parkingPass1 to null to prevent tearDown() from deleting an parkingPass1 that has already been deleted
		$this->parkingPass1 = null;
	}

	/**
	 * test updating an parkingPass1 from mySQL
	 */
	public function testUpdateValidParkingPass1() {
		//zeroth, ensure the parkingPass1 and mySQL class are the sane
		$this->assertNotNull($this->parkingPass1);
		$this->assertNotNull($this->mysqli);

		//first, assert the parkingPass1 is inserted into mySQL by grabbing it from mySQL and asserting the primary key
		$this->parkingPass1->insert($this->mysqli);
		$mysqlParkingPass1 = ParkingPass::getParkingPassByParkingPassId($this->mysqli, $this->parkingPass1->getParkingPassId());
		$this->assertIdentical($this->parkingPass1->getParkingPassId(), $mysqlParkingPass1->getParkingPassId());

		//second, grab an parkingPass1 from mySQL
		$mysqlParkingPass1 = ParkingPass::getParkingPassByParkingPassId($this->mysqli, $this->parkingPass1->getParkingPassId());

		//third, assert the parkingPass1 created and mySQL's parkingPass1 are the same object
		$this->assertIdentical($this->parkingPass1->getParkingPassId(), $mysqlParkingPass1->getParkingPassId());
		$this->assertIdentical($this->parkingPass1->getAdminProfileId(), $mysqlParkingPass1->getAdminProfileId());
		$this->assertIdentical($this->parkingPass1->getParkingSpotId(), $mysqlParkingPass1->getParkingSpotId());
		$this->assertIdentical($this->parkingPass1->getVehicleId(), $mysqlParkingPass1->getVehicleId());
		$this->assertIdentical($this->parkingPass1->getEndDateTime(), $mysqlParkingPass1->getEndDateTime());
		$this->assertIdentical($this->parkingPass1->getIssuedDateTime(), $mysqlParkingPass1->getIssuedDateTime());
		$this->assertIdentical($this->parkingPass1->getStartDateTime(), $mysqlParkingPass1->getStartDateTime());
		$this->assertIdentical($this->parkingPass1->getUuId(), $mysqlParkingPass1->getUuId());
	}


	/**
	 * test updating an parkingPass1 from mySQL that does not exist
	 */
	public function testUpdateInvalidParkingPass1() {
		//zeroth, ensure the parkingPass1 and mySQL class are the sane
		$this->assertNotNull($this->parkingPass1);
		$this->assertNotNull($this->mysqli);

		//first, try to update the parkingPass1 before inserting it and ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$this->parkingPass1->update($this->mysqli);

		//second, set the parkingPass1 to null to prevent tearDown() from deleting an parkingPass1 that has already been deleted
		$this->parkingPass1 = null;
	}

	/**
	 * test grabbing a valid parkingPass from mySQL by parkingPassId
	 **/
	public function testSelectValidParkingPassByParkingPassId() {
		// zeroth, ensure the ParkingPass and mySQL class are sane
		$this->assertNotNull($this->parkingPass1);
		$this->assertNotNull($this->mysqli);

		// first, assert the ParkingPass is inserted into mySQL by grabbing it from mySQL and asserting the primary key 
		$this->parkingPass1->insert($this->mysqli);
		$mysqlParkingPass = ParkingPass::getParkingPassByParkingPassId($this->mysqli, $this->parkingPass1->getParkingPassId());
		$this->assertIdentical($this->parkingPass1->getParkingPassId(), $mysqlParkingPass->getParkingPassId());
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
	 * test grabbing valid parkingPasses from mySQL by adminProfileId
	 **/
	public function testSelectValidParkingPassesByAdminProfileId() {
		// zeroth, ensure the ParkingPass and mySQL class are sane
		$this->assertNotNull($this->parkingPass1);
		$this->assertNotNull($this->parkingPass2);
		$this->assertNotNull($this->mysqli);

		// first, assert the ParkingPass is inserted into mySQL by grabbing it from mySQL and asserting the primary key
		$this->parkingPass1->insert($this->mysqli);
		$this->parkingPass2->insert($this->mysqli);
		$adminProfileId = 1;
		$mysqlParkingPassArray = ParkingPass::getParkingPassByAdminProfileId($this->mysqli, $this->parkingPass1->getAdminProfileId());
		$this->assertIsA($mysqlParkingPassArray, "array");
		$this->assertIdentical(count($mysqlParkingPassArray), 2);

		// test each object in the array
		foreach($mysqlParkingPassArray as $parkingPass) {
			$this->assertTrue($parkingPass->getParkingPassId() > 0);
			$this->assertTrue(strpos($parkingPass->getAdminProfileId(), $adminProfileId) >= 0);
		}
	}

	/**
	 * test grabbing invalid parkingPass from mySQL by adminProfileId
	 */
	public function testSelectInvalidParkingPassesByAdminProfileId() {
		// zeroth, ensure that mySQL is sane
		$this->assertNotNull($this->mysqli);

		//attempt to grab invalid parkingPasses form mySQL
		$mysqlParkingPass = ParkingPass::getParkingPassByAdminProfileId($this->mysqli, PHP_INT_MAX);
		$this->assertNull($mysqlParkingPass);
	}


	/**
	 * test grabbing valid parkingPasses from mySQL by parkingSpotId
	 **/
	public function testSelectValidParkingPassesByParkingSpotId() {
		// zeroth, ensure the ParkingPass and mySQL class are sane
		$this->assertNotNull($this->parkingPass1);
		$this->assertNotNull($this->parkingPass2);
		$this->assertNotNull($this->mysqli);

		// first, assert the ParkingPass is inserted into mySQL by grabbing it from mySQL and asserting the primary key
		$this->parkingPass1->insert($this->mysqli);
		$this->parkingPass2->insert($this->mysqli);
		$mysqlParkingPassArray = ParkingPass::getParkingPassByParkingSpotId($this->mysqli, $this->parkingPass1->getParkingSpotId());
		$this->assertIsA($mysqlParkingPassArray, "array");
		$this->assertIdentical(count($mysqlParkingPassArray), 2);

		// test each object in the array
		foreach($mysqlParkingPassArray as $parkingPass) {
			$this->assertTrue($parkingPass->getParkingPassId() > 0);
			$this->assertTrue($parkingPass->getParkingSpotId() === $this->parkingPass1->getParkingSpotId());
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
		$this->assertNotNull($this->parkingPass1);
		$this->assertNotNull($this->parkingPass2);
		$this->assertNotNull($this->mysqli);

		// first, assert the ParkingPass is inserted into mySQL by grabbing it from mySQL and asserting the primary key
		$this->parkingPass1->insert($this->mysqli);
		$this->parkingPass2->insert($this->mysqli);
		$mysqlParkingPassArray = ParkingPass::getParkingPassByVehicleId($this->mysqli, $this->parkingPass1->getVehicleId());
		$this->assertIsA($mysqlParkingPassArray, "array");
		$this->assertIdentical(count($mysqlParkingPassArray), 2);

		// test each object in the array
		foreach($mysqlParkingPassArray as $parkingPass) {
			$this->assertTrue($parkingPass->getParkingPassId() > 0);
			$this->assertTrue($parkingPass->getVehicleId() === $this->parkingPass1->getVehicleId());
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