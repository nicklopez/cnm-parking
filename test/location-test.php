<?php
// first, require the SimpleTest framework <http://simpletest.org/>
// this path is *not* universal, but deployed on the bootcamp-coders server
require_once("/usr/lib/php5/simpletest/autorun.php");

//next, require the encrypted configuration functions
require_once("/home/cnmparki/etc/mysql/encrypted-config.php");

// next, require the class from the project under scrutiny
require_once("../php/classes/location.php");

/**
 * Unit test for the Location class
 *
 * This is a SimpleTest test case for the CRUD methods of the Location class.
 *
 * @see Location
 * @author Nick Lopez <nick@nicklopezcodes.com>
 */
class LocationTest extends UnitTestCase {
	/**
	 * mysqli object shared amongst all tests
	 */
	private $mysqli = null;
	/**
	 *  first instance of the object we are testing with
	 */
	private $location1 = null;
	/**
	 *  second instance of the object we are testing with
	 */
	private $location2 = null;

	// this section contains location variables with constants needed for creating a new Location
	/**
	 * description of CNM parking location
	 **/
	private $locationDescription1 = "CNM STEMulus";
	/**
	 * additional notes (optional) of CNM parking location
	 **/
	private $locationNote1 = "Address goes here";
	/**
	 * gps coordinates (latitude) of CNM parking location
	 **/
	private $latitude1 = 89;
	/**
	 * gps coordinates (longitude) of CNM parking location
	 **/
	private $longitude1 = 170;
	/**
	 * description of CNM parking location
	 **/
	private $locationDescription2 = "CNM STEMulus";
	/**
	 * additional notes (optional) of CNM parking location
	 **/
	private $locationNote2 = "Address goes here";
	/**
	 * gps coordinates (latitude) of CNM parking location
	 **/
	private $latitude2 = 39;
	/**
	 * gps coordinates (longitude) of CNM parking location
	 **/
	private $longitude2 = 29;


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
		$this->location1 = new Location(null, $this->latitude1, $this->locationDescription1, $this->locationNote1, $this->longitude1);
		$this->location2 = new Location(null, $this->latitude2, $this->locationDescription2, $this->locationNote2, $this->longitude2);
	}

	/**
	 * tears down the connection to mySQL and deletes the test instance object
	 **/
	public function tearDown() {
		// destroy the object if it was created
		if($this->location1 !== null && $this->location1->getLocationId() !== null) {
			$this->location1->delete($this->mysqli);
			$this->location1 = null;
		}

		if($this->location2 !== null && $this->location2->getLocationId() !== null) {
			$this->location2->delete($this->mysqli);
			$this->location2 = null;
		}

		// disconnect from mySQL
		if($this->mysqli !== null) {
			$this->mysqli->close();
			$this->mysqli = null;
		}
	}

	/**
	 * test inserting a valid location into mySQL
	 **/
	public function testInsertValidLocation() {
		// zeroth, ensure the Location and mySQL class are sane
		$this->assertNotNull($this->location1);
		$this->assertNotNull($this->mysqli);

		// first, insert the Location into mySQL
		$this->location1->insert($this->mysqli);

		// second, grab a Location from mySQL
		$mysqlLocation = Location::getLocationByLocationId($this->mysqli, $this->location1->getLocationId());

		// third, assert the Location we have created and mySQL's Location are the same object
		$this->assertIdentical($this->location1->getLocationId(), $mysqlLocation->getLocationId());
		$this->assertIdentical($this->location1->getLatitude(), $mysqlLocation->getLatitude());
		$this->assertIdentical($this->location1->getLocationDescription(), $mysqlLocation->getLocationDescription());
		$this->assertIdentical($this->location1->getLocationNote(), $mysqlLocation->getLocationNote());
		$this->assertIdentical($this->location1->getLongitude(), $mysqlLocation->getLongitude());
	}


	/**
	 * test inserting an invalid location into mySQL
	 **/
	public function testInsertInvalidLocation() {
		// zeroth, ensure the Location and mySQL class are sane
		$this->assertNotNull($this->location1);
		$this->assertNotNull($this->mysqli);

		// first, set the location id to an invented value that should never insert in the first place
		$this->location1->setLocationId(99);

		// second, try to insert the Location and ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$this->location1->insert($this->mysqli);

		// third, set the Location to null to prevent tearDown() from deleting an Location that never existed
		$this->location1 = null;
	}

	/**
	 * test deleting an Location from mySQL
	 **/
	public function testDeleteValidLocation() {
		// zeroth, ensure the Location and mySQL class are sane
		$this->assertNotNull($this->location1);
		$this->assertNotNull($this->mysqli);

		// first, assert the Location is inserted into mySQL by grabbing it from mySQL and asserting the primary key
		$this->location1->insert($this->mysqli);
		$mysqlLocation = Location::getLocationByLocationId($this->mysqli, $this->location1->getLocationId());
		$this->assertIdentical($this->location1->getLocationId(), $mysqlLocation->getLocationId());

		// second, delete the Location from mySQL and re-grab it from mySQL and assert it does not exist
		$this->location1->delete($this->mysqli);
		$mysqlLocation = Location::getLocationByLocationId($this->mysqli, $this->location1->getLocationId());
		$this->assertNull($mysqlLocation);

		// third, set the Location to null to prevent tearDown() from deleting an Location that has already been deleted
		$this->location1 = null;
	}

	/**
	 * test deleting an Location from mySQL that does not exist
	 **/
	public function testDeleteInvalidLocation() {
		// zeroth, ensure the Location and mySQL class are sane
		$this->assertNotNull($this->location1);
		$this->assertNotNull($this->mysqli);

		// first, try to delete the Location before inserting it and ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$this->location1->delete($this->mysqli);

		// second, set the Location to null to prevent tearDown() from deleting an Location that has already been deleted
		$this->location1 = null;
	}

	/**
	 * test updating a Location from mySQL
	 **/
	public function testUpdateValidLocation() {
		// zeroth, ensure the Location and mySQL class are sane
		$this->assertNotNull($this->location1);
		$this->assertNotNull($this->mysqli);

		// first, assert the Location is inserted into mySQL by grabbing it from mySQL and asserting the primary key
		$this->location1->insert($this->mysqli);
		$mysqlLocation = Location::getLocationByLocationId($this->mysqli, $this->location1->getLocationId());
		$this->assertIdentical($this->location1->getLocationId(), $mysqlLocation->getLocationId());

		// second, change the Location, update it mySQL
		$newContent = "UNM";
		$this->location1->setLocationDescription($newContent);
		$this->location1->update($this->mysqli);

		// third, re-grab the Location from mySQL
		$mysqlLocation = Location::getLocationByLocationId($this->mysqli, $this->location1->getLocationId());
		$this->assertNotNull($mysqlLocation);

		// fourth, assert the Location we have updated and mySQL's Location are the same object
		$this->assertIdentical($this->location1->getLocationId(), $mysqlLocation->getLocationId());
		$this->assertIdentical($this->location1->getLatitude(), $mysqlLocation->getLatitude());
		$this->assertIdentical($this->location1->getLocationDescription(), $mysqlLocation->getLocationDescription());
		$this->assertIdentical($this->location1->getLocationNote(), $mysqlLocation->getLocationNote());
		$this->assertIdentical($this->location1->getLongitude(), $mysqlLocation->getLongitude());
	}

	/**
	 * test updating an Location from mySQL that does not exist
	 **/
	public function testUpdateInvalidLocation() {
		// zeroth, ensure the Location and mySQL class are sane
		$this->assertNotNull($this->location1);
		$this->assertNotNull($this->mysqli);

		// first, try to update the Location before inserting it and ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$this->location1->update($this->mysqli);

		// second, set the Location to null to prevent tearDown() from deleting an Location that has already been deleted
		$this->location1 = null;
	}

	/**
	 * test selecting a location by locationId from mySQL
	 **/
	public function testSelectValidLocation() {
		// zeroth, ensure the Location and mySQL class are sane
		$this->assertNotNull($this->location1);
		$this->assertNotNull($this->mysqli);

		// first, insert the Location into mySQL
		$this->location1->insert($this->mysqli);
		$mysqlLocation = Location::getLocationByLocationId($this->mysqli, $this->location1->getLocationId());

		// second, assert the query returned a result
		$this->assertNotNull($mysqlLocation);
	}

	/**
	 * test selecting a Location that does not exist in mySQL
	 **/
	public function testSelectInvalidLocation() {
		// zeroth, ensure the Location and mySQL class are sane
		$this->assertNotNull($this->location1);
		$this->assertNotNull($this->mysqli);

		// first, try to selecting a location by locationId
		$locationId = 0;

		// ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$mysqlLocation = Location::getLocationByLocationId($this->mysqli, $locationId);

		// second, assert the query returned no results
		$this->assertNull($mysqlLocation);
	}

	/**
	 * test selecting a location by description from mySQL
	 */
	public function testSelectValidLocationByLocationDescription() {
		// zeroth, ensure the Location and mySQL class are sane
		$this->assertNotNull($this->location1);
		$this->assertNotNull($this->location2);
		$this->assertNotNull($this->mysqli);

		// first, insert the two test locations
		$this->location1->insert($this->mysqli);
		$this->location2->insert($this->mysqli);

		// second, grab an array of Locations from mySQL and assert we got an array
		$locationDescription = "STEMulus";
		$mysqlLocation = Location::getLocationByLocationDescription($this->mysqli, $locationDescription);
		$this->assertIsA($mysqlLocation, "array");
		$this->assertIdentical(count($mysqlLocation), 2);

		// third, verify each Location by asserting the primary key and the select criteria
		foreach($mysqlLocation as $location) {
			$this->assertTrue($location->getLocationId() > 0);
			$this->assertTrue(strpos($location->getLocationDescription(), $locationDescription) >= 0);
		}
	}

	/**
	 * test grabbing no locations from mySQL by non existent content
	 **/
	public function testSelectInvalidLocationByLocationDescription() {
		// zeroth, ensure the Location and mySQL class are sane
		$this->assertNotNull($this->location1);
		$this->assertNotNull($this->location2);
		$this->assertNotNull($this->mysqli);

		// first, insert the two test locations
		$this->location1->insert($this->mysqli);
		$this->location2->insert($this->mysqli);

		// second, try to grab an array of Locations from mySQL and assert null
		$locationDescription = "NA";
		$mysqlLocation = Location::getLocationByLocationDescription($this->mysqli, $locationDescription);
		$this->assertNull($mysqlLocation);
	}
}
?>