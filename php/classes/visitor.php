<?php
/**
 * CNM Parking Visitor Information
 *
 * This is where visitor information is stored
 *
 * @author Nick Lopez <nick@nicklopezcodes.com>
 **/
class Visitor {
	/**
	 * id for CNM parking visitor; this is the primary key
	 **/
	private $visitorId;
	/**
	 * email address of CNM parking visitor
	 **/
	private $visitorEmail;
	/**
	 * first name of CNM parking visitor
	 **/
	private $visitorFirstName;
	/**
	 * last name of CNM parking visitor
	 **/
	private $visitorLastName;
	/**
	 * 10 digit phone number of CNM parking visitor
	 **/
	private $visitorPhone;

	/**
	 * constructor for visitor
	 *
	 * @param mixed $newVisitorId id of the new visitor
	 * @param string $newVisitorEmail string containing visitor's email address
	 * @param string $newVisitorFirstName string containing visitor's first name
	 * @param string $newVisitorLastName string containing visitor's last name
	 * @param string $newVisitorPhone string containing visitor's phone
	 * @throws InvalidArgumentException if data types are not valid
	 * @throws RangeException if data values are out of bounds (e.g., strings too long, negative integers)
	 **/
	public function __construct($newVisitorId, $newVisitorEmail, $newVisitorFirstName, $newVisitorLastName, $newVisitorPhone) {
		try {
			$this->setVisitorId($newVisitorId);
			$this->setVisitorEmail($newVisitorEmail);
			$this->setVisitorFirstName($newVisitorFirstName);
			$this->setVisitorLastName($newVisitorLastName);
			$this->setVisitorPhone($newVisitorPhone);
		} catch(InvalidArgumentException $invalidArgument) {
			// rethrow the exception to the caller
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			// rethrow the exception to the caller
			throw(new RangeException($range->getMessage(), 0, $range));
		}
	}


	/**
	 * accessor method for visitor id
	 *
	 * @return int value of visitor id
	 **/
	public function getVisitorId() {
		return ($this->visitorId);
	}

	/**
	 * mutator method for visitor id
	 *
	 * @param mixed $newVisitorId new value of visitor id
	 * @throws InvalidArgumentException if $newVisitorId is not an integer
	 * @throws RangeException if $newVisitorId is not positive
	 **/
	public function setVisitorId($newVisitorId) {
		// base case: if the visitor id is null, this a new visitor without a mySQL assigned id (yet)
		if($newVisitorId === null) {
			$this->visitorId = null;
			return;
		}

		// verify the visitor id is valid
		$newVisitorId = filter_var($newVisitorId, FILTER_VALIDATE_INT);
		if($newVisitorId === false) {
			throw(new InvalidArgumentException("visitor id is not a valid integer"));
		}

		// verify the visitor id is positive
		if($newVisitorId <= 0) {
			throw(new RangeException("visitor id is not positive"));
		}

		// convert and store the visitor id
		$this->visitorId = intval($newVisitorId);
	}

	/**
	 * accessor method for visitor email
	 *
	 * @return string value of visitor email
	 **/
	public function getVisitorEmail() {
		return ($this->visitorEmail);
	}

	/**
	 * mutator method for visitor email
	 *
	 * @param string $newVisitorEmail new value of visitor email
	 * @throws InvalidArgumentException if $newVisitorEmail is not a string or insecure
	 * @throws RangeException if $newVisitorEmail is > 128 characters
	 **/
	public function setVisitorEmail($newVisitorEmail) {
		// verify the visitor email is secure
		$newVisitorEmail = trim($newVisitorEmail);
		$newVisitorEmail = filter_var($newVisitorEmail, FILTER_SANITIZE_EMAIL);

		if(empty($newVisitorEmail) === true) {
			throw(new InvalidArgumentException("visitor email is empty or insecure"));
		}

		// verify the visitor email will fit in the database
		if(strlen($newVisitorEmail) > 128) {
			throw(new RangeException("visitor email too large"));
		}

		// store the visitor email
		$this->visitorEmail = $newVisitorEmail;
	}

	/**
	 * accessor method for visitor first name
	 *
	 * @return string value of visitor first name
	 **/
	public function getVisitorFirstName() {
		return ($this->visitorFirstName);
	}

	/**
	 * mutator method for visitor first name
	 *
	 * @param string $newVisitorFirstName new value of visitor first name
	 * @throws InvalidArgumentException if $newVisitorFirstName is not a string or insecure
	 * @throws RangeException if $newVisitorFirstName is > 128 characters
	 **/
	public function setVisitorFirstName($newVisitorFirstName) {
		// verify the visitor first name is secure
		$newVisitorFirstName = trim($newVisitorFirstName);
		$newVisitorFirstName = filter_var($newVisitorFirstName, FILTER_SANITIZE_STRING);
		if(empty($newVisitorFirstName) === true) {
			throw(new InvalidArgumentException("visitor first name is empty or insecure"));
		}

		// verify the visitor first name will fit in the database
		if(strlen($newVisitorFirstName) > 128) {
			throw(new RangeException("visitor first name too large"));
		}

		// store the visitor first name
		$this->visitorFirstName = $newVisitorFirstName;
	}

