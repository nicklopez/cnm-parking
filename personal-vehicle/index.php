<?php
$headerTitle = "Visitor Vehicle Information";
$title = "CNM Visitor Vehcile Parking Information";
require_once("../php/lib/header.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
require_once("../php/classes/visitor.php");
require_once("../php/classes/invite.php");
require_once("../php/lib/csrf.php");

// start session
session_start();

try {
	// verify $_GET["activation"] has an activation token; if not, throw an exception
	if(!isset($_GET["activation"])) {
		throw (new InvalidArgumentException("No activation token detected.  Resubmit request."));
	}

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
?>

		<form id="request-invite" method="post" action="../php/controllers/personal-vehicle-post.php" novalidate="novalidate">
			<?php echo generateInputTags(); ?>

			<label for="visitorFirstName">First Name:</label>
			<input type="text" id="visitorFirstName" disabled="disabled" name="visitorFirstName" value="<?php echo $visitor->getVisitorFirstName(); ?>" ><br>

			<label for="visitorLastName">Last Name:</label>
			<input type="text" id="visitorLastName" disabled="disabled" name="visitorLastName" value="<?php echo $visitor->getVisitorLastName(); ?>" ><br>

			<label for="visitorEmail">Email:</label>
			<input type="text" id="visitorEmail" disabled="disabled" name="visitorEmail" value="<?php echo $visitor->getVisitorEmail(); ?>" ><br>

			<label for="visitorPhone">Phone Number:</label>
			<input type="text" id="visitorPhone" disabled="disabled" name="visitorPhone" value="<?php echo $visitor->getVisitorPhone(); ?>" ><br>

			<label for="vehicleMake">Vehicle Make:</label>
			<input type="text" id="vehicleMake" name="vehicleMake" size="128" maxlength="128"><br>

			<label for="vehicleModel">Vehicle Model:</label>
			<input type="text" id="vehicleModel" name="vehicleModel" size="128" maxlength="128"><br>

			<label for="vehicleYear">Vehicle Year:</label>
			<input type="text" id="vehicleYear" name="vehicleYear" size="128" maxlength="128"><br>

			<label for="vehicleColor">Vehicle Color:</label>
			<input type="text" id="vehicleColor" name="vehicleColor" size="128" maxlength="128"><br>

			<label for="vehiclePlate">Vehicle Plate #:</label>
			<input type="text" id="vehiclePlateNumber" name="vehiclePlateNumber" size="128" maxlength="128"><br>

			<label for="vehiclePlateState">Plate State:</label>
			<select name="vehiclePlateState" id="vehiclePlateState" style="width:128px;">
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

			<label for"location">Parking Lot Location:</label>
			<select name="location" id="location">
				<option value="1">CNM - STEMulus Center</option>
				<option value="2">CNM - Main Campus</option>
			</select><br>

			<label for="startDateTime">Start Date/Time</label>
			<input type="text" id="startDateTime" name="startDateTime"><br>

			<label for="endDateTime">End Date/Time</label>
			<input type="text" id="endDateTime" name="endDateTime"><br>

			<button id="sendRequest" type="submit">Send Request</button>
		</form>
		<p id="outputArea"></p>


<!--		<div class="container">-->
<!--			<div class="row">-->
<!--				<div class="col-xs-12 col-md-4">-->
<!--					<div class="form-group">-->
<!--						<label for="selectListLocation" class="control-label">Choose Location</label><br/>-->
<!--						<select id="selectListLocation" name="selectListLocation" class="form-control"  >-->
<!--							<option name="intLocationInput" value="1">City Lot 1</option>-->
<!--							<option name="intLocationInput" value="2">City Lot 2</option>-->
<!--							<option name="intLocationInput" value="3">City Lot 3</option>-->
<!--						</select>-->
<!--					</div>-->
<!--				</div>-->
<!--				<div class="col-xs-12 col-md-4">-->
<!--					<div class="form-group">-->
<!--						<label for="dateTimePickerArrival" class="control-label">Arrival</label><br/>-->
<!--						<div class="input-group date" id="dateTimePickerArrival">-->
<!--							<input type="text" class="xdsoft_datetimepicker xdsoft_inline" name="dateTimePickerArrival" id="dateTimePickerArrival"/>-->
<!--						</div>-->
<!--					</div>-->
<!--				</div>-->
<!--				<div class="col-xs-12 col-md-4">-->
<!--					<div class="form-group">-->
<!--						<label for="dateTimePickerDeparture" class="control-label">Departure</label><br/>-->
<!--						<div class="input-group date" id="dateTimePickerDeparture">-->
<!--							<input type="text"class="xdsoft_datetimepicker xdsoft_inline" name="dateTimePickerDeparture" id="dateTimePickerDeparture"/>-->
<!--						</div>-->
<!--					</div>-->
<!--				</div>-->
<!--			</div>-->
<!--			<div class="row">-->
<!--				<div class="col-xs-12">-->
<!--					<button id="verifyAvailabilitySubmit" type="submit">Verify Availability</button>-->
<!--				</div>-->
<!--			</div>-->
<!--		</div>-->
<!--	</form>-->
<!--	<div id="outputArea"></div>-->
<!--</body>-->

<!--		<button id="submit" type="submit">Submit</button>-->
<!--	</form>-->
<!--</body>-->

<?php
require_once("../php/lib/footer.php");
?>

