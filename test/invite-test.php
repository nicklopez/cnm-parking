<?php
// first, require the SimpleTest framework <http://simpletest.org/>
// this path is *not* universal, but deployed on the bootcamp-coders server
require_once("/usr/lib/php5/simpletest/autorun.php");

//next, require the encrypted configuration functions
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

// next, require the class from the project under scrutiny
require_once("../php/classes/invite.php");
require_once("../php/classes/visitor.php");
require_once("../php/classes/admin.php");
require_once("../php/classes/adminProfile.php");

/**
 * Unit test for the Invite class
 *
 * This is a SimpleTest test case for the CRUD methods of the Invite class.
 *
 * @see Invite
 * @author Nick Lopez <nick@nicklopezcodes.com>
 */
class InviteTest extends UnitTestCase {
	/**
	 * mysqli object shared amongst all tests
	 */
	private $mysqli = null;
	/**
	 * first instance of the object we are testing with
	 */
	private $invite1 = null;
	private $admin1 = null;
	private $adminProfileId1 = null;
	private $visitorId1 = null;
	/**
	 * second instance of the object we are testing with
	 */
	private $invite2 = null;

	// this section contains invite variables with constants needed for creating a new invite
	/**
	 * datetime the invite was approved or declined
	 **/
	private $actionDateTime1 = "2015-02-15 00:00:00";
	/**
	 * token generated for end requester (visitor)
	 **/
	private $activation1 = "1234";
	/**
	 * boolean; invite approved or declined
	 **/
	private $approved1 = 1;
	/**
	 * datetime the invite was generated
	 **/
	private $createDateTime1 = "2015-02-15 00:00:00";
	/**
	 * datetime the invite was approved or declined
	 **/
	private $actionDateTime2 = "2015-02-15 00:00:00";
	/**
	 * token generated for end requester (visitor)
	 **/
	private $activation2 = "5678";
	/**
	 * boolean; invite approved or declined
	 **/
	private $approved2 = 0;
	/**
	 * datetime the invite was generated
	 **/
	private $createDateTime2 = "2015-02-15 00:00:00";

	/**
	 * setup the mySQL connection for this test
	 */
	public function setUp() {
		// now retrieve the configuration parameters
		try {
			$configFile = "/etc/apache2/capstone-mysql/cnmparking.ini";
			$configArray = readConfig($configFile);
		} catch(InvalidArgumentException $invalidArgument) {
			// re-throw the exception to the caller
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		}

		// first, connect to mysqli
		mysqli_report(MYSQLI_REPORT_STRICT);
		$this->mysqli = new mysqli($configArray["hostname"], $configArray["username"], $configArray["password"], $configArray["database"]);

		// second, create an instance of the object under scrutiny
		$this->visitorId1 = new Visitor(null, "myfirstname@example.com", "John", "Doe", "9990001122");
		$this->visitorId1->insert($this->mysqli);
		$this->admin1 = new Admin(null, "12345678123456781234567812345678", "admin@cnm.edu", "12345678123456781234567812345678123456781234567812345678123456781234567812345678123456781234567812345678123456781234567812345678", "1234567812345678123456781234567812345678123456781234567812345678");
		$this->admin1->insert($this->mysqli);
		$this->adminProfileId1 = new AdminProfile(null, $this->admin1->getAdminId(), "CNM Admin", "Jones");
		$this->adminProfileId1->insert($this->mysqli);
		$this->invite1 = new Invite(null, $this->actionDateTime1, $this->activation1, $this->adminProfileId1->getAdminProfileId(), $this->approved1, $this->createDateTime1, $this->visitorId1->getVisitorId());
		$this->invite2 = new Invite(null, $this->actionDateTime2, $this->activation2, $this->adminProfileId1->getAdminProfileId(), $this->approved2, $this->createDateTime2, $this->visitorId1->getVisitorId());
	}

