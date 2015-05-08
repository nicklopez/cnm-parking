<?php
/**
 * CNM Parking Log Information
 *
 * This is where parking pass log details are stored
 *
 * @author Nick Lopez <nick@nicklopezcodes.com>
 **/
class Log {
	/**
	 * id of the log entry; this is the primary key
	 **/
	private $logId;
	/**
	 * id of admin profile creating a parking pass (can be null); this is a foreign key
	 **/
	private $adminProfileId;
	/**
	 * id of the visitor requesting a parking pass (can be null); this is a foreign key
	 **/
	private $visitorId;
	/**
	 * datetime log message was recorded
	 **/
	private $logDateTime;
	/**
	 * log message details
	 **/
	private $message;

	/**
	 * constructor for the log entry
	 *
	 * @param mixed $newLogId id of the log entry
	 * @param int $newAdminProfileId id of admin profile creating a parking pass
	 * @param int $newVisitorId id of the visitor requesting a parking pass
	 * @param datetime $newLogDateTime datetime log message was recorded
	 * @param string $newMessage log message details
	 * @throws InvalidArgumentException if data types are not valid
	 * @throws RangeException if data values are out of bounds (e.g., strings too long, negative integers)
	 **/
	public function __construct($newLogId, $newAdminProfileId, $newVisitorId, $newLogDateTime, $newMessage) {
		try {
			$this->setLogId($newLogId);
			$this->setAdminProfileId($newAdminProfileId);
			$this->setVisitorId($newVisitorId);
			$this->setLogDateTime($newLogDateTime);
			$this->setMessage($newMessage);
		} catch(InvalidArgumentException $invalidArgument) {
			// rethrow the exception to the caller
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			// rethrow the exception to the caller
			throw(new RangeException($range->getMessage(), 0, $range));
		}
	}

	/**
	 * accessor method for log Id
	 *
	 * @return mixed value of log Id
	 **/
	public function getLogId() {
		return ($this->logId);
	}

