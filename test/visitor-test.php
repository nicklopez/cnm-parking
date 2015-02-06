<?php
// first, require the SimpleTest framework <http://simpletest.org/>
// this path is *not* universal, but deployed on the bootcamp-coders server
require_once("/usr/lib/php5/simpletest/autorun.php");

//next, require the encrypted configuration functions
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

// next, require the class from the project under scrutiny
require_once("../php/classes/visitor.php");

/**
 * Unit test for the Visitor class
 *
 * This is a SimpleTest test case for the CRUD methods of the Visitor class.
 *
 * @see Visitor
 * @author Nick Lopez <nick@nicklopezcodes.com>
 */
class VisitorTest extends UnitTestCase {
	/**
	 * mysqli object shared amongst all tests
	 */
	private $mysqli = null;
	/**
	 * first instance of the object we are testing with
	 */
	private $visitor1 = null;
	/**
	 * second instance of the object we are testing with
	 */
	private $visitor2 = null;

	// this section contains visitor variables with constants needed for creating a new visitor
	/**
	 * email address of CNM parking visitor
	 **/
	private $visitorEmail1 = "nick1@nicklopezcodes.com";
	/**
	 * first name of CNM parking visitor
	 **/
	private $visitorFirstName1 = "Nick1";
	/**
	 * last name of CNM parking visitor
	 **/
	private $visitorLastName1 = "Lopez1";
	/**
	 * 10 digit phone number of CNM parking visitor
	 **/
	private $visitorPhone1 = "5055552234";
	/**
	 * email address of CNM parking visitor
	 **/
	private $visitorEmail2 = "nick2@nicklopezcodes.com";
	/**
	 * first name of CNM parking visitor
	 **/
	private $visitorFirstName2 = "Nick2";
	/**
	 * last name of CNM parking visitor
	 **/
	private $visitorLastName2 = "Lopez2";
	/**
	 * 10 digit phone number of CNM parking visitor
	 **/
	private $visitorPhone2 = "5055552234";


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
		$this->visitor1 = new Visitor(null, $this->visitorEmail1, $this->visitorFirstName1, $this->visitorLastName1, $this->visitorPhone1);
		$this->visitor2 = new Visitor(null, $this->visitorEmail2, $this->visitorFirstName2, $this->visitorLastName2, $this->visitorPhone2);
	}

	/**
	 * tears down the connection to mySQL and deletes the test instance object
	 **/
	public function tearDown() {
// destroy the object if it was created
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
	 * test inserting a valid visitor into mySQL
	 **/
	public function testInsertValidVisitor() {
		// zeroth, ensure the Visitor and mySQL class are sane
		$this->assertNotNull($this->visitor1);
		$this->assertNotNull($this->mysqli);

		// first, insert the Visitor into mySQL
		$this->visitor1->insert($this->mysqli);

		// second, grab a Visitor from mySQL
		$mysqlVisitor = Visitor::getVisitorByVisitorId($this->mysqli, $this->visitor1->getVisitorId());

		// third, assert the Vehicle we have created and mySQL's Vehicle are the same object
		$this->assertIdentical($this->visitor1->getVisitorId(), $mysqlVisitor->getVisitorId());
		$this->assertIdentical($this->visitor1->getVisitorEmail(), $mysqlVisitor->getVisitorEmail());
		$this->assertIdentical($this->visitor1->getVisitorFirstName(), $mysqlVisitor->getVisitorFirstName());
		$this->assertIdentical($this->visitor1->getVisitorLastName(), $mysqlVisitor->getVisitorLastName());
		$this->assertIdentical($this->visitor1->getVisitorPhone(), $mysqlVisitor->getVisitorPhone());
	}


	/**
	 * test inserting an invalid visitor into mySQL
	 **/
	public function testInsertInvalidVisitor() {
		// zeroth, ensure the Visitor and mySQL class are sane
		$this->assertNotNull($this->visitor1);
		$this->assertNotNull($this->mysqli);

		// first, set the visitor id to an invented value that should never insert in the first place
		$this->visitor1->setVisitorId(99);

		// second, try to insert the visitor and ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$this->visitor1->insert($this->mysqli);

		// third, set the Visitor to null to prevent tearDown() from deleting a Visitor that never existed
		$this->visitor1 = null;
	}

	/**
	 * test deleting a Visitor from mySQL
	 **/
	public function testDeleteValidVisitor() {
		// zeroth, ensure the Visitor and mySQL class are sane
		$this->assertNotNull($this->visitor1);
		$this->assertNotNull($this->mysqli);

		// first, assert the Visitor is inserted into mySQL by grabbing it from mySQL and asserting the primary key
		$this->visitor1->insert($this->mysqli);
		$mysqlVisitor = Visitor::getVisitorByVisitorId($this->mysqli, $this->visitor1->getVisitorId());
		$this->assertIdentical($this->visitor1->getVisitorId(), $mysqlVisitor->getVisitorId());

		// second, delete the Visitor from mySQL and re-grab it from mySQL and assert it does not exist
		$this->visitor1->delete($this->mysqli);
		$mysqlVisitor = Visitor::getVisitorByVisitorId($this->mysqli, $this->visitor1->getVisitorId());
		$this->assertNull($mysqlVisitor);

		// third, set the Visitor to null to prevent tearDown() from deleting a Visitor that has already been deleted
		$this->visitor1 = null;
	}

	/**
	 * test deleting a Visitor from mySQL that does not exist
	 **/
	public function testDeleteInvalidVisitor() {
		// zeroth, ensure the Visitor and mySQL class are sane
		$this->assertNotNull($this->visitor1);
		$this->assertNotNull($this->mysqli);

		// first, try to delete the Visitor before inserting it and ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$this->visitor1->delete($this->mysqli);

		// second, set the Visitor to null to prevent tearDown() from deleting a Visitor that has already been deleted
		$this->visitor1 = null;
	}

	/**
	 * test updating a Visitor from mySQL
	 **/
	public function testUpdateValidVisitor() {
		// zeroth, ensure the Visitor and mySQL class are sane
		$this->assertNotNull($this->visitor1);
		$this->assertNotNull($this->mysqli);

		// first, assert the Visitor is inserted into mySQL by grabbing it from mySQL and asserting the primary key
		$this->visitor1->insert($this->mysqli);
		$mysqlVisitor = Visitor::getVisitorByVisitorId($this->mysqli, $this->visitor1->getVisitorId());
		$this->assertIdentical($this->visitor1->getVisitorId(), $mysqlVisitor->getVisitorId());

		// second, change the Visitor, update it mySQL
		$newContent = "Jackson";
		$this->visitor1->setVisitorLastName($newContent);
		$this->visitor1->update($this->mysqli);

		// third, re-grab the Visitor from mySQL
		$mysqlVisitor = Visitor::getVisitorByVisitorId($this->mysqli, $this->visitor1->getVisitorId());
		$this->assertNotNull($mysqlVisitor);

		// fourth, assert the Visitor we have updated and mySQL's Visitor are the same object
		$this->assertIdentical($this->visitor1->getVisitorId(), $mysqlVisitor->getVisitorId());
		$this->assertIdentical($this->visitor1->getVisitorEmail(), $mysqlVisitor->getVisitorEmail());
		$this->assertIdentical($this->visitor1->getVisitorFirstName(), $mysqlVisitor->getVisitorFirstName());
		$this->assertIdentical($this->visitor1->getVisitorLastName(), $mysqlVisitor->getVisitorLastName());
		$this->assertIdentical($this->visitor1->getVisitorPhone(), $mysqlVisitor->getVisitorPhone());
	}

	/**
	 * test updating a Visitor from mySQL that does not exist
	 **/
	public function testUpdateInvalidVisitor() {
		// zeroth, ensure the Visitor and mySQL class are sane
		$this->assertNotNull($this->visitor1);
		$this->assertNotNull($this->mysqli);

		// first, try to update the Visitor before inserting it and ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$this->visitor1->update($this->mysqli);

		// second, set the Visitor to null to prevent tearDown() from deleting a Visitor that has already been deleted
		$this->visitor1 = null;
	}

	/**
	 * test selecting a Visitor by visitorId from mySQL
	 **/
	public function testSelectValidVisitor() {
		// zeroth, ensure the Visitor and mySQL class are sane
		$this->assertNotNull($this->visitor1);
		$this->assertNotNull($this->mysqli);

		// first, insert the Visitor into mySQL
		$this->visitor1->insert($this->mysqli);
		$mysqlVisitor = Visitor::getVisitorByVisitorId($this->mysqli, $this->visitor1->getVisitorId());

		// second, assert the query returned a result
		$this->assertNotNull($mysqlVisitor);
	}

	/**
	 * test selecting a Visitor that does not exist in mySQL
	 **/
	public function testSelectInvalidVisitor() {
		// zeroth, ensure the Visitor and mySQL class are sane
		$this->assertNotNull($this->visitor1);
		$this->assertNotNull($this->mysqli);

		// first, try to selecting a visitor by visitorId
		$visitorId = 0;

		// ensure the exception is thrown
		$this->expectException("mysqli_sql_exception");
		$mysqlVisitor = Visitor::getVisitorByVisitorId($this->mysqli, $visitorId);

		// second, assert the query returned no results
		$this->assertNull($mysqlVisitor);
	}

	/**
	 * test selecting a Visitor by first name from mySQL
	 **/
	public function testSelectValidFirstName() {
		// zeroth, ensure the Visitor and mySQL class are sane
		$this->assertNotNull($this->visitor1);
		$this->assertNotNull($this->visitor2);
		$this->assertNotNull($this->mysqli);

		// first, insert the two test visitors
		$this->visitor1->insert($this->mysqli);
		$this->visitor2->insert($this->mysqli);

		// second, grab an array of Visitors from mySQL and assert we got an array
		$firstName = "Nick";
		$mysqlVisitor = Visitor::getVisitorByVisitorFirstName($this->mysqli, $firstName);
		$this->assertIsA($mysqlVisitor, "array");
		$this->assertIdentical(count($mysqlVisitor), 2);

		// third, verify each Visitor by asserting the primary key and the select criteria
		foreach($mysqlVisitor as $visitor) {
			$this->assertTrue($visitor->getVisitorId() > 0);
			$this->assertTrue(strpos($visitor->getVisitorFirstName(), $firstName) >= 0);
		}
	}

	/**
	 * test selecting a Visitor that does not exist in mySQL
	 **/
	public function testSelectInvalidFirstName() {
// zeroth, ensure the Visitor and mySQL class are sane
		$this->assertNotNull($this->visitor1);
		$this->assertNotNull($this->visitor2);
		$this->assertNotNull($this->mysqli);

		// first, insert the two test tweets
		$this->visitor1->insert($this->mysqli);
		$this->visitor2->insert($this->mysqli);

		// second, try to grab an array of visitors from mySQL and assert null
		$firstName = "NA";
		$mysqlLocation = Visitor::getVisitorByVisitorFirstName($this->mysqli, $firstName);
		$this->assertNull($mysqlLocation);
	}


	/**
	 * test selecting a Visitor by last name from mySQL
	 **/
	public function testSelectValidLastName() {
		// zeroth, ensure the Visitor and mySQL class are sane
		$this->assertNotNull($this->visitor1);
		$this->assertNotNull($this->visitor2);
		$this->assertNotNull($this->mysqli);

		// first, insert the two test locations
		$this->visitor1->insert($this->mysqli);
		$this->visitor2->insert($this->mysqli);

		// second, grab an array of Visitors from mySQL and assert we got an array
		$lastName = "Lopez";
		$mysqlVisitor = Visitor::getVisitorByVisitorLastName($this->mysqli, $lastName);
		$this->assertIsA($mysqlVisitor, "array");
		$this->assertIdentical(count($mysqlVisitor), 2);

		// third, verify each visitor by asserting the primary key and the select criteria
		foreach($mysqlVisitor as $visitor) {
			$this->assertTrue($visitor->getVisitorId() > 0);
			$this->assertTrue(strpos($visitor->getVisitorFirstName(), $lastName) >= 0);
		}
	}

	/**
	 * test selecting a Visitor that does not exist in mySQL
	 **/
	public function testSelectInvalidLastName() {
		// zeroth, ensure the Location and mySQL class are sane
		$this->assertNotNull($this->visitor1);
		$this->assertNotNull($this->visitor2);
		$this->assertNotNull($this->mysqli);

		// first, insert the two test tweets
		$this->visitor1->insert($this->mysqli);
		$this->visitor2->insert($this->mysqli);

		// second, try to grab an array of Locations from mySQL and assert null
		$lastName = "NA";
		$mysqlLocation = Visitor::getVisitorByVisitorLastName($this->mysqli, $lastName);
		$this->assertNull($mysqlLocation);
	}
}
?>