	/**
	 * accessor method for visitor last name
	 *
	 * @return string value of visitor last name
	 **/
	public function getVisitorLastName() {
		return ($this->visitorLastName);
	}

	/**
	 * mutator method for visitor last name
	 *
	 * @param string $newVisitorLastName new value of visitor last name
	 * @throws InvalidArgumentException if $newVisitorLastName is not a string or insecure
	 * @throws RangeException if $newVisitorLastName is > 128 characters
	 **/
	public function setVisitorLastName($newVisitorLastName) {
		// verify the visitor last name is secure
		$newVisitorLastName = trim($newVisitorLastName);
		$newVisitorLastName = filter_var($newVisitorLastName, FILTER_SANITIZE_STRING);
		if(empty($newVisitorLastName) === true) {
			throw(new InvalidArgumentException("visitor last name is empty or insecure"));
		}

		// verify the visitor last name will fit in the database
		if(strlen($newVisitorLastName) > 128) {
			throw(new RangeException("visitor last name too large"));
		}

		// store the visitor last name
		$this->visitorLastName = $newVisitorLastName;
	}

	/**
	 * accessor method for visitor phone number
	 *
	 * @return string value of visitor phone number
	 **/
	public function getVisitorPhone() {
		return ($this->visitorPhone);
	}

	/**
	 * mutator method for visitor phone number
	 *
	 * @param string $newVisitorPhone new value of visitor phone number
	 * @throws InvalidArgumentException if $newVisitorPhone is not a string or insecure
	 * @throws RangeException if $newVisitorPhone is > 24 characters
	 **/
	public function setVisitorPhone($newVisitorPhone) {
		// verify the visitor phone number is secure
		$newVisitorPhone = trim($newVisitorPhone);
		$newVisitorPhone = filter_var($newVisitorPhone, FILTER_SANITIZE_STRING);
		if(empty($newVisitorPhone) === true) {
			throw(new InvalidArgumentException("visitor phone number is empty or insecure"));
		}

		// verify the visitor phone number will fit in the database
		if(strlen($newVisitorPhone) > 24) {
			throw(new RangeException("visitor phone number too large"));
		}

		// store the visitor phone number
		$this->visitorPhone = $newVisitorPhone;
	}

	/**
	 * inserts visitor into mySQL
	 *
	 * @param PDO $pdo pointer to mySQL connection, by reference
	 * @throws PDOException when mySQL related errors occur
	 **/
	public function insert(PDO &$pdo) {
		// handle degenerate cases
		if(gettype($pdo) !== "object" || get_class($pdo) !== "PDO") {
			throw(new PDOException("input is not a PDO object"));
		}

		// enforce the visitorId is null (i.e., don't insert a visitor that already exists)
		if($this->visitorId !== null) {
			throw(new PDOException("not a new visitor"));
		}

		// create query template
		$query = "INSERT INTO visitor(visitorEmail, visitorFirstName, visitorLastName, visitorPhone) VALUES(:visitorEmail, :visitorFirstName, :visitorLastName, :visitorPhone)";
		$statement = $pdo->prepare($query);
		if($statement === false) {
			throw(new PDOException("unable to prepare statement"));
		}

		// bind the visitor variables to the place holders in the template
		$parameters = array("visitorEmail" => $this->visitorEmail, "visitorFirstName" => $this->visitorFirstName, "visitorLastName" => $this->visitorLastName, "visitorPhone" => $this->visitorPhone);

		// execute the statement
		if($statement->execute($parameters) === false) {
			throw(new PDOException("unable to execute mySQL statement: " . $statement->error));
		}

		// update the null visitorId with what mySQL just gave us
		$this->visitorId = $pdo->lastInsertId();

		// clean up the statement
//		$statement->close();
	}

	/**
	 * deletes visitor from mySQL
	 *
	 * @param PDO $pdo pointer to mySQL connection, by reference
	 * @throws PDOException when mySQL related errors occur
	 **/
	public function delete(PDO &$pdo) {
		// handle degenerate cases
		if(gettype($pdo) !== "object" || get_class($pdo) !== "PDO") {
			throw(new PDOException("input is not a PDO object"));
		}

		// enforce the visitorId is not null (i.e., don't delete a visitor that hasn't been inserted)
		if($this->visitorId === null) {
			throw(new PDOException("unable to delete a visitor that does not exist"));
		}

		// create query template
		$query = "DELETE FROM visitor WHERE visitorId = :visitorId";
		$statement = $pdo->prepare($query);
		if($statement === false) {
			throw(new PDOException("unable to prepare statement"));
		}

		// bind the visitor variables to the place holder in the template
		$parameters = array("visitorId" => $this->visitorId);

		// execute the statement
		$statement->execute($parameters);

		// clean up the statement
//		$statement->close();
	}

