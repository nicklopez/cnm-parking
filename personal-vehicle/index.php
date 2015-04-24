<?php
$title = "Parking Pass";
$headerTitle = "Create a parking pass";
require_once("../php/lib/header.php");
require_once("/home/cnmparki/etc/mysql/encrypted-config.php");
require_once("../php/classes/visitor.php");
require_once("../php/classes/vehicle.php");
require_once("../php/classes/invite.php");
require_once("../php/lib/csrf.php");

// start session
session_start();

try {

	//set up connection to database
	$configArray = readConfig("/home/cnmparki/etc/mysql/cnmparking.ini");
	$host = $configArray["hostname"];
	$db = $configArray["database"];
	$dsn = "mysql:host=$host;dbname=$db";
	$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
	$pdo = new PDO($dsn, $configArray["username"], $configArray["password"], $options);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

	// check for previous parking pass from activation
//	$set = Invite::getParkingPassByActivation($pdo, $_GET["activation"]);
//	if($set !== false) {
//		echo '<div class="alert alert-success" role="alert" id="message">Invite has expired. Please submit a new invite.</div>';
//		exit;
//	}

	// verify $_GET["activation"] has an activation token; if not, throw an exception
	if(!isset($_GET["activation"])) {
		//		header("location: ../request-invite/index.php");
		echo '<div class="alert alert-success" role="alert" id="message">Please submit a new request.</div>';
	}
//sdaf
	$activation = $_GET["activation"];
	$resultObjects = Invite::getInviteByActivation($pdo, $activation);

	if(empty($resultObjects) === true) {
		throw (new InvalidArgumentException("No objects in array"));
	}

	$invite = $resultObjects["invite"];
	$visitor = $resultObjects["visitor"];

} catch(Exception $exception) {
	// actually, echo the exception since this is the end of the line
	echo "<p class=\"alert alert-danger\">" . $exception->getMessage() . "</p>";
}
?>
<?php
require_once("../verify-availability/index.php");
?>

