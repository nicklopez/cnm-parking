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

}
?>