	/**
	 * updates visitor in mySQL
	 *
	 * @param PDO $pdo pointer to mySQL connection, by reference
	 * @throws PDOException when mySQL related errors occur
	 **/
	public function update(PDO &$pdo) {
		// handle degenerate cases
		if(gettype($pdo) !== "object" || get_class($pdo) !== "PDO") {
			throw(new PDOException("input is not a PDO object"));
		}

		// enforce the visitorId is not null (i.e., don't update a visitor that hasn't been inserted)
		if($this->visitorId === null) {
			throw(new PDOException("unable to update a visitor that does not exist"));
		}

		// create query template
		$query = "UPDATE visitor SET visitorEmail = :visitorEmail, visitorFirstName = :visitorFirstName, visitorLastName = :visitorLastName, visitorPhone = :visitorPhone WHERE visitorId = :visitorId";
		$statement = $pdo->prepare($query);
		if($statement === false) {
			throw(new PDOException("unable to prepare statement"));
		}

		// bind the visitor variables to the place holders in the template
		$parameters = array("visitorEmail" => $this->visitorEmail, "visitorFirstName" => $this->visitorFirstName, "visitorLastName" => $this->visitorLastName, "visitorPhone" => $this->visitorPhone, "visitorId" => $this->visitorId);

		// execute the statement
		$statement->execute($parameters);

		// clean up the statement
//		$statement->close();
	}

	/**
	 * gets the visitor by visitor Id
	 *
	 * @param PDO $pdo pointer to mySQL connection, by reference
	 * @param int $visitorId visitor to search for
	 * @return mixed visitor found or null if not found
	 * @throws PDOException when mySQL related errors occur
	 **/
	public static function getVisitorByVisitorId(PDO &$pdo, $visitorId) {
		// handle degenerate cases
		if(gettype($pdo) !== "object" || get_class($pdo) !== "PDO") {
			throw(new PDOException("input is not a PDO object"));
		}

		// sanitize the visitorId before searching
		$visitorId = filter_var($visitorId, FILTER_VALIDATE_INT);
		if($visitorId === false) {
			throw(new PDOException("visitor id is not an integer"));
		}
		if($visitorId <= 0) {
			throw(new PDOException("visitor id is not positive"));
		}

		// create query template
		$query = "SELECT visitorId, visitorEmail, visitorFirstName, visitorLastName, visitorPhone FROM visitor WHERE visitorId= :visitorId";
		$statement = $pdo->prepare($query);
		if($statement === false) {
			throw(new PDOException("unable to prepare statement"));
		}

		// bind the visitor id to the place holder in the template
		$parameters = array("visitorId" => $visitorId);

		// execute the statement
		$statement->execute($parameters);
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		// get result from the SELECT query

		// grab the visitor from mySQL
		try {
			$visitor = null;
			$row = $statement->fetch();
			if($row !== null) {
				$visitor = new Visitor($row["visitorId"], $row["visitorEmail"], $row["visitorFirstName"], $row["visitorLastName"], $row["visitorPhone"]);
			}
		} catch(Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new PDOException($exception->getMessage(), 0, $exception));
		}

