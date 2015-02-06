<?php
// first, require the SimpleTest framework <http://simpletest.org/>
// this path is *not* universal, but deployed on the bootcamp-coders server
require_once("/usr/lib/php5/simpletest/autorun.php");

//next, require the encrypted configuration functions
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

// next, require the class from the project under scrutiny
require_once("../../php/classes/admin-profile.php");

/**
 * Unit test for the Visitor class
 *
 * This is a SimpleTest test case for the CRUD methods of the AdminProfile class.
 *
 * @see AdminProfile
 * @author David Fevig <davidfevig@davidfevig.com>
 */
class AdminProfileTest extends UnitTestCase {
	/**
	 * mysqli object shared amongst all tests
	 */
	private $mysqli = null;
	/**
	 * first instance of the object we are testing with
	 */
	private $adminProfile1 = null;
	/**
	 * second instance of the object we are testing with
	 */
	private $adminProfile2 = null;
	/**
	 * admin id of the test admin profile
	 **/
	private $adminId1 = 72;
	/**
	 * admin id of the test admin profile
	 **/
	private $adminId2 = 73;

	// this section contains visitor variables with constants needed for creating a new admin profile
	/**
	 * first name of admin
	 **/
	private $adminFirstName1 = "Frank1";
	/**
	 * last name of admin
	 **/
	private $adminLastName1 = "Ranger1";
	/**
	 * first name of admin
	 **/
	private $adminFirstName2 = "Frank2";
	/**
	 * last name of admin
	 **/
	private $adminLastName2 = "Ranger2";



	/**
	 * setup the mySQL connection for this test
	 */
	public function setUp() {
		// now retrieve the configuration parameters
		try {
			$configFile = "/etc/apache2/capstone-mysql/cnmparking.ini";
			$configArray = readConfig($configFile);
		} catch (InvalidArgumentException $invalidArgument) {
			// re-throw the exception to the caller
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		}

		// first, connect to mysqli
		mysqli_report(MYSQLI_REPORT_STRICT);
		$this->mysqli = new mysqli($configArray["hostname"], $configArray["username"], $configArray["password"], $configArray["database"]);

		// second, create an instance of the object under scrutiny
		$this->adminProfile1 = new AdminProfile(null, $this->adminId1, $this->adminFirstName1, $this->adminLastName1);
		$this->adminProfile2 = new AdminProfile(null, $this->adminId2, $this->adminFirstName2, $this->adminLastName2);
	}

	/**
	 * tears down the connection to mySQL and deletes the test instance object
	 **/
	public function tearDown() {
// destroy the object if it was created
		if($this->adminProfile1 !== null && $this->adminProfile1->getAdminProfileId() !== null) {
			$this->adminProfile1->delete($this->mysqli);
			$this->adminProfile1 = null;
		}

		if($this->adminProfile2 !== null && $this->adminProfile2->getAdminProfileId() !== null) {
			$this->adminProfile2->delete($this->mysqli);
			$this->adminProfile2 = null;
		}

		// disconnect from mySQL
		if($this->mysqli !== null) {
			$this->mysqli->close();
			$this->mysqli = null;
		}
	}

	/**
	 * test inserting a valid admin profile into mySQL
	 **/
	public function testInsertValidAdminProfile() {
		// zeroth, ensure the Visitor and mySQL class are sane
		$this->assertNotNull($this->adminProfile1);
		$this->assertNotNull($this->mysqli);

		// first, insert the AdminProfile into mySQL
		$this->adminProfile1->insert($this->mysqli);

		// second, grab a AdminProfile from mySQL
		$mysqlAdminProfile = AdminProfile::getAdminProfileByAdminProfileId($this->mysqli, $this->adminProfile1->getAdminProfileId());

		// third, assert the Vehicle we have created and mySQL's Vehicle are the same object
		$this->assertIdentical($this->adminProfile1->getAdminProfileId(), $mysqlAdminProfile->getAdminProfileId());
		$this->assertIdentical($this->adminProfile1->getAdminId(), $mysqlAdminProfile->getAdminId());
		$this->assertIdentical($this->adminProfile1->getAdminFirstName(), $mysqlAdminProfile->getAdminFirstName());
		$this->assertIdentical($this->adminProfile1->getAdminLastName(), $mysqlAdminProfile->getAdminLastName());
	}


	/**
	 * test inserting an invalid admin profile into mySQL
	 **/
	public function testInsertInvalidAdminProfile() {
		// zeroth, ensure the Visitor and mySQL class are sane
		$this->assertNotNull($this->adminProfile1);
		$this->assertNotNull($this->mysqli);

		// first, set the admin profile id to an invented value that should never insert in the first place
		$this->adminProfile1->setAdminProfileId(500);

		// second, try to insert the admin profile and ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$this->adminProfile1->insert($this->mysqli);

		// third, set the AdminProfile to null to prevent tearDown() from deleting an AdminProfile that never existed
		$this->adminProfile1 = null;
	}

