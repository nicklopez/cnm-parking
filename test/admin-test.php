<?php
// require the encrypted config functions
require_once("/home/cnmparki/etc/mysql/encrypted-config.php");

// first, require the SimpleTest framework <http://simpletest.org/>
// this path is *NOT* universal, but deployed on the bootcamp-coders server
require_once("/usr/lib/php5/simpletest/autorun.php");

// next, require the class from the project under scrutiny
require_once("../php/classes/admin.php");

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
	 * first instance of the object we are testing
	 **/
	private $admin = null;
	// this section contains member variables with constants needed for creating a new admin
	/**
	 * creating the admin with required attributes
	 **/
	/**
	 * content of the admin activation type
	 **/
	private $activation = "12345678123456781234567812345678";
	/**
	 * admin email address
	 */
	private $adminEmail = "admin@cnm.edu";
	/**
	 * content of the password hash
	 */
	private $passHash = "12345678123456781234567812345678123456781234567812345678123456781234567812345678123456781234567812345678123456781234567812345678";
	/**
	 * content of the salt
	 */
	private $salt = "1234567812345678123456781234567812345678123456781234567812345678";

	/**
	 * sets up the mySQL connection for this test
	 */
	public function setUp() {
		// connect to mysqli
		mysqli_report(MYSQLI_REPORT_STRICT);
		$configArray = readConfig("/home/cnmparki/etc/mysql/cnmparking.ini");
		$this->mysqli = new mysqli($configArray["hostname"], $configArray["username"], $configArray["password"], $configArray["database"]);

		// second, create an instance of the object under scrutiny
		$this->admin = new Admin(null, $this->activation, $this->adminEmail, $this->passHash, $this->salt);
	}

	/**
	 * tears down the connection to mySQL and deletes the test instance object
	 **/
	public function tearDown() {
		// destroy the object if it was created
		if($this->admin !== null && $this->admin->getAdminId() !== null) {
			$this->admin->delete($this->mysqli);
			$this->admin = null;
		}

		// disconnect from mySQL
		if($this->mysqli !== null) {
			$this->mysqli->close();
			$this->mysqli = null;
		}
	}
	/**
	 * test inserting a valid admin into mySQL
	 */
	public function testInsertValidAdmin() {
		// zeroth, ensure the admin and mySQL class are sane
		$this->assertNotNull($this->admin);
		$this->assertNotNull($this->mysqli);

		// first, insert the admin into mySQL
		$this->admin->insert($this->mysqli);

		// second, grab an admin from mySQL
		$mysqlAdmin = Admin::getAdminByAdminId($this->mysqli, $this->admin->getAdminId());

		// third, assert the admin created and mySQL's admin are the same object
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
		// zeroth, ensure the admin and mySQL class are the same
		$this->assertNotNull($this->admin);
		$this->assertNotNull($this->mysqli);

		// first, set the admin id to an invented value that should never insert in the first place
		$this->admin->setAdminId(500);

		// second, try to insert the admin and ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$this->admin->insert($this->mysqli);

		// third, set the admin to null to prevent the tearDown() from deleting an admin that never existed
		$this->admin = null;
	}
	/**
	 * test deleting an admin from mySQL
	 */
	public function testDeleteValidAdmin() {
		//zeroth, ensure the admin and mySQL class are the sane
		$this->assertNotNull($this->admin);
		$this->assertNotNull($this->mysqli);

		// first, assert the admin is inserted into mySQL by grabbing it from mySQL and asserting the primary key
		$this->admin->insert($this->mysqli);
		$mysqlAdmin = Admin::getAdminByAdminId($this->mysqli, $this->admin->getAdminId());
		$this->assertIdentical($this->admin->getAdminId(), $mysqlAdmin->getAdminId());

		// second, delete the admin from mySQL and re-grab it from mySQL and assert id does not exist
		$this->admin->delete($this->mysqli);
		$mysqlAdmin= Admin::getAdminByAdminId($this->mysqli, $this->admin->getAdminId());
		$this->assertNull($mysqlAdmin);

		// third, set the admin to null to prevent tearDown() from deleting a admin that has already been deleted
		$this->admin = null;
	}
	/**
	 * test deleting an admin from mySQL that does not exist
	 */
	public function testDeleteInvalidAdmin() {
		// zeroth, ensure the admin and mySQL class are the sane
		$this->assertNotNull($this->admin);
		$this->assertNotNull($this->mysqli);

		// first, try to delete the admin before inserting it and ensure the exception is thrown
		$this->expectException('mysqli_sql_exception');
		$this->admin->delete($this->mysqli);

		// second, set the admin to null to prevent tearDown() from deleting an admin that has already been deleted
		$this->admin = null;
	}
	/**
	 * test updating an admin from mySQL
	 */
	public function testUpdateValidAdmin() {
		// zeroth, ensure the admin and mySQL class are the sane
		$this->assertNotNull($this->admin);
		$this->assertNotNull($this->mysqli);

		//first, assert the admin is inserted into mySQL by grabbing it from mySQL and asserting the primary key
		$this->admin->insert($this->mysqli);
		$mysqlAdmin = Admin::getAdminByAdminId($this->mysqli, $this->admin->getAdminId());
		$this->assertIdentical($this->admin->getAdminId(), $mysqlAdmin->getAdminId());

		// second, change the Admin, update it mySQL
		$newContent = "admin5@cnm.edu";
		$this->admin->setAdminEmail($newContent);
		$this->admin->update($this->mysqli);

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
	/**
	 * test selecting an Admin by AdminId from mySQL
	 **/
	public function testSelectValidAdminProfile() {
		// zeroth, ensure the Admin and mySQL class are sane
		$this->assertNotNull($this->admin);
		$this->assertNotNull($this->mysqli);

		// first, insert the Admin into mySQL
		$this->admin->insert($this->mysqli);
		$mysqlAdmin = Admin::getAdminByAdminId($this->mysqli, $this->admin->getAdminId());

		//second, assert the query returned a result
		$this->assertNotNull($mysqlAdmin);
	}
	/**
	 * test selecting an Admin that does not exist in mySQL
	 **/
	public function testSelectInvalidAdmin() {
		// zeroth, ensure the Admin and mySQL class are the same
		$this->assertNotNull($this->admin);
		$this->assertNotNull($this->mysqli);

		// first, try to select an admin by adminId
		$adminId = 0;

		// exception is thrown
		$this->expectException("mysqli_sql_exception");
		$mysqliAdmin = Admin::getAdminByAdminId($this->mysqli, $adminId);

		// second, assert the query returned no results
		$this->assertNull($mysqliAdmin);
	}

	/**
	 * test selecting an Admin by admin email
	 **/
	public function testSelectValidEmail() {
		// zeroth, ensure the Admin and mySQL class are sane
		$this->assertNotNull($this->admin);
		$this->assertNotNull($this->mysqli);

		//first, insert the two test admin profiles
		$this->admin->insert($this->mysqli);
		$mysqlAdmin = Admin::getAdminByAdminEmail($this->mysqli, $this->adminEmail);
		$this->assertIdentical($this->admin->getAdminEmail(), $mysqlAdmin->getAdminEmail());

	}
	/**
	 * test selecting an Admin that does not exist in mySQL
	**/
	public function testSelectInvalidEmail() {
		//zeroth, ensure the Admin and mySQL class are sane
		$this->assertNotNull($this->admin);
		$this->assertNotNull($this->mysqli);

		//first, insert the two test admin profiles
		$this->admin->insert($this->mysqli);
		$mysqlAdmin = Admin::getAdminByAdminEmail($this->mysqli,$this->adminEmail);
		$this->assertIdentical($this->admin->getAdminEmail(), $mysqlAdmin->getAdminEmail());

	}

}

?>