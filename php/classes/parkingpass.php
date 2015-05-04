<?php
/**
 *Class file for parkingPass
 *
 * @author Kyle Dozier <kyle@kedlogic.com
 */

class ParkingPass {
	/**
	 * Primary Key / int, auto-inc
	 *
	 * id for ParkingPass Class
	 */
	private $parkingPassId;

	/**
	 * Foreign Key / int, not null
	 *
	 * id to reference Admin Profile Id
	 */
	private $adminProfileId;

	/**
	 * Foreign Key / int, not null
	 *
	 * id for the invite
	 */
	private $inviteId;

	/**
	 * Foreign Key / int
	 *
	 * id to reference ParkingSpot Class
	 */
	private $parkingSpotId;

	/**
	 * Foreign Key / int, not null
	 *
	 * id to reference Vehicle Class
	 */
	private $vehicleId;

	/**
	 * datetime, not null
	 *
	 * until when is the pass valid
	 */
	private $endDateTime;

	/**
	 * datetime
	 *
	 * when the pass was issued
	 */
	private $issuedDateTime;

	/**
	 * datetime, not null
	 *
	 * from when is the the pass valid
	 */
	private $startDateTime;

	/**
	 * string, char 36,
	 *
	 * uuId
	 */
	private $uuId;

	/**
	 * constructor for the parkingPass class
	 *
	 * @param $newParkingPassId
	 * @param $newAdminProfileId
	 * @param $newInviteId
	 * @param $newParkingSpotId
	 * @param $newVehicleId
	 * @param $newEndDateTime
	 * @param $newIssuedDateTime
	 * @param $newStartDateTime
	 * @param $newUuId
	 */
	public function __construct($newParkingPassId, $newAdminProfileId, $newInviteId, $newParkingSpotId, $newVehicleId, $newEndDateTime, $newIssuedDateTime, $newStartDateTime, $newUuId = null) {
		try {
			$this->setParkingPassId($newParkingPassId);
			$this->setAdminProfileId($newAdminProfileId);
			$this->setInviteId($newInviteId);
			$this->setParkingSpotId($newParkingSpotId);
			$this->setVehicleId($newVehicleId);
			$this->setEndDateTime($newEndDateTime);
			$this->setIssuedDateTime($newIssuedDateTime);
			$this->setStartDateTime($newStartDateTime);
			$this->setUuId($newUuId);
		} catch(InvalidArgumentException $invalidArgument) {
			// rethrow the exception to caller
			throw(new InvalidArgumentException($invalidArgument-> getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			// rethrow the exception to caller
			throw(new RangeException($range->getMessage(), 0, $range));
		}
	}

	/**
	 * accessor method for parkingPassId
	 *
	 * @return mixed value of parkingPassId
	 */
	public function getParkingPassId() {
		return ($this->parkingPassId);
	}

	/**
	 * mutator method for parkingPassId
	 *
	 * @param mixed $newParkingPassId new value of parkingPassId
	 * @throws InvalidArgumentException if $newParkingPassId is not an integer
	 * @throws RangeException if $newParkingPassId is not positive
	 */
	public function setParkingPassId($newParkingPassId) {
		// base case: if the parkingPassId is null, this is a new object
		if($newParkingPassId === null) {
			$this->parkingPassId = null;
			return;
		}

		// verify that parkingPassId is valid
		$newParkingPassId = filter_var($newParkingPassId, FILTER_VALIDATE_INT);
		if($newParkingPassId === false) {
			throw(new InvalidArgumentException("parkingPassId is not a valid integer"));
		}

		// verify that parkingPassId is positive
		if($newParkingPassId <= 0) {
			throw(new RangeException("parkingPassId is not positive"));
		}
		// convert and store the parkingPassId
		$this->parkingPassId = intval(($newParkingPassId));
	}

	/**
	 * accessor method for adminProfileId
	 *
	 * @return int value of adminProfileId
	 */
	public function getAdminProfileId() {
		return ($this->adminProfileId);
	}

	/**
	 * mutator method for adminProfileId
	 *
	 * @param int $newAdminProfileId new value of adminProfileId
	 * @throws InvalidArgumentException if $newAdminProfileId is not an integer or is null
	 * @throws RangeException if $newAdminProfileId is not positive
	 */
	public function setAdminProfileId($newAdminProfileId) {
		// verify that adminProfileId is valid
		$newAdminProfileId = filter_var($newAdminProfileId, FILTER_VALIDATE_INT);
		if($newAdminProfileId === false) {
			throw(new InvalidArgumentException("adminProfileId is not a valid integer or is null"));
		}

		// verify that adminProfileId is positive
		if($newAdminProfileId <= 0) {
			throw(new RangeException("adminProfileId is not positive"));
		}
		// convert and store the adminProfileId
		$this->adminProfileId = intval($newAdminProfileId);
	}

	/**
	 * accessor method for invite id
	 *
	 * @return int value of invite id
	 */
	public function getInviteId() {
		return ($this->inviteId);
	}

	/**
	 * mutator method for invite id
	 *
	 * @param int $newInviteId new value of invite id
	 * @throws InvalidArgumentException if $newInviteId is not an integer
	 * @throws RangeException if $newInviteId is not positive
	 */
	public function setInviteId($newInviteId) {
		// verify that invite id is valid
		$newInviteId = filter_var($newInviteId, FILTER_VALIDATE_INT);
		if($newInviteId === false) {
			$this->inviteId = null;
			return;
		}

		// verify that invite id is positive
		if($newInviteId <= 0) {
			throw(new RangeException("invite id is not positive"));
		}
		// convert and store the invite id
		$this->inviteId = intval($newInviteId);
	}

	/**
	 * accessor method for parkingSpotId
	 *
	 * @return int value of parkingSpotId
	 */
	public function getParkingSpotId() {
		return ($this->parkingSpotId);
	}

	/**
	 * mutator method for parkingSpotId
	 *
	 * @param int $newParkingSpotId new value of parkingSpotId
	 * @throws InvalidArgumentException if $newParkingSpotId is not an integer or is null
	 * @throws RangeException if $newParkingSpotId is not positive
	 */
	public function setParkingSpotId($newParkingSpotId) {
		// verify that parkingPassId is valid
		$newParkingSpotId = filter_var($newParkingSpotId, FILTER_VALIDATE_INT);
		if($newParkingSpotId === false) {
			throw(new InvalidArgumentException("parkingSpotId is not a valid integer or is null"));
		}

		// verify that parkingSpotId is positive
		if($newParkingSpotId <= 0) {
			throw(new RangeException("parkingSpotId is not positive"));
		}
		// convert and store the parkingSpotId
		$this->parkingSpotId = intval(($newParkingSpotId));
	}

	/**
	 * accessor method for vehicleId
	 *
	 * @return int value of vehicleId
	 */
	public function getVehicleId() {
		return ($this->vehicleId);
	}

	/**
	 * mutator method for vehicleId
	 *
	 * @param int $newVehicleId new value of vehicleId
	 * @throws InvalidArgumentException if $newVehicleId is not an integer or is null
	 * @throws RangeException if $newVehicleId is not positive
	 */
	public function setVehicleId($newVehicleId) {
		// verify that vehicleId is valid
		$newVehicleId = filter_var($newVehicleId, FILTER_VALIDATE_INT);
		if($newVehicleId === false) {
			throw(new InvalidArgumentException("vehicleId is not a valid integer or is null"));
		}

		// verify that vehicleId is positive
		if($newVehicleId <= 0) {
			throw(new RangeException("vehicleId is not positive"));
		}
		// convert and store the vehicleId
		$this->vehicleId = intval($newVehicleId);
	}

	/**
	 * accessor method for endDateTime
	 *
	 * @return DateTime value of endDateTime
	 */
	public function getEndDateTime() {
		return($this->endDateTime);
	}

	/**
	 * mutator method for endDateTime
	 *
	 * @param mixed $newEndDateTime endDateTime as DateTime or string
	 * @throws InvalidArgumentException if $newEndDateTime is not a valid object or string
	 * @throws RangeException if $newEndDateTime is a date that doesn't exist
	 */
	public function setEndDateTime($newEndDateTime) {
		// verify not null
		if($newEndDateTime === null) {
			throw(new InvalidArgumentException("endDateTime cannot be null"));
		}

		// store the endDateTime
		try {
			$newEndDateTime = self::sanitizeDate($newEndDateTime);
			$this->endDateTime = $newEndDateTime;
		} catch(InvalidArgumentException $invalidArgument) {
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			throw(new RangeException($range->getMessage(), 0, $range));
		}
	}

	/**
	 * accessor method for issuedDateTime
	 *
	 * @return DateTime value of issuedDateTime
	 */
	public function getIssuedDateTime() {
		return($this->issuedDateTime);
	}

	/**
	 * mutator method for issuedDateTime
	 *
	 * @param mixed $newIssuedDateTime issuedDateTime as DateTime or string(or current datetime if null)
	 * @throws InvalidArgumentException if $newIssuedDateTime is not a valid object or string
	 * @throws RangeException if $newIssuedDateTime is a date that doesn't exist
	 */
	public function setIssuedDateTime($newIssuedDateTime) {
		// base case: if issuedDateTime is null, use current DateTime(NOW)
		if($newIssuedDateTime === null) {
			$this->issuedDateTime = new DateTime();
			return;
		}

		// store the issuedDateTime
		try {
			$newIssuedDateTime = self::sanitizeDate($newIssuedDateTime);
			$this->issuedDateTime = $newIssuedDateTime;
		} catch(InvalidArgumentException $invalidArgument) {
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			throw(new RangeException($range->getMessage(), 0, $range));
		}
	}

	/**
	 * accessor method for startDateTime
	 *
	 * @return DateTime value of startDateTime
	 */
	public function getStartDateTime() {
		return($this->startDateTime);
	}

	/**
	 * mutator method for startDateTime
	 *
	 * @param mixed $newStartDateTime startDateTime as DateTime or string
	 * @throws InvalidArgumentException if $newStartDateTime is not a valid object or string
	 * @throws RangeException if $newStartDateTime is a date that doesn't exist
	 */
	public function setStartDateTime($newStartDateTime) {
		// verify not null
		if($newStartDateTime === null) {
			throw(new InvalidArgumentException("startDateTime cannot be null"));
		}

		// store the startDateTime
		try {
			$newStartDateTime = self::sanitizeDate($newStartDateTime);
			$this->startDateTime = $newStartDateTime;
		} catch(InvalidArgumentException $invalidArgument) {
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			throw(new RangeException($range->getMessage(), 0, $range));
		}
	}

	/**
	 * accessor method for uuId
	 *
	 * @return mixed value of uuId
	 */
	public function getUuId() {
		return ($this->uuId);
	}

	/**
	 * mutator method for uuId
	 *
	 * @param mixed $newUuId uuId as string (or null if new)
	 * @throws InvalidArgumentException  if $newUuID is insecure or in improper format
	 * @throws RangeException if $newUuID is of improper length
	 */
	public function setUuId($newUuId) {
		// base case: if uuId is null, this is new object
		if($newUuId === null) {
			$this->uuId = null;
			return;
		}

		// verify is secure
		$newUuId = trim($newUuId);
		$newUuId = filter_var($newUuId, FILTER_SANITIZE_STRING);
		if(empty($newUuId) === true) {
			throw(new InvalidArgumentException("uuId is insecure"));
		}

		// verify string length
		//if(strlen($newUuId) != 36) {
		//	throw(new RangeException("uuId is improper length"));
		//}

		// treat uuId as string : aaaaaaaa-bbbb-cccc-dddd-eeeeeeeeeeee
		$newUuId = trim($newUuId);
		if((preg_match("/^[\da-f]{8}-[\da-f]{4}-[\da-f]{4}-[\da-f]{4}-[\da-f]{12}$/", $newUuId)) !== 1) {
			throw(new InvalidArgumentException("uuId is not in proper format"));
		}

		// store the uuId
		$this->uuId = $newUuId;
	}

	/**
	 * inserts this ParkingPass in mySQL
	 *
	 * @param PDO $pdo pointer to mySQL connection, by reference
	 * @throws PDOException when mySQL related errors occur
	 */
	public function  insert(PDO &$pdo) {
		// handle degenerate cases
		if (gettype($pdo) !== "object" || get_class($pdo) !=="PDO") {
			throw(new PDOException("input is not a PDO object"));
		}

		// enforce that parkingPassId is null
		if($this->parkingPassId !== null) {
			throw(new PDOException("not a new parkingPass"));
		}

		// create query template
		$query = "INSERT INTO parkingPass(adminProfileId, inviteId, parkingSpotId, vehicleId, endDateTime, issuedDateTime, startDateTime, uuId) VALUES(:adminProfileId, :inviteId, :parkingSpotId, :vehicleId, :endDateTime, :issuedDateTime, :startDateTime, UUID())";
		$statement = $pdo->prepare($query);
		if($statement === false) {
			throw(new PDOException("unable to prepare statement"));
		}

		// bind the member variables to the place holders in the template
		$formatEnd = $this->endDateTime->format("Y-m-d H:i:s");
		$formatIssued = $this->issuedDateTime->format("Y-m-d H:i:s");
		$formatStart = $this->startDateTime->format("Y-m-d H:i:s");
		$parameters = array("adminProfileId" => $this->adminProfileId, "inviteId" => $this->inviteId, "parkingSpotId" => $this->parkingSpotId, "vehicleId" => $this->vehicleId, "endDateTime" => $formatEnd, "issuedDateTime" => $formatIssued,  "startDateTime" => $formatStart);

		// execute the statement
		$statement->execute($parameters);

		// update the null parkingPassId with what mySQL just gave us
		$this->parkingPassId = $pdo->lastInsertId();

		// regrab from mySQl to store the generated uuId
		$parkingPass = self::getParkingPassByParkingPassId($pdo, $this->parkingPassId);
		$this->setUuId($parkingPass->getUuId());
	}

	/**
	 * delete parkingPass from mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 */
	public function delete(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !=="object" || get_class($mysqli) !=="mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// enforce parkingPassId is not null
		if($this->parkingPassId === null) {
			throw(new mysqli_sql_exception("parkingPassId does not exist"));
		}

		// create query template
		$query	="DELETE FROM parkingPass WHERE parkingPassId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception(" unable to prepare statement"));
		}

		// bind the member variables to the place holders in the template
		$wasClean = $statement->bind_param("i", $this->parkingPassId);
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
	 * update parkingPass in mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 */
	public function update(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !=="object" || get_class($mysqli) !=="mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// enforce parkingPassId is not null
		if($this->parkingPassId === null) {
			throw(new mysqli_sql_exception("parkingPassId does not exists"));
		}

		// create query template
		$query	= "UPDATE parkingPass SET adminProfileId = ?, inviteId = ?, parkingSpotId = ?, vehicleId  = ?, endDateTime = ?, issuedDateTime = ?, startDateTime = ?, uuId = ? WHERE parkingPassId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception(" unable to prepare statement"));
		}

		// bind the member variables to the place holders in the template
		$formatEnd = $this->endDateTime->format("Y-m-d H:i:s");
		$formatIssued = $this->issuedDateTime->format("Y-m-d H:i:s");
		$formatStart = $this->startDateTime->format("Y-m-d H:i:s");
		$wasClean = $statement->bind_param("iiissssi", $this->adminProfileId, $this->inviteId, $this->parkingSpotId, $this->vehicleId, $formatEnd, $formatIssued, $formatStart, $this->uuId, $this->parkingPassId);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("unable to bind paramaters"));
		}

		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("unable to execute mySQL statement: " . $statement->error));
		}

		// clean up the statement
		$statement->close();
	}

	/**
	 * gets parkingPass by parkingPassId
	 *
	 * @param PDO $pdo pointer to mySQL connection, by reference
	 * @param int $parkingPassId parkingPassId to search for
	 * @throws mixed array of parkingPassId 's found or null if not found
	 * @throws PDOException when mySQL related errors occur
	 */
	public static function getParkingPassByParkingPassId(PDO &$pdo, $parkingPassId) {
		// handle degenerate cases
		if(gettype($pdo) !== "object" || get_class($pdo) !== "PDO") {
			throw(new PDOException("input is not a PDO object"));
		}

		// sanitize before searching
		$parkingPassId = trim($parkingPassId);
		$parkingPassId = filter_var($parkingPassId, FILTER_VALIDATE_INT);
		if($parkingPassId === false) {
			throw(new PDOException("parkingPassId is not an integer"));
		}
		if($parkingPassId <= 0) {
			throw(new PDOException("parkingPassId is not positive"));
		}

		// create query template
		$query = "SELECT parkingPassId, adminProfileId, inviteId, parkingSpotId, vehicleId, endDateTime, issuedDateTime, startDateTime, uuId FROM parkingPass WHERE parkingPassId = :parkingPassId";
		$statement = $pdo->prepare($query);
		if($statement === false) {
			throw(new PDOException(" unable to prepare statement"));
		}

		// bind the member variables to the place holders in the template
		$parameters = array("parkingPassId" => $parkingPassId);

		// execute the statement
		$statement->execute($parameters);
		$statement->setFetchMode(PDO::FETCH_ASSOC);

		// build parkingPass
		try {
			$row = $statement->fetch();
			if($row !== null) {
				$parkingPass = new ParkingPass($row["parkingPassId"], $row["adminProfileId"], $row["inviteId"], $row["parkingSpotId"], $row["vehicleId"], $row["endDateTime"], $row["issuedDateTime"], $row["startDateTime"], $row["uuId"]);
				return ($parkingPass);
			}
		} catch(Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new PDOException($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * gets parkingPass by adminProfileId
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param int $adminProfileId adminProfileId to search for
	 * @throws mixed array of adminProfileId 's found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 */
	public static function getParkingPassByAdminProfileId(&$mysqli, $adminProfileId) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// sanitize before searching
		$adminProfileId = trim($adminProfileId);
		$adminProfileId = filter_var($adminProfileId, FILTER_VALIDATE_INT);
		if($adminProfileId === false) {
			throw(new mysqli_sql_exception("adminProfileId is not an integer"));
		}
		if($adminProfileId <= 0) {
			throw(new mysqli_sql_exception("adminProfileId is not positive"));
		}

		// create query template
		$query = "SELECT parkingPassId, adminProfileId, inviteId, parkingSpotId, vehicleId, endDateTime, issuedDateTime, startDateTime, uuId FROM parkingPass WHERE adminProfileId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception(" unable to prepare statement"));
		}

		// bind the member variables to the place holders in the template
		$wasClean = $statement->bind_param("i", $adminProfileId);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("unable to bind parameters"));
		}

		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("unable to execute mySQL statement: " . $statement->error));
		}

		// get result from SELECT query
		$result = $statement->get_result();
		if($result === false) {
			throw(new mysqli_sql_exception("unable to get result set"));
		}

		// build array of parkingPass
		$parkingPasses = array();
		while(($row = $result->fetch_assoc()) !== null) {
			try {
				$parkingPass = new ParkingPass($row["parkingPassId"], $row["adminProfileId"], $row["inviteId"], $row["parkingSpotId"], $row["vehicleId"], $row["endDateTime"], $row["issuedDateTime"], $row["startDateTime"], $row["uuId"]);
				$parkingPasses[] = $parkingPass;
			} catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new mysqli_sql_exception($exception->getMessage(), 0, $exception));
			}
		}

		// count the results in array and return:
		// 1) null if zero results
		// 2) the entire array if > 1 result
		$numberOfParkingPasses = count($parkingPasses);
		if($numberOfParkingPasses === 0) {
			return (null);
		} else {
			return ($parkingPasses);
		}
	}

	/**
	 * gets parkingPass by parkingSpotId
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param int $parkingSpotId parkingSpotId to search for
	 * @throws mixed array of parkingSpotId 's found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 */
	public static function getParkingPassByParkingSpotId(&$mysqli, $parkingSpotId) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// sanitize before searching
		$parkingSpotId = trim($parkingSpotId);
		$parkingSpotId = filter_var($parkingSpotId, FILTER_VALIDATE_INT);
		if($parkingSpotId === false) {
			throw(new mysqli_sql_exception("parkingSpotId is not an integer"));
		}
		if($parkingSpotId <= 0) {
			throw(new mysqli_sql_exception("parkingSpotId is not positive"));
		}

		// create query template
		$query = "SELECT parkingPassId, adminProfileId, parkingSpotId, vehicleId, endDateTime, issuedDateTime, startDateTime, uuId FROM parkingPass WHERE parkingSpotId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception(" unable to prepare statement"));
		}

		// bind the member variables to the place holders in the template
		$wasClean = $statement->bind_param("i", $parkingSpotId);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("unable to bind paramaters"));
		}

		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("unable to execute mySQL statement: " . $statement->error));
		}

		// get result from SELECT query
		$result = $statement->get_result();
		if($result === false) {
			throw(new mysqli_sql_exception("unable to get result set"));
		}

		// build array of parkingPass
		$parkingPasses = array();
		while(($row = $result->fetch_assoc()) !== null) {
			try {
				$parkingPass = new ParkingPass($row["parkingPassId"], $row["adminProfileId"], $row["parkingSpotId"], $row["vehicleId"], $row["endDateTime"], $row["issuedDateTime"], $row["startDateTime"], $row["uuId"]);
				$parkingPasses[] = $parkingPass;
			} catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new mysqli_sql_exception($exception->getMessage(), 0, $exception));
			}
		}

		// count the results in array and return:
		// 1) null if zero results
		// 2) the entire array if > 1 result
		$numberOfParkingPasses = count($parkingPasses);
		if($numberOfParkingPasses === 0) {
			return (null);
		} else {
			return($parkingPasses);
		}
	}

	/**
	 * gets parkingPass by vehicleId
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param int $vehicleId vehicleId to search for
	 * @throws mixed array of vehicleId 's found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 */
	public static function getParkingPassByVehicleId(&$mysqli, $vehicleId) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// sanitize before searching
		$vehicleId = trim($vehicleId);
		$vehicleId = filter_var($vehicleId, FILTER_VALIDATE_INT);
		if($vehicleId === false) {
			throw(new mysqli_sql_exception("vehicleId is not an integer"));
		}
		if($vehicleId <= 0) {
			throw(new mysqli_sql_exception("vehicleId is not positive"));
		}

		// create query template
		$query = "SELECT parkingPassId, adminProfileId, parkingSpotId, vehicleId, endDateTime, issuedDateTime, startDateTime, uuId FROM parkingPass WHERE vehicleId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception(" unable to prepare statement"));
		}

		// bind the member variables to the place holders in the template
		$wasClean = $statement->bind_param("i", $vehicleId);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("unable to bind paramaters"));
		}

		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("unable to execute mySQL statement: " . $statement->error));
		}

		// get result from SELECT query
		$result = $statement->get_result();
		if($result === false) {
			throw(new mysqli_sql_exception("unable to get result set"));
		}

		// build array of parkingPass
		$parkingPasses = array();
		while(($row = $result->fetch_assoc()) !== null) {
			try {
				$parkingPass = new ParkingPass($row["parkingPassId"], $row["adminProfileId"], $row["parkingSpotId"], $row["vehicleId"], $row["endDateTime"], $row["issuedDateTime"], $row["startDateTime"], $row["uuId"]);
				$parkingPasses[] = $parkingPass;
			} catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new mysqli_sql_exception($exception->getMessage(), 0, $exception));
			}
		}

		// count the results in array and return:
		// 1) null if zero results
		// 2) the entire array if > 1 result
		$numberOfParkingPasses = count($parkingPasses);
		if($numberOfParkingPasses === 0) {
			return (null);
		} else {
			return ($parkingPasses);
		}
	}

	/**
	 * gets parkingPass by issuedDateTime
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param string $issuedDateTime issuedDateTime to search for
	 * @throws mixed array of issuedDateTime 's found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 */
	public static function getParkingPassByIssuedDateTime(&$mysqli, $issuedDateTime) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// sanitize before searching - Using static function
		try {
			$issuedDateTime = self::sanitizeDate($issuedDateTime);
			// clone and assign sunrise/sunset to remove Time requirement
			$sunrise = clone $issuedDateTime;
			$sunrise->setTime(0, 0, 0);
			$sunset = clone $issuedDateTime;
			$sunset->setTime(23, 59, 59);
		} catch(InvalidArgumentException $invalidArgument) {
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			throw(new RangeException($range->getMessage(), 0, $range));
		}

		// create query template
		$query = "SELECT parkingPassId, adminProfileId, parkingSpotId, vehicleId, endDateTime, issuedDateTime, startDateTime, uuId FROM parkingPass WHERE issuedDateTime >= ? AND issuedDateTime <= ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception(" unable to prepare statement"));
		}

		// bind the member variables to the place holders in the template
		$sunrise = $sunrise->format("Y-m-d H:i:s");
		$sunset = $sunset->format("Y-m-d H:i:s");
		$wasClean = $statement->bind_param("ss", $sunrise, $sunset);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("unable to bind paramaters"));
		}
		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("unable to execute mySQL statement: " . $statement->error));
		}

		// get result from SELECT query
		$result = $statement->get_result();
		if($result === false) {
			throw(new mysqli_sql_exception("unable to get result set"));
		}

		// build array of parkingPass
		$parkingPasses = array();
		while(($row = $result->fetch_assoc()) !== null) {
			try {
				$parkingPass = new ParkingPass($row["parkingPassId"], $row["adminProfileId"], $row["parkingSpotId"], $row["vehicleId"], $row["endDateTime"], $row["issuedDateTime"], $row["startDateTime"], $row["uuId"]);
				$parkingPasses[] = $parkingPass;
			} catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new mysqli_sql_exception($exception->getMessage(), 0, $exception));
			}
		}

		// count the results in array and return:
		// 1) null if zero results
		// 2) the entire array if > 1 result
		$numberOfParkingPasses = count($parkingPasses);
		if($numberOfParkingPasses === 0) {
			return (null);
		} else {
			return ($parkingPasses);
		}
	}

	/**
	 * gets parkingPass by DateTime range of startDateTime - endDateTime
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param string $startDateTime, $endDateTime startDateTime to endDateTime range to search for
	 * @throws mixed array of DateTime Ranges found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 */
	public static function getParkingPassByStartDateTimeEndDateTimeRange(&$mysqli, $startDateTime, $endDateTime) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// sanitize before searching - Using static function
		try {
			$sunrise = self::sanitizeDate($startDateTime);
			$sunset = self::sanitizeDate($endDateTime);
		} catch(InvalidArgumentException $invalidArgument) {
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			throw(new RangeException($range->getMessage(), 0, $range));
		}

		// create query template
		$query = "SELECT parkingPassId, adminProfileId, parkingSpotId, vehicleId, endDateTime, issuedDateTime, startDateTime, uuId FROM parkingPass WHERE startDateTime >= ? AND endDateTime <= ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception(" unable to prepare statement"));
		}

		// bind the member variables to the place holders in the template
		$sunrise = $sunrise->format("Y-m-d H:i:s");
		$sunset = $sunset->format("Y-m-d H:i:s");
		$wasClean = $statement->bind_param("ss", $sunrise, $sunset);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("unable to bind parameters"));
		}
		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("unable to execute mySQL statement: " . $statement->error));
		}

		// get result from SELECT query
		$result = $statement->get_result();
		if($result === false) {
			throw(new mysqli_sql_exception("unable to get result set"));
		}

		// build array of parkingPass
		$parkingPasses = array();
		while(($row = $result->fetch_assoc()) !== null) {
			try {
				$parkingPass = new ParkingPass($row["parkingPassId"], $row["adminProfileId"], $row["parkingSpotId"], $row["vehicleId"], $row["endDateTime"], $row["issuedDateTime"], $row["startDateTime"], $row["uuId"]);
				$parkingPasses[] = $parkingPass;
			} catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new mysqli_sql_exception($exception->getMessage(), 0, $exception));
			}
		}

		// count the results in array and return:
		// 1) null if zero results
		// 2) the entire array if > 1 result
		$numberOfParkingPasses = count($parkingPasses);
		if($numberOfParkingPasses === 0) {
			return (null);
		} else {
			return ($parkingPasses);
		}
	}

	/**
	 * gets parkingPass 	by uuId
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param string $uuId uuId to search for
	 * @throws mixed array of uuId 's found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 */
	public static function getParkingPassByUuId(&$mysqli, $uuId) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// sanitize before searching
		$uuId = trim($uuId);
		$uuId = filter_var($uuId, FILTER_SANITIZE_STRING);
		if($uuId === false ||  strlen($uuId) !== 36) {
			throw(new mysqli_sql_exception("uuId is invalid or not secure"));
		}

		// create query template
		$query = "SELECT parkingPassId, adminProfileId, parkingSpotId, vehicleId, endDateTime, issuedDateTime, startDateTime, uuId FROM parkingPass WHERE uuId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception(" unable to prepare statement"));
		}

		// bind the member variables to the place holders in the template
		$wasClean = $statement->bind_param("i", $uuId);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("unable to bind parameters"));
		}

		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("unable to execute mySQL statement: " . $statement->error));
		}

		// get result from SELECT query
		$result = $statement->get_result();
		if($result === false) {
			throw(new mysqli_sql_exception("unable to get result set"));
		}

		// build array of parkingPass
		$parkingPasses = array();
		while(($row = $result->fetch_assoc()) !== null) {
			try {
				$parkingPass = new ParkingPass($row["parkingPassId"], $row["adminProfileId"], $row["parkingSpotId"], $row["vehicleId"], $row["endDateTime"], $row["issuedDateTime"], $row["startDateTime"], $row["uuId"]);
				$parkingPasses[] = $parkingPass;
			} catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new mysqli_sql_exception($exception->getMessage(), 0, $exception));
			}
		}

		// count the results in array and return:
		// 1) null if zero results
		// 2) the entire array if > 1 result
		$numberOfParkingPasses = count($parkingPasses);
		if($numberOfParkingPasses === 0) {
			return (null);
		} else {
			return ($parkingPasses);
		}
	}

	/**
	 * Verify Availability: Searches for and returns 1 placard number that is free(no conflicts) during given datetime range.
	 *
	 * @param PDO $pdo
	 * @param $location
	 * @param $arrival
	 * @param $departure
	 */
	public static function getParkingPassAvailability(PDO &$pdo, $location, $arrival, $departure) {
		// handle degenerate cases
		if(gettype($pdo) !== "object" || get_class($pdo) !== "PDO") {
			throw(new PDOException("input is not a PDO object"));
		}

		// sanitize dates before searching - Using static function
		try {
			$sunrise = self::sanitizeDate($arrival);
			$sunset = self::sanitizeDate($departure);
		} catch(InvalidArgumentException $invalidArgument) {
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			throw(new RangeException($range->getMessage(), 0, $range));
		}

		// sanitize the locationId before searching
		$location = filter_var($location, FILTER_VALIDATE_INT);
		if($location === false) {
			throw(new mysqli_sql_exception("location id is not an integer"));
		}
		if($location <= 0) {
			throw(new mysqli_sql_exception("location id is not positive"));
		}

		// query available parking spots (placards)
		$query = "SELECT parkingSpotId FROM parkingSpot WHERE parkingSpotId NOT IN
		(SELECT parkingSpot.parkingSpotId FROM parkingSpot INNER JOIN parkingPass ON parkingSpot.parkingSpotId = parkingPass.parkingSpotId
		WHERE (:sunrise >= startDateTime AND :sunset <= endDateTime AND locationId = :locationId)
		OR (:sunrise < startDateTime AND :sunset > endDateTime AND locationId = :locationId)
		OR (:sunrise < startDateTime AND :sunset > startDateTime AND locationId = :locationId)
		OR (:sunrise <= startDateTime AND :sunset > enddatetime AND locationId = :locationId)
		UNION ALL
		SELECT parkingSpot.parkingSpotId FROM parkingSpot INNER JOIN placardAssignment ON parkingSpot.parkingSpotId = placardAssignment.parkingSpotId
		WHERE ISNULL(returnDateTime))
		AND locationId = :locationId
		LIMIT 1";

		$statement = $pdo->prepare($query);
		if($statement === false) {
			throw(new PDOException(" unable to prepare statement"));
		}

		// bind the member variables to the place holders in the template
		$sunrise = $sunrise->format("Y-m-d H:i:s");
		$sunset = $sunset->format("Y-m-d H:i:s");
		$parameters = array("locationId" => $location, "sunrise" => $sunrise, "sunset" => $sunset);

		// execute the statement
		$statement->execute($parameters);
		$statement->setFetchMode(PDO::FETCH_ASSOC);

		// build array of result
		$result = $statement->fetch();

		// return result
		return($result);
	}

	/**
	 * gets parkingPass and visitor data by DateTime range of startDateTime - endDateTime
	 *
	 * @param PDO $pdo pointer to mySQL connection, by reference
	 * @param datetime $startDateTime, $endDateTime startDateTime to endDateTime range to search for
	 * @return mixed visitor parking data found or null if not found
	 * @throws RangeException if data values are out of bounds (e.g., strings too long, negative integers)
	 * @throws PDOException when mySQL related errors occur
	 */
	public static function getVisitorParkingDataByDateRange(PDO &$pdo, $startDateTime, $endDateTime) {
		// handle degenerate cases
		if(gettype($pdo) !== "object" || get_class($pdo) !== "PDO") {
			throw(new PDOException("input is not a PDO object"));
		}

		// sanitize before searching - Using static function
		try {
			$sunrise = self::sanitizeShortDate($startDateTime);
			$sunset = self::sanitizeShortDate($endDateTime);
		} catch(InvalidArgumentException $invalidArgument) {
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			throw(new RangeException($range->getMessage(), 0, $range));
		}

		// create query template
		$query = "SELECT adminFirstName, adminLastName, locationDescription, locationNote, placardNumber, vehiclePlateNumber, visitorFirstName, visitorLastName, endDateTime, issuedDateTime, startDateTime FROM parkingPass
		INNER JOIN vehicle ON vehicle.vehicleId = parkingPass.vehicleId
		INNER JOIN adminProfile ON adminProfile.adminProfileId = parkingPass.adminProfileId
		INNER JOIN parkingSpot ON parkingSpot.parkingSpotId = parkingPass.parkingSpotId
		INNER JOIN location ON location.locationId = parkingSpot.locationId
		INNER JOIN visitor ON visitor.visitorId = vehicle.visitorId
		WHERE startDateTime >= :startDateTime AND endDateTime <= :endDateTime
		ORDER BY startDateTime";
		$statement = $pdo->prepare($query);
		if($statement === false) {
			throw(new PDOException(" unable to prepare statement"));
		}

		// bind the member variables to the place holders in the template
		$sunrise = $sunrise->format("Y-m-d 00:00:00");
		$sunset = $sunset->format("Y-m-d 23:59:59");
		$parameters = array("startDateTime" => $sunrise, "endDateTime" => $sunset);

		// execute the statement
		$statement->execute($parameters);
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		// get result from SELECT query

		// build array of parkingPass
		$visitorData = array();
		while(($row = $statement->fetch()) !== false) {
			try {
				$visitorData[] = $row;
			} catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new PDOException($exception->getMessage(), 0, $exception));
			}
		}

		// count the results in array and return:
		// 1) null if zero results
		// 2) the entire array if > 1 result
		$recordCount = count($visitorData);
		if($recordCount === 0) {
			return (null);
		} else {
			return ($visitorData);
		}
	}

	/**
	 * gets available placards; physical placard to be issued
	 *
	 * @param PDO $pdo pointer to mySQL connection, by reference
	 * @param int $location id of the location to search for
	 * @param datetime $arrival, begin date time range of on-site visit
	 * @param datetime $departure end date time range of on-site visit
	 * @return mixed available placards found or null if not found
	 * @throws RangeException if data values are out of bounds (e.g., strings too long, negative integers)
	 * @throws PDOException when mySQL related errors occur
	 */
	public static function getAvailablePlacards(PDO &$pdo, $location, $arrival, $departure) {
		// handle degenerate cases
		if(gettype($pdo) !== "object" || get_class($pdo) !== "PDO") {
			throw(new PDOException("input is not a PDO object"));
		}

		// sanitize dates before searching - Using static function
		try {
			$sunrise = self::sanitizeShortDate($arrival);
			$sunset = self::sanitizeShortDate($departure);
		} catch(InvalidArgumentException $invalidArgument) {
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			throw(new RangeException($range->getMessage(), 0, $range));
		}

		// sanitize the locationId before searching
		$location = filter_var($location, FILTER_VALIDATE_INT);
		if($location === false) {
			throw(new PDOException("location id is not an integer"));
		}
		if($location <= 0) {
			throw(new PDOException("location id is not positive"));
		}

		// query available parking spots (placards)
		$query = "SELECT parkingSpotId, placardNumber, locationId FROM parkingSpot WHERE parkingSpotId NOT IN
		(SELECT parkingSpot.parkingSpotId FROM parkingSpot INNER JOIN parkingPass ON parkingSpot.parkingSpotId = parkingPass.parkingSpotId
		WHERE (:sunrise >= startDateTime AND :sunset <= endDateTime AND locationId = :locationId)
		OR (:sunrise < startDateTime AND :sunset > endDateTime AND locationId = :locationId)
		OR (:sunrise < startDateTime AND :sunset > startDateTime AND locationId = :locationId)
		OR (:sunrise <= startDateTime AND :sunset > enddatetime AND locationId = :locationId)
		UNION ALL
		SELECT parkingSpot.parkingSpotId FROM parkingSpot INNER JOIN placardAssignment ON parkingSpot.parkingSpotId = placardAssignment.parkingSpotId
		WHERE ISNULL(returnDateTime))
		AND locationId = :locationId";

		$statement = $pdo->prepare($query);
		if($statement === false) {
			throw(new PDOException("unable to prepare statement"));
		}

		// bind the member variables to the place holders in the template
		$formattedSunrise = $sunrise->format("Y-m-d H:i:s");
		$formattedSunset = $sunset->format("Y-m-d H:i:s");
		$parameters = array("locationId" => $location, "sunrise" => $formattedSunrise, "sunset" => $formattedSunset);

		// execute the statement
		$statement->execute($parameters);
		$statement->setFetchMode(PDO::FETCH_ASSOC);

		// build array of result
		$placards = array();
		while(($row = $statement->fetch()) !== false) {
			try {
				$placards[] = $row;
			} catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new PDOException($exception->getMessage(), 0, $exception));
			}
		}

		// return result
		return ($placards);
	}

	/**
	 * gets total assigned parking passes, grouped by date
	 *
	 * @param PDO $pdo pointer to mySQL connection, by reference
	 * @param string $startDateTime startDateTime range to search for
	 * @param string $endDateTime endDateTime range to search for
	 * @return mixed total number of parking passes if found or null if not found
	 * @throws RangeException if data values are out of bounds (e.g., strings too long, negative integers)
	 * @throws PDOException when mySQL related errors occur
	 */
	public static function getParkingPassCountByDate(&$pdo, $startDateTime, $endDateTime) {
		// handle degenerate cases
		if(gettype($pdo) !== "object" || get_class($pdo) !== "PDO") {
			throw(new PDOException("input is not a PDO object"));
		}

		// sanitize before searching - Using static function
		try {
			$startDateTime = self::sanitizeDate($startDateTime);
			$endDateTime = self::sanitizeDate($endDateTime);
		} catch(InvalidArgumentException $invalidArgument) {
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			throw(new RangeException($range->getMessage(), 0, $range));
		}

		// create query template
		$query = "SELECT count(startDateTime) AS title, DATE_FORMAT(startDateTime, '%Y-%m-%d') AS start FROM parkingPass WHERE startDateTime >= :startDateTime AND endDateTime <= :endDateTime
		GROUP BY start";
		$statement = $pdo->prepare($query);
		if($statement === false) {
			throw(new PDOException(" unable to prepare statement"));
		}

		// bind the member variables to the place holders in the template
		$startDateTime = $startDateTime->format("Y-m-d H:i:s");
		$endDateTime = $endDateTime->format("Y-m-d H:i:s");
		$parameters = array("startDateTime" => $startDateTime, "endDateTime" => $endDateTime);

		// execute the statement
		$statement->execute($parameters);
		$statement->setFetchMode(PDO::FETCH_ASSOC);

		// build array of parkingPass
		$passCount = array();
		while(($row = $statement->fetch()) !== false) {
			try {
				if(($row["title"]) === "0") {
					return null;
				} else {
					$passCount[] = $row;
				}
			} catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new PDOException($exception->getMessage(), 0, $exception));
			}
		}

		return ($passCount);
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

	/**
	 * sanitizes a date either as a DateTime object or mySQL date string
	 *
	 * @param mixed $newDate short date to sanitize
	 * @return DateTime sanitized DateTime object
	 * @throws InvalidArgumentException if the date is in an invalid format
	 * @throws RangeException if the date is not a Gregorian date
	 **/
	public static function sanitizeShortDate($newDate) {
		// base case: if the date is a DateTime object, there's no work to be done
		if(is_object($newDate) === true && get_class($newDate) === "DateTime") {
			return($newDate);
		}

		// treat the date as a mySQL date string: n-j-y
		$newDate = trim($newDate);
		if((preg_match("/^(\d{1,2})-(\d{1,2})-(\d{4})$/", $newDate, $matches)) !== 1) {
			throw(new InvalidArgumentException("date is not a valid date"));
		}

		// verify the date is really a valid calendar date
		$month  = intval($matches[1]);
		$day    = intval($matches[2]);
		$year   = intval($matches[3]);
		if(checkdate($month, $day, $year) === false) {
			throw(new RangeException("date $newDate is not a Gregorian date"));
		}

		// store the DateTime value
		$newDate = DateTime::createFromFormat("m-d-Y", $newDate);
		return($newDate);
	}
}
?>