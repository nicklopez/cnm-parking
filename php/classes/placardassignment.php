<?php
/**
 * CNM Parking Placard Assignment Information
 *
 * This is where placard assignee details are stored
 *
 * @author Nick Lopez <nick@nicklopezcodes.com>
 **/
class PlacardAssignment {
	/**
	 * id for the assignment; this is the primary key
	 **/
	private $assignId;
	/**
	 * id of admin profile assigning the placard; this is a foreign key
	 **/
	private $adminProfileId;
	/**
	 * id of the parking spot / placard assigned; this is a foreign key
	 **/
	private $parkingSpotId;
	/**
	 * datetime the placard is due for return
	 **/
	private $endDateTime;
	/**
	 * first name of placard assignee
	 **/
	private $firstName;
	/**
	 * datetime the placard was issued
	 **/
	private $issuedDateTime;
	/**
	 * last name of placard assignee
	 **/
	private $lastName;
	/**
	 * datetime the placard was actually returned
	 **/
	private $returnDateTime;
	/**
	 * datetime begin date of placard assignment
	 **/
	private $startDateTime;

	/**
	 * constructor for the placard assignment
	 *
	 * @param mixed $newAssignId id of the placard assignment
	 * @param int $newAdminProfileId id of the admin profile assigning the placard
	 * @param int $newParkingSpotId id of the parking spot / placard assigned
	 * @param datetime $newEndDateTime datetime the placard is due for return
	 * @param string $newFirstName first name of placard assignee
	 * @param datetime $newIssuedDateTime datetime the placard was issued
	 * @param string $newLastName last name of placard assignee
	 * @param datetime $newReturnDateTime datetime the placard was actually returned
	 * @param datetime $newStartDateTime datetime begin date of placard assignment
	 * @throws InvalidArgumentException if data types are not valid
	 * @throws RangeException if data values are out of bounds (e.g., strings too long, negative integers)
	 **/
	public function __construct($newAssignId, $newAdminProfileId, $newParkingSpotId, $newEndDateTime, $newFirstName, $newIssuedDateTime, $newLastName, $newReturnDateTime, $newStartDateTime) {
		try {
			$this->setAssignId($newAssignId);
			$this->setAdminProfileId($newAdminProfileId);
			$this->setParkingSpotId($newParkingSpotId);
			$this->setEndDateTime($newEndDateTime);
			$this->setFirstName($newFirstName);
			$this->setIssuedDateTime($newIssuedDateTime);
			$this->setLastName($newLastName);
			$this->setReturnDateTime($newReturnDateTime);
			$this->setStartDateTime($newStartDateTime);
		} catch(InvalidArgumentException $invalidArgument) {
			// rethrow the exception to the caller
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			// rethrow the exception to the caller
			throw(new RangeException($range->getMessage(), 0, $range));
		}
	}

	/**
	 * accessor method for assign Id
	 *
	 * @return mixed value of assign Id
	 **/
	public function getAssignId() {
		return ($this->assignId);
	}

	/**
	 * mutator method for assign id
	 *
	 * @param mixed $newAssignId new value of assign id
	 * @throws InvalidArgumentException if $newAssignId is not an integer
	 * @throws RangeException if $newAssignId is not positive
	 **/
	public function setAssignId($newAssignId) {
		// base case: if the assign id is null, this a new placard assignment without a mySQL assigned id (yet)
		if($newAssignId === null) {
			$this->assignId = null;
			return;
		}

		// verify the assign id is valid
		$newAssignId = filter_var($newAssignId, FILTER_VALIDATE_INT);
		if($newAssignId === false) {
			throw(new InvalidArgumentException("assign id is not a valid integer"));
		}

		// verify the assign id is positive
		if($newAssignId <= 0) {
			throw(new RangeException("assign id is not positive"));
		}

		// convert and store the assign id
		$this->assignId = intval($newAssignId);
	}

	/**
	 * accessor method for admin profile Id
	 *
	 * @return int value of admin profile Id
	 **/
	public function getAdminProfileId() {
		return ($this->adminProfileId);
	}

