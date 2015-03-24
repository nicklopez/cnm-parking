<?php
/**
 * Creation of the CNM parking Admin
 *
 * These the CNM parking of admin credentials
 *
 * @author David Fevig <davidfevig@davidfevig.com>
 **/
class Admin {
	/**
	 * id for CNM Parking admin; this is the primary key
	 **/
	private $adminId;
	/**
	 * activation of the CNM parking admin
	 **/
	private $activation;
	/**
	 * CNM parking admin's email; unique attribute
	 **/
	private $adminEmail;
	/**
	 * password hash
	 **/
	private $passHash;
	/**
	 * salt used for password hash
	 **/
	private $salt;

	/**
	 * constructor for the Admin
	 *
	 ** @param int $newAdminId id of the new admin
	 * @param string $newActivation string containing the activation message
	 * @param string $newAdminEmail string containing the admin's email
	 * @param string $newPassHash string containing the password hashing
	 * @param string $newSalt string containing the salt
	 * @throws InvalidArgumentException if data types are not valid
	 * @throws RangeException if data values are out of bounds (e.g., strings too long, negative integers)
	 *
	 **/
	public function __construct($newAdminId, $newActivation, $newAdminEmail, $newPassHash, $newSalt) {
		try {
			$this->setAdminId($newAdminId);
			$this->setActivation($newActivation);
			$this->setAdminEmail($newAdminEmail);
			$this->setPassHash($newPassHash);
			$this->setSalt($newSalt);
		} catch(InvalidArgumentException $invalidArgument) {
			// rethrow the exception to the caller
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			// rethrow the exception to the caller
			throw(new RangeException($range->getMessage(), 0, $range));
		}
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
		// base case: if the admin id is null, this is a new admin without a mySQL assigned id (yet)
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
	 * accessor method for the activation
	 *
	 * @return string value for the activation
	 **/
	public function getActivation() {
		return($this->activation);
	}

	/**
	 * mutator method for the activation
	 *
	 * @param string $newActivation new value of activation content
	 * @throws InvalidArgumentException if $newActivation is not a string or insecure
	 * @throws RangeException if $newActivation is > 128 characters
	 *
	 **/
	public function setActivation($newActivation) {
		// verify the activation is secure
		$newActivation = trim($newActivation);
		$newActivation = filter_var($newActivation, FILTER_SANITIZE_STRING);
		if(empty($newActivation) === true) {
			throw(new InvalidArgumentException("activation content is empty or insecure"));
		}
		// verify the activation will fit in the database
		if(strlen($newActivation) != 32) {
			throw(new RangeException("activation is incorrect length"));
		}
		//verify the activation is a hex value
		if (ctype_xdigit($newActivation) === false) {
			throw(new InvalidArgumentException("activation is not a hex "));
		}
		// store the activation content
		$this->activation = $newActivation;
	}
	/**
	 * accessor method for the admin email
	 *
	 * @return string value for the admin email
	 **/
	public function getAdminEmail() {
		return($this->adminEmail);
	}

	/**
	 * mutator method for the admin email
	 *
	 * @param string $newAdminEmail new value of the admin email
	 * @throws InvalidArgumentException if $newAdminEmail is not a string or insecure
	 * @throws RangeException if $newAdminEmail is > 128 characters
	 *
	 **/
	public function setAdminEmail($newAdminEmail) {
		// verify the admin email is secure is secure
		$newAdminEmail = trim($newAdminEmail);
		$newAdminEmail = filter_var($newAdminEmail, FILTER_SANITIZE_EMAIL);
		if(empty($newAdminEmail) === true) {
			throw(new InvalidArgumentException("the admin email is empty or insecure"));
		}
		// verify the admin email will fit in the database
		if(strlen($newAdminEmail) > 128) {
			throw(new RangeException("admin email hash too large"));
		}
		// store the admin email
		$this->adminEmail = $newAdminEmail;
	}
	/**
	 * accessor method for the password hash
	 *
	 * @return string value for the password hash
	 **/
	public function getPassHash() {
		return($this->passHash);
	}

	/**
	 * mutator method for the password hash
	 *
	 * @param string $newPassHash new value of password hash
	 * @throws InvalidArgumentException if $newPassHash is not a string or insecure
	 * @throws RangeException if $newPassHash is > 128 characters
	 *
	 **/
	public function setPassHash($newPassHash) {
		// verify the password hash is secure
		$newPassHash = trim($newPassHash);
		$newPassHash = filter_var($newPassHash, FILTER_SANITIZE_STRING);
		if(empty($newPassHash) === true) {
			throw(new InvalidArgumentException("password hash is empty or insecure"));
		}
		// verify the pass hash will fit in the database
		if(strlen($newPassHash) != 128) {
			throw(new RangeException("password hash is incorrect length"));
		}
		//verify the pass hash is a hex value
		if (ctype_xdigit($newPassHash) === false) {
			throw(new InvalidArgumentException("pass hash is not a hex "));
		}
		// store the activation content
		$this->passHash = $newPassHash;
	}
	/**
	 * accessor method for the password hash
	 *
	 * @return string value for the password hash
	 **/
	public function getSalt() {
		return($this->salt);
	}

	/**
	 * mutator method for the salt
	 *
	 * @param string $newSalt new value of the salt
	 * @throws InvalidArgumentException if $newSalt is not a string or insecure
	 * @throws RangeException if $newSalt is > 128 characters
	 *
	 **/
	public function setSalt($newSalt) {
		// verify the salt is secure
		$newSalt = trim($newSalt);
		$newSalt = filter_var($newSalt, FILTER_SANITIZE_STRING);
		if(empty($newSalt) === true) {
			throw(new InvalidArgumentException("salt is empty or insecure"));
		}
		// verify the salt will fit in the database
		if(strlen($newSalt) != 64) {
			throw(new RangeException("salt incorrect length"));
		}
		//verify the pass hash is a hex value
		if (ctype_xdigit($newSalt) === false) {
			throw(new InvalidArgumentException("salt is not a hex"));
		}
		// store the salt
		$this->salt = $newSalt;
	}


	/**
	 * inserts the Admin into mySQL
	 *
	 * @param PDO $pdo pointer to PDO connection, by reference
	 * @throws PDOException when mySQL related errors occur
	 **/
	public function insert(PDO &$pdo) {
		// enforce the admin id is null (i.e., don't insert an admin that already exists)
		if($this->adminId !== null) {
			throw(new PDOException("not a new admin"));
		}

		// create query template
		$query	 = "INSERT INTO admin(activation, adminEmail, passHash, salt) VALUES(:activation, :adminEmail, :passHash, :salt)";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holders in the template
		$parameters = array("activation" => $this->activation, "adminEmail" => $this->adminEmail, "passHash" => $this->passHash, "salt" => $this->salt);
		$statement->execute($parameters);

		// update the null adminId with what mySQL just gave us
		$this->adminId = intval($pdo->lastInsertId());
	}

	/**
	 * deletes the Admin from mySQL
	 *
	 * @param PDO $pdo pointer to PDO connection, by reference
	 * @throws PDOException when mySQL related errors occur
	 **/
	public function delete(PDO &$pdo) {

		// enforce the adminId is not null (i.e., don't delete an admin that hasn't been inserted)
		if($this->adminId === null) {
			throw(new PDOException("unable to delete an admin that does not exist"));
		}

		// create query template
		$query	 = "DELETE FROM admin WHERE adminId = :adminId";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holder in the template
		$parameters = array("adminId" => $this->adminId);
		$statement->execute($parameters);
	}

	/**
	 * updates the Admin in mySQL
	 *
	 * @param PDO $pdo pointer to PDO connection, by reference
	 * @throws PDOException when mySQL related errors occur
	 **/
	public function update(PDO &$pdo) {
		// enforce the adminId is not null (i.e., don't update an admin that hasn't been inserted)
		if($this->adminId === null) {
			throw(new PDOException("unable to update an admin that does not exist"));
		}

		// create query template
		$query	 = "UPDATE admin SET activation = :activation, adminEmail = :adminEmail, passHash = :passHash, salt = :salt WHERE adminId = :adminId";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holders in the template
		$parameters = array("activation" => $this->activation, "adminEmail" => $this->adminEmail, "passHash" => $this->passHash, "salt" => $this->salt, "adminId" => $this->adminId);
		$statement->execute($parameters);
	}


	/**
	 * get the Admin by adminId
	 *
	 * @param PDO $pdo pointer to PDO connection, by reference
	 * @param int $adminId admin content to search for
	 * @return mixed Admin found or null if not found
	 * @throws PDOException when mySQL related errors occur
	 **/
	public static function getAdminByAdminId(PDO &$pdo, $adminId) {
		// sanitize the adminId before searching
		$adminId = filter_var($adminId, FILTER_VALIDATE_INT);
		if($adminId === false) {
			throw(new PDOException("admin id is not an integer"));
		}
		if($adminId <= 0) {
			throw(new PDOException("admin id is not positive"));
		}

		// create query template
		$query	 = "SELECT adminId, activation, adminEmail, passHash, salt FROM admin WHERE adminId = :adminId";
		$statement = $pdo->prepare($query);

		// bind the admin content to the place holder in the template
		$parameters = array("adminId" => $adminId);
		$statement->execute($parameters);

		// grab the admin from mySQL
		try {
			$admin = null;
			$statement->setFetchMode(PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== null) {
				$admin = new Admin($row["adminId"], $row["activation"], $row["adminEmail"], $row["passHash"], $row["salt"]);
			}
		} catch(Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new PDOException($exception->getMessage(), 0, $exception));
		}
		return($admin);
	}

	/**
	 * gets all Admins
	 *
	 * @param PDO $pdo pointer to PDO connection, by reference
	 * @return int array of Admins found or null if not found
	 * @throws PDOException when mySQL related errors occur
	 **/
	public static function getAllAdmins(PDO &$pdo) {

		// create query template
		$query	 = "SELECT adminId, activation, adminEmail, passHash, salt FROM admin";
		$statement = $pdo->prepare($query);
		$statement->execute();

		// build an array of admins
		$admins = array();
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$admin	= new Admin($row["adminId"], $row["activation"], $row["adminEmail"], $row["passHash"], $row["salt"]);
				$admins[] = $admin;
			}
			catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new mysqli_sql_exception($exception->getMessage(), 0, $exception));
			}
		}
		// count the results in the array and return:
		// 1) null if 0 results
		// 2) the entire array if >= 1 result
		$numberOfAdmins = count($admins);
		if($numberOfAdmins === 0) {
			return(null);
		} else {
			return($admins);
		}
	}
	/**
	 * gets Admins by Admin Email
	 *
	 * @param PDO $pdo pointer to PDO connection, by reference
	 * @param string $adminEmail to search Admin for Admin Email
	 * @return mixed array of $adminEmail if not found or null if not found
	 * @throws PDOException when mySQL related errors occur
	 **/
	public static function getAdminByAdminEmail(PDO &$pdo, $adminEmail) {

		// sanitize the adminEmail before searching
		$adminEmail = trim($adminEmail);
		$adminEmail = filter_var($adminEmail, FILTER_SANITIZE_STRING);
		if(empty($adminEmail) === true) {
			throw(new PDOException("admin email is empty or insecure"));
		}

		// create query template
		$query = "SELECT adminId, activation, adminEmail, passHash, salt FROM admin WHERE adminEmail = :adminEmail";
		$statement = $pdo->prepare($query);

		// bind the adminEmail to the place holder in the template
		$parameters = array("adminEmail" => $adminEmail);
		$statement->execute($parameters);

		$statement->setFetchMode(PDO::FETCH_ASSOC);

		// return results
		$row = $statement->fetch();
		try {
			$admin = new Admin($row["adminId"], $row["activation"], $row["adminEmail"], $row["passHash"], $row["salt"]);

		} catch(Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new PDOException($exception->getMessage(), 0, $exception));
		}
		return ($admin);
	}
}

?>