	/**
	 * tears down the connection to mySQL and deletes the test instance object
	 **/
	public function tearDown() {
		// destroy the object if it was created
		if($this->invite1 !== null && $this->invite1->getInviteId() !== null) {
			$this->invite1->delete($this->mysqli);
			$this->invite1 = null;
		}

		if($this->invite2 !== null && $this->invite2->getInviteId() !== null) {
			$this->invite2->delete($this->mysqli);
			$this->invite2 = null;
		}

		if($this->adminProfileId1 !== null && $this->adminProfileId1->getAdminProfileId() !== null) {
			$this->adminProfileId1->delete($this->mysqli);
			$this->adminProfileId1 = null;
		}

		if($this->admin1 !== null && $this->admin1->getAdminId() !== null) {
			$this->admin1->delete($this->mysqli);
			$this->admin1 = null;
		}

		if($this->visitorId1 !== null && $this->visitorId1->getVisitorId() !== null) {
			$this->visitorId1->delete($this->mysqli);
			$this->visitorId1 = null;
		}

		// disconnect from mySQL
		if($this->mysqli !== null) {
			$this->mysqli->close();
			$this->mysqli = null;
		}
	}

	/**
	 * test inserting a valid invite into mySQL
	 **/
	public function testInsertValidInvite() {
		// zeroth, ensure the Invite and mySQL class are sane
		$this->assertNotNull($this->invite1);
		$this->assertNotNull($this->mysqli);

		// first, insert the Invite into mySQL
		$this->invite1->insert($this->mysqli);

		// second, grab an Invite from mySQL
		$mysqlInvite = Invite::getInviteByInviteId($this->mysqli, $this->invite1->getInviteId());

		// third, assert the Invite we have created and mySQL's Invite are the same object
		$this->assertIdentical($this->invite1->getInviteId(), $mysqlInvite->getInviteId());
		$this->assertIdentical($this->invite1->getActionDateTime(), $mysqlInvite->getActionDateTime());
		$this->assertIdentical($this->invite1->getActivation(), $mysqlInvite->getActivation());
		$this->assertIdentical($this->invite1->getAdminProfileId(), $mysqlInvite->getAdminProfileId());
		$this->assertIdentical($this->invite1->getApproved(), $mysqlInvite->getApproved());
		$this->assertIdentical($this->invite1->getCreateDateTime(), $mysqlInvite->getCreateDateTime());
		$this->assertIdentical($this->invite1->getVisitorId(), $mysqlInvite->getVisitorId());
	}


	/**
	 * test inserting an invalid invite into mySQL
	 **/
	public function testInsertInvalidInvite() {
		// zeroth, ensure the Invite and mySQL class are sane
		$this->assertNotNull($this->invite1);
		$this->assertNotNull($this->mysqli);

		// first, set the invite id to an invented value that should never insert in the first place
		$this->invite1->setInviteId(99);

		// second, try to insert the Invite and ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$this->invite1->insert($this->mysqli);

		// third, set the Invite to null to prevent tearDown() from deleting an Invite that never existed
		$this->invite1 = null;
	}

	/**
	 * test deleting an Invite from mySQL
	 **/
	public function testDeleteValidInvite() {
		// zeroth, ensure the Invite and mySQL class are sane
		$this->assertNotNull($this->invite1);
		$this->assertNotNull($this->mysqli);

		// first, assert the Invite is inserted into mySQL by grabbing it from mySQL and asserting the primary key
		$this->invite1->insert($this->mysqli);
		$mysqlInvite = Invite::getInviteByInviteId($this->mysqli, $this->invite1->getInviteId());
		$this->assertIdentical($this->invite1->getInviteId(), $mysqlInvite->getInviteId());

		// second, delete the Invite from mySQL and re-grab it from mySQL and assert it does not exist
		$this->invite1->delete($this->mysqli);
		$mysqlInvite = Invite::getInviteByInviteId($this->mysqli, $this->invite1->getInviteId());
		$this->assertNull($mysqlInvite);

		// third, set the Invite to null to prevent tearDown() from deleting an Invite that has already been deleted
		$this->invite1 = null;
	}

	/**
	 * test deleting an Invite from mySQL that does not exist
	 **/
	public function testDeleteInvalidInvite() {
		// zeroth, ensure the Invite and mySQL class are sane
		$this->assertNotNull($this->invite1);
		$this->assertNotNull($this->mysqli);

		// first, try to delete the Invite before inserting it and ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$this->invite1->delete($this->mysqli);

		// second, set the Invite to null to prevent tearDown() from deleting an Invite that has already been deleted
		$this->invite1 = null;
	}

