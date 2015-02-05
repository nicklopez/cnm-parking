<?php
//first, require the SimpleTest framework <http://simpletest.org/>
//this path is *NOT* universal, but deployed on the bootcamp-coders server
require_once("/usr/lib/php5/simpletest/autorun.php");

// next, require the class from the project under scrutiny
require_once("adminProfile.php");

/**
 * Unit test for the AdminProfile test
 *
 * This is a SimpleTest test case for the CRUD methods of the Admin class.
 *
 * @see Admin
 * @author David Fevig <davidfevig@davidfevig.com>
 **/

class AdminProfileTest extends UnitTestCase {
	/**
	 * mysqli object shared amongst all tests
	 **/
	private $mysqli = null;
	/**
	 *instance of the object we are testing
	 */
	private $adminProfile = null;
	// this section contains member variables with constants needed for creating a new admin
	/**
	 * int of admin id foreign key
	 */
	private $adminId = 39;
	/**
	 * content of the admin first name
	 */
	private $adminFirstName = "Frank";
	/**
	 * content of the admin last name
	 */
	private $adminLastName = "Ranger";

	/**
	 * sets up the mySQL connection for this test
	 */
	public function setUp() {
		//first, connect to mysqli
		mysqli_report(MYSQLI_REPORT_STRICT);
		$this->mysqli = new mysqli("localhost", "cnmparking-dba", "smutramrenalgoren", "cnmparking");

		// second, create an instance of the object under scrutiny
		$this->adminProfile = new AdminProfile(null, $this->adminId, $this->adminFirstName, $this->adminLastName);
	}

	/**
	 * tears down he connection to mySQL and deletes the test instance object
	 **/
	public function tearDown() {
		// destroy the object if it was created
		if($this->adminProfile !== null) {
			$this->adminProfile->delete($this->mysqli);
			$this->adminProfile = null;
		}

		//disconnect from mySQL
		if($this->mysqli !== null) {
			$this->mysqli->close();
			$this->mysqli = null;
		}
	}
	/**
	 * test inserting a valid admin profile into mySQL
	 */
	public function testInsertValidAdminProfile() {
		//zeroth, ensure the admin profile and mySQL class are sane
		$this->assertNotNull($this->adminProfile);
		$this->assertNotNull($this->mysqli);

		//first, insert the admin profile into mySQL
		$this->adminProfile->insert($this->mysqli);

		//second, grab an admin profile from mySQL
		$mysqlAdminProfile = AdminProfile::getAdminByAdminProfileId($this->mysqli, $this->adminProfile->getAdminProfileId());

		//third, assert the admin profile created and mySQL's admin profile are the same object
		$this->assertIdentical($this->adminProfile->getAdminProfileId(), $mysqlAdminProfile->getAdminProfileId());
		$this->assertIdentical($this->adminProfile->getAdminId(), $mysqlAdminProfile->getAdminId());
		$this->assertIdentical($this->adminProfile->getAdminFirstName(), $mysqlAdminProfile->getAdminFirstName());
		$this->assertIdentical($this->adminProfile->getAdminLastName(), $mysqlAdminProfile->getAdminLastName());
	}
	/**
	 * test inserting an invalid admin profile into mySQL
	 */
	public function testInsertInvalidAdminProfile() {
		//zeroth, ensure the admin and mySQL class are the same
		$this->assertNotNull($this->adminProfile);
		$this->assertNotNull($this->mysqli);

		//first, set the admin profile id to an invented value that should never insert in the first place
		$this->adminProfile->setAdminProfileId(500);

		//second, try to insert the admin profile and ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$this->adminProfile->insert($this->mysqli);

		//third, set the admin profile to null to prevent the tearDown() from deleting an admin that never existed
		$this->adminProfile = null;
	}
	/**
	 * test deleting an admin profile from mySQL
	 */
	public function testDeleteValidAdminProfile() {
		//zeroth, ensure the admin and mySQL class are the sane
		$this->assertNotNull($this->adminProfile);
		$this->assertNotNull($this->mysqli);

		//first, assert the admin profile is inserted into mySQL by grabbing it from mySQL and asserting the primary key
		$this->adminProfile->insert($this->mysqli);
		$mysqlAdminProfile = AdminProfile::getAdminByAdminProfileId($this->mysqli, $this->adminProfile->getAdminProfileId());
		$this->assertIdentical($this->adminProfile->getAdminProfileId(), $mysqlAdminProfile->getAdminProfileId());

		//second, delete the admin profile from mySQL and re-grab it from mySQL and assert id does not exist
		$this->adminProfile->delete($this->mysqli);
		$mysqlAdminProfile= AdminProfile::getAdminProfileByAdminId($this->mysqli, $this->adminProfile->getAdminProfileId());
		$this->assertNull($mysqlAdminProfile);

		//third, set the admin profile to null to prevent tearDown() from deleting a admin that has already been deleted
		$this->adminProfile = null;
	}
	/**
	 * test deleting an admin profile from mySQL that does not exist
	 */
	public function testDeleteInvalidAdminProfile() {
		//zeroth, ensure the admin profile and mySQL class are the sane
		$this->assertNotNull($this->adminProfile);
		$this->assertNotNull($this->mysqli);

		//first, try to delete the admin profile before inserting it and ensure the exception is thrown
		$this->expectException('mysqli_sql_exception');
		$this->adminProfile->delete($this->mysqli);

		//second, set the admin to null to prevent tearDown() from deleting an admin that has already been deleted
		$this->adminProfile = null;
	}
	/**
	 * test updating an admin from mySQL
	 */
	public function testUpdateValidAdminProfile() {
		//zeroth, ensure the admin profile and mySQL class are the sane
		$this->assertNotNull($this->adminProfile);
		$this->assertNotNull($this->mysqli);

		//first, assert the admin profile is inserted into mySQL by grabbing it from mySQL and asserting the primary key
		$this->adminProfile->insert($this->mysqli);
		$mysqlAdminProfile = AdminProfile::getAdminByAdminProfileId($this->mysqli, $this->adminProfile->getAdminProfileId());
		$this->assertIdentical($this->adminProfile->getAdminProfileId(), $mysqlAdminProfile->getAdminProfileId());

		//third, re-grab the admin profile from mySQL
		$mysqlAdminProfile = AdminProfile::getAdminByAdminProfileId($this->mysqli, $this->adminProfile->getAdminProfileId());
		$this->assertNotNull($mysqlAdminProfile);

		//forth, assert the admin profile is updated and mySQL's admin are the same object
		$this->assertIdentical($this->adminProfile->getAdminProfileId(), $mysqlAdminProfile->getAdminProfileId());
		$this->assertIdentical($this->adminProfile->getAdminId(), $mysqlAdminProfile->getAdminId());
		$this->assertIdentical($this->adminProfile->getAdminFirstName(), $mysqlAdminProfile->getAdminFirstName());
		$this->assertIdentical($this->adminProfile->getAdminLastName(), $mysqlAdminProfile->getAdminLastName());
	}
	/**
	 * test updating an admin from mySQL that does not exist
	 */
	public function testUpdateInvalidAdminProfile() {
		//zeroth, ensure the admin and mySQL class are the sane
		$this->assertNotNull($this->adminProfile);
		$this->assertNotNull($this->mysqli);

		//first, try to update the admin before inserting it and ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$this->adminProfile->update($this->mysqli);

		//second, set the admin to null to prevent tearDown() from deleting an admin that has already been deleted
		$this->adminProfile = null;
	}
}

?>