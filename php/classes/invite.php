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
	 * @param mixed $newActionDateTime datetime the invite was approved or declined; can be null
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
		return ($this->inviteId);
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
		return ($this->actionDateTime);
	}

	/**
	 * mutator method for actionDateTime
	 *
	 * @param mixed $newActionDateTime actionDateTime as DateTime or string
	 * @throws InvalidArgumentException if $newActionDateTime is not a valid object or string
	 * @throws RangeException if $newActionDateTime is a date that doesn't exist
	 */
	public function setActionDateTime($newActionDateTime) {
		// base case: if the date is null, use the current date and time
		if($newActionDateTime === null) {
			$this->actionDateTime = null;
			return;
		}

		// base case: if the date is a DateTime object, there's no work to be done
		if(is_object($newActionDateTime) === true && get_class($newActionDateTime) === "DateTime") {
			$this->actionDateTime = $newActionDateTime;
			return;
		}

		// treat the date as a mySQL date string: Y-m-d H:i:s
		$newActionDateTime = trim($newActionDateTime);
		if((preg_match("/^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})$/", $newActionDateTime, $matches)) !== 1) {
			throw(new InvalidArgumentException("action date is not a valid date"));
		}

		// verify the date is really a valid calendar date
		$year = intval($matches[1]);
		$month = intval($matches[2]);
		$day = intval($matches[3]);
		$hour = intval($matches[4]);
		$minute = intval($matches[5]);
		$second = intval($matches[6]);
		if(checkdate($month, $day, $year) === false) {
			throw(new RangeException("action date $newActionDateTime is not a Gregorian date"));
		}

		// verify the time is really a valid wall clock time
		if($hour < 0 || $hour >= 24 || $minute < 0 || $minute >= 60 || $second < 0 || $second >= 60) {
			throw(new RangeException("action date $newActionDateTime is not a valid time"));
		}

		// store the action date
		$newActionDateTime = DateTime::createFromFormat("Y-m-d H:i:s", $newActionDateTime);
		$this->actionDateTime = $newActionDateTime;
	}

	/**
	 * accessor method for activation (token)
	 *
	 * @return string value of activation (token)
	 **/
	public function getActivation() {
		return ($this->activation);
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
		if($newAdminProfileId === null) {
			$this->adminProfileId = null;
			return;
		}

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
		return ($this->approved);
	}

	/**
	 * mutator method for approved (or declined)
	 *
	 * @param mixed $newApproved new value of approved (approved or declined)
	 * @throws InvalidArgumentException if $newApproved is not a string or insecure
	 * @throws RangeException if $newApproved is not 0 or 1
	 **/
	public function setApproved($newApproved) {
		// verify approved is valid
		if($newApproved === NULL) {
			$this->approved = NULL;
			return;
		} else {
			$newApproved = filter_var($newApproved, FILTER_VALIDATE_BOOLEAN);
		}

		if($newApproved < 0 || $newApproved > 1) {
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
		return ($this->createDateTime);
	}

	/**
	 * mutator method for createDateTime
	 *
	 * @param datetime $newCreateDateTime actionDateTime as DateTime or string
	 * @throws InvalidArgumentException if $newCreateDateTime is not a valid object or string
	 * @throws RangeException if $newCreateDateTime is a date that doesn't exist
	 */
	public function setCreateDateTime($newCreateDateTime) {
		// base case: if the date is null, use the current date and time
		if($newCreateDateTime === null) {
			$this->createDateTime = new DateTime();
			return;
		}

		// base case: if the date is a DateTime object, there's no work to be done
		if(is_object($newCreateDateTime) === true && get_class($newCreateDateTime) === "DateTime") {
			$this->createDateTime = $newCreateDateTime;
			return;
		}

		// treat the date as a mySQL date string: Y-m-d H:i:s
		$newCreateDateTime = trim($newCreateDateTime);
		if((preg_match("/^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})$/", $newCreateDateTime, $matches)) !== 1) {
			throw(new InvalidArgumentException("action date is not a valid date"));
		}

		// verify the date is really a valid calendar date
		$year = intval($matches[1]);
		$month = intval($matches[2]);
		$day = intval($matches[3]);
		$hour = intval($matches[4]);
		$minute = intval($matches[5]);
		$second = intval($matches[6]);
		if(checkdate($month, $day, $year) === false) {
			throw(new RangeException("create date $newCreateDateTime is not a Gregorian date"));
		}

		// verify the time is really a valid wall clock time
		if($hour < 0 || $hour >= 24 || $minute < 0 || $minute >= 60 || $second < 0 || $second >= 60) {
			throw(new RangeException("create date $newCreateDateTime is not a valid time"));
		}

		// store the create date
		$newCreateDateTime = DateTime::createFromFormat("Y-m-d H:i:s", $newCreateDateTime);
		$this->createDateTime = $newCreateDateTime;
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
	 * @param PDO $pdo pointer to mySQL connection, by reference
	 * @throws PDOException when mySQL related errors occur
	 **/
	public function insert(PDO &$pdo) {
		// handle degenerate cases
		if(gettype($pdo) !== "object" || get_class($pdo) !== "PDO") {
			throw(new PDOException("input is not a PDO object"));
		}

		// enforce the inviteId is null (i.e., don't insert an invite that already exists)
		if($this->inviteId !== null) {
			throw(new PDOException("not a new invite"));
		}

		// create query template
		$query = "INSERT INTO invite(inviteId, actionDateTime, activation, adminProfileId, approved, createDateTime, visitorId) VALUES(:inviteId, :actionDateTime, :activation, :adminProfileId, :approved, :createDateTime, :visitorId)";
		$statement = $pdo->prepare($query);
		if($statement === false) {
			throw(new PDOException("unable to prepare statement"));
		}

		// bind the invite variables to the place holders in the template
		if($this->actionDateTime === null) {
			$formattedActionDate = null;
		} else {
			$formattedActionDate = $this->actionDateTime->format("Y-m-d H:i:s");
		}

		$formattedCreateDate = $this->createDateTime->format("Y-m-d H:i:s");
		$parameters = array("inviteId" => $this->inviteId, "actionDateTime" => $formattedActionDate, "activation" => $this->activation, "adminProfileId" => $this->adminProfileId, "approved" => $this->approved, "createDateTime" => $formattedCreateDate, "visitorId" => $this->visitorId);

		// execute the statement
		if($statement->execute($parameters) === false) {
			throw(new PDOException("unable to execute mySQL statement: " . $statement->error));
		}

		// update the null inviteId with what mySQL just gave us
		$this->inviteId = $pdo->lastInsertId();

		// clean up the statement
//		$statement->close();
	}

	/**
	 * deletes an invite from mySQL
	 *
	 * @param PDO $pdo pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function delete(PDO &$pdo) {
		// handle degenerate cases
		if(gettype($pdo) !== "object" || get_class($pdo) !== "PDO") {
			throw(new mysqli_sql_exception("input is not a PDO object"));
		}

		// enforce the inviteId is not null (i.e., don't delete an invite that hasn't been inserted)
		if($this->inviteId === null) {
			throw(new mysqli_sql_exception("unable to delete an invite that does not exist"));
		}

		// create query template
		$query = "DELETE FROM invite WHERE inviteId = :inviteId";
		$statement = $pdo->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		// bind the invite variables to the place holder in the template
		$parameters = array("inviteId" => $this->inviteId);

		// execute the statement
		$statement->execute($parameters);

		// clean up the statement
		$statement->close();
	}

	/**
	 * updates invite in mySQL
	 *
	 * @param PDO $pdo pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function update(PDO &$pdo) {
		// handle degenerate cases
		if(gettype($pdo) !== "object" || get_class($pdo) !== "PDO") {
			throw(new mysqli_sql_exception("input is not a PDO object"));
		}

		// enforce the inviteId is not null (i.e., don't update an invite that hasn't been inserted)
		if($this->inviteId === null) {
			throw(new mysqli_sql_exception("unable to update an invite that does not exist"));
		}

		// create query template
		$query = "UPDATE invite SET actionDateTime = :actionDateTime, activation = :activation, adminProfileId = :adminProfileId, approved = :approved, createDateTime = :createDateTime, visitorId = :visitorId WHERE inviteId = :inviteId";
		$statement = $pdo->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		// bind the invite variables to the place holders in the template
		$formattedActionDate = $this->actionDateTime->format("Y-m-d H:i:s");
		$formattedCreateDate = $this->createDateTime->format("Y-m-d H:i:s");
		$parameters = array("actionDateTime" => $formattedActionDate, "activation" => $this->activation, "adminProfileId" => $this->adminProfileId, "approved" => $this->approved, "createDateTime" => $formattedCreateDate, "visitorId" => $this->visitorId, "inviteId" => $this->inviteId);

		// execute the statement
		$statement->execute($parameters);

		// clean up the statement
//		$statement->close();
	}

	/**
	 * gets the invite by invite Id
	 *
	 * @param PDO $pdo pointer to mySQL connection, by reference
	 * @param int $inviteId invite to search for by invite id
	 * @return mixed invite found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public static function getInviteByInviteId(PDO &$pdo, $inviteId) {
		// handle degenerate cases
		if(gettype($pdo) !== "object" || get_class($pdo) !== "PDO") {
			throw(new mysqli_sql_exception("input is not a PDO object"));
		}

		// sanitize the inviteId before searching
		$inviteId = filter_var($inviteId, FILTER_VALIDATE_INT);
		if($inviteId === false) {
			throw(new mysqli_sql_exception("invite id is not an integer"));
		}
		if($inviteId <= 0) {
			throw(new mysqli_sql_exception("invite id is not positive"));
		}

		// create query template
		$query = "SELECT inviteId, actionDateTime, activation, adminProfileId, approved, createDateTime, visitorId FROM invite WHERE inviteId= :inviteId";
		$statement = $pdo->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		// bind the invite id to the place holder in the template
		$parameters = array("inviteId" => $inviteId);

		// execute the statement
		$statement->execute($parameters);
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		// get result from the SELECT query

		// grab the invite from mySQL
		try {
			$invite = null;
			$row = $statement->fetch();
			if($row !== null) {
				$invite = new Invite($row["inviteId"], $row["actionDateTime"], $row["activation"], $row["adminProfileId"], $row["approved"], $row["createDateTime"], $row["visitorId"]);
			}
		} catch(Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new mysqli_sql_exception($exception->getMessage(), 0, $exception));
		}

		// free up memory and return the result
//		$result->free();
//		$statement->close();
		return ($invite);
	}

	/**
	 * gets the invite by activation (token)
	 *
	 * @param PDO $pdo pointer to mySQL connection, by reference
	 * @param int $activation invite to search for by activation (token)
	 * @return mixed invite found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public static function getInviteByActivation(&$pdo, $activation) {
		// handle degenerate cases
		if(gettype($pdo) !== "object" || get_class($pdo) !== "PDO") {
			throw(new mysqli_sql_exception("input is not a PDO object"));
		}

		// sanitize activation (token) before searching
		$activation = trim($activation);
		$activation = filter_var($activation, FILTER_SANITIZE_STRING);
		if(empty($activation) === true) {
			throw(new InvalidArgumentException("activation is empty or insecure"));
		}

		// create query template
		$query = "SELECT inviteId, visitor.visitorFirstName, visitor.visitorLastName, visitor.visitorEmail, visitor.visitorPhone, actionDateTime, activation, adminProfileId, approved, createDateTime, visitor.visitorId FROM invite
		INNER JOIN visitor ON visitor.visitorId = invite.visitorId
		WHERE activation = :activation";
		$statement = $pdo->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		// bind the activation (token) to the place holder in the template
		$parameters = array("activation" => $activation);

		// execute the statement
		$statement->execute($parameters);
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		// get result from the SELECT query

		// handle degenerate case: more than 1 row!?
		$numRows = $statement->rowCount();
		if($numRows > 1) {
			throw(new mysqli_sql_exception("activation (token) is not unique"));
		} else if($numRows === 0) {
			return (null);
		}

		// build up the array of 2 objects
		try {
			$row = $statement->fetch();
			$objects = array();
			$visitor = new Visitor($row["visitorId"], $row["visitorEmail"], $row["visitorFirstName"], $row["visitorLastName"], $row["visitorPhone"]);
			$invite = new Invite($row["inviteId"], $row["actionDateTime"], $row["activation"], $row["adminProfileId"], $row["approved"], $row["createDateTime"], $row["visitorId"]);
			$objects["visitor"] = $visitor;
			$objects["invite"] = $invite;
		} catch(Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new mysqli_sql_exception($exception->getMessage(), 0, $exception));
		}

			// free up memory and return the results
//			$result->free();
//			$statement->close();
			return($objects);
		}

	/**
	 * retrieves all invite requests for review
	 *
	 * @param PDO $pdo pointer to mySQL connection, by reference
	 * @return mixed invite found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public static function getPendingInvite(PDO &$pdo) {
		// handle degenerate cases
		if(gettype($pdo) !== "object" || get_class($pdo) !== "PDO") {
			throw(new PDOException("input is not a PDO object"));
		}

		// create query template
		$query = "SELECT inviteId, CONCAT(visitor.visitorFirstName, ' ', visitor.visitorLastName) AS fullName, visitor.visitorEmail, actionDateTime, activation, adminProfileId, approved, createDateTime, invite.visitorId FROM invite
		INNER JOIN visitor ON visitor.visitorId = invite.visitorId
		WHERE approved IS NULL";

		$statement = $pdo->prepare($query);

		// execute the statement
		$statement->execute();

		// get result from the SELECT query
		$statement->setFetchMode(PDO::FETCH_ASSOC);

		// grab all pending invites from mySQL
		$invites = array();
		while(($row = $statement->fetch()) !== false)
			try {
				$invites[] = $row;

			} catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new PDOException($exception->getMessage(), 0, $exception));
			}

		// free up memory and return the results
//		$result->free();
//		$statement->close();

		$numberOfInvites = count($invites);
		if($numberOfInvites === 0) {
			return(null);
		} else {
			return $invites;
		}
	}
}
?>