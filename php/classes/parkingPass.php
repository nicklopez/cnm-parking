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
	 * Foreign Key / int, not null
	 *
	 * id to reference Admin Class
	 */
	private $adminId;

	/**
	 * string, char 36,
	 *
	 * uuid
	 */
	private $uuId;

	/**
	 * datetime, not null
	 *
	 * from when is the the pass valid
	 */
	private $startDateTime;

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

		// store the tweet date
		$newDate = DateTime::createFromFormat("Y-m-d H:i:s", $newDate);
		return($newDate);
	}

	public function __construct($newParkingPassId, $newParkingSpotId, $newVehicleId, $newAdminId, $newUuId, $newStartDateTime, $newEndDateTime, $newIssuedDateTime) {
		try {
			$this->setParkingPassId($newParkingPassId);
			$this->setParkingSpotId($newParkingSpotId);
			$this->setVehicleId($newVehicleId);
			$this->setAdminId($newAdminId);
			$this->setUuId($newUuId);
			$this->setStartDateTime($newStartDateTime);
			$this->setEndDateTime($newEndDateTime);
			$this->setIssuedDateTime($newIssuedDateTime);
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
		if($newParkingPassId = null) {
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
		$this->adminId = intval(($newVehicleId));
	}

	/**
	 * accessor method for adminId
	 *
	 * @return int value of adminId
	 */
	public function getAdminId() {
		return ($this->adminId);
	}

	/**
	 * mutator method for adminId
	 *
	 * @param int $newAdminId new value of adminId
	 * @throws InvalidArgumentException if $newAdminId is not an integer or is null
	 * @throws RangeException if $newAdminId is not positive
	 */
	public function setAdminId($newAdminId) {
		// verify that adminId is valid
		$newAdminId = filter_var($newAdminId, FILTER_VALIDATE_INT);
		if($newAdminId === false) {
			throw(new InvalidArgumentException("adminId is not a valid integer or is null"));
		}

		// verify that adminId is positive
		if($newAdminId <= 0) {
			throw(new RangeException("adminId is not positive"));
		}
		// convert and store the adminId
		$this->adminId = intval(($newAdminId));
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
	// public function
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
		if(strlen($newUuId) !== 36) {
			throw(new RangeException("uuId is improper length"));
		}

		// treat uuId as string : aaaaaaaa-bbbb-cccc-dddd-eeeeeeeeeeee
		$newUuId = trim($newUuId);
		if((preg_match("/^[\da-f]{8}-[\da-f]{4}-[\da-f]{4}-[\da-f]{4}-[\da-f]{12}$/", $newUuId, $matches)) !== 1) {
			throw(new InvalidArgumentException("uuId is not in proper format"));
		}

		// store the uuId
		$this->uuId = $newUuId;
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
	 * inserts this ParkingPass in mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 */
	public function  insert(&$mysqli) {
		// handle degenerate cases
		if (gettype($mysqli) !== "object" || get_class($mysqli) !=="mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// enforce that parkingPassId is null
		if($this->parkingPassId !== null) {
			throw(new mysqli_sql_exception("not a new parkingPass"));
		}

		// create query template
		$query = "INSERT INTO parkingPass(parkingSpotId, vehicleId, adminId, uuId, startDateTime, endDateTime, issueDateTime) VALUES(?, ?, ?, ?, ?, ?, ?)";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		// bind the member variables to the place holders in the template
		$formatStart = $this->startDateTime->format("Y-m-d H:i:s");
		$formatEnd = $this->endDateTime->format("Y-m-d H:i:s");
		$formatIssued = $this->issuedDateTime->format("Y-m-d H:i:s");
		$wasClean = $statement->bind_param("iiiis", $this->parkingPassId, $this->parkingSpotId, $this->vehicleId, $this->adminId, $this->uuId, $formatStart, $formatEnd, $formatIssued);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("unable to bind paramaters"));
		}

		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("unable to execute mySQL statement: " . $statement->error));
		}

		// update the null parkingPassId with what mySQL just gave us
		$this->parkingPassId = $mysqli->insert_id;

		// clean up the statement
		$statement->close();
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
		$query	= "UPDATE parkingSpot SET parkingSpotId = ?, locationId = ?, placardNumber = ? WHERE parkingSpotId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception(" unable to prepare statement"));
		}

		// bind the member variables to the place holders in the template
		$formatStart = $this->startDateTime->format("Y-m-d H:i:s");
		$formatEnd = $this->endDateTime->format("Y-m-d H:i:s");
		$formatIssued = $this->issuedDateTime->format("Y-m-d H:i:s");
		$wasClean = $statement->bind_param("iiiis", $this->parkingPassId, $this->parkingSpotId, $this->vehicleId, $this->adminId, $this->uuId, $formatStart, $formatEnd, $formatIssued);
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
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param int $parkingPassId parkingPassId to search for
	 * @throws mixed array of parkingPassId 's found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 */
	public function getParkingPassByParkingPassId(&$mysqli, $parkingPassId) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// sanitize before searching
		$parkingPassId = trim($parkingPassId);
		$parkingPassId = filter_var($parkingPassId, FILTER_VALIDATE_INT);
		if($parkingPassId === false) {
			throw(new mysqli_sql_exception("parkingPassId is not an integer"));
		}
		if($parkingPassId <= 0) {
			throw(new mysqli_sql_exception("parkingPassId is not positive"));
		}

		// create query template
		$query = "SELECT parkingPassId, parkingSpotId, vehicleId, adminId, uuId, startDateTime, endDateTime, issuedDateTime FROM parkingPass WHERE parkingPassId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception(" unable to prepare statement"));
		}

		// bind the member variables to the place holders in the template
		$wasClean = $statement->bind_param("i", $parkingPassId);
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
		$parkingPass = array();
		while(($row = $result->fetch_assoc()) !== null) {
			try {
				$parkingPass = new ParkingPass($row["parkingPassId"], $row["parkingSpotId"], $row["vehicleId"], $row["adminId"], $row["uuId"], $row["startDateTime"], $row["endDateTime"], $row["issuedDateTime"]);
				$parkingPass[] = $parkingPass;
			} catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new mysqli_sql_exception($exception->getMessage(), 0, $exception));
			}
		}

		// count the results in array and return:
		// 1) null if zero results
		// 2) the entire array if > 1 result
		$numberOfParkingPasses = count($parkingPass);
		if($numberOfParkingPasses === 0) {
			return (null);
		} else {
			return ($parkingPass);
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
	public function getParkingPassByParkingSpotId(&$mysqli, $parkingSpotId) {
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
		$query = "SELECT parkingPassId, parkingSpotId, vehicleId, adminId, uuId, startDateTime, endDateTime, issuedDateTime FROM parkingPass WHERE parkingSpotId = ?";
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
				$parkingPass = new ParkingPass($row["parkingPassId"], $row["parkingSpotId"], $row["vehicleId"], $row["adminId"], $row["uuId"], $row["startDateTime"], $row["endDateTime"], $row["issuedDateTime"]);
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
	public function getParkingPassByVehicleId(&$mysqli, $vehicleId) {
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
		$query = "SELECT parkingPassId, parkingSpotId, vehicleId, adminId, uuId, startDateTime, endDateTime, issuedDateTime FROM parkingPass WHERE vehicleId = ?";
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
				$parkingPass = new ParkingPass($row["parkingPassId"], $row["parkingSpotId"], $row["vehicleId"], $row["adminId"], $row["uuId"], $row["startDateTime"], $row["endDateTime"], $row["issuedDateTime"]);
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
	 * gets parkingPass by adminId
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param int $adminId adminId to search for
	 * @throws mixed array of adminId 's found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 */
	public function getParkingPassByAdminId(&$mysqli, $adminId) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// sanitize before searching
		$adminId = trim($adminId);
		$adminId = filter_var($adminId, FILTER_VALIDATE_INT);
		if($adminId === false) {
			throw(new mysqli_sql_exception("adminId is not an integer"));
		}
		if($adminId <= 0) {
			throw(new mysqli_sql_exception("adminId is not positive"));
		}

		// create query template
		$query = "SELECT parkingPassId, parkingSpotId, vehicleId, adminId, uuId, startDateTime, endDateTime, issuedDateTime FROM parkingPass WHERE adminId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception(" unable to prepare statement"));
		}

		// bind the member variables to the place holders in the template
		$wasClean = $statement->bind_param("i", $adminId);
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
				$parkingPass = new ParkingPass($row["parkingPassId"], $row["parkingSpotId"], $row["vehicleId"], $row["adminId"], $row["uuId"], $row["startDateTime"], $row["endDateTime"], $row["issuedDateTime"]);
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
 * gets parkingPass by uuId
 *
 * @param resource $mysqli pointer to mySQL connection, by reference
 * @param string $uuId uuId to search for
 * @throws mixed array of uuId 's found or null if not found
 * @throws mysqli_sql_exception when mySQL related errors occur
 */
public function getParkingPassByUuId(&$mysqli, $uuId) {
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
	$query = "SELECT parkingPassId, parkingSpotId, vehicleId, adminId, uuId, startDateTime, endDateTime, issuedDateTime FROM parkingPass WHERE uuId = ?";
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
			$parkingPass = new ParkingPass($row["parkingPassId"], $row["parkingSpotId"], $row["vehicleId"], $row["adminId"], $row["uuId"], $row["startDateTime"], $row["endDateTime"], $row["issuedDateTime"]);
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
}
?>