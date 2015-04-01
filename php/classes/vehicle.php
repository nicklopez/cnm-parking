<?php
/**
 * CNM Parking Vehicle Information
 *
 * This is where visitor vehicle information is stored
 *
 * @author Nick Lopez <nick@nicklopezcodes.com>
 **/
class Vehicle {
	/**
	 * id for vehicle; this is the primary key
	 **/
	private $vehicleId;
	/**
	 * id of the visitor (owner of vehicle); this is a foreign key
	 **/
	private $visitorId;
	/**
	 * color of vehicle
	 **/
	private $vehicleColor;
	/**
	 * make of vehicle
	 **/
	private $vehicleMake;
	/**
	 * model of vehicle
	 **/
	private $vehicleModel;
	/**
	 * license plate number of vehicle
	 **/
	private $vehiclePlateNumber;
	/**
	 * state of vehicle license plate
	 **/
	private $vehiclePlateState;
	/**
	 * year of vehicle
	 **/
	private $vehicleYear;

	/**
	 * constructor for the vehicle
	 *
	 * @param mixed $newVehicleId id of the vehicle or null if a new vehicle
	 * @param int $newVisitorId id of the vehicle owner
	 * @param string $newVehicleColor string containing color of vehicle
	 * @param string $newVehicleMake string containing make of vehicle
	 * @param string $newVehicleModel string containing model of vehicle
	 * @param string $newVehiclePlateNumber string containing license plate number of vehicle
	 * @param string $newVehiclePlateState string containing license plate state
	 * @param int $newVehicleYear year of vehicle (YYYY)
	 * @throws InvalidArgumentException if data types are not valid
	 * @throws RangeException if data values are out of bounds (e.g., strings too long, negative integers)
	 **/
	public function __construct($newVehicleId, $newVisitorId, $newVehicleColor, $newVehicleMake, $newVehicleModel, $newVehiclePlateNumber, $newVehiclePlateState, $newVehicleYear) {
		try {
			$this->setVehicleId($newVehicleId);
			$this->setVisitorId($newVisitorId);
			$this->setVehicleColor($newVehicleColor);
			$this->setVehicleMake($newVehicleMake);
			$this->setVehicleModel($newVehicleModel);
			$this->setVehiclePlateNumber($newVehiclePlateNumber);
			$this->setVehiclePlateState($newVehiclePlateState);
			$this->setVehicleYear($newVehicleYear);
		} catch(InvalidArgumentException $invalidArgument) {
			// rethrow the exception to the caller
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			// rethrow the exception to the caller
			throw(new RangeException($range->getMessage(), 0, $range));
		}
	}

	/**
	 * accessor method for vehicle Id
	 *
	 * @return mixed value of vehicle Id
	 **/
	public function getVehicleId() {
		return($this->vehicleId);
	}

