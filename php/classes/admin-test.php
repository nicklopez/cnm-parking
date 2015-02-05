<?php
//first, require the SimpleTest framework <http://simpletest.org/>
//this path is *NOT* universal, but deployed on the bootcamp-coders server
require_once("/usr/lib/php5/simpletest/autorun.php");

// next, require the class from the project under scrutiny
require_once("admin.php");

/**
 * Unit test for the Admin test
 *
 * This is a SimpleTest test case for the CRUD methods of the Admin class.
 *
 * @see Admin
 * @author David Fevig <davidfevig@davidfevig.com>
 **/

class AdminTest extends UnitTestCase {
	/**
	 * mysqli object shared amongst all tests
	 **/
	private $mysqli = null;
	/**
	 *instance of the object we are testing
	 */
	private $admin = null;
	// this section contains member variables with constants needed for creating a new admin
	/**
	 * content of the activation type
	 */
	private $activation = "Admin Active";
	/**
	 * content of the admin email
	 */
	private $adminEmail = "admin5@cnm.edu";
	/**
	 * content of the password hash
	 */
	private $passHash = "ab12bc99";
	/**
	 * content of the salt
	 */
	private $salt = "abc123";

	/**
	 * sets up the mySQL connection for this test
	 */
	public function setUp() {
		//first, connect to mysqli
		mysqli_report(MYSQLI_REPORT_STRICT);
		$this->mysqli = new mysqli("localhost", "cnmparking-dba", "smutramrenalgoren", "cnmparking");

		// second, create an instance of the object under scrutiny
		$this->admin = new Admin(null, $this->activation, $this->adminEmail, $this->passHash, $this->salt);
	}

	/**
	 * tears down he connection to mySQL and deletes the test instance object
	 **/
	public function tearDown() {
		// destroy the object if it was created
		if($this->admin !== null) {
			$this->admin->delete($this->mysqli);
			$this->admin = null;
		}

		//disconnect from mySQL
		if($this->mysqli !== null) {
			$this->mysqli->close();
			$this->mysqli = null;
		}
	}
	/**
	 * test inserting a valid admin into mySQL
	 */
	public function testInsertValidAdmin() {
		//zeroth, ensure the admin and mySQL class are sane
		$this->assertNotNull($this->admin);
		$this->assertNotNull($this->mysqli);

		//first, insert the admin into mySQL
		$this->admin->insert($this->mysqli);

		//second, grab an admin from mySQL
		$mysqlAdmin = Admin::getAdminByAdminId($this->mysqli, $this->admin->getAdminId());

		//third, assert the admin created and mySQL's admin are the same object
		$this->assertIdentical($this->admin->getAdminId(), $mysqlAdmin->getadminId());
		$this->assertIdentical($this->admin->getActivation(), $mysqlAdmin->getActivation());
		$this->assertIdentical($this->admin->getAdminEmail(), $mysqlAdmin->getAdminEmail());
		$this->assertIdentical($this->admin->getPassHash(), $mysqlAdmin->getPassHash());
		$this->assertIdentical($this->admin->getSalt(), $mysqlAdmin->getSalt());
	}
	/**
	 * test inserting an invalid admin into mySQL
	 */
	public function testInsertInvalidAdmin() {
		//zeroth, ensure the admin and mySQL class are the same
		$this->assertNotNull($this->admin);
		$this->assertNotNull($this->mysqli);

		//first, set the admin id to an invented value that should never insert in the first place
		$this->admin->setAdminId(500);

		//second, try to insert the admin and ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$this->admin->insert($this->mysqli);

		//third, set the admin to null to prevent the tearDown() from deleting an admin that never existed
		$this->admin = null;
	}
	/**
	 * test deleting an admin from mySQL
	 */
	public function testDeleteValidAdmin() {
		//zeroth, ensure the admin and mySQL class are the sane
		$this->assertNotNull($this->admin);
		$this->assertNotNull($this->mysqli);

		//first, assert the admin is inserted into mySQL by grabbing it from mySQL and asserting the primary key
		$this->admin->insert($this->mysqli);
		$mysqlAdmin = Admin::getAdminByAdminId($this->mysqli, $this->admin->getAdminId());
		$this->assertIdentical($this->admin->getAdminId(), $mysqlAdmin->getAdminId());

		//second, delete the admin from mySQL and re-grab it from mySQL and assert id does not exist
		$this->admin->delete($this->mysqli);
		$mysqlAdmin= Admin::getAdminByAdminId($this->mysqli, $this->admin->getAdminId());
		$this->assertNull($mysqlAdmin);

		//third, set the admin to null to prevent tearDown() from deleting a admin that has already been deleted
		$this->admin = null;
	}
	/**
	 * test deleting an admin from mySQL that does not exist
	 */
	public function testDeleteInvalidAdmin() {
		//zeroth, ensure the admin and mySQL class are the sane
		$this->assertNotNull($this->admin);
		$this->assertNotNull($this->mysqli);

		//first, try to delete the admin before inserting it and ensure the exception is thrown
		$this->expectException('mysqli_sql_exception');
		$this->admin->delete($this->mysqli);

		//second, set the admin to null to prevent tearDown() from deleting an admin that has already been deleted
		$this->admin = null;
	}
	/**
	 * test updating an admin from mySQL
	 */
	public function testUpdateValidAdmin() {
		//zeroth, ensure the admin and mySQL class are the sane
		$this->assertNotNull($this->admin);
		$this->assertNotNull($this->mysqli);

		//first, assert the admin is inserted into mySQL by grabbing it from mySQL and asserting the primary key
		$this->admin->insert($this->mysqli);
		$mysqlAdmin = Admin::getAdminByAdminId($this->mysqli, $this->admin->getAdminId());
		$this->assertIdentical($this->admin->getAdminId(), $mysqlAdmin->getAdminId());

		//third, re-grab the admin from mySQL
		$mysqlAdmin = Admin::getAdminByAdminId($this->mysqli, $this->admin->getAdminId());
		$this->assertNotNull($mysqlAdmin);

		//forth, assert the admin is updated and mySQL's admin are the same object
		$this->assertIdentical($this->admin->getAdminId(), $mysqlAdmin->getAdminId());
		$this->assertIdentical($this->admin->getActivation(), $mysqlAdmin->getActivation());
		$this->assertIdentical($this->admin->getAdminEmail(), $mysqlAdmin->getAdminEmail());
		$this->assertIdentical($this->admin->getPassHash(), $mysqlAdmin->getPassHash());
		$this->assertIdentical($this->admin->getSalt(), $mysqlAdmin->getSalt());
	}

	/**
	 * test updating an admin from mySQL that does not exist
	 */
	public function testUpdateInvalidAdmin() {
		//zeroth, ensure the admin and mySQL class are the sane
		$this->assertNotNull($this->admin);
		$this->assertNotNull($this->mysqli);

		//first, try to update the admin before inserting it and ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$this->admin->update($this->mysqli);

		//second, set the admin to null to prevent tearDown() from deleting an admin that has already been deleted
		$this->admin = null;
	}
}

?>