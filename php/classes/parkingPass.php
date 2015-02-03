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

			// verify that parkingPasssId is valid
			$newParkingPassId = filter_var($newParkingPassId, FILTER_VALIDATE_INT);
			if($newParkingPassId === false) {
				throw(new InvalidArgumentException("parkingPassId is not a valid integer"));

			// verify that parkingPassId is positive
			if($newParkingPassId <= 0) {
				throw(new RangeException("parkingPassId is not positive"));
			}

			// convert and store the parkingPassId
				$this->parkingPassId = intval(($newParkingPassId));
		}
	}
}
?>