	/**
	 * mutator method for admin profile id
	 *
	 * @param int $newAdminProfileId id of admin profile
	 * @throws InvalidArgumentException if $newAdminProfileId is not an integer
	 * @throws RangeException if $newAdminProfileId is not positive
	 **/
	public function setAdminProfileId($newAdminProfileId) {

		// verify the admin profile id is valid
		$newAdminProfileId = filter_var($newAdminProfileId, FILTER_VALIDATE_INT);
		if($newAdminProfileId === false) {
			throw(new InvalidArgumentException("admin profile id is not a valid integer"));
		}

		// verify the admin profile id is positive
		if($newAdminProfileId <= 0) {
			throw(new RangeException("admin profile id is not positive"));
		}

		// convert and store the admin profile id
		$this->adminProfileId = intval($newAdminProfileId);
	}

	/**
	 * accessor method for parking spot id
	 *
	 * @return int value of parking spot id
	 **/
	public function getParkingSpotId() {
		return ($this->parkingSpotId);
	}

	/**
	 * mutator method for parking spot id
	 *
	 * @param mixed $newParkingSpotId id of parking spot
	 * @throws InvalidArgumentException if $newParkingSpotId is not an integer
	 * @throws RangeException if $newParkingSpotId is not positive
	 **/
	public function setParkingSpotId($newParkingSpotId) {

		// verify the parking spot id is valid
		$newParkingSpotId = filter_var($newParkingSpotId, FILTER_VALIDATE_INT);
		if($newParkingSpotId === false) {
			throw(new InvalidArgumentException("parking spot id is not a valid integer"));
		}

		// verify the parking spot id is positive
		if($newParkingSpotId <= 0) {
			throw(new RangeException("parking spot id is not positive"));
		}

		// convert and store the parking spot id
		$this->parkingSpotId = intval($newParkingSpotId);
	}

	/**
	 * accessor method for end datetime
	 *
	 * @return datetime value of placard due date for return
	 **/
	public function getEndDateTime() {
		return ($this->endDateTime);
	}

	/**
	 * mutator method for end datetime
	 *
	 * @param datetime $newEndDateTime endDateTime as DateTime or string
	 * @throws InvalidArgumentException if $newCreateDateTime is not a valid object or string
	 * @throws RangeException if $newCreateDateTime is a date that doesn't exist
	 */
	public function setEndDateTime($newEndDateTime) {
		// base case: if the date is a DateTime object, there's no work to be done
		if(is_object($newEndDateTime) === true && get_class($newEndDateTime) === "DateTime") {
			$this->endDateTime = $newEndDateTime;
			return;
		}

		// treat the date as a mySQL date string: n-j-y
		$newEndDateTime = trim($newEndDateTime);
		if((preg_match("/^(\d{1,2})-(\d{1,2})-(\d{2})$/", $newEndDateTime, $matches)) !== 1) {
			throw(new InvalidArgumentException("end date is not a valid date"));
		}

		// verify the date is really a valid calendar date
		$month = intval($matches[1]);
		$day = intval($matches[2]);
		$year = intval($matches[3]);
		if(checkdate($month, $day, $year) === false) {
			throw(new RangeException("end date $newEndDateTime is not a Gregorian date"));
		}

		// store the end date
		$newEndDateTime = DateTime::createFromFormat("n-j-y", $newEndDateTime);
		$this->endDateTime = $newEndDateTime;
	}

	/**
	 * accessor method for placard assignee
	 *
	 * @return string value of first name
	 **/
	public function getFirstName() {
		return ($this->firstName);
	}

	/**
	 * mutator method for placard assignee
	 *
	 * @param string $newFirstName new value of placard assignees first name
	 * @throws InvalidArgumentException if $newFirstName is not a string or insecure
	 * @throws RangeException if $newFirstName is > 128 characters
	 **/
	public function setFirstName($newFirstName) {
		// verify the first name is secure
		$newFirstName = trim($newFirstName);
		$newFirstName = filter_var($newFirstName, FILTER_SANITIZE_STRING);
		if(empty($newFirstName) === true) {
			throw(new InvalidArgumentException("first name is empty or insecure"));
		}

		// verify the first name will fit in the database
		if(strlen($newFirstName) > 128) {
			throw(new RangeException("first name too large"));
		}

		// store the first name
		$this->firstName = $newFirstName;
	}

	/**
	 * accessor method for issued datetime
	 *
	 * @return datetime value of placard issued date
	 **/
	public function getIssuedDateTime() {
		return ($this->issuedDateTime);
	}

