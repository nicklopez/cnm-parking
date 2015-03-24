<?php
/**
 * Creation of the CNM Parking Admin Profile
 *
 * This is where the admin profile information is stored
 *
 * @author David Fevig <davidfevig@davidfevig.com>
 **/
class AdminProfile {
	/**
	 * id for CNM Parking admin profile; this is the primary key
	 **/
	private $adminProfileId;
	/**
	 * id for CNM Parking admin id; this is a foreign key
	 **/
	private $adminId;
	/**
	 * CNM parking admin's first name
	 **/
	private $adminFirstName;
	/**
	 * CNM parking admin's last name
	 **/
	private $adminLastName;

	/**
	 * constructor for the Admin Profile
	 *
	 * @param int $newAdminProfileId id of the new admin profile
	 * @param int $newAdminId id containing the admin id
	 * @param string $newAdminFirstName string containing the admin's first name
	 * @param string $newAdminLastName string containing the admin's last name
	 * @throws InvalidArgumentException if data types are not valid
	 * @throws RangeException if data values are out of bounds (e.g., strings too long, negative integers
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
		// verify the admin first name will fit in the database
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
	 * @param string $newAdminLastName new value of the admin last name
	 * @throws InvalidArgumentException if $newAdminLastName is not a string or insecure
	 * @throws RangeException if $newAdminLastName is > 128 characters
	 *
	 **/
	public function setAdminLastName($newAdminLastName) {
		// verify the admin last name is secure is secure
		$newAdminLastName = trim($newAdminLastName);
		$newAdminLastName = filter_var($newAdminLastName, FILTER_SANITIZE_STRING);
		if(empty($newAdminLastName) === true) {
			throw(new InvalidArgumentException("the admin last name is empty or insecure"));
		}
		// verify the admin last name will fit in the database
		if(strlen($newAdminLastName) > 128) {
			throw(new RangeException("admin last name is too large"));
		}
		// store the admin last name
		$this->adminLastName = $newAdminLastName;
	}
	/**
	 * inserts the AdminProfile into mySQL
	 *
	 * @param PDO $pdo pointer to mySQL connection, by reference
	 * @throws PDOException when mySQL related errors occur
	 **/
	public function insert(PDO &$pdo) {

		// enforce the adminId is null (i.e., don't insert an admin that already exists)
		if($this->adminProfileId !== null) {
			throw(new PDOException("not a new admin profile"));
		}

		// create query template
		$query	 = "INSERT INTO adminProfile(adminId, adminFirstName, adminLastName) VALUES (:adminId, :adminFirstName, :adminLastName)";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holders in the template
		$parameters = array("adminId" => $this->adminId, "adminFirstName" => $this->adminFirstName, "adminLastName" => $this->adminLastName);
		$statement->execute($parameters);

		// update the null admin profile id with what mySQL just gave us
		$this->adminProfileId = intval($pdo->lastInsertId());
	}

	/**
	 * deletes the AdminProfile from mySQL
	 *
	 * @param PDO $pdo pointer to mySQL connection, by reference
	 * @throws PDOException when mySQL related errors occur
	 **/
	public function delete(PDO &$pdo) {
		// enforce the adminProfileId is not null (i.e., don't delete an admin profile that hasn't been inserted)
		if($this->adminProfileId === null) {
			throw(new PDOException("unable to delete an admin profile that does not exist"));
		}

		// create query template
		$query	 = "DELETE FROM adminProfile WHERE adminProfileId = :adminProfileId";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holder in the template
		$parameters = array("adminProfileId" => $this->adminProfileId);
		$statement->execute($parameters);
	}

	/**
	 * updates the AdminProfile in mySQL
	 *
	 * @param PDO $pdo pointer to PDO connection, by reference
	 * @throws PDOException when mySQL related errors occur
	 **/
	public function update(PDO &$pdo) {

		// enforce the admin profile Id is not null (i.e., don't update an admin profile that hasn't been inserted)
		if($this->adminProfileId === null) {
			throw(new PDOException("unable to update an admin profile that does not exist"));
		}

		// create query template
		$query = "UPDATE adminProfile SET adminFirstName = :adminFirstName, adminLastName = :adminLastName WHERE adminProfileId = :adminProfileId";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holders in the template
		$parameters = array("adminFirstName" => $this->adminFirstName, "adminLastName" => $this->adminLastName, "adminProfileId" => $this->adminProfileId);
		$statement->execute($parameters);
	}

