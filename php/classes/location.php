<?php
/**
 * CNM Parking Location Information
 *
 * This is where location information is stored
 *
 * @author Nick Lopez <nick@nicklopezcodes.com>
 **/
class Location {
	/**
	 * id for CNM parking location; this is the primary key
	 **/
	private $locationId;
	/**
	 * description of CNM parking location
	 **/
	private $locationDescription;
	/**
	 * additional notes (optional) of CNM parking location
	 **/
	private $locationNote;
	/**
	 * gps coordinates (latitude) of CNM parking location
	 **/
	private $latitude;
	/**
	 * gps coordinates (longitude) of CNM parking location
	 **/
	private $longitude;

	/**
	 * constructor for location
	 *
	 * @param mixed $newLocationId id of the new location
	 * @param float $newLatitude float containing gps coordinates, latitude
	 * @param string $newLocationDescription string containing description of location
	 * @param string $newLocationNote string containing additional notes regarding location
	 * @param float $newLongitude float containing gps coordinates, longitude
	 * @throws InvalidArgumentException if data types are not valid
	 * @throws RangeException if data values are out of bounds (e.g., strings too long, negative integers)
	 **/
	public function __construct($newLocationId, $newLatitude, $newLocationDescription, $newLocationNote, $newLongitude) {
		try {
			$this->setLocationId($newLocationId);
			$this->setLatitude($newLatitude);
			$this->setLocationDescription($newLocationDescription);
			$this->setLocationNote($newLocationNote);
			$this->setLongitude($newLongitude);
		} catch(InvalidArgumentException $invalidArgument) {
			// rethrow the exception to the caller
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			// rethrow the exception to the caller
			throw(new RangeException($range->getMessage(), 0, $range));
		}
	}


	/**
	 * accessor method for location id
	 *
	 * @return int value of location id
	 **/
	public function getLocationId() {
		return ($this->locationId);
	}

	/**
	 * mutator method for location id
	 *
	 * @param mixed $newLocationId new value of location id
	 * @throws InvalidArgumentException if $newLocationId is not an integer
	 * @throws RangeException if $newLocationId is not positive
	 **/
	public function setLocationId($newLocationId) {
		// base case: if the location id is null, this a new location without a mySQL assigned id (yet)
		if($newLocationId === null) {
			$this->locationId = null;
			return;
		}

		// verify the location id is valid
		$newLocationId = filter_var($newLocationId, FILTER_VALIDATE_INT);
		if($newLocationId === false) {
			throw(new InvalidArgumentException("location id is not a valid integer"));
		}

		// verify the location id is positive
		if($newLocationId <= 0) {
			throw(new RangeException("location id is not positive"));
		}

		// convert and store the location id
		$this->locationId = intval($newLocationId);
	}

	/**
	 * accessor method for location description
	 *
	 * @return string value of location description
	 **/
	public function getLocationDescription() {
		return ($this->locationDescription);
	}

	/**
	 * mutator method for location description
	 *
	 * @param string $newLocationDescription new value of location description
	 * @throws InvalidArgumentException if $newLocationDescription is not a string or insecure
	 * @throws RangeException if $newLocationDescription is > 128 characters
	 **/
	public function setLocationDescription($newLocationDescription) {
		// verify the location description is secure
		$newLocationDescription = trim($newLocationDescription);
		$newLocationDescription = filter_var($newLocationDescription, FILTER_SANITIZE_EMAIL);
		if(empty($newLocationDescription) === true) {
			throw(new InvalidArgumentException("location description is empty or insecure"));
		}

		// verify the location description will fit in the database
		if(strlen($newLocationDescription) > 128) {
			throw(new RangeException("location description too large"));
		}

		// store the location description
		$this->locationDescription = $newLocationDescription;
	}

	/**
	 * accessor method for location note
	 *
	 * @return string value of location note
	 **/
	public function getLocationNote() {
		return ($this->locationNote);
	}

