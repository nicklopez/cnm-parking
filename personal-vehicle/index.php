<?php
$pageTitle = "Visitor Vehicle Information";
require_once("../php/lib/header.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
require_once("../php/classes/visitor.php");
require_once("../php/classes/invite.php");




try {
	// verify $_GET["activation"] has an activation token; if not, throw an exception
	if(!isset($_GET["activation"])) {
		throw (new InvalidArgumentException("No activation token detected.  Resubmit request."));
	}
	var_dump($_GET["activation"]);


	//set up connection
	mysqli_report(MYSQLI_REPORT_STRICT);
	$configArray = readConfig("/etc/apache2/capstone-mysql/cnmparking.ini");
	$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);

	$activation = $_GET["activation"];
	$resultObjects = Invite::getInviteByActivation($mysqli, $activation);

	if(empty($resultObjects) === true) {
		throw (new InvalidArgumentException("No objects in array"));
	}

	$invite = $resultObjects["invite"];
	$visitor = $resultObjects["visitor"];
	} catch(Exception $exception) {
	// actually, echo the exception since this is the end of the line
		echo "<p class=\"alert alert-danger\">" . $exception->getMessage() . "</p>";
	}
	var_dump($invite->getAdminProfileId());

?>

<body>
	<script src="../js/personal-vehicle.js"></script>

	<h1>CNM Visitor Vehicle Parking Information</h1>
	<form method="post" action="../php/controllers/personal-vehicle-post.php">

		<label for="visitorFirstName">First Name</label>
		<input type="text" id="visitorFirstName" disabled="disabled" name="visitorFirstName" value="<?php echo $visitor->getVisitorFirstName(); ?>" ><br>

		<label for="visitorLastName">Last Name</label>
		<input type="text" id="visitorLastName" disabled="disabled" name="visitorLastName" value="<?php echo $visitor->getVisitorLastName(); ?>" ><br>

		<label for="visitorEmail">Email</label>
		<input type="text" id="visitorEmail" disabled="disabled" name="visitorEmail" value="<?php echo $visitor->getVisitorEmail(); ?>" ><br>

		<label for="visitorPhone">Phone Number</label>
		<input type="text" id="visitorPhone" disabled="disabled" name="visitorPhone" value="<?php echo $visitor->getVisitorPhone(); ?>" ><br>

		<label for="adminProfileId">AdminProfileId</label>
		<input type="text" id="adminProfileId" name="adminProfileId" value="<?php echo $invite->getAdminProfileId(); ?>" ><br>


<!--		<select>-->
<!--			name="visitorVehicle"-->
<!--			<option>+New</option>-->
<!--		</select>-->
<!--		<br>-->

		<label for="vehicleMake">Vehicle Make</label>
		<input type="text" id="vehicleMake" name="vehicleMake"><br>

		<label for="vehicleModel">Vehicle Model</label>
		<input type="text" id="vehicleModel" name="vehicleModel"><br>

		<label for="vehicleYear">Vehicle Year</label>
		<input type="text" id="vehicleYear" name="vehicleYear"><br>

		<label for="vehicleColor">Vehicle Color</label>
		<input type="text" id="vehicleColor" name="vehicleColor"><br>

		<label for="vehiclePlateNumber">Vehicle Plate #</label>
		<input type="text" id="vehiclePlateNumber" name="vehiclePlateNumber"><br>

		<label for="vehiclePlateState">Plate State Issued</label>
		<input type="text" id="vehiclePlateState" name="vehiclePlateState"><br>

<!--		<label for="parkingLocation">Parking Location</label>-->
<!--		<select id="vehiclePlateState" name="vehiclePlateState">-->
<!--			<option>CNM STEMulus</option>-->
<!--		</select>-->
<!--		<br>-->

		<label for="startDateTime">Start Date/Time</label>
		<input type="text" id="startDateTime" name="startDateTime"><br>


		<label for="endDateTime">End Date/Time</label>
		<input type="text" id="endDateTime" name="endDateTime"><br>


		<button id="submit" type="submit">Submit</button>
	</form>
</body>

<?php
require_once("../php/lib/footer.php");
?>