	/**
	 * mutator method for issued datetime
	 *
	 * @param datetime $newIssuedDateTime issuedDateTime as DateTime or string
	 */
	public function setIssuedDateTime($newIssuedDateTime) {
		// base case: always use current datetime
		if($newIssuedDateTime === null) {
			$newIssuedDateTime = new DateTime();
			$newIssuedDateTime->setTimezone(new DateTimeZone('America/Denver'));
			$this->issuedDateTime = $newIssuedDateTime;
			return;
		}
	}

	/**
	 * accessor method for placard assignee
	 *
	 * @return string value of last name
	 **/
	public function getLastName() {
		return ($this->lastName);
	}

	/**
	 * mutator method for placard assignee
	 *
	 * @param string $newLastName new value of placard assignees last name
	 * @throws InvalidArgumentException if $newLastName is not a string or insecure
	 * @throws RangeException if $newLastName is > 128 characters
	 **/
	public function setLastName($newLastName) {
		// verify the last name is secure
		$newLastName = trim($newLastName);
		$newLastName = filter_var($newLastName, FILTER_SANITIZE_STRING);
		if(empty($newLastName) === true) {
			throw(new InvalidArgumentException("last name is empty or insecure"));
		}

		// verify the last name will fit in the database
		if(strlen($newLastName) > 128) {
			throw(new RangeException("last name too large"));
		}

		// store the last name
		$this->lastName = $newLastName;
	}

	/**
	 * accessor method for return date time
	 *
	 * @return datetime value of placard actual return date time
	 **/
	public function getReturnDateTime() {
		return ($this->returnDateTime);
	}

	/**
	 * mutator method for return date time
	 *
	 * @param datetime $newReturnDateTime returnDateTime as DateTime or string
	 * @throws InvalidArgumentException if $newReturnDateTime is not a valid object or string
	 * @throws RangeException if $newReturnDateTime is a date that doesn't exist
	 */
	public function setReturnDateTime($newReturnDateTime) {
		// base case: if the date is null, use the current date and time
		if(empty($newReturnDateTime) === true) {
			$this->returnDateTime = null;
			return;
		}

		// base case: if the date is a DateTime object, there's no work to be done
		if(is_object($newReturnDateTime) === true && get_class($newReturnDateTime) === "DateTime") {
			$this->returnDateTime = $newReturnDateTime;
			return;
		}

		// treat the date as a mySQL date string: Y-m-d H:i:s
		$newReturnDateTime = trim($newReturnDateTime);
		if((preg_match("/^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})$/", $newReturnDateTime, $matches)) !== 1) {
			throw(new InvalidArgumentException("return date is not a valid date"));
		}

		// verify the date is really a valid calendar date
		$year = intval($matches[1]);
		$month = intval($matches[2]);
		$day = intval($matches[3]);
		$hour = intval($matches[4]);
		$minute = intval($matches[5]);
		$second = intval($matches[6]);
		if(checkdate($month, $day, $year) === false) {
			throw(new RangeException("return date $newReturnDateTime is not a Gregorian date"));
		}

		// verify the time is really a valid wall clock time
		if($hour < 0 || $hour >= 24 || $minute < 0 || $minute >= 60 || $second < 0 || $second >= 60) {
			throw(new RangeException("return date $newReturnDateTime is not a valid time"));
		}

		// store the return date
		$newReturnDateTime = DateTime::createFromFormat("Y-m-d H:i:s", $newReturnDateTime);
		$this->returnDateTime = $newReturnDateTime;
	}

	/**
	 * accessor method for start date time
	 *
	 * @return datetime value of placard begin datetime of placard assignment
	 **/
	public function getStartDateTime() {
		return ($this->startDateTime);
	}

	/**
	 * mutator method for start date time
	 *
	 * @param datetime $newStartDateTime startDateTime as DateTime or string
	 * @throws InvalidArgumentException if $newStartDateTime is not a valid object or string
	 * @throws RangeException if $newStartDateTime is a date that doesn't exist
	 */
	public function setStartDateTime($newStartDateTime) {
		// base case: if the date is a DateTime object, there's no work to be done
		if(is_object($newStartDateTime) === true && get_class($newStartDateTime) === "DateTime") {
			$this->startDateTime = $newStartDateTime;
			return;
		}

		// treat the date as a mySQL date string: n-j-y
		$newStartDateTime = trim($newStartDateTime);
		if((preg_match("/^(\d{1,2})-(\d{1,2})-(\d{2})$/", $newStartDateTime, $matches)) !== 1) {
			throw(new InvalidArgumentException("start date is not a valid date"));
		}

		// verify the date is really a valid calendar date
		$month = intval($matches[1]);
		$day = intval($matches[2]);
		$year = intval($matches[3]);
		if(checkdate($month, $day, $year) === false) {
			throw(new RangeException("start date $newStartDateTime is not a Gregorian date"));
		}

		// store the start date
		$newStartDateTime = DateTime::createFromFormat("n-j-y", $newStartDateTime);
		$this->startDateTime = $newStartDateTime;
	}