	/**
	 * mutator method for location note
	 *
	 * @param string $newLocationNote new value of location note
	 * @throws InvalidArgumentException if $newLocationNote is not a string or insecure
	 * @throws RangeException if $newLocationNote is > 128 characters
	 **/
	public function setLocationNote($newLocationNote) {
		// verify the location note is secure
		$newLocationNote = trim($newLocationNote);
		$newLocationNote = filter_var($newLocationNote, FILTER_SANITIZE_STRING);
		if(empty($newLocationNote) === true) {
			throw(new InvalidArgumentException("location note is empty or insecure"));
		}

		// verify the location note will fit in the database
		if(strlen($newLocationNote) > 128) {
			throw(new RangeException("location note too large"));
		}

		// store the location note
		$this->locationNote = $newLocationNote;
	}

	/**
	 * accessor method for gps coordinates, latitude
	 *
	 * @return float value of gps coordinates, latitude
	 **/
	public function getLatitude() {
		return ($this->latitude);
	}

	/**
	 * mutator method for new gps coordinates, latitude
	 *
	 * @param float $newLatitude new GPS coordinates, latitude
	 * @throws InvalidArgumentException if $newLatitude is not a float or insecure
	 * @throws RangeException if $newLatitude is > 5 characters
	 */
	public function setLatitude($newLatitude) {
		// verify gps coordinates, latitude is valid
		$newLatitude = filter_var($newLatitude, FILTER_VALIDATE_FLOAT);
		if(empty($newLatitude) === true) {
			throw(new InvalidArgumentException("gps coordinates, latitude is empty or insecure"));
		}

		// verify gps coordinates, latitude does not exceed 5 characters
		if(strlen($newLatitude) > 5) {
			throw(new RangeException("gps coordinates, latitude too large"));
		}

		// store gps coordinates, latitude
		$this->latitude = $newLatitude;
	}

	/**
	 * accessor method for gps coordinates, longitude
	 *
	 * @return float value of gps coordinates, longitude
	 **/
	public function getLongitude() {
		return ($this->longitude);
	}

	/**
	 * mutator method for new gps coordinates, longitude
	 *
	 * @param float $newLongitude new GPS coordinates, longitude
	 * @throws InvalidArgumentException if $newLatitude is not a float or insecure
	 * @throws RangeException if $newLatitude is > 5 characters
	 */
	public function setLongitude($newLongitude) {
		// verify gps coordinates, longitude is valid
		$newLongitude = filter_var($newLongitude, FILTER_VALIDATE_FLOAT);
		if(empty($newLongitude) === true) {
			throw(new InvalidArgumentException("gps coordinates, longitude is empty or insecure"));
		}

		// verify gps coordinates, longitude does not exceed 5 characters
		if(strlen($newLongitude) > 5) {
			throw(new RangeException("gps coordinates, longitude too large"));
		}

		// store gps coordinates, longitude
		$this->longitude = $newLongitude;
	}

