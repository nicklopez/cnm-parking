<?php
/**
 * CNM Parking Request Invite Information
 *
 * This is where site invite log information is stored
 *
 * @author Nick Lopez <nick@nicklopezcodes.com>
 **/
class Invite {
	/**
	 * id for invite; this is the primary key
	 **/
	private $inviteId;
	/**
	 * datetime the invite was approved or declined
	 **/
	private $actionDateTime;
	/**
	 * token generated for end requester (visitor)
	 **/
	private $activation;
	/**
	 * id of adminProfile approving or declining invite; this is a foreign key
	 **/
	private $adminProfileId;
	/**
	 * boolean; invite approved or declined
	 **/
	private $approved;
	/**
	 * datetime the invite was generated
	 **/
	private $createDateTime;
	/**
	 * id of visitor if visitor exists; this is a foreign key
	 **/
	private $visitorId;

	/**
	 * constructor for the site invite
	 *
	 * @param mixed $newInviteId id of the site invite
	 * @param mixed $newActionDateTime datetime the invite was approved or declined
	 * @param string $newActivation string containing token for site invite
	 * @param int $newAdminProfileId id of adminProfile approving or declining invite
	 * @param boolean $newApproved boolean invite approved or declined
	 * @param datetime $newCreateDateTime datetime the site invite was generated
	 * @param int $newVisitorId id of visitor if visitor exists
	 * @throws InvalidArgumentException if data types are not valid
	 * @throws RangeException if data values are out of bounds (e.g., strings too long, negative integers)
	 **/
	public function __construct($newInviteId, $newActionDateTime, $newActivation, $newAdminProfileId, $newApproved, $newCreateDateTime, $newVisitorId) {
		try {
			$this->setInviteId($newInviteId);
			$this->setActionDateTime($newActionDateTime);
			$this->setActivation($newActivation);
			$this->setAdminProfileId($newAdminProfileId);
			$this->setApproved($newApproved);
			$this->setCreateDateTime($newCreateDateTime);
			$this->setVisitorId($newVisitorId);
		} catch(InvalidArgumentException $invalidArgument) {
			// rethrow the exception to the caller
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			// rethrow the exception to the caller
			throw(new RangeException($range->getMessage(), 0, $range));
		}
	}

	/**
	 * accessor method for invite Id
	 *
	 * @return mixed value of invite Id
	 **/
	public function getInviteId() {
		return($this->inviteId);
	}

	/**
	 * mutator method for invite id
	 *
	 * @param mixed $newInviteId new value of invite id
	 * @throws InvalidArgumentException if $newInviteId is not an integer
	 * @throws RangeException if $newInviteId is not positive
	 **/
	public function setInviteId($newInviteId) {
		// base case: if the invite id is null, this a new invite without a mySQL assigned id (yet)
		if($newInviteId === null) {
			$this->inviteId = null;
			return;
		}

		// verify the invite id is valid
		$newInviteId = filter_var($newInviteId, FILTER_VALIDATE_INT);
		if($newInviteId === false) {
			throw(new InvalidArgumentException("invite id is not a valid integer"));
		}

		// verify the invite id is positive
		if($newInviteId <= 0) {
			throw(new RangeException("invite id is not positive"));
		}

		// convert and store the invite id
		$this->inviteId = intval($newInviteId);
	}

	/**
	 * accessor method for action date time
	 *
	 * @return datetime value of action date time
	 **/
	public function getActionDateTime() {
		return($this->actionDateTime);
	}

	/**
	 * mutator method for actionDateTime
	 *
	 * @param mixed $newActionDateTime actionDateTime as DateTime or string
	 * @throws InvalidArgumentException if $newActionDateTime is not a valid object or string
	 * @throws RangeException if $newActionDateTime is a date that doesn't exist
	 */
	public function setActionDateTime($newActionDateTime) {

		// store the actionDateTime
		try {
			$newActionDateTime = self::sanitizeDate($newActionDateTime);
			$this->actionDateTime = $newActionDateTime;
		} catch(InvalidArgumentException $invalidArgument) {
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			throw(new RangeException($range->getMessage(), 0, $range));
		}
	}

