<?php
// first, require the SimpleTest framework <http://simpletest.org/>
// this path is *not* universal, but deployed on the bootcamp-coders server
require_once("/usr/lib/php5/simpletest/autorun.php");

//next, require the encrypted configuration functions
require_once("/home/cnmparki/etc/mysql/encrypted-config.php");

// next, require the class from the project under scrutiny
require_once("../php/classes/vehicle.php");
require_once("../php/classes/visitor.php");


/**
 * Unit test for the Vehicle class
 *
 * This is a SimpleTest test case for the CRUD methods of the Vehicle class.
 *
 * @see Vehicle
 * @author Nick Lopez <nick@nicklopezcodes.com>
 */
class VehicleTest extends UnitTestCase {
	/**
	 * mysqli object shared amongst all tests
	 */
	private $mysqli = null;
	/**
	 * first instance of the object we are testing with
	 */
	private $vehicle1 = null;
	/**
	 * second instance of the object we are testing with
	 */
	private $vehicle2 = null;

	// this section contains vehicle variables with constants needed for creating a new Vehicle
	/**
	 * id of the visitor (owner of vehicle); this is a foreign key
	 **/
	private $visitor1 = null;
	/**
	 * color of vehicle
	 **/
	private $vehicleColor1 = "White";
	/**
	 * make of vehicle
	 **/
	private $vehicleMake1 = "Honda";
	/**
	 * model of vehicle
	 **/
	private $vehicleModel1 = "Accord";
	/**
	 * license plate number of vehicle
	 **/
	private $vehiclePlateNumber1 = "999XXX";
	/**
	 * state of vehicle license plate
	 **/
	private $vehiclePlateState1 = "NM";
	/**
	 * year of vehicle
	 **/
	private $vehicleYear1 = 2015;
	/**
	 * id of the visitor (owner of vehicle); this is a foreign key
	 **/
	private $visitor2 = null;
	/**
	 * color of vehicle
	 **/
	private $vehicleColor2 = "Black";
	/**
	 * make of vehicle
	 **/
	private $vehicleMake2 = "Honda";
	/**
	 * model of vehicle
	 **/
	private $vehicleModel2 = "Civic";
	/**
	 * license plate number of vehicle
	 **/
	private $vehiclePlateNumber2 = "999AAA";
	/**
	 * state of vehicle license plate
	 **/
	private $vehiclePlateState2 = "NM";
	/**
	 * year of vehicle
	 **/
	private $vehicleYear2 = 2015;


	/**
	 * setup the mySQL connection for this test
	 */
	public function setUp() {
		// now retrieve the configuration parameters
		try {
			$configFile = "/home/cnmparki/etc/mysql/cnmparking.ini";
			$configArray = readConfig($configFile);
		} catch (InvalidArgumentException $invalidArgument) {
		// re-throw the exception to the caller
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		}

		// first, connect to mysqli
		mysqli_report(MYSQLI_REPORT_STRICT);
		$this->mysqli = new mysqli($configArray["hostname"], $configArray["username"], $configArray["password"], $configArray["database"]);

		// second, create an instance of the object under scrutiny
		$this->visitor1 = new Visitor(null, "myfirstname@example.com", "John", "Doe", "9990001122");
		$this->visitor2 = new Visitor(null, "mylastname@example.com", "Jane", "Doe", "9990001123");
		$this->visitor1->insert($this->mysqli);
		$this->visitor2->insert($this->mysqli);
		$this->vehicle1 = new Vehicle(null, $this->visitor1->getVisitorId(), $this->vehicleColor1, $this->vehicleMake1, $this->vehicleModel1, $this->vehiclePlateNumber1, $this->vehiclePlateState1, $this->vehicleYear1);
		$this->vehicle2 = new Vehicle(null, $this->visitor2->getVisitorId(), $this->vehicleColor2, $this->vehicleMake2, $this->vehicleModel2, $this->vehiclePlateNumber2, $this->vehiclePlateState2, $this->vehicleYear2);
	}