<div class="container">
	<hr>
	<form id="personal-vehicle" method="post" action="../php/controllers/personal-vehicle-post.php">
		<div class="row">
			<div class="form-group">
				<?php echo generateInputTags(); ?>

				<!--hidden foreign key forms-->
				<input type="hidden" id="adminProfileId" name="adminProfileId" value="<?php echo $invite->getAdminProfileId(); ?>">
				<input type="hidden" id="visitorId" name="visitorId" value="<?php echo $visitor->getVisitorId(); ?>" >
				<input type="hidden" id="activation" name="activation" value="<?php echo $_GET["activation"]; ?>">
				<input type="hidden" id="inviteId" name="inviteId" value="<?php echo $invite->getInviteId(); ?>"
				<input type="hidden" id="vehicleId" name="vehicleId">
				<input type="hidden" id="parkingSpotId" name="parkingSpotId">
			</div>
			<div class="col-xs-12 col-md-6">
				<div class="form-group">
					<label>VISITOR INFO</label>
				</div>
				<div class="form-group">
					<label for="visitorFirstName">First Name:</label>
					<input type="text" class="form-control name" id="visitorFirstName" name="visitorFirstName" value="<?php echo $visitor->getVisitorFirstName(); ?>" readonly>
				</div>
				<div class="form-group">
					<label for="visitorLastName">Last Name:</label>
					<input type="text" class="form-control name" id="visitorLastName" name="visitorLastName" value="<?php echo $visitor->getVisitorLastName(); ?>" readonly>
				</div>
				<div class="form-group">
					<label for="visitorEmail">Email:</label>
					<input type="text" class="form-control email" id="visitorEmail" name="visitorEmail" value="<?php echo $visitor->getVisitorEmail(); ?>" readonly>
				</div>
				<div class="form-group">
					<label for="visitorPhone">Phone Number:</label>
					<input type="text" class="form-control phone" id="visitorPhone" name="visitorPhone" value="<?php echo $visitor->getVisitorPhone(); ?>" readonly>
				</div>
			</div>
			<div class="col-xs-12 col-md-6" >
				<?php
				$visitorId = $visitor->getVisitorId();
				// get array of vehicles from class method, then echo out each row in an object tag
				$vehicles = Vehicle::getVehicleByVisitorId($pdo, $visitorId);
				if ($vehicles != null) { ?>
				<div class="form-group">
					<label for="selectVehicle">Please Select A Vehicle</label>
					<?php
					echo "<select id=selectVehicle name=selectVehicle class=form-control onchange='test()';>";
					echo "<option value='0'> -- Select Your Vehicle -- </option>";
					foreach($vehicles as $vehicle) {
						$id = $vehicle["vehicleId"];
						$cars = $vehicle["vehicleYear"] . " " . $vehicle["vehicleMake"] . " " . $vehicle["vehicleModel"];
						echo "<option value=$id>$cars</option>";
					}
					echo "<option value='addVehicle'>Add New Vehicle</option>";
					echo "</select>";
					?>
				</div>
				<div id="extra" style="visibility: hidden">
					<div class="form-group" >
						<label for="vehicleYear">Vehicle Year:</label>
						<input class="form-control name" id="vehicleYear" name="vehicleYear" size="128" maxlength="128">
					</div>
					<div class="form-group">
						<label  for="vehicleMake">Vehicle Make:</label>
						<input  class="form-control name" id="vehicleMake" name="vehicleMake" size="128" maxlength="128">
					</div>
					<div class="form-group">
						<label  for="vehicleModel">Vehicle Model:</label>
						<input  class="form-control name" id="vehicleModel" name="vehicleModel" size="128" maxlength="128">
					</div>
					<div class="form-group">
						<label  for="vehicleColor">Vehicle Color:</label>
						<input  class="form-control name" id="vehicleColor" name="vehicleColor" size="128" maxlength="128">
					</div>
					<div class="form-group">
						<label for="vehiclePlate">Vehicle Plate #:</label>
						<input class="form-control name" id="vehiclePlateNumber" name="vehiclePlateNumber" size="128" maxlength="128">
						<input hidden="hidden" type="text" id="arrivalDate" name="arrivalDate">
						<input hidden="hidden" type="text" id="departureDate" name="departureDate">
					</div>
					<div class="form-group">
						<label for="vehiclePlateState">Plate State:</label>
						<select name="vehiclePlateState" class="btn btn-default" id="vehiclePlateState">
							<option value="AL">AL</option>
							<option value="AK">AK</option>
							<option value="AZ">AZ</option>
							<option value="AR">AR</option>
							<option value="CA">CA</option>
							<option value="CO">CO</option>
							<option value="CT">CT</option>
							<option value="DE">DE</option>
							<option value="FL">FL</option>
							<option value="GA">GA</option>
							<option value="HI">HI</option>
							<option value="ID">ID</option>
							<option value="IL">IL</option>
							<option value="IN">IN</option>
							<option value="IA">IA</option>
							<option value="KS">KS</option>
							<option value="KY">KY</option>
							<option value="LA">LA</option>
							<option value="ME">ME</option>
							<option value="MD">MD</option>
							<option value="MA">MA</option>
							<option value="MI">MI</option>
							<option value="MN">MN</option>
							<option value="MS">MS</option>
							<option value="MO">MO</option>
							<option value="MT">MT</option>
							<option value="NE">NE</option>
							<option value="NV">NV</option>
							<option value="NH">NH</option>
							<option value="NJ">NJ</option>
							<option selected="selected" value="NM">NM</option>
							<option value="NY">NY</option>
							<option value="NC">NC</option>
							<option value="ND">ND</option>
							<option value="OH">OH</option>
							<option value="OK">OK</option>
							<option value="OR">OR</option>
							<option value="PA">PA</option>
							<option value="RI">RI</option>
							<option value="SC">SC</option>
							<option value="SD">SD</option>
							<option value="TN">TN</option>
							<option value="TX">TX</option>
							<option value="UT">UT</option>
							<option value="VT">VT</option>
							<option value="VA">VA</option>
							<option value="WA">WA</option>
							<option value="WV">WV</option>
							<option value="WI">WI</option>
							<option value="WY">WY</option>
						</select><br>
					</div>
				</div>
				<!--end div for hidden form-->
			</div>
	</form>
	<div class="row">
		<div class="col-xs-12">
			<button id="sendRequest" class="btn btn-primary btn-lg " type="submit">Send Request</button>
		</div>
	</div>
</div>
<p id="outputArea"></p>

<?php
} else {
	?>

	<div class="container">
		<form id="personal-vehicle" method="post" action="../php/controllers/personal-vehicle-post.php">
			<div class="row">
				<div class="form-group">
					<label for="vehicleYear">Vehicle Year:</label>
					<input type="text" class="form-control name" id="vehicleYear" name="vehicleYear" size="128" maxlength="128">
				</div>
				<div class="form-group">
					<label for="vehicleMake">Vehicle Make:</label>
					<input type="text" class="form-control name" id="vehicleMake" name="vehicleMake" size="128" maxlength="128">
				</div>
				<div class="form-group">
					<label for="vehicleModel">Vehicle Model:</label>
					<input type="text" class="form-control name" id="vehicleModel" name="vehicleModel" size="128" maxlength="128">
				</div>
				<div class="form-group">
					<label for="vehicleColor">Vehicle Color:</label>
					<input type="text" class="form-control name" id="vehicleColor" name="vehicleColor" size="128" maxlength="128">
				</div>
				<div class="form-group">
					<label for="vehiclePlate">Vehicle Plate #:</label>
					<input type="text" class="form-control name" id="vehiclePlateNumber" name="vehiclePlateNumber" size="128" maxlength="128">
					<input hidden="hidden" type="text" id="arrivalDate" name="arrivalDate">
					<input hidden="hidden" type="text" id="departureDate" name="departureDate">
				</div>
				<div class="form-group">
					<label for="vehiclePlateState">Plate State:</label>
					<select name="vehiclePlateState" class="btn btn-default" id="vehiclePlateState">
						<option value="AL">AL</option>
						<option value="AK">AK</option>
						<option value="AZ">AZ</option>
						<option value="AR">AR</option>
						<option value="CA">CA</option>
						<option value="CO">CO</option>
						<option value="CT">CT</option>
						<option value="DE">DE</option>
						<option value="FL">FL</option>
						<option value="GA">GA</option>
						<option value="HI">HI</option>
						<option value="ID">ID</option>
						<option value="IL">IL</option>
						<option value="IN">IN</option>
						<option value="IA">IA</option>
						<option value="KS">KS</option>
						<option value="KY">KY</option>
						<option value="LA">LA</option>
						<option value="ME">ME</option>
						<option value="MD">MD</option>
						<option value="MA">MA</option>
						<option value="MI">MI</option>
						<option value="MN">MN</option>
						<option value="MS">MS</option>
						<option value="MO">MO</option>
						<option value="MT">MT</option>
						<option value="NE">NE</option>
						<option value="NV">NV</option>
						<option value="NH">NH</option>
						<option value="NJ">NJ</option>
						<option selected="selected" value="NM">NM</option>
						<option value="NY">NY</option>
						<option value="NC">NC</option>
						<option value="ND">ND</option>
						<option value="OH">OH</option>
						<option value="OK">OK</option>
						<option value="OR">OR</option>
						<option value="PA">PA</option>
						<option value="RI">RI</option>
						<option value="SC">SC</option>
						<option value="SD">SD</option>
						<option value="TN">TN</option>
						<option value="TX">TX</option>
						<option value="UT">UT</option>
						<option value="VT">VT</option>
						<option value="VA">VA</option>
						<option value="WA">WA</option>
						<option value="WV">WV</option>
						<option value="WI">WI</option>
						<option value="WY">WY</option>
					</select><br>
				</div>
			</div>
		</form>
		<div class="row">
			<div class="col-xs-12">
				<button id="sendRequest" class="btn btn-primary btn-lg " type="submit">Send Request</button>
			</div>
		</div>
	</div>
	<p id="outputArea"></p>
<?php

//close if/else statement
}

require_once("../php/lib/footer.php");
?>