	/**
	 * gets the AdminProfile by admin id
	 *
	 * @param PDO $pdo pointer to mySQL connection, by reference
	 * @param int $adminId to search for admin profile
	 * @return mixed array of Admin profiles found, Admin profile found, or null if not found
	 * @throws PDOException when mySQL related errors occur
	 **/
	public static function getAdminProfileByAdminId(PDO &$pdo, $adminId) {
		// validate the int before searching
		$adminId = filter_var($adminId, FILTER_VALIDATE_INT);
		if($adminId === false) {
			throw(new PDOException("admin profile id is not an integer"));
		}
		if($adminId <=0) {
			throw(new PDOException("admin profile id is not positive"));
		}

		// create query template
		$query = "SELECT adminProfileId, adminId, adminFirstName, adminLastName FROM adminProfile WHERE adminId = :adminId";
		$statement = $pdo->prepare($query);

		// bind the admin id to the place holder in the template
		$parameters = array("adminId" => $adminId);
		$statement->execute($parameters);

		// build an array of admin profiles
		try {
			$adminProfile = null;
			$statement->setFetchMode(PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$adminProfile = new AdminProfile($row["adminProfileId"], $row["adminId"], $row["adminFirstName"], $row["adminLastName"]);
			}
		} catch(Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new PDOException($exception->getMessage(), 0, $exception));
		}
		return($adminProfile);

	}

	/**
	 * gets the AdminProfile by adminProfileId
	 *
	 * @param PDO $pdo pointer to mySQL connection, by reference
	 * @param int $adminProfileId admin profile content to search for
	 * @return mixed AdminProfile found or null if not found
	 * @throws PDOException when mySQL related errors occur
	 **/
	public static function getAdminProfileByAdminProfileId(PDO &$pdo, $adminProfileId) {
		// sanitize the admin profile Id before searching
		$adminProfileId = filter_var($adminProfileId, FILTER_VALIDATE_INT);
		if($adminProfileId === false) {
			throw(new PDOException("admin profile id is not an integer"));
		}
		if($adminProfileId <= 0) {
			throw(new PDOException("admin profile id is not positive"));
		}

		// create query template
		$query	 = "SELECT adminProfileId, adminId, adminFirstName, adminLastName FROM adminProfile WHERE adminProfileId = :adminProfileId";
		$statement = $pdo->prepare($query);

		// bind the admin content to the place holder in the template
		$parameters = array("adminProfileId" => $adminProfileId);
		$statement->execute($parameters);

		// grab the adminProfile from mySQL
		try {
			$adminProfile = null;
			$statement->setFetchMode(PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== null) {
				$adminProfile = new AdminProfile($row["adminProfileId"], $row["adminId"], $row["adminFirstName"], $row["adminLastName"]);
			}
		} catch(Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new PDOException($exception->getMessage(), 0, $exception));
		}
		return($adminProfile);
	}

	/**
	 * gets all Admin Profile by admin first name
	 *
	 * @param PDO $pdo pointer to mySQL connection, by reference
	 * @param string $adminFirstName to search AdminProfile for by first name
	 * @return mixed admin if found or null if not found
	 * @throws PDOException when mySQL related errors occur
	 **/
	public static function getAdminProfileByAdminFirstName(PDO &$pdo, $adminFirstName) {


		// sanitize the adminFirstName before searching
		$adminFirstName = trim($adminFirstName);
		$adminFirstName = filter_var($adminFirstName, FILTER_SANITIZE_STRING);
		if(empty($adminFirstName) === true) {
			throw(new PDOException("admin first name is empty or insecure"));
		}

		// create query template
		$query = "SELECT adminProfileId, adminId, adminFirstName, adminLastName FROM adminProfile WHERE adminFirstName LIKE :adminFirstName";
		$statement = $pdo->prepare($query);

		// bind the adminFirstName to the place holder in the template
		$adminFirstName = "%$adminFirstName%";
		$parameters = array("adminFirstName" => $adminFirstName);
		$statement->execute($parameters);

		$statement->setFetchMode(PDO::FETCH_ASSOC);

		// grab the adminProfile from mySQL
		$admins = array();
		$row = $statement->fetch();
		while($row !== null) {
			try {
				$admin = new AdminProfile($row["adminProfileId"], $row["adminId"], $row["adminFirstName"], $row["adminLastName"]);
				$admins[] = $admin;
			} catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new PDOException($exception->getMessage(), 0, $exception));
			}
		}
		// count the results in the array and return:
		// 1) null if 0 results
		// 2) the entire array if > 1 result
		$numberOfAdmins = count($admins);
		if($numberOfAdmins === 0) {
			return (null);
		} else {
			return ($admins);
		}
	}
	/**
	 * gets all Admin Profiles by admin last Name
	 *
	 * @param PDO $pdo pointer to mySQL connection, by reference
	 * @param string $adminLastName to search AdminProfile for last name
	 * @return mixed admin if not found or null if not found
	 * @throws PDOException when mySQL related errors occur
	 **/
	public static function getAdminProfileByAdminLastName(PDO &$pdo, $adminLastName) {
		// sanitize the adminLastName before searching
		$adminLastName = trim($adminLastName);
		$adminLastName = filter_var($adminLastName, FILTER_SANITIZE_STRING);
		if(empty($adminLastName) === true) {
			throw(new PDOException("admin first last is empty or insecure"));
		}

		// create query template
		$query = "SELECT adminProfileId, adminId, adminFirstName, adminLastName FROM adminProfile WHERE adminLastName LIKE :adminLastName";
		$statement = $pdo->prepare($query);

		// bind the adminFirstName to the place holder in the template
		$adminLastName = "%$adminLastName%";
		$parameters = array("adminLastName" => $adminLastName);
		$statement->execute($parameters);

		$statement->setFetchMode(PDO::FETCH_ASSOC);

		// grab the adminProfile from mySQL
		$admins = array();
		$row = $statement->fetch();
		while($row !== null) {
			try {
				$admin = new AdminProfile($row["adminProfileId"], $row["adminId"], $row["adminFirstName"], $row["adminLastName"]);
				$admins[] = $admin;
			} catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new PDOException($exception->getMessage(), 0, $exception));
			}
		}
		// count the results in the array and return:
		// 1) null if 0 results
		// 2) the entire array if > 1 result
		$numberOfAdmins = count($admins);
		if($numberOfAdmins === 0) {
			return (null);
		} else {
			return ($admins);
		}
	}
}
?>