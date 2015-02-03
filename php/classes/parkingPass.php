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
	 * string, not null
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
	 * @return int value of uuId
	 */
	public function getUuId() {
		return ($this->uuId);
	}

	/**
	 * mutator method for uuId
	 *
	 *
	 */



}
?>