	/**
	 * tears down the connection to mySQL and deletes the test instance object
	 **/
	public function tearDown() {
// destroy the object if it was created
		if($this->vehicle1 !== null && $this->vehicle1->getVehicleId() !== null) {
			$this->vehicle1->delete($this->mysqli);
			$this->vehicle1 = null;
		}

		if($this->vehicle2 !== null && $this->vehicle2->getVehicleId() !== null) {
			$this->vehicle2->delete($this->mysqli);
			$this->vehicle2 = null;
		}

		if($this->visitor1 !== null && $this->visitor1->getVisitorId() !== null) {
			$this->visitor1->delete($this->mysqli);
			$this->visitor1 = null;
		}

		if($this->visitor2 !== null && $this->visitor2->getVisitorId() !== null) {
			$this->visitor2->delete($this->mysqli);
			$this->visitor2 = null;
		}

		// disconnect from mySQL
		if($this->mysqli !== null) {
			$this->mysqli->close();
			$this->mysqli = null;
		}
	}

	/**
	 * test inserting a valid vehicle into mySQL
	 **/
	public function testInsertValidVehicle() {
		// zeroth, ensure the Vehicle and mySQL class are sane
		$this->assertNotNull($this->vehicle1);
		$this->assertNotNull($this->mysqli);

		// first, insert the Vehicle into mySQL
		$this->vehicle1->insert($this->mysqli);

		// second, grab an Vehicle from mySQL
		$mysqlVehicle = Vehicle::getVehicleByVehicleId($this->mysqli, $this->vehicle1->getVehicleId());

		// third, assert the Vehicle we have created and mySQL's Vehicle are the same object
		$this->assertIdentical($this->vehicle1->getVehicleId(), $mysqlVehicle->getVehicleId());
		$this->assertIdentical($this->vehicle1->getVisitorId(), $mysqlVehicle->getVisitorId());
		$this->assertIdentical($this->vehicle1->getVehicleColor(), $mysqlVehicle->getVehicleColor());
		$this->assertIdentical($this->vehicle1->getVehicleMake(), $mysqlVehicle->getVehicleMake());
		$this->assertIdentical($this->vehicle1->getVehicleModel(), $mysqlVehicle->getVehicleModel());
		$this->assertIdentical($this->vehicle1->getVehiclePlateNumber(), $mysqlVehicle->getVehiclePlateNumber());
		$this->assertIdentical($this->vehicle1->getVehiclePlateState(), $mysqlVehicle->getVehiclePlateState());
		$this->assertIdentical($this->vehicle1->getVehicleYear(), $mysqlVehicle->getVehicleYear());
	}


	/**
	 * test inserting an invalid vehicle into mySQL
	 **/
	public function testInsertInvalidVehicle() {
		// zeroth, ensure the Vehicle and mySQL class are sane
		$this->assertNotNull($this->vehicle1);
		$this->assertNotNull($this->mysqli);

		// first, set the vehicle id to an invented value that should never insert in the first place
		$this->vehicle1->setVehicleId(99);

		// second, try to insert the Vehicle and ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$this->vehicle1->insert($this->mysqli);

		// third, set the Vehicle to null to prevent tearDown() from deleting an Vehicle that never existed
		$this->vehicle1 = null;
	}

	/**
	 * test deleting an Vehicle from mySQL
	 **/
	public function testDeleteValidVehicle() {
		// zeroth, ensure the Vehicle and mySQL class are sane
		$this->assertNotNull($this->vehicle1);
		$this->assertNotNull($this->mysqli);

		// first, assert the Vehicle is inserted into mySQL by grabbing it from mySQL and asserting the primary key
		$this->vehicle1->insert($this->mysqli);
		$mysqlVehicle = Vehicle::getVehicleByVehicleId($this->mysqli, $this->vehicle1->getVehicleId());
		$this->assertIdentical($this->vehicle1->getVisitorId(), $mysqlVehicle->getVisitorId());

		// second, delete the Vehicle from mySQL and re-grab it from mySQL and assert it does not exist
		$this->vehicle1->delete($this->mysqli);
		$mysqlVehicle = Vehicle::getVehicleByVehicleId($this->mysqli, $this->vehicle1->getVehicleId());
		$this->assertNull($mysqlVehicle);

		// third, set the Vehicle to null to prevent tearDown() from deleting an Vehicle that has already been deleted
		$this->vehicle1 = null;
	}