	/**
	 * mutator method for log id
	 *
	 * @param mixed $newLogId new value of log id
	 * @throws InvalidArgumentException if $newLogId is not an integer
	 * @throws RangeException if $newLogId is not positive
	 **/
	public function setLogId($newLogId) {
		// base case: if the log id is null, this a new log entry without a mySQL assigned id (yet)
		if($newLogId === null) {
			$this->logId = null;
			return;
		}

		// verify the log id is valid
		$newLogId = filter_var($newLogId, FILTER_VALIDATE_INT);
		if($newLogId === false) {
			throw(new InvalidArgumentException("log id is not a valid integer"));
		}

		// verify the log id is positive
		if($newLogId <= 0) {
			throw(new RangeException("log id is not positive"));
		}

		// convert and store the log id
		$this->logId = intval($newLogId);
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

		// verify the admin profile id is null or valid
		if(empty($newAdminProfileId)) {
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
	 * @param mixed $newVisitorId id of visitor
	 * @throws InvalidArgumentException if $newVisitorId is not an integer
	 * @throws RangeException if $newVisitorId is not positive
	 **/
	public function setVisitorId($newVisitorId) {

		// verify the visitor id is null or valid
		if(empty($newVisitorId)) {
			$this->visitorId = null;
			return;
		}

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
	 * accessor method for log datetime
	 *
	 * @return datetime value of log entry
	 **/
	public function getLogDateTime() {
		return ($this->logDateTime);
	}

	/**
	 * mutator method for log datetime
	 *
	 * @param datetime $newLogDateTime logDateTime as DateTime or string
	 * @throws InvalidArgumentException if $newLogDateTime is not a valid object or string
	 * @throws RangeException if $newLogDateTime is a date that doesn't exist
	 */
	public function setLogDateTime($newLogDateTime) {
		// base case: always use current datetime
		if($newLogDateTime === null) {
			$newLogDateTime = new DateTime();
			$newLogDateTime->setTimezone(new DateTimeZone('America/Denver'));
			$this->logDateTime = $newLogDateTime;
			return;
		}
	}

	/**
	 * accessor method for log message
	 *
	 * @return string value of log message
	 **/
	public function getMessage() {
		return ($this->message);
	}

	/**
	 * mutator method for log message
	 *
	 * @param string $newMessage new value of log entry message description
	 * @throws InvalidArgumentException if $newMessage is not a string or insecure
	 * @throws RangeException if $newMessage is > 128 characters
	 **/
	public function setMessage($newMessage) {
		// verify the log message is secure
		$newMessage = trim($newMessage);
		$newMessage = filter_var($newMessage, FILTER_SANITIZE_STRING);
		if(empty($newMessage) === true) {
			throw(new InvalidArgumentException("log message is empty or insecure"));
		}

		// verify the log message will fit in the database
		if(strlen($newMessage) > 128) {
			throw(new RangeException("log message too large"));
		}

		// store the log message
		$this->message = $newMessage;
	}

	/**
	 * inserts log entry into mySQL
	 *
	 * @param PDO $pdo pointer to mySQL connection, by reference
	 * @throws PDOException when mySQL related errors occur
	 **/
	public function insert(PDO &$pdo) {
		// handle degenerate cases
		if(gettype($pdo) !== "object" || get_class($pdo) !== "PDO") {
			throw(new PDOException("input is not a PDO object"));
		}

		// enforce the log id is null (i.e., don't insert an entry that already exists)
		if($this->logId !== null) {
			throw(new PDOException("not a new log entry"));
		}

		// create query template
		$query = "INSERT INTO log(logId, adminProfileId, visitorId, logDateTime, message) VALUES(:logId, :adminProfileId, :visitorId, :logDateTime, :message)";
		$statement = $pdo->prepare($query);
		if($statement === false) {
			throw(new PDOException("unable to prepare statement"));
		}

		// bind the assignment variables to the place holders in the template
		$formattedLogDate = $this->logDateTime->format("Y-m-d H:i:s");
		$parameters = array("logId" => $this->logId, "adminProfileId" => $this->adminProfileId, "visitorId" => $this->visitorId, "logDateTime" => $formattedLogDate, "message" => $this->message);

		// execute the statement
		if($statement->execute($parameters) === false) {
			throw(new PDOException("unable to execute mySQL statement: " . $statement->errorInfo()));
		}

		// update the null logId with what mySQL just gave us
		$this->logId = $pdo->lastInsertId();
	}

	/**
	 * gets parking pass log details
	 *
	 * @param PDO $pdo pointer to mySQL connection, by reference
	 * @return mixed log details found or null if not found
	 * @throws PDOException when mySQL related errors occur
	 **/
	public static function getParkingPassLog(PDO &$pdo) {
		// handle degenerate cases
		if(gettype($pdo) !== "object" || get_class($pdo) !== "PDO") {
			throw(new PDOException("input is not a PDO object"));
		}

		// create query template
		$query = "SELECT logDateTime, CONCAT(visitor.visitorFirstName, ' ', visitor.visitorLastName, ' (', visitor.visitorEmail, ')') AS visitor, CONCAT(adminProfile.adminFirstName, ' ', adminProfile.adminLastName, ' (Admin)') AS admin, message FROM log
		LEFT OUTER JOIN visitor ON visitor.visitorId = log.visitorId
		LEFT OUTER JOIN adminProfile ON adminProfile.adminProfileId = log.adminProfileId
		WHERE logDateTime >= CURDATE()-14";
		$statement = $pdo->prepare($query);
		if($statement === false) {
			throw(new PDOException("unable to prepare statement"));
		}

		// execute the statement
		$statement->execute();
		$statement->setFetchMode(PDO::FETCH_ASSOC);

		// grab the log detail from mySQL
		$log = array();
		while(($row = $statement->fetch()) !== false) {
			try {
				$log[] = $row;
			} catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new PDOException($exception->getMessage(), 0, $exception));
			}
		}

		// Return the result
		return ($log);
	}
}
?>