	/**
	 * accessor method for activation (token)
	 *
	 * @return string value of activation (token)
	 **/
	public function getActivation() {
		return($this->activation);
	}

	/**
	 * mutator method for activation (token)
	 *
	 * @param string $newActivation new value of activation (token)
	 * @throws InvalidArgumentException if $newActivation is not a string or insecure
	 * @throws RangeException if $newActivation is > 32 characters
	 **/
	public function setActivation($newActivation) {
		// verify the activation (token) is secure
		$newActivation = trim($newActivation);
		$newActivation = filter_var($newActivation, FILTER_SANITIZE_STRING);
		if(empty($newActivation) === true) {
			throw(new InvalidArgumentException("activation (token) is empty or insecure"));
		}

		// verify the activation (token) will fit in the database
		if(strlen($newActivation) > 32) {
			throw(new RangeException("activation (token) too large"));
		}

		// store the activation
		$this->activation = $newActivation;
	}

	/**
	 * accessor method for admin profile Id
	 *
	 * @return int value of admin profile Id
	 **/
	public function getAdminProfileId() {
		return($this->adminProfileId);
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
	 * accessor method for approved or declined
	 *
	 * @return boolean value of approved or declined
	 **/
	public function getApproved() {
		return($this->approved);
	}

	/**
	 * mutator method for approved (or declined)
	 *
	 * @param string $newApproved new value of approved (approved or declined)
	 * @throws InvalidArgumentException if $newApproved is not a string or insecure
	 * @throws RangeException if $newApproved is not 0 or 1
	 **/
	public function setApproved($newApproved) {
		// verify approved is valid
		$newApproved = filter_var($newApproved, FILTER_VALIDATE_BOOLEAN);
		if($newApproved === false) {
			throw(new InvalidArgumentException("approved is not boolean"));
		}

		// verify approved is 0 or 1
		if($newApproved !== 0 || $newApproved !== 1) {
			throw(new RangeException("approved must be 0 or 1"));
		}

		// store approved
		$this->approved = $newApproved;
	}

	/**
	 * accessor method for create date time
	 *
	 * @return datetime value of invite create date time
	 **/
	public function getCreateDateTime() {
		return($this->createDateTime);
	}

	/**
	 * mutator method for createDateTime
	 *
	 * @param mixed $newCreateDateTime actionDateTime as DateTime or string
	 * @throws InvalidArgumentException if $newCreateDateTime is not a valid object or string
	 * @throws RangeException if $newCreateDateTime is a date that doesn't exist
	 */
	public function setCreateDateTime($newCreateDateTime) {
		// verify not null
		if($newCreateDateTime === null) {
			throw(new InvalidArgumentException("createDateTime cannot be null"));
		}

		// store the createDateTime
		try {
			$newCreateDateTime = self::sanitizeDate($newCreateDateTime);
			$this->createDateTime = $newCreateDateTime;
		} catch(InvalidArgumentException $invalidArgument) {
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			throw(new RangeException($range->getMessage(), 0, $range));
		}
	}

	/**
	 * accessor method for visitor id
	 *
	 * @return int value of visitor id
	 **/
	public function getVisitorId() {
		return($this->visitorId);
	}

	/**
	 * mutator method for visitor id
	 *
	 * @param int $newVisitorId id of admin profile
	 * @throws InvalidArgumentException if $newVisitorId is not an integer
	 * @throws RangeException if $newVisitorId is not positive
	 **/
	public function setVisitorId($newVisitorId) {

		// verify the visitor id is valid
		$newVisitorId = filter_var($newVisitorId, FILTER_VALIDATE_INT);
		if($newVisitorId === false) {
			throw(new InvalidArgumentException("visitor id is not a valid integer"));
		}

		// verify the admin profile id is positive
		if($newVisitorId <= 0) {
			throw(new RangeException("visitor id is not positive"));
		}

		// convert and store the visitor id
		$this->visitorId = intval($newVisitorId);
	}

	/**
	 * inserts invite into mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function insert(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// enforce the inviteId is null (i.e., don't insert an invite that already exists)
		if($this->inviteId!== null) {
			throw(new mysqli_sql_exception("not a new invite"));
		}

		// create query template
		$query	 = "INSERT INTO invite(inviteId, actionDateTime, activation, adminProfileId, approved, createDateTime, visitorId) VALUES(?, ?, ?, ?, ?, ?, ?)";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		// bind the invite variables to the place holders in the template
		$wasClean	  = $statement->bind_param("ississi", $this->inviteId, $this->actionDateTime, $this->activation, $this->adminProfileId, $this->approved, $this->createDateTime, $this->visitorId);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("unable to bind parameters"));
		}

		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("unable to execute mySQL statement: " . $statement->error));
		}

		// update the null inviteId with what mySQL just gave us
		$this->inviteId= $mysqli->insert_id;

		// clean up the statement
		$statement->close();
	}

	/**
	 * deletes an invite from mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function delete(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// enforce the inviteId is not null (i.e., don't delete an invite that hasn't been inserted)
		if($this->inviteId=== null) {
			throw(new mysqli_sql_exception("unable to delete an invite that does not exist"));
		}

		// create query template
		$query	 = "DELETE FROM invite WHERE inviteId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		// bind the invite variables to the place holder in the template
		$wasClean = $statement->bind_param("i", $this->inviteId);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("unable to bind parameters"));
		}

		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("unable to execute mySQL statement: " . $statement->error));
		}

		// clean up the statement
		$statement->close();
	}

	/**
	 * updates invite in mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function update(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// enforce the inviteId is not null (i.e., don't update an invite that hasn't been inserted)
		if($this->inviteId=== null) {
			throw(new mysqli_sql_exception("unable to update an invite that does not exist"));
		}

		// create query template
		$query	 = "UPDATE invite SET actionDateTime = ?, activation = ?, adminProfileId = ?, approved = ?, createDateTime = ?, visitorId = ? WHERE inviteId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		// bind the invite variables to the place holders in the template
		$wasClean = $statement->bind_param("ssissii", $this->actionDateTime, $this->activation, $this->adminProfileId, $this->approved, $this->createDateTime, $this->visitorId, $this->inviteId);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("unable to bind parameters"));
		}

		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("unable to execute mySQL statement: " . $statement->error));
		}

		// clean up the statement
		$statement->close();
	}

	/**
	 * sanitizes a date either as a DateTime object or mySQL date string
	 *
	 * @param mixed $newDate date to sanitize (or null to just create the current date and time)
	 * @return DateTime sanitized DateTime object
	 * @throws InvalidArgumentException if the date is in an invalid format
	 * @throws RangeException if the date is not a Gregorian date
	 **/
	public static function sanitizeDate($newDate) {
		// base case: if the date is null, use the current date and time
		if($newDate === null) {
			$newDate = new DateTime();
			return($newDate);
		}

		// base case: if the date is a DateTime object, there's no work to be done
		if(is_object($newDate) === true && get_class($newDate) === "DateTime") {
			return($newDate);
		}

		// treat the date as a mySQL date string: Y-m-d H:i:s
		$newDate = trim($newDate);
		if((preg_match("/^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})$/", $newDate, $matches)) !== 1) {
			throw(new InvalidArgumentException("date is not a valid date"));
		}

		// verify the date is really a valid calendar date
		$year   = intval($matches[1]);
		$month  = intval($matches[2]);
		$day    = intval($matches[3]);
		$hour   = intval($matches[4]);
		$minute = intval($matches[5]);
		$second = intval($matches[6]);
		if(checkdate($month, $day, $year) === false) {
			throw(new RangeException("date $newDate is not a Gregorian date"));
		}

		// verify the time is really a valid wall clock time
		if($hour < 0 || $hour >= 24 || $minute < 0 || $minute >= 60 || $second < 0  || $second >= 60) {
			throw(new RangeException("date $newDate is not a valid time"));
		}

		// store the DateTime value
		$newDate = DateTime::createFromFormat("Y-m-d H:i:s", $newDate);
		return($newDate);
	}
}
?>