		// free up memory and return the result
//		$result->free();
//		$statement->close();
		return ($visitor);
	}

	/**
	 * gets the visitor by visitor first name
	 *
	 * @param PDO $pdo pointer to mySQL connection, by reference
	 * @param string $visitorFirstName visitor to search for by first name
	 * @return mixed visitor found or null if not found
	 * @throws PDOException when mySQL related errors occur
	 **/
	public static function getVisitorByVisitorFirstName(PDO &$pdo, $visitorFirstName) {
		// handle degenerate cases
		if(gettype($pdo) !== "object" || get_class($pdo) !== "PDO") {
			throw(new PDOException("input is not a PDO object"));
		}

		// sanitize the visitorFirstName before searching
		$visitorFirstName = trim($visitorFirstName);
		$visitorFirstName = filter_var($visitorFirstName, FILTER_SANITIZE_STRING);
		if(empty($visitorFirstName) === true) {
			throw(new InvalidArgumentException("visitor first name is empty or insecure"));
		}

		// create query template
		$query = "SELECT visitorId, visitorEmail, visitorFirstName, visitorLastName, visitorPhone FROM visitor WHERE visitorFirstName LIKE :visitorFirstName";
		$statement = $pdo->prepare($query);
		if($statement === false) {
			throw(new PDOException("unable to prepare statement"));
		}

		// bind the visitorFirstName to the place holder in the template
		$visitorFirstName = "%$visitorFirstName%";
		$parameters = array("visitorFirstName" => $visitorFirstName);

		// execute the statement
		$statement->execute($parameters);
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		// get result from the SELECT query

		// grab the visitor from mySQL
		$visitors = array();
		while(($row = $statement->fetch()) !== null) {
			try {
				$visitor = new Visitor($row["visitorId"], $row["visitorEmail"], $row["visitorFirstName"], $row["visitorLastName"], $row["visitorPhone"]);
				$visitors[] = $visitor;
			} catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new PDOException($exception->getMessage(), 0, $exception));
			}
		}
		// count the results in the array and return:
		// 1) null if 0 results
		// 2) the entire array if > 1 result
		$numberOfVisitors = count($visitors);
		if($numberOfVisitors === 0) {
			return (null);
		} else {
			return ($visitors);
		}
	}

	/**
	 * gets the visitor by visitor last name
	 *
	 * @param PDO $pdo pointer to mySQL connection, by reference
	 * @param string $visitorLastName visitor to search for by first name
	 * @return mixed visitor found or null if not found
	 * @throws PDOException when mySQL related errors occur
	 **/
	public static function getVisitorByVisitorLastName(PDO &$pdo, $visitorLastName) {
		// handle degenerate cases
		if(gettype($pdo) !== "object" || get_class($pdo) !== "PDO") {
			throw(new PDOException("input is not a PDO object"));
		}

		// sanitize the visitorLastName before searching
		$visitorLastName = trim($visitorLastName);
		$visitorLastName = filter_var($visitorLastName, FILTER_SANITIZE_STRING);
		if(empty($visitorLastName) === true) {
			throw(new InvalidArgumentException("visitor last name is empty or insecure"));
		}

		// create query template
		$query = "SELECT visitorId, visitorEmail, visitorFirstName, visitorLastName, visitorPhone FROM visitor WHERE visitorLastName LIKE :visitorLastName";
		$statement = $pdo->prepare($query);
		if($statement === false) {
			throw(new PDOException("unable to prepare statement"));
		}

		// bind the visitorLastName to the place holder in the template
		$visitorLastName = "%$visitorLastName%";
		$parameters = array("visitorLastName" => $visitorLastName);

		// execute the statement
		$statement->execute($parameters);
		$statement->setFetchMode(PDO::FETCH_ASSOC);

		// get result from the SELECT query

		// grab the visitor from mySQL
		$visitors = array();
		while(($row = $statement->fetch()) !== null) {
			try {
				$visitor = new Visitor($row["visitorId"], $row["visitorEmail"], $row["visitorFirstName"], $row["visitorLastName"], $row["visitorPhone"]);
				$visitors[] = $visitor;
			} catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new PDOException($exception->getMessage(), 0, $exception));
			}
		}
		// count the results in the array and return:
		// 1) null if 0 results
		// 2) the entire array if > 1 result
		$numberOfVisitors = count($visitors);
		if($numberOfVisitors === 0) {
			return (null);
		} else {
			return ($visitors);
		}
	}

	/**
	 * gets the visitor by email address
	 *
	 * @param PDO $pdo pointer to mySQL connection, by reference
	 * @param string $visitorEmail visitor to search for by email address
	 * @return mixed visitor found or null if not found
	 * @throws PDOException when mySQL related errors occur
	 **/
	public static function getVisitorByVisitorEmail(PDO &$pdo, $visitorEmail) {
		// handle degenerate cases
		if(gettype($pdo) !== "object" || get_class($pdo) !== "PDO") {
			throw(new PDOException("input is not a PDO object"));
		}

		// sanitize the visitorEmail before searching
		$visitorEmail = trim($visitorEmail);
		$visitorEmail = filter_var($visitorEmail, FILTER_SANITIZE_EMAIL);
		if(empty($visitorEmail) === true) {
			throw(new InvalidArgumentException("visitor email address is empty or insecure"));
		}

		// create query template
		$query = "SELECT visitorId, visitorEmail, visitorFirstName, visitorLastName, visitorPhone FROM visitor WHERE visitorEmail = :visitorEmail";
		$statement = $pdo->prepare($query);

		// bind the visitorEmail to the place holder in the template
		$parameters = array("visitorEmail" => $visitorEmail);

		// execute the statement
		$statement->execute($parameters);
		$statement->setFetchMode(PDO::FETCH_ASSOC);

		// grab the visitor from mySQL
		try {
			$visitor = null;
			$row = $statement->fetch();
			if($row !== false) {
				$visitor = new Visitor($row["visitorId"], $row["visitorEmail"], $row["visitorFirstName"], $row["visitorLastName"], $row["visitorPhone"]);
			}
		} catch (Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new PDOException($exception->getMessage(), 0, $exception));
		}

		// return the result
		return ($visitor);
	}
}
?>