	/**
	 * inserts location into mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function insert(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// enforce the locationId is null (i.e., don't insert a location that already exists)
		if($this->locationId !== null) {
			throw(new mysqli_sql_exception("not a new location"));
		}

		// create query template
		$query = "INSERT INTO location(latitude, locationDescription, locationNote, longitude) VALUES(?, ?, ?, ?)";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		// bind the location variables to the place holders in the template
		$wasClean = $statement->bind_param("dssd", $this->latitude, $this->locationDescription, $this->locationNote, $this->longitude);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("unable to bind parameters"));
		}

		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("unable to execute mySQL statement: " . $statement->error));
		}

		// update the null locationId with what mySQL just gave us
		$this->locationId = $mysqli->insert_id;

		// clean up the statement
		$statement->close();
	}

	/**
	 * deletes location from mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function delete(&$mysqli) {
// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

// enforce the locationId is not null (i.e., don't delete a location that hasn't been inserted)
		if($this->locationId === null) {
			throw(new mysqli_sql_exception("unable to delete a location that does not exist"));
		}

// create query template
		$query = "DELETE FROM location WHERE locationId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

// bind the location variables to the place holder in the template
		$wasClean = $statement->bind_param("i", $this->locationId);
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
	 * updates location in mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function update(&$mysqli) {
// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

// enforce the locationId is not null (i.e., don't update a location that hasn't been inserted)
		if($this->locationId === null) {
			throw(new mysqli_sql_exception("unable to update a location that does not exist"));
		}

// create query template
		$query = "UPDATE location SET latitude = ?, locationDescription = ?, locationNote = ?, longitude = ? WHERE locationId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

// bind the location variables to the place holders in the template
		$wasClean = $statement->bind_param("dssdi", $this->latitude, $this->locationDescription, $this->locationNote, $this->longitude, $this->locationId);
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
	 * gets the location by location Id
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param int $locationId location to search for
	 * @return mixed location found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public static function getLocationByLocationId(&$mysqli, $locationId) {
// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

// sanitize the locationId before searching
		$locationId = filter_var($locationId, FILTER_VALIDATE_INT);
		if($locationId === false) {
			throw(new mysqli_sql_exception("location id is not an integer"));
		}
		if($locationId <= 0) {
			throw(new mysqli_sql_exception("location id is not positive"));
		}

// create query template
		$query = "SELECT locationId, latitude, locationDescription, locationNote, longitude FROM location WHERE locationId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

// bind the location id to the place holder in the template
		$wasClean = $statement->bind_param("i", $locationId);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("unable to bind parameters"));
		}

// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("unable to execute mySQL statement: " . $statement->error));
		}

// get result from the SELECT query
		$result = $statement->get_result();
		if($result === false) {
			throw(new mysqli_sql_exception("unable to get result set"));
		}

// grab the location from mySQL
		try {
			$location = null;
			$row = $result->fetch_assoc();
			if($row !== null) {
				$location = new Location($row["locationId"], $row["latitude"], $row["locationDescription"], $row["locationNote"], $row["longitude"]);
			}
		} catch(Exception $exception) {
// if the row couldn't be converted, rethrow it
			throw(new mysqli_sql_exception($exception->getMessage(), 0, $exception));
		}

// free up memory and return the result
		$result->free();
		$statement->close();
		return ($location);
	}

	/**
	 * gets the location by location description
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param string $locationDescription location to search for by description
	 * @return mixed location found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public static function getLocationByLocationDescription(&$mysqli, $locationDescription) {
// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

// sanitize the location description before searching
		$locationDescription = trim($locationDescription);
		$locationDescription = filter_var($locationDescription, FILTER_SANITIZE_STRING);
		if(empty($locationDescription) === true) {
			throw(new InvalidArgumentException("location description is empty or insecure"));
		}

// create query template
		$query = "SELECT locationId, latitude, locationDescription, locationNote, longitude FROM location WHERE locationDescription LIKE ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

// bind the location description to the place holder in the template
		$locationDescription = "%$locationDescription%";
		$wasClean = $statement->bind_param("s", $locationDescription);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("unable to bind parameters"));
		}

// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("unable to execute mySQL statement: " . $statement->error));
		}

// get result from the SELECT query
		$result = $statement->get_result();
		if($result === false) {
			throw(new mysqli_sql_exception("unable to get result set"));
		}

// grab the location from mySQL
		try {
			$location = null;
			$row = $result->fetch_assoc();
			if($row !== null) {
				$location = new Location($row["locationId"], $row["latitude"], $row["locationDescription"], $row["locationNote"], $row["longitude"]);
			}
		} catch(Exception $exception) {
// if the row couldn't be converted, rethrow it
			throw(new mysqli_sql_exception($exception->getMessage(), 0, $exception));
		}

// free up memory and return the result
		$result->free();
		$statement->close();
		return ($location);
	}
}
?>