	/**
	 * test updating an Invite from mySQL
	 **/
	public function testUpdateValidInvite() {
		// zeroth, ensure the Invite and mySQL class are sane
		$this->assertNotNull($this->invite1);
		$this->assertNotNull($this->mysqli);

		// first, assert the Invite is inserted into mySQL by grabbing it from mySQL and asserting the primary key
		$this->invite1->insert($this->mysqli);
		$mysqlInvite = Invite::getInviteByInviteId($this->mysqli, $this->invite1->getInviteId());
		$this->assertIdentical($this->invite1->getInviteId(), $mysqlInvite->getInviteId());

		// second, change the Invite and update it in mySQL
		$newContent = 0;
		$this->invite1->setApproved($newContent);
		$this->invite1->update($this->mysqli);

		// third, re-grab the Invite from mySQL
		$mysqlInvite = Invite::getInviteByInviteId($this->mysqli, $this->invite1->getInviteId());
		$this->assertNotNull($mysqlInvite);

		// fourth, assert the Invite we have updated and mySQL's Invite are the same object

		$this->assertIdentical($this->invite1->getInviteId(), $mysqlInvite->getInviteId());
		$this->assertIdentical($this->invite1->getActionDateTime(), $mysqlInvite->getActionDateTime());
		$this->assertIdentical($this->invite1->getActivation(), $mysqlInvite->getActivation());
		$this->assertIdentical($this->invite1->getAdminProfileId(), $mysqlInvite->getAdminProfileId());
		$this->assertIdentical($this->invite1->getApproved(), $mysqlInvite->getApproved());
		$this->assertIdentical($this->invite1->getCreateDateTime(), $mysqlInvite->getCreateDateTime());
		$this->assertIdentical($this->invite1->getVisitorId(), $mysqlInvite->getVisitorId());
	}

	/**
	 * test updating an Invite from mySQL that does not exist
	 **/
	public function testUpdateInvalidInvite() {
		// zeroth, ensure the Invite and mySQL class are sane
		$this->assertNotNull($this->invite1);
		$this->assertNotNull($this->mysqli);

		// first, try to update the Invite before inserting it and ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$this->invite1->update($this->mysqli);

		// second, set the Invite to null to prevent tearDown() from deleting an Invite that has already been deleted
		$this->invite1 = null;
	}

	/**
	 * test selecting an invite by inviteId from mySQL
	 **/
	public function testSelectValidInviteByInviteId() {
		// zeroth, ensure the invite and mySQL class are sane
		$this->assertNotNull($this->invite1);
		$this->assertNotNull($this->mysqli);

		// first, insert the invite into mySQL
		$this->invite1->insert($this->mysqli);
		$mysqlInvite = Invite::getInviteByInviteId($this->mysqli, $this->invite1->getInviteId());

		// second, assert the query returned a result
		$this->assertNotNull($mysqlInvite);
	}

	/**
	 * test selecting an invite that does not exist in mySQL
	 **/
	public function testSelectInvalidInviteByInviteId() {
		// zeroth, ensure the invite and mySQL class are sane
		$this->assertNotNull($this->invite1);
		$this->assertNotNull($this->mysqli);

		// first, try selecting an invite by inviteId
		$invite = 0;

		// ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$mysqlInvite = Invite::getInviteByInviteId($this->mysqli, $invite);

		// second, assert the query returned no results
		$this->assertNull($mysqlInvite);
	}

	/**
	 * test selecting an invite by activation (token) from mySQL
	 **/
	public function testSelectValidInviteByActivation() {
		// zeroth, ensure the invite and mySQL class are sane
		$this->assertNotNull($this->invite1);
		$this->assertNotNull($this->mysqli);

		// first, insert the invite into mySQL
		$this->invite1->insert($this->mysqli);
		$mysqlInvite = Invite::getInviteByActivation($this->mysqli, $this->invite1->getActivation());

		// second, assert the query returned a result
		$this->assertNotNull($mysqlInvite);
	}

	/**
	 * test selecting an invite with an activation (token) that does not exist in mySQL
	 **/
	public function testSelectInvalidInviteByActivation() {
		// zeroth, ensure the invite and mySQL class are sane
		$this->assertNotNull($this->invite1);
		$this->assertNotNull($this->mysqli);

		// first, insert an invite and try selecting by activation (token)
		$this->invite1->insert($this->mysqli);
		$activation = "NA";
		$mysqlInvite = Invite::getInviteByActivation($this->mysqli, $activation);

		// second, assert the query returned no results
		$this->assertNull($mysqlInvite);
	}
}
?>