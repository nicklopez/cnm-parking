<?php
// require the encrypted config functions
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

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
	private $admin1 = null;
	// this section contains member variables with constants needed for creating a new admin
	/**
	 * second instance of the object we are testing
	 **/
	private $admin2 = null;
	/**
	 * creating the admin with required attributes
	 **/
	/**
	 * content of the admin activation type
	 **/
	private $activation = "Admin Active";
	/**
	 * admin1 email address
	 */
	private $adminEmail1 = "admin1@cnm.edu";
	/**
	 * admin email address
	 **/
	private $adminEmail2 = "admin2@cnm.edu";
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
		// now retrieve the configuration parameters
		try {
			$configFile = "/etc/apache2/capstone-mysql/cnmparking.ini";
			$configArray = readConfig($configFile);
		} catch (InvalidArgumentException $invalidArgument) {
			// re-throw the exception to the caller
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		}

		// connect to mysqli
		mysqli_report(MYSQLI_REPORT_STRICT);
		$this->mysqli = new mysqli($configArray["hostname"], $configArray["username"], $configArray["password"], $configArray["database"]);

		// second, create an instance of the object under scrutiny
		$this->admin1 = new Admin(null, $this->activation, $this->adminEmail1, $this->passHash, $this->salt);
		// third, create a second instance of the object under scrutiny
		$this->admin2 = new Admin(null, $this->activation, $this->adminEmail2, $this->passHash, $this->salt);
	}

	/**
	 * tears down the connection to mySQL and deletes the test instance object
	 **/
	public function tearDown() {
		// destroy the object if it was created
		if($this->admin1 !== null && $this->admin1->getAdminId() !== null) {
			$this->admin1->delete($this->mysqli);
			$this->admin1 = null;
		}
		if($this->admin2 !== null && $this->admin2->getAdminId() !== null)
			$this->admin2->delete($this->mysqli);
		$this->admin2 = null;

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
		$this->assertNotNull($this->admin1);
		$this->assertNotNull($this->mysqli);

		// first, insert the admin into mySQL
		$this->admin1->insert($this->mysqli);

		// second, grab an admin from mySQL
		$mysqlAdmin = Admin::getAdminByAdminId($this->mysqli, $this->admin1->getAdminId());

		// third, assert the admin created and mySQL's admin are the same object
		$this->assertIdentical($this->admin1->getAdminId(), $mysqlAdmin->getadminId());
		$this->assertIdentical($this->admin1->getActivation(), $mysqlAdmin->getActivation());
		$this->assertIdentical($this->admin1->getAdminEmail(), $mysqlAdmin->getAdminEmail());
		$this->assertIdentical($this->admin1->getPassHash(), $mysqlAdmin->getPassHash());
		$this->assertIdentical($this->admin1->getSalt(), $mysqlAdmin->getSalt());
	}
	/**
	 * test inserting an invalid admin into mySQL
	 */
	public function testInsertInvalidAdmin() {
		// zeroth, ensure the admin and mySQL class are the same
		$this->assertNotNull($this->admin1);
		$this->assertNotNull($this->mysqli);

		// first, set the admin id to an invented value that should never insert in the first place
		$this->admin1->setAdminId(500);

		// second, try to insert the admin and ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$this->admin1->insert($this->mysqli);

		// third, set the admin to null to prevent the tearDown() from deleting an admin that never existed
		$this->admin1 = null;
	}
	/**
	 * test deleting an admin from mySQL
	 */
	public function testDeleteValidAdmin() {
		//zeroth, ensure the admin and mySQL class are the sane
		$this->assertNotNull($this->admin1);
		$this->assertNotNull($this->mysqli);

		// first, assert the admin is inserted into mySQL by grabbing it from mySQL and asserting the primary key
		$this->admin1->insert($this->mysqli);
		$mysqlAdmin = Admin::getAdminByAdminId($this->mysqli, $this->admin1->getAdminId());
		$this->assertIdentical($this->admin1->getAdminId(), $mysqlAdmin->getAdminId());

		// second, delete the admin from mySQL and re-grab it from mySQL and assert id does not exist
		$this->admin1->delete($this->mysqli);
		$mysqlAdmin= Admin::getAdminByAdminId($this->mysqli, $this->admin1->getAdminId());
		$this->assertNull($mysqlAdmin);

		// third, set the admin to null to prevent tearDown() from deleting a admin that has already been deleted
		$this->admin1 = null;
	}
	/**
	 * test deleting an admin from mySQL that does not exist
	 */
	public function testDeleteInvalidAdmin() {
		// zeroth, ensure the admin and mySQL class are the sane
		$this->assertNotNull($this->admin1);
		$this->assertNotNull($this->mysqli);

		// first, try to delete the admin before inserting it and ensure the exception is thrown
		$this->expectException('mysqli_sql_exception');
		$this->admin1->delete($this->mysqli);

		// second, set the admin to null to prevent tearDown() from deleting an admin that has already been deleted
		$this->admin1 = null;
	}
	/**
	 * test updating an admin from mySQL
	 */
	public function testUpdateValidAdmin() {
		// zeroth, ensure the admin and mySQL class are the sane
		$this->assertNotNull($this->admin1);
		$this->assertNotNull($this->mysqli);

		//first, assert the admin is inserted into mySQL by grabbing it from mySQL and asserting the primary key
		$this->admin1->insert($this->mysqli);
		$mysqlAdmin = Admin::getAdminByAdminId($this->mysqli, $this->admin1->getAdminId());
		$this->assertIdentical($this->admin1->getAdminId(), $mysqlAdmin->getAdminId());

		// second, change the Admin, update it mySQL
		$newContent = "admin5@cnm.edu";
		$this->admin1->setAdminEmail($newContent);
		$this->admin1->update($this->mysqli);

		//third, re-grab the admin from mySQL
		$mysqlAdmin = Admin::getAdminByAdminId($this->mysqli, $this->admin1->getAdminId());
		$this->assertNotNull($mysqlAdmin);

		//forth, assert the admin is updated and mySQL's admin are the same object
		$this->assertIdentical($this->admin1->getAdminId(), $mysqlAdmin->getAdminId());
		$this->assertIdentical($this->admin1->getActivation(), $mysqlAdmin->getActivation());
		$this->assertIdentical($this->admin1->getAdminEmail(), $mysqlAdmin->getAdminEmail());
		$this->assertIdentical($this->admin1->getPassHash(), $mysqlAdmin->getPassHash());
		$this->assertIdentical($this->admin1->getSalt(), $mysqlAdmin->getSalt());
	}

	/**
	 * test updating an admin from mySQL that does not exist
	 */
	public function testUpdateInvalidAdmin() {
		//zeroth, ensure the admin and mySQL class are the sane
		$this->assertNotNull($this->admin1);
		$this->assertNotNull($this->mysqli);

		//first, try to update the admin before inserting it and ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$this->admin1->update($this->mysqli);

		//second, set the admin to null to prevent tearDown() from deleting an admin that has already been deleted
		$this->admin1 = null;
	}
	/**
	 * test selecting an Admin by AdminId from mySQL
	 **/
	public function testSelectValidAdminProfile() {
		// zeroth, ensure the Admin and mySQL class are sane
		$this->assertNotNull($this->admin1);
		$this->assertNotNull($this->mysqli);

		// first, insert the Admin into mySQL
		$this->admin1->insert($this->mysqli);
		$mysqlAdmin = Admin::getAdminByAdminId($this->mysqli, $this->admin1->getAdminId());

		//second, assert the query returned a result
		$this->assertNotNull($mysqlAdmin);
	}
	/**
	 * test selecting an Admin that does not exist in mySQL
	 **/
	public function testSelectInvalidAdmin() {
		// zeroth, ensure the Admin and mySQL class are the same
		$this->assertNotNull($this->admin1);
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
		$this->assertNotNull($this->admin1);
		$this->assertNotNull($this->admin2);
		$this->assertNotNull($this->mysqli);

		//first, insert the two test admin profiles
		$this->admin1->insert($this->mysqli);
		$this->admin2->insert($this->mysqli);

		// second, grab an array of Admins from mySQL and assert we have an array
		$email = "admin";
		$mysqlAdmin = Admin::getAdminByAdminEmail($this->mysqli, $email);
		$this->assertIsA($mysqlAdmin, "array");
		$this->assertIdentical(count($mysqlAdmin), 2);

		// third, verify each Admin by asserting by asserting the primary key and the select criteria
		foreach($mysqlAdmin as $admin) {
			$this->assertTrue($admin->getAdminId() > 0);
			$this->assertTrue(strpos($admin->getAdminEmail(), $email) >= 0);
		}
	}
	/**
	 * test selecting an Admin that does not exist in mySQL
	**/
	public function testSelectInvalidEmail() {
		//zeroth, ensure the Admin and mySQL class are sane
		$this->assertNotNull($this->admin1);
		$this->assertNotNull($this->admin2);
		$this->assertNotNull($this->mysqli);

		//first, insert the two test admin profiles
		$this->admin1->insert($this->mysqli);
		$this->admin2->insert($this->mysqli);

		//second, try to grab an array of admins from mySQL and assert null
		$email = "NA";
		$mysqlAdmin = Admin::getAdminByAdminEmail($this->mysqli, $email);
		$this->assertNull($mysqlAdmin);
	}

}

?>