	/**
	 * test deleting an AdminProfile from mySQL
	 **/
	public function testDeleteValidAdminProfile() {
		// zeroth, ensure the AdminProfile and mySQL class are sane
		$this->assertNotNull($this->adminProfile1);
		$this->assertNotNull($this->mysqli);

		// first, assert the AdminProfile is inserted into mySQL by grabbing it from mySQL and asserting the primary key
		$this->adminProfile1->insert($this->mysqli);
		$mysqlAdminProfile = AdminProfile::getAdminProfileByAdminProfileId($this->mysqli, $this->adminProfile1->getAdminProfileId());
		$this->assertIdentical($this->adminProfile1->getAdminProfileId(), $mysqlAdminProfile->getAdminProfileId());

		// second, delete the AdminProfile from mySQL and re-grab it from mySQL and assert it does not exist
		$this->adminProfile1->delete($this->mysqli);
		$mysqlAdminProfile = AdminProfile::getAdminProfileByAdminProfileId($this->mysqli, $this->adminProfile1->getAdminProfileId());
		$this->assertNull($mysqlAdminProfile);

		// third, set the AdminProfile to null to prevent tearDown() from deleting an AdminProfile that has already been deleted
		$this->adminProfile1 = null;
	}

	/**
	 * test deleting an AdminProfile from mySQL that does not exist
	 **/
	public function testDeleteInvalidAdminProfile() {
		// zeroth, ensure the AdminProfile and mySQL class are sane
		$this->assertNotNull($this->adminProfile1);
		$this->assertNotNull($this->mysqli);

		// first, try to delete the AdminProfile before inserting it and ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$this->adminProfile1->delete($this->mysqli);

		// second, set the AdminProfile to null to prevent tearDown() from deleting an AdminProfile that has already been deleted
		$this->adminProfile1 = null;
	}

	/**
	 * test updating an AdminProfile from mySQL
	 **/
	public function testUpdateValidAdminProfile() {
		// zeroth, ensure the AdminProfile and mySQL class are sane
		$this->assertNotNull($this->adminProfile1);
		$this->assertNotNull($this->mysqli);

		// first, assert the AdminProfile is inserted into mySQL by grabbing it from mySQL and asserting the primary key
		$this->adminProfile1->insert($this->mysqli);
		$mysqlAdminProfile = AdminProfile::getAdminProfileByAdminProfileId($this->mysqli, $this->adminProfile1->getAdminProfileId());
		$this->assertIdentical($this->adminProfile1->getAdminProfileId(), $mysqlAdminProfile->getAdminProfileId());

		// second, change the AdminProfile, update it mySQL
		$newContent = "Jones";
		$this->adminProfile1->setAdminLastName($newContent);
		$this->adminProfile1->update($this->mysqli);

		// third, re-grab the AdminProfile from mySQL
		$mysqlAdminProfile = AdminProfile::getAdminProfileByAdminProfileId($this->mysqli, $this->adminProfile1->getAdminProfileId());
		$this->assertNotNull($mysqlAdminProfile);

		// fourth, assert the AdminProfile we have updated and mySQL's AdminProfile are the same object
		$this->assertIdentical($this->adminProfile1->getAdminProfileId(), $mysqlAdminProfile->getAdminProfileId());
		$this->assertIdentical($this->adminProfile1->getAdminId(), $mysqlAdminProfile->getAdminId());
		$this->assertIdentical($this->adminProfile1->getAdminFirstName(), $mysqlAdminProfile->getAdminFirstName());
		$this->assertIdentical($this->adminProfile1->getAdminLastName(), $mysqlAdminProfile->getAdminLastName());
	}

	/**
	 * test updating an AdminProfile from mySQL that does not exist
	 **/
	public function testUpdateInvalidAdminProfile() {
		// zeroth, ensure the AdminProfile and mySQL class are sane
		$this->assertNotNull($this->adminProfile1);
		$this->assertNotNull($this->mysqli);

		// first, try to update the AdminProfile before inserting it and ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$this->adminProfile1->update($this->mysqli);

		// second, set the AdminProfile to null to prevent tearDown() from deleting a AdminProfile that has already been deleted
		$this->adminProfile1 = null;
	}

	/**
	 * test selecting AdminProfile by adminProfileId from mySQL
	 **/
	public function testSelectValidAdminProfile() {
		// zeroth, ensure the AdminProfile and mySQL class are sane
		$this->assertNotNull($this->adminProfile1);
		$this->assertNotNull($this->mysqli);

		// first, insert the AdminProfile into mySQL
		$this->adminProfile1->insert($this->mysqli);
		$mysqlAdminProfile = AdminProfile::getAdminProfileByAdminProfileId($this->mysqli, $this->adminProfile1->getAdminProfileId());

		// second, assert the query returned a result
		$this->assertNotNull($mysqlAdminProfile);
	}