	/**
	 * mutator method for vehicle id
	 *
	 * @param mixed $newVehicleId new value of vehicle id
	 * @throws InvalidArgumentException if $newVehicleId is not an integer
	 * @throws RangeException if $newVehicleId is not positive
	 **/
	public function setVehicleId($newVehicleId) {
		// base case: if the vehicle id is null, this a new vehicle without a mySQL assigned id (yet)
		if($newVehicleId === null) {
			$this->vehicleId = null;
			return;
		}

		// verify the vehicle id is valid
		$newVehicleId = filter_var($newVehicleId, FILTER_VALIDATE_INT);
		if($newVehicleId === false) {
			throw(new InvalidArgumentException("vehicle id is not a valid integer"));
		}

		// verify the vehicle id is positive
		if($newVehicleId <= 0) {
			throw(new RangeException("vehicle id is not positive"));
		}

		// convert and store the vehicle id
		$this->vehicleId = intval($newVehicleId);
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
	 * @param int $newVisitorId new value of visitor id
	 * @throws InvalidArgumentException if $newVisitorId is not an integer or not positive
	 * @throws RangeException if $newVisitorId is not positive
	 **/
	public function setVisitorId($newVisitorId) {
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
	 * accessor method for vehicle color
	 *
	 * @return string value of vehicle color
	 **/
	public function getVehicleColor() {
		return($this->vehicleColor);
	}

	/**
	 * mutator method for vehicle color
	 *
	 * @param string $newVehicleColor new value of vehicle color
	 * @throws InvalidArgumentException if $newVehicleColor is not a string or insecure
	 * @throws RangeException if $newVehicleColor is > 128 characters
	 **/
	public function setVehicleColor($newVehicleColor) {
		// verify the vehicle color is secure
		$newVehicleColor = trim($newVehicleColor);
		$newVehicleColor = filter_var($newVehicleColor, FILTER_SANITIZE_STRING);
		if(empty($newVehicleColor) === true) {
			throw(new InvalidArgumentException("vehicle color is empty or insecure"));
		}

		// verify the vehicle color will fit in the database
		if(strlen($newVehicleColor) > 128) {
			throw(new RangeException("vehicle color too large"));
		}

		// store the vehicle color
		$this->vehicleColor = $newVehicleColor;
	}

	/**
	 * accessor method for vehicle make
	 *
	 * @return string value of vehicle make
	 **/
	public function getVehicleMake() {
		return($this->vehicleMake);
	}

	/**
	 * mutator method for vehicle color
	 *
	 * @param string $newVehicleMake new value of vehicle make
	 * @throws InvalidArgumentException if $newVehicleMake is not a string or insecure
	 * @throws RangeException if $newVehicleMake is > 128 characters
	 **/
	public function setVehicleMake($newVehicleMake) {
		// verify the vehicle make is secure
		$newVehicleMake = trim($newVehicleMake);
		$newVehicleMake = filter_var($newVehicleMake, FILTER_SANITIZE_STRING);
		if(empty($newVehicleMake) === true) {
			throw(new InvalidArgumentException("vehicle make is empty or insecure"));
		}

		// verify the vehicle make will fit in the database
		if(strlen($newVehicleMake) > 128) {
			throw(new RangeException("vehicle make too large"));
		}

		// store the vehicle make
		$this->vehicleMake = $newVehicleMake;
	}

	/**
	 * accessor method for vehicle model
	 *
	 * @return string value of vehicle model
	 **/
	public function getVehicleModel() {
		return($this->vehicleModel);
	}

	/**
	 * mutator method for vehicle model
	 *
	 * @param string $newVehicleModel new value of vehicle model
	 * @throws InvalidArgumentException if $newVehicleModel is not a string or insecure
	 * @throws RangeException if $newVehicleModel is > 128 characters
	 **/
	public function setVehicleModel($newVehicleModel) {
		// verify the vehicle model is secure
		$newVehicleModel = trim($newVehicleModel);
		$newVehicleModel = filter_var($newVehicleModel, FILTER_SANITIZE_STRING);
		if(empty($newVehicleModel) === true) {
			throw(new InvalidArgumentException("vehicle model is empty or insecure"));
		}

		// verify the vehicle model will fit in the database
		if(strlen($newVehicleModel) > 128) {
			throw(new RangeException("vehicle model too large"));
		}

		// store the vehicle model
		$this->vehicleModel = $newVehicleModel;
	}

	/**
	 * accessor method for vehicle plate number
	 *
	 * @return string value of vehicle plate number
	 **/
	public function getVehiclePlateNumber() {
		return($this->vehiclePlateNumber);
	}

	/**
	 * mutator method for vehicle plate number
	 *
	 * @param string $newVehiclePlateNumber new value of vehicle plate number
	 * @throws InvalidArgumentException if $newVehiclePlateNumber is not a string or insecure
	 * @throws RangeException if $newVehiclePlateNumber is > 128 characters
	 **/
	public function setVehiclePlateNumber($newVehiclePlateNumber) {
		// verify the vehicle plate number is secure
		$newVehiclePlateNumber = trim($newVehiclePlateNumber);
		$newVehiclePlateNumber = filter_var($newVehiclePlateNumber, FILTER_SANITIZE_STRING);
		if(empty($newVehiclePlateNumber) === true) {
			throw(new InvalidArgumentException("vehicle plate number is empty or insecure"));
		}

		// verify the vehicle plate number will fit in the database
		if(strlen($newVehiclePlateNumber) > 128) {
			throw(new RangeException("vehicle plate number too large"));
		}

		// store the vehicle plate number
		$this->vehiclePlateNumber = $newVehiclePlateNumber;
	}

	/**
	 * accessor method for vehicle plate state
	 *
	 * @return string value of vehicle plate state
	 **/
	public function getVehiclePlateState() {
		return($this->vehiclePlateState);
	}

	/**
	 * mutator method for vehicle plate state
	 *
	 * @param string $newVehiclePlateState new value of vehicle plate state
	 * @throws InvalidArgumentException if $newVehiclePlateState is not a string or insecure
	 * @throws RangeException if $newVehiclePlateState is > 2 characters
	 **/
	public function setVehiclePlateState($newVehiclePlateState) {
		// verify the vehicle plate state is secure
		$newVehiclePlateState = trim($newVehiclePlateState);
		$newVehiclePlateState = filter_var($newVehiclePlateState, FILTER_SANITIZE_STRING);
		if(empty($newVehiclePlateState) === true) {
			throw(new InvalidArgumentException("vehicle plate state is empty or insecure"));
		}

		// verify the vehicle plate state will fit in the database
		if(strlen($newVehiclePlateState) > 2) {
			throw(new RangeException("vehicle plate state too large"));
		}

		// store the vehicle plate state
		$this->vehiclePlateState = $newVehiclePlateState;
	}

	/**
	 * accessor method for vehicle year
	 *
	 * @return int value of vehicle year
	 **/
	public function getVehicleYear() {
		return($this->vehicleYear);
	}

	/**
	 * mutator method for vehicle year
	 *
	 * @param int $newVehicleYear new value of vehicle year
	 * @throws InvalidArgumentException if $newVehicleYear is not an integer or not positive
	 * @throws RangeException if $newVehicleYear is not positive
	 **/
	public function setVehicleYear($newVehicleYear) {
		// verify the vehicle year is valid
		$newVehicleYear = filter_var($newVehicleYear, FILTER_VALIDATE_INT);
		if($newVehicleYear === false) {
			throw(new InvalidArgumentException("vehicle year is not a valid integer"));
		}

		// verify the vehicle year is positive
		if($newVehicleYear <= 1900) {
			throw(new RangeException("vehicle year is not positive"));
		}

		// convert and store the vehicle year
		$this->vehicleYear= intval($newVehicleYear);
	}

	/**
	 * inserts vehicle into mySQL
	 *
	 * @param PDO $pdo pointer to mySQL connection, by reference
	 * @throws PDOException when mySQL related errors occur
	 **/
	public function insert(PDO &$pdo) {
		// handle degenerate cases
		if(gettype($pdo) !== "object" || get_class($pdo) !== "PDO") {
			throw(new PDOException("input is not a PDO object"));
		}

		// enforce the vehicleId is null (i.e., don't insert a vehicle that already exists)
		if($this->vehicleId!== null) {
			throw(new PDOException("not a new vehicle"));
		}

		// create query template
		$query = "INSERT INTO vehicle(visitorId, vehicleColor, vehicleMake, vehicleModel, vehiclePlateNumber, vehiclePlateState, vehicleYear) VALUES(:visitorId, :vehicleColor, :vehicleMake, :vehicleModel, :vehiclePlateNumber, :vehiclePlateState, :vehicleYear)";
		$statement = $pdo->prepare($query);
		if($statement === false) {
			throw(new PDOException("unable to prepare statement"));
		}

		// bind the vehicle variables to the place holders in the template
		$parameters = array("visitorId" => $this->visitorId, "vehicleColor" => $this->vehicleColor, "vehicleMake" => $this->vehicleMake, "vehicleModel" => $this->vehicleModel, "vehiclePlateNumber" => $this->vehiclePlateNumber, "vehiclePlateState" => $this->vehiclePlateState, "vehicleYear" => $this->vehicleYear);

		$statement->execute($parameters);

		// update the null vehicleId with what mySQL just gave us
		$this->vehicleId= $pdo->lastInsertId();
	}

	/**
	 * deletes vehicle from mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function delete(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// enforce the vehicleId is not null (i.e., don't delete a vehicle that hasn't been inserted)
		if($this->vehicleId=== null) {
			throw(new mysqli_sql_exception("unable to delete a vehicle that does not exist"));
		}

		// create query template
		$query	 = "DELETE FROM vehicle WHERE vehicleId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		// bind the vehicle variables to the place holder in the template
		$wasClean = $statement->bind_param("i", $this->vehicleId);
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
	 * updates vehicle in mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function update(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// enforce the vehicleId is not null (i.e., don't update a vehicle that hasn't been inserted)
		if($this->vehicleId=== null) {
			throw(new mysqli_sql_exception("unable to update a vehicle that does not exist"));
		}

		// create query template
		$query	 = "UPDATE vehicle SET visitorId = ?, vehicleColor = ?, vehicleMake = ?, vehicleModel = ?, vehiclePlateNumber = ?, vehiclePlateState = ?, vehicleYear = ? WHERE vehicleId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		// bind the vehicle variables to the place holders in the template
		$wasClean = $statement->bind_param("isssssii", $this->visitorId, $this->vehicleColor, $this->vehicleMake, $this->vehicleModel, $this->vehiclePlateNumber, $this->vehiclePlateState, $this->vehicleYear, $this->vehicleId);
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
	 * gets the vehicle by vehicle Id
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param int $vehicleId vehicle to search for by vehicle id
	 * @return mixed vehicle found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public static function getVehicleByVehicleId(&$mysqli, $vehicleId) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// sanitize the vehicleId before searching
		$vehicleId = filter_var($vehicleId, FILTER_VALIDATE_INT);
		if($vehicleId === false) {
			throw(new mysqli_sql_exception("vehicle id is not an integer"));
		}
		if($vehicleId <= 0) {
			throw(new mysqli_sql_exception("vehicle id is not positive"));
		}

		// create query template
		$query	 = "SELECT vehicleId, visitorId, vehicleColor, vehicleMake, vehicleModel, vehiclePlateNumber, vehiclePlateState, vehicleYear FROM vehicle WHERE vehicleId= ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		// bind the vehicle id to the place holder in the template
		$wasClean = $statement->bind_param("i", $vehicleId);
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

		// grab the vehicle from mySQL
		try {
			$vehicle = null;
			$row   = $result->fetch_assoc();
			if($row !== null) {
				$vehicle = new Vehicle($row["vehicleId"], $row["visitorId"], $row["vehicleColor"], $row["vehicleMake"], $row["vehicleModel"], $row["vehiclePlateNumber"], $row["vehiclePlateState"], $row["vehicleYear"]);
			}
		} catch(Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new mysqli_sql_exception($exception->getMessage(), 0, $exception));
		}

		// free up memory and return the result
		$result->free();
		$statement->close();
		return($vehicle);
	}

	/**
	 * gets the vehicle by visitor Id
	 *
	 * @param PDO $pdo pointer to mySQL connection, by reference
	 * @param int $visitorId vehicle to search for by visitor
	 * @return mixed vehicle found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public static function getVehicleByVisitorId(PDO &$pdo, $visitorId) {
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
		$query	 = "SELECT vehicleId, visitorId, vehicleColor, vehicleMake, vehicleModel, vehiclePlateNumber, vehiclePlateState, vehicleYear FROM vehicle WHERE visitorId= :visitorId";
		$statement = $pdo->prepare($query);
		if($statement === false) {
			throw(new PDOException("unable to prepare statement"));
		}

		// bind the visitor id to the place holder in the template
		$parameters = array("visitorId" => $visitorId);

		// execute the statement
		$statement->execute($parameters);
		$statement->setFetchMode(PDO::FETCH_ASSOC);

		// grab the vehicle from mySQL
		try {
			$vehicle = array();
			while(($row = $statement->fetch()) !== false) {
				$vehicle[] = $row;
			}
		} catch(Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new PDOException($exception->getMessage(), 0, $exception));
		}

		// return the result
		return($vehicle);
	}

	/**
	 * gets the vehicle by vehiclePlateNumber
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param string $vehiclePlateNumber vehicle to search for by plate number
	 * @return mixed vehicle found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public static function getVehicleByPlateNumber(&$mysqli, $vehiclePlateNumber) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// sanitize the vehiclePlateNumber before searching
		$vehiclePlateNumber = trim($vehiclePlateNumber);
		$vehiclePlateNumber = filter_var($vehiclePlateNumber, FILTER_SANITIZE_STRING);
		if(empty($vehiclePlateNumber) === true) {
			throw(new InvalidArgumentException("vehicle plate number is empty or insecure"));
		}

		// create query template
		$query	 = "SELECT vehicleId, visitorId, vehicleColor, vehicleMake, vehicleModel, vehiclePlateNumber, vehiclePlateState, vehicleYear FROM vehicle WHERE vehiclePlateNumber LIKE ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		// bind the vehiclePlateNumber to the place holder in the template
		$vehiclePlateNumber = "%$vehiclePlateNumber%";
		$wasClean = $statement->bind_param("s", $vehiclePlateNumber);
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

		// grab the vehicle from mySQL
		$vehicles = array();
		while(($row = $result->fetch_assoc()) !== null) {
			try {
				$vehicle = new Vehicle($row["vehicleId"], $row["visitorId"], $row["vehicleColor"], $row["vehicleMake"], $row["vehicleModel"], $row["vehiclePlateNumber"], $row["vehiclePlateState"], $row["vehicleYear"]);
				$vehicles[] = $vehicle;
			} catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new mysqli_sql_exception($exception->getMessage(), 0, $exception));
			}
		}
		// count the results in the array and return:
		// 1) null if 0 results
		// 2) the entire array if > 1 result
		$numberOfVehicles = count($vehicles);
		if($numberOfVehicles === 0) {
			return (null);
		} else {
			return ($vehicles);
		}
	}
}
?>