	/**
	 * test deleting an Vehicle from mySQL that does not exist
	 **/
	public function testDeleteInvalidVehicle() {
		// zeroth, ensure the Vehicle and mySQL class are sane
		$this->assertNotNull($this->vehicle1);
		$this->assertNotNull($this->mysqli);

		// first, try to delete the Vehicle before inserting it and ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$this->vehicle1->delete($this->mysqli);

		// second, set the Vehicle to null to prevent tearDown() from deleting an Vehicle that has already been deleted
		$this->vehicle1 = null;
	}

	/**
	 * test updating a Vehicle from mySQL
	 **/
	public function testUpdateValidVehicle() {
		// zeroth, ensure the Vehicle and mySQL class are sane
		$this->assertNotNull($this->vehicle1);
		$this->assertNotNull($this->mysqli);

		// first, assert the Vehicle is inserted into mySQL by grabbing it from mySQL and asserting the primary key
		$this->vehicle1->insert($this->mysqli);
		$mysqlVehicle = Vehicle::getVehicleByVehicleId($this->mysqli, $this->vehicle1->getVehicleId());
		$this->assertIdentical($this->vehicle1->getVisitorId(), $mysqlVehicle->getVisitorId());

		// second, change the Vehicle, update it mySQL
		$newContent = "Black";
		$this->vehicle1->setVehicleColor($newContent);
		$this->vehicle1->update($this->mysqli);

		// third, re-grab the Vehicle from mySQL
		$mysqlVehicle = Vehicle::getVehicleByVehicleId($this->mysqli, $this->vehicle1->getVehicleId());
		$this->assertNotNull($mysqlVehicle);

		// fourth, assert the Vehicle we have updated and mySQL's Vehicle are the same object

		$this->assertIdentical($this->vehicle1->getVehicleId(), $mysqlVehicle->getVehicleId());
		$this->assertIdentical($this->vehicle1->getVisitorId(), $mysqlVehicle->getVisitorId());
		$this->assertIdentical($this->vehicle1->getVehicleColor(), $mysqlVehicle->getVehicleColor());
		$this->assertIdentical($this->vehicle1->getVehicleMake(), $mysqlVehicle->getVehicleMake());
		$this->assertIdentical($this->vehicle1->getVehicleModel(), $mysqlVehicle->getVehicleModel());
		$this->assertIdentical($this->vehicle1->getVehiclePlateNumber(), $mysqlVehicle->getVehiclePlateNumber());
		$this->assertIdentical($this->vehicle1->getVehiclePlateState(), $mysqlVehicle->getVehiclePlateState());
		$this->assertIdentical($this->vehicle1->getVehicleYear(), $mysqlVehicle->getVehicleYear());
	}

	/**
	 * test updating an Vehicle from mySQL that does not exist
	 **/
	public function testUpdateInvalidVehicle() {
		// zeroth, ensure the Vehicle and mySQL class are sane
		$this->assertNotNull($this->vehicle1);
		$this->assertNotNull($this->mysqli);

		// first, try to update the Vehicle before inserting it and ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$this->vehicle1->update($this->mysqli);

		// second, set the Vehicle to null to prevent tearDown() from deleting an Vehicle that has already been deleted
		$this->vehicle1 = null;
	}

	/**
	 * test selecting a vehicle by vehicleId from mySQL
	 **/
	public function testSelectValidVehicleByVehicleId() {
		// zeroth, ensure the Visitor and mySQL class are sane
		$this->assertNotNull($this->vehicle1);
		$this->assertNotNull($this->mysqli);

		// first, insert the vehicle into mySQL
		$this->vehicle1->insert($this->mysqli);
		$mysqlVehicle = Vehicle::getVehicleByVehicleId($this->mysqli, $this->vehicle1->getVehicleId());

		// second, assert the query returned a result
		$this->assertNotNull($mysqlVehicle);
	}

