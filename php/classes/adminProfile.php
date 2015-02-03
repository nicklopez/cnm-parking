<?php
/**
 * Class creation of the Admin Profile table
 *
 * Admin is the creation of admin profile
 *
 * @author David Fevig <davidfevig@davidfevig.com>
 **/
class AdminProfile {
	/**
	 * id for admin profile; this is the primary key
	 **/
	private $adminProfileId;
	/**
	 * id for adminId; this is a foreign key
	 **/
	private $adminId;
	/**
	 * admin first name
	 **/
	private $adminFirstName;
	/**
	 * admin last name
	 **/
	private $adminLastName;

	/**
	 * constructor for Admin Profile
	 *
	 * place holder for constructor description
	 *
	 **/
	public function __construct($newAdminProfileId, $newAdminId, $newAdminFirstName, $newAdminLastName) {
		try {
			$this->setAdminProfileId($newAdminProfileId);
			$this->setAdminId($newAdminId);
			$this->setAdminFirstName($newAdminFirstName);
			$this->setAdminLastName($newAdminLastName);
		} catch(InvalidArgumentException $invalidArgument) {
			// rethrow the exception to the caller
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			// rethrow the exception to the caller
			throw(new RangeException($range->getMessage(), 0, $range));
		}
	}

	/**
	 * accessor method for admin profile id
	 *
	 * @return int value of admin profile id
	 **/
	public function getAdminProfileId() {
		return($this->adminProfileId);
	}

	/**
	 * mutator method for admin profile id
	 *
	 * @param int $newAdminProfileId new value of admin profile id
	 * @throws InvalidArgumentException if $newAdminProfileId is not an integer
	 * @throws RangeException if $newAdminProfileId is not positive
	 **/
	public function setAdminProfileId($newAdminProfileId) {
		// base case: if the admin profile id is null, this a new admin profile without a mySQL assigned id (yet)
		if($newAdminProfileId === null) {
			$this->adminProfileId = null;
			return;
		}

		// verify the admin profile id is valid
		$newAdminProfileId = filter_var($newAdminProfileId, FILTER_VALIDATE_INT);
		if($newAdminProfileId === false) {
			throw(new InvalidArgumentException("admin id is not a valid integer"));
		}

		// verify the admin id is positive
		if($newAdminProfileId <= 0) {
			throw(new RangeException("admin profile id is not positive"));
		}

		// convert and store the profile id
		$this->adminProfileId = intval($newAdminProfileId);
	}
	/**
	 * accessor method for admin id
	 *
	 * @return int value of admin id
	 **/
	public function getAdminId() {
		return($this->adminId);
	}

	/**
	 * mutator method for admin id
	 *
	 * @param int $newAdminId new value of admin id
	 * @throws InvalidArgumentException if $newAdminId is not an integer
	 * @throws RangeException if $newAdminId is not positive
	 **/
	public function setAdminId($newAdminId) {
		// base case: if the admin id is null, this a new admin without a mySQL assigned id (yet)
		if($newAdminId === null) {
			$this->adminId = null;
			return;
		}

		// verify the admin id is valid
		$newAdminId = filter_var($newAdminId, FILTER_VALIDATE_INT);
		if($newAdminId === false) {
			throw(new InvalidArgumentException("admin id is not a valid integer"));
		}

		// verify the admin id is positive
		if($newAdminId <= 0) {
			throw(new RangeException("admin id is not positive"));
		}

		// convert and store the profile id
		$this->adminId = intval($newAdminId);
	}

	/**
	 * accessor method for the admin first name
	 *
	 * @return string value for the admin first name
	 *
	 **/
	public function getAdminFirstName() {
		return($this->adminFirstName);
	}

	/**
	 * mutator method for the admin first name
	 *
	 * @param string $newAdminFirstName new value of admin first name content
	 * @throws InvalidArgumentException if $newAdminFirstName is not a string or insecure
	 * @throws RangeException if $newAdminFirstName is > 128 characters
	 *
	 **/
	public function setAdminFirstName($newAdminFirstName) {
		// verify the first name is secure
		$newAdminFirstName = trim($newAdminFirstName);
		$newAdminFirstName = filter_var($newAdminFirstName, FILTER_SANITIZE_STRING);
		if(empty($newAdminFirstName) === true) {
			throw(new InvalidArgumentException("admin first name is empty or insecure"));
		}
		if(strlen($newAdminFirstName) > 128) {
			throw(new RangeException("admin first name too large"));
		}
		// store the admin first name
		$this->adminFirstName = $newAdminFirstName;
	}
	/**
	 * accessor method for the admin last name
	 *
	 * @return string value for the admin last name
	 **/
	public function getAdminLastName() {
		return($this->adminLastName);
	}

