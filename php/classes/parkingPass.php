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

	//constructor

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
	 * @param mixed $newParkingPassId new value of paringSpotId
	 * @throws InvalidArgumentException if $newParkingPassId is not an integer
	 * @throws RangeException if $newParkingPassId is not positive
	 */
	public function setParkingPassId($newParkingPassId) {
		// base case: if null then id is new
		if($newParkingPassId === null) {
			$this->parkingPassId = null;
			return;
		}

		// verify is integer
		$newParkingPassId = filter_var($newParkingPassId, FILTER_VALIDATE_INT);
		if($newParkingPassId === false) {
			throw(new InvalidArgumentException("parkingPassId is not a valid integer"));
		}

		// verify is positive
		if($newParkingPassId <= 0) {
			throw(new RangeException("parkingPassId is not positive"));
		}
		// convert and store
		$this ->parkingPassId = intval($newParkingPassId);
	}

	/**
	 * accessor method for LocationId
	 *
	 * @return int value of locationId
	 */
	public function getLocationId() {
		return ($this->locationId);
	}

	/**
	 * mutator method for locationIdId
	 *
	 * @param int $newLocationId new value of locationIdId
	 * @throws InvalidArgumentException if $newLocationId is null
	 * @throws InvalidArgumentException if $newLocationId is not an integer
	 * @throws RangeException if $newLocationId is not positive
	 */
	public function setLocationId($newLocationId) {
		// verify not null
		if($newLocationId === null) {
			throw(new InvalidArgumentException("locationId is null"));
		}

		// verify is integer
		$newLocationId = filter_var($newLocationId, FILTER_VALIDATE_INT);
		if($newLocationId === false) {
			throw(new InvalidArgumentException("locationId is not a valid integer"));
		}

		// verify is positive
		if($newLocationId <= 0) {
			throw(new RangeException("locationId is not positive"));
		}
		// convert and store
		$this ->locationId = intval($newLocationId);
	}

	/**
	 * accessor method for placardNumber
	 *
	 * @return string value of placardNumber
	 */
	public function getplacardNumber() {
		return($this->placardNumber);
	}

	/**
	 * mutator method for placardNumber
	 *
	 * @param string $newPlacardNumber
	 * @throw InvalidArgumentException if $newPlacardNumber is empty or insecure
	 * @throw RangeException if $newPlacardNumber is > 16 characters long
	 */
	public function setPlacardNumber($newPlacardNumber) {
		// verify is secure
		$newPlacardNumber = trim($newPlacardNumber);
		$newPlacardNumber = filter_var($newPlacardNumber, FILTER_SANITIZE_STRING);
		if(empty($newPlacardNumber) === true) {
			throw(new InvalidArgumentException("placardNumber is empty or insecure"));
		}

		// verify string length
		if(strlen($newPlacardNumber) > 16) {
			throw(new RangeException("placardNumber is too long"));
		}

		// store
		$this->placardNumber = $newPlacardNumber;
	}

	/**
	 * insert parkingSpot into mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 */
	public function insert(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !=="object" || get_class($mysqli) !=="mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// enforce parkingSpotId is null
		if($this->parkingSpotId !== null) {
			throw(new mysqli_sql_exception("parkingSpotId already exists"));
		}

		// create query template
		$query	="INSERT INTO parkingSpot(locationId, placardNumber) VALUES(?, ?)";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception(" unable to prepare statement"));
		}

		// bind the member variables to the place holders in the template
		$wasClean = $statement->bind_param("is", $this->locationId, $this->placardNumber);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("unable to bind parameters"));
		}

		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("unable to execute mySQL statement: " . $statement->error));
		}

		// update the null parkingSpot with what mySQL just gave us
		$this->parkingSpotId = $mysqli->insert_id;

		// clean up the statement
		$statement->close();
	}

	/**
	 * delete parkingSpot from mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 */
	public function delete(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !=="object" || get_class($mysqli) !=="mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// enforce parkingSpotId is not null
		if($this->parkingSpotId === null) {
			throw(new mysqli_sql_exception("parkingSpotId does not exist"));
		}

		// create query template
		$query	="DELETE FROM parkingSpot WHERE parkingSpotId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception(" unable to prepare statement"));
		}

		// bind the member variables to the place holders in the template
		$wasClean = $statement->bind_param("i", $this->parkingSpotId);
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
	 * update parkingSpot in mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 */
	public function update(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !=="object" || get_class($mysqli) !=="mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// enforce parkingSpotId is not null
		if($this->parkingSpotId === null) {
			throw(new mysqli_sql_exception("parkingSpotId does not exists"));
		}

		// create query template
		$query	= "UPDATE parkingSpot SET parkingSpotId = ?, locationId = ?, placardNumber = ? WHERE parkingSpotId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception(" unable to prepare statement"));
		}

		// bind the member variables to the place holders in the template
		$wasClean = $statement->bind_param("is", $this->locationId, $this->placardNumber);
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
	 * gets parkingSpot by parkingSpotId
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param int $parkingSpotId parkingSpotId to search for
	 * @throws mixed array of parkingSpotId 's found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 */
	public function getParkingSpotByParkingSpotId(&$mysqli, $parkingSpotId) {
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
		$query = "SELECT parkingSpotId, locationId, placardNumber FROM parkingSpot WHERE parkingSpotId LIKE ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception(" unable to prepare statement"));
		}

		// bind the member variables to the place holders in the template
		$wasClean = $statement->bind_param("is", $this->locationId, $this->placardNumber);
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

		// build array of parkingSpot
		$parkingSpots = array();
		while(($row = $result->fetch_assoc()) !== null) {
			try {
				$parkingSpot = new ParkingSpot($row["parkingSpotId"], $row["locationId"], $row["placardNumber"]);
				$parkingSpots[] = $parkingSpot;
			} catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new mysqli_sql_exception($exception->getMessage(), 0, $exception));
			}
		}

		// count the results in array and return:
		// 1) null if zero results
		// 2) the entire array if > 1 result
		$numberOfParkingSpots = count($parkingSpots);
		if($numberOfParkingSpots === 0) {
			return (null);
		} else {
			return ($parkingSpots);
		}
	}
	/**
	 * gets parkingSpot by placardNumber
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param string $placardNumber placardNumber to search for
	 * @throws mixed array of placardNumber 's found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 */
	public
	function getParkingSpotByplacardNumber(&$mysqli, $placardNumber) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// sanitize before searching
		$placardNumber = trim($placardNumber);
		$placardNumber = filter_var($placardNumber, FILTER_SANITIZE_STRING);
		if($placardNumber === false) {
			throw(new mysqli_sql_exception("placardNumber is empty or not secure"));
		}
		if($placardNumber <= 0) {
			throw(new mysqli_sql_exception("parkingSpotId is not positive"));
		}
		// create query template
		$query = "SELECT parkingSpotId, locationId, placardNumber FROM parkingSpot WHERE placardNumber LIKE ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception(" unable to prepare statement"));
		}

		// bind the member variables to the place holders in the template
		$wasClean = $statement->bind_param("is", $this->locationId, $this->placardNumber);
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

		// build array of parkingSpot
		$parkingSpots = array();
		while(($row = $result->fetch_assoc()) !== null) {
			try {
				$parkingSpot = new ParkingSpot($row["parkingSpotId"], $row["locationId"], $row["placardNumber"]);
				$parkingSpots[] = $parkingSpot;
			} catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new mysqli_sql_exception($exception->getMessage(), 0, $exception));
			}
		}

		// count the results in array and return:
		// 1) null if zero results
		// 2) the entire array if > 1 result
		$numberOfParkingSpots = count($parkingSpots);
		if($numberOfParkingSpots === 0) {
			return (null);
		} else {
			return ($parkingSpots);
		}

	}
}
?>