	/**
 * test selecting an AdminProfile that does not exist in mySQL
	 **/
	public function testSelectInvalidAdminProfile() {
		// zeroth, ensure the AdminProfile and mySQL class are sane
		$this->assertNotNull($this->adminProfile1);
		$this->assertNotNull($this->mysqli);

		// first, try to selecting an admin profile by adminProfileId
		$adminProfileId = 0;

		// ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$mysqlAdminProfile = AdminProfile::getAdminProfileByAdminProfileId($this->mysqli, $adminProfileId);

		// second, assert the query returned no results
		$this->assertNull($mysqlAdminProfile);
	}

	/**
	 * test selecting an AdminProfile by first name from mySQL
	 **/
	public function testSelectValidFirstName() {
		// zeroth, ensure the AdminProfile and mySQL class are sane
		$this->assertNotNull($this->adminProfile1);
		$this->assertNotNull($this->adminProfile2);
		$this->assertNotNull($this->mysqli);

		// first, insert the two test admin profiles
		$this->adminProfile1->insert($this->mysqli);
		$this->adminProfile2->insert($this->mysqli);

		// second, grab an array of AdminProfiles from mySQL and assert we have an array
		$firstName = "Frank";
		$mysqlAdminProfile = AdminProfile::getAdminProfileByAdminFirstName($this->mysqli, $firstName);
		$this->assertIsA($mysqlAdminProfile, "array");
		$this->assertIdentical(count($mysqlAdminProfile), 2);

		// third, verify each AdminProfile by asserting the primary key and the select criteria
		foreach($mysqlAdminProfile as $adminProfile) {
			$this->assertTrue($adminProfile->getAdminProfileId() > 0);
			$this->assertTrue(strpos($adminProfile->getAdminFirstName(), $firstName) >= 0);
		}
	}

	/**
	 * test selecting an AdminProfile that does not exist in mySQL
	 **/
	public function testSelectInvalidFirstName() {
// zeroth, ensure the AdminProfile and mySQL class are sane
		$this->assertNotNull($this->adminProfile1);
		$this->assertNotNull($this->adminProfile2);
		$this->assertNotNull($this->mysqli);

		// first, insert the two test admin profiles
		$this->adminProfile1->insert($this->mysqli);
		$this->adminProfile2->insert($this->mysqli);

		// second, try to grab an array of admin profiles from mySQL and assert null
		$firstName = "NA";
		$mysqlAdminProfile = AdminProfile::getAdminProfileByAdminFirstName($this->mysqli, $firstName);
		$this->assertNull($mysqlAdminProfile);
	}

	/**
	 * test selecting an AdminProfile by last name from mySQL
	 **/
	public function testSelectValidLastName() {
		// zeroth, ensure the Visitor and mySQL class are sane
		$this->assertNotNull($this->adminProfile1);
		$this->assertNotNull($this->adminProfile2);

		// first, insert the two test admin profiles
		$this->adminProfile1->insert($this->mysqli);
		$this->adminProfile2->insert($this->mysqli);

		// second, grab an array of AdminProfiles from mySQL and assert we have an array
		$lastName = "Ranger";
		$mysqlAdminProfile = AdminProfile::getAdminProfileByAdminLastName($this->mysqli, $lastName);
		$this->assertIsA($mysqlAdminProfile, "array");
		$this->assertIdentical(count($mysqlAdminProfile), 2);

		// third, verify each AdminProfile by asserting the primary key and the select criteria
		foreach($mysqlAdminProfile as $adminProfile) {
			$this->assertTrue($adminProfile->getAdminProfileId() > 0);
			$this->assertTrue(strpos($adminProfile->getAdminLastName(), $lastName) >= 0);
		}
	}
	/**
	 * test selecting an AdminProfile that does not exist in mySQL
	 **/
	public function testSelectInvalidLastName() {
// zeroth, ensure the AdminProfile and mySQL class are sane
		$this->assertNotNull($this->adminProfile1);
		$this->assertNotNull($this->adminProfile2);
		$this->assertNotNull($this->mysqli);

		// first, insert the two test admin profiles
		$this->adminProfile1->insert($this->mysqli);
		$this->adminProfile2->insert($this->mysqli);

		// second, try to grab an array of admin profiles from mySQL and assert null
		$lastName = "NA";
		$mysqlAdminProfile = AdminProfile::getAdminProfileByAdminLastName($this->mysqli, $lastName);
		$this->assertNull($mysqlAdminProfile);
	}
}
?>