	/**
	 * mutator method for the admin last name
	 *
	 * @param string $newAdminEmail new value of the admin last name
	 * @throws InvalidArgumentException if $newLastName is not a string or insecure
	 * @throws RangeException if $newAdminLastName is > 128 characters
	 *
	 **/
	public function setAdminLastName($newAdminLastName) {
		// verify the admin email is secure is secure
		$newAdminLastName = trim($newAdminLastName);
		$newAdminLastName = filter_var($newAdminLastName, FILTER_SANITIZE_STRING);
		if(empty($newAdminLastName) === true) {
			throw(new InvalidArgumentException("the admin last name is empty or insecure"));
		}
		if(strlen($newAdminLastName) > 128) {
			throw(new RangeException("admin last name is too large"));
		}
		// store the admin email
		$this->adminLastName = $newAdminLastName;
	}
		/**
	 * inserts the AdminProfile into mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function insert(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// enforce the adminId is null (i.e., don't insert an admin that already exists)
		if($this->adminProfileId !== null) {
			throw(new mysqli_sql_exception("not a new admin profile"));
		}

		// create query template
		$query	 = "INSERT INTO adminProfile(adminProfileId, adminId, adminFirstName, adminLastName) VALUES(?, ?, ?, ?)";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		// bind the member variables to the place holders in the template
		$wasClean	  = $statement->bind_param("iiss", $this->adminProfileId, $this->adminId, $this->adminFirstName, $this->adminFirstName);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("unable to bind parameters"));
		}

		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("unable to execute mySQL statement: " . $statement->error));
		}

		// update the null adminId with what mySQL just gave us
		$this->adminProfileId = $mysqli->insert_id;

		// clean up the statement
		$statement->close();
	}

	/**
	 * deletes the AdminProfile from mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function delete(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// enforce the adminId is not null (i.e., don't delete an admin that hasn't been inserted)
		if($this->adminProfileId === null) {
			throw(new mysqli_sql_exception("unable to delete an admin that does not exist"));
		}

		// create query template
		$query	 = "DELETE FROM adminProfile WHERE adminProfileId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		// bind the member variables to the place holder in the template
		$wasClean = $statement->bind_param("i", $this->adminProfileId);
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
	 * updates the AdminProfile in mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function update(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// enforce the admin profile Id is not null (i.e., don't update an admin profile that hasn't been inserted)
		if($this->adminProfileId === null) {
			throw(new mysqli_sql_exception("unable to update an admin that does not exist"));
		}

		// create query template
		$query	 = "UPDATE adminProfile SET adminProfileId = ?, adminId = ?, adminFirstName = ?, adminLastName = ? WHERE adminProfileId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		// bind the member variables to the place holders in the template
		$wasClean = $statement->bind_param("iiss",  $this->adminProfileId, $this->adminId, $this->adminFirstName, $this->adminLastName);
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
	 * gets the AdminProfile by admin id
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param int $adminId to search for admin profile
	 * @return mixed array of Admin profiles found, Admin profile found, or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public static function getAdminProfileByAdminId(&$mysqli, $adminId) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// validate the int before searching
		$adminId = trim($adminId);
		$adminId = filter_var($adminId, FILTER_VALIDATE_INT);

		// create query template
		$query	 = "SELECT adminProfileId, adminId, adminFirstName, adminLastName FROM adminProfile WHERE adminId LIKE ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		// bind the admin id to the place holder in the template
		$wasClean = $statement->bind_param("i", $adminId);
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

		// build an array of admin id
		$adminIds = array();
		while(($row = $result->fetch_assoc()) !== null) {
			try {
				$adminId	= new AdminProfile($row["newAdminProfileId"], $row["newAdminId"], $row["newAdminFirstName"], $row["newAdminLastName"]);
				$adminIds[] = $adminId;
			}
			catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new mysqli_sql_exception($exception->getMessage(), 0, $exception));
			}
		}

		// count the results in the array and return:
		// 1) null if 0 results
		// 2) a single object if 1 result
		// 3) the entire array if > 1 result
		$numberOfAdminIds = count($adminIds);
		if($numberOfAdminIds === 0) {
			return(null);
		} else if($numberOfAdminIds === 1) {
			return($adminIds[0]);
		} else {
			return($adminIds);
		}
	}

	/**
	 * gets the AdminProfile by adminProfileId
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param int $adminProfileId admin profile content to search for
	 * @return mixed AdminProfile found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public static function getAdminByAdminProfileId(&$mysqli, $adminProfileId) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// sanitize the adminId before searching
		$adminProfileId = filter_var($adminProfileId, FILTER_VALIDATE_INT);
		if($adminProfileId === false) {
			throw(new mysqli_sql_exception("admin profile id is not an integer"));
		}
		if($adminProfileId <= 0) {
			throw(new mysqli_sql_exception("admin profile id is not positive"));
		}

		// create query template
		$query	 = "SELECT adminProfileId, adminId, adminFistName, adminLastName FROM adminProfile WHERE adminProfileId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		// bind the admin content to the place holder in the template
		$wasClean = $statement->bind_param("i", $adminProfileId);
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

		// grab the adminProfile from mySQL
		try {
			$adminProfile = null;
			$row   = $result->fetch_assoc();
			if($row !== null) {
				$adminProfile = new AdminProfile($row["adminProfileId"], $row["adminId"], $row["adminFirstName"], $row["adminLastName"]);
			}
		} catch(Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new mysqli_sql_exception($exception->getMessage(), 0, $exception));
		}

		// free up memory and return the result
		$result->free();
		$statement->close();
		return($adminProfile);
	}

	/**
	 * gets all AdminProfiles
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @return int array of AdminProfiles found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public static function getAdminProfiles(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// create query template
		$query	 = "SELECT adminProfileId, adminId, adminFistName, adminLastName FROM adminProfile";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("unable to prepare statement"));
		}

		// execute the statements
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("unable to execute mySQL statement: " . $statement->error));
		}

		// get result from the SELECT query
		$result = $statement->get_result();
		if($result === false) {
			throw(new mysqli_sql_exception("unable to get result set"));
		}

		// build an array of admin profiles
		$adminProfiles = array();
		while(($row = $result->fetch_assoc()) !== null) {
			try {
				$adminProfiles	= new AdminProfile($row["adminProfileId"], $row["adminId"], $row["adminFirstName"], $row["adminLastName"]);
				$adminProfiles[] = $adminProfile;
			}
			catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new mysqli_sql_exception($exception->getMessage(), 0, $exception));
			}
		}

		// count the results in the array and return:
		// 1) null if 0 results
		// 2) the entire array if >= 1 result
		$numberOfAdminProfiles = count($adminProfiles);
		if($numberOfAdminProfiles === 0) {
			return(null);
		} else {
			return($adminProfiles);
		}
	}
}
?>