	/**
	 * inserts placard assignment into mySQL
	 *
	 * @param PDO $pdo pointer to mySQL connection, by reference
	 * @throws PDOException when mySQL related errors occur
	 **/
	public function insert(PDO &$pdo) {
		// handle degenerate cases
		if(gettype($pdo) !== "object" || get_class($pdo) !== "PDO") {
			throw(new PDOException("input is not a PDO object"));
		}

		// enforce the assign id is null (i.e., don't insert an assignment that already exists)
		if($this->assignId !== null) {
			throw(new PDOException("not a new placard assignment"));
		}

		// create query template
		$query = "INSERT INTO placardAssignment(assignId, adminProfileId, parkingSpotId, endDateTime, firstName, issuedDateTime, lastName, returnDateTime, startDateTime) VALUES(:assignId, :adminProfileId, :parkingSpotId, :endDateTime, :firstName, :issuedDateTime, :lastName, :returnDateTime, :startDateTime)";
		$statement = $pdo->prepare($query);
		if($statement === false) {
			throw(new PDOException("unable to prepare statement"));
		}

		// bind the assignment variables to the place holders in the template
		if($this->returnDateTime === null) {
			$formattedReturnDate = null;
		} else {
			$formattedReturnDate = $this->returnDateTime->format("Y-m-d H:i:s");
		}

		$formattedIssuedDate = $this->issuedDateTime->format("Y-m-d H:i:s");
		$formattedStartDate = $this->startDateTime->format("Y-m-d 00:00:00");
		$formattedEndDate = $this->endDateTime->format("Y-m-d 00:00:00");
		$parameters = array("assignId" => $this->assignId, "adminProfileId" => $this->adminProfileId, "parkingSpotId" => $this->parkingSpotId, "endDateTime" => $formattedEndDate, "firstName" => $this->firstName, "issuedDateTime" => $formattedIssuedDate, "lastName" => $this->lastName, "returnDateTime" => $formattedReturnDate, "startDateTime" => $formattedStartDate);

		// execute the statement
		if($statement->execute($parameters) === false) {
			throw(new PDOException("unable to execute mySQL statement: " . $statement->error));
		}

		// update the null assignId with what mySQL just gave us
		$this->assignId = $pdo->lastInsertId();
	}

	/**
	 * updates placard assignment in mySQL
	 *
	 * @param PDO $pdo pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function update(PDO &$pdo) {
		// handle degenerate cases
		if(gettype($pdo) !== "object" || get_class($pdo) !== "PDO") {
			throw(new mysqli_sql_exception("input is not a PDO object"));
		}

		// enforce the assignId is not null (i.e., don't update an assignment that hasn't been inserted)
		if($this->assignId === null) {
			throw(new mysqli_sql_exception("unable to update an assignment that does not exist"));
		}

		// create query template
		$query = "UPDATE placardAssignment SET adminProfileId = :adminProfileId, parkingSpotId = :parkingSpotId, endDateTime = :endDateTime, firstName = :firstName, lastName = :lastName, returnDateTime = :returnDateTime, startDateTime = :startDateTime WHERE assignId = :assignId";
		$statement = $pdo->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		// bind the assignment variables to the place holders in the template
		if($this->returnDateTime === null) {
			$formattedReturnDate = null;
		} else {
			$formattedReturnDate = $this->returnDateTime->format("Y-m-d H:i:s");
		}

		$formattedStartDate = $this->startDateTime->format("Y-m-d 00:00:00");
		$formattedEndDate = $this->endDateTime->format("Y-m-d 00:00:00");
		$parameters = array("adminProfileId" => $this->adminProfileId, "parkingSpotId" => $this->parkingSpotId, "endDateTime" => $formattedEndDate, "firstName" => $this->firstName, "lastName" => $this->lastName, "returnDateTime" => $formattedReturnDate, "startDateTime" => $formattedStartDate, "assignId" => $this->assignId);

		// execute the statement
		$statement->execute($parameters);
	}
}
?>