	/**
	 * test selecting a vehicle that does not exist in mySQL
	 **/
	public function testSelectInvalidVehicleByVehicleId() {
		// zeroth, ensure the vehicle and mySQL class are sane
		$this->assertNotNull($this->vehicle1);
		$this->assertNotNull($this->mysqli);

		// first, try to selecting a vehicle by vehicleId
		$vehicle = 0;

		// ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$mysqlVehicle = Vehicle::getVehicleByVehicleId($this->mysqli, $vehicle);

		// second, assert the query returned no results
		$this->assertNull($mysqlVehicle);
	}

	/**
	 * test selecting a vehicle by visitorId from mySQL
	 **/
	public function testSelectValidVehicleByVisitorId() {
		// zeroth, ensure the Vehicle and mySQL class are sane
		$this->assertNotNull($this->vehicle1);
		$this->assertNotNull($this->mysqli);

		// first, insert the Vehicle into mySQL
		$this->vehicle1->insert($this->mysqli);
		$mysqlVisitor = Vehicle::getVehicleByVisitorId($this->mysqli, $this->vehicle1->getVisitorId());

		// second, assert the query returned a result
		$this->assertNotNull($mysqlVisitor);
	}

	/**
	 * test selecting a vehicle that does not exist in mySQL
	 **/
	public function testSelectInvalidVehicleByVisitorId() {
		// zeroth, ensure the Vehicle and mySQL class are sane
		$this->assertNotNull($this->vehicle1);
		$this->assertNotNull($this->mysqli);

		// first, try to selecting a vehicle by visitorId
		$visitor = 0;

		// ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$mysqlVisitor = Vehicle::getVehicleByVisitorId($this->mysqli, $visitor);

		// second, assert the query returned no results
		$this->assertNull($mysqlVisitor);
	}

	/**
	 * test selecting a vehicle by plate number from mySQL
	 */
	public function testSelectValidVehicleByPlateNumber() {
		// zeroth, ensure the Vehicle and mySQL class are sane
		$this->assertNotNull($this->vehicle1);
		$this->assertNotNull($this->vehicle2);
		$this->assertNotNull($this->mysqli);

		// first, insert the two test vehicles
		$this->vehicle1->insert($this->mysqli);
		$this->vehicle2->insert($this->mysqli);

		// second, grab an array of Vehicles from mySQL and assert we got an array
		$plateNumber = "999";
		$mysqlVehicle = Vehicle::getVehicleByPlateNumber($this->mysqli, $plateNumber);
		$this->assertIsA($mysqlVehicle, "array");
		$this->assertIdentical(count($mysqlVehicle), 2);

		// third, verify each Vehicle by asserting the primary key and the select criteria
		foreach($mysqlVehicle as $vehicle) {
			$this->assertTrue($vehicle->getVehicleId() > 0);
			$this->assertTrue(strpos($vehicle->getVehiclePlateNumber(), $plateNumber) >= 0);
		}
	}

	/**
	 * test grabbing no vehicles from mySQL by non existent content
	 **/
	public function testSelectInvalidVehicleByPlateNumber() {
		// zeroth, ensure the Vehicle and mySQL class are sane
		$this->assertNotNull($this->vehicle1);
		$this->assertNotNull($this->vehicle2);
		$this->assertNotNull($this->mysqli);

		// first, insert the two test vehicles
		$this->vehicle1->insert($this->mysqli);
		$this->vehicle2->insert($this->mysqli);

		// second, try to grab an array of Vehicles from mySQL and assert null
		$plateNumber = "NA";
		$mysqlVehicle = Vehicle::getVehicleByPlateNumber($this->mysqli, $plateNumber);
		$this->assertNull($mysqlVehicle);
	}
}
?>