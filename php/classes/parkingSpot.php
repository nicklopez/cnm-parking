<?php
/**
 *Class file for parkingSpot
 *
 * @author Kyle Dozier <kyle@kedlogic.com
 */
class ParkingSpot {
	/**
	 * Primary Key / int, auto-inc
	 *
	 * id for ParkingSpot Class
	 */
	private $parkingSpotId;

	/**
	 * Foreign Key / int, not null
	 *
	 * id to reference Location Class
	 */
	private $locationId;

	/**
	 * string, not null
	 *
	 * number/id of official placard/pass
	 */
	private $placardNumber;

	//constructor

	/**
	 * accessor method for parkingSpotId
	 *
	 * @return mixed value of parkingSpotId
	 */
	public function getParkingSpotId() {
		return ($this->parkingSpotId);
	}

	/**
	 * mutator method for parkingSpotId
	 *
	 * @param mixed $newParkingSpotId new value of paringSpotId
	 * @throws InvalidArgumentException if $newParkingSpotId is not an integer
	 * @throws RangeException if $newParkingSpotId is not positive
	 */
	public function setParkingSpotId($newParkingSpotId) {
		// base case: if null then id is new
		if($newParkingSpotId === null) {
			$this->parkingSpotId = null;
			return;
		}

		// verify is integer
		$newParkingSpotId = filter_var($newParkingSpotId, FILTER_VALIDATE_INT);
		if($newParkingSpotId === false) {
			throw(new InvalidArgumentException("parkingSpotId is not a valid integer"));
		}

		// verify is positive
		if($newParkingSpotId <= 0) {
			throw(new RangeException("parkingSpotId is not positive"));
		}
		// convert and store
		$this ->parkingSpotId = intval($newParkingSpotId);
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



}
?>