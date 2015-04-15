<?php
$title = "Parking Pass";
$headerTitle = "Create a parking pass";
require_once("../php/lib/header.php");
require_once("/home/cnmparki/etc/mysql/encrypted-config.php");
require_once("../php/classes/visitor.php");
require_once("../php/classes/invite.php");
require_once("../php/classes/vehicle.php");
require_once("../php/lib/csrf.php");

// start session
session_start();

// assign url to session variable
$_SESSION['url'] = $_SERVER['REQUEST_URI'];

// checks for active session
if(!isset($_SESSION["adminProfileId"])) {
	header("location: ../admin-login/index.php");
}
?>

<nav class="navbar navbar-default">
	<div class="container-fluid">
		<a id="logout" href="../php/controllers/admin-logout.php" class="btn btn-primary navbar-btn pull-right">Log Out</a>
		<p id="welcome" class="navbar-text pull-right">Welcome back, <?php echo $_SESSION["adminFirstName"]; ?></p>
		<ul class="nav navbar-nav">
			<li role="presentation" class="active"><a class="navbar-brand" href="../php/test-portal/test-portal.php">Home</a></li>
			<li role="presentation"><a href="../create-pass">Create Parking Pass</a></li>
			<li role="presentation"><a href="../send-invite">Manage Invites</a></li>
			<li role="presentation"><a href="../manage-parking">Manage Parking</a></li>
			<li role="presentation"><a href="../reports">Reports</a></li>
		</ul>
	</div>
</nav>

<!--Require the Verify Availability Form-->
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
				<input type="hidden" id="adminProfileId" name="adminProfileId" value=<?php echo $_SESSION["adminProfileId"]; ?>>
				<input type="hidden" id="visitorId" name="visitorId">
				<input type="hidden" id="activation" name="activation">
				<input type="hidden" id="parkingSpotId" name="parkingSpotId">
				<input type="hidden" id="inviteId" name="inviteId">
			</div>
			<div class="col-xs-12 col-md-6">
				<div class="form-group">
					<label for="visitorEmail">Email:</label>
					<input type="text" class="form-control email" id="visitorEmail" name="visitorEmail" onblur="showVisitor(this.value);">
				</div>
				<div class="form-group">
					<label for="visitorFirstName">First Name:</label>
					<input type="text" class="form-control name" id="visitorFirstName" name="visitorFirstName">
				</div>
				<div class="form-group">
					<label for="visitorLastName">Last Name:</label>
					<input type="text" class="form-control name" id="visitorLastName" name="visitorLastName">
				</div>
				<div class="form-group">
					<label for="visitorPhone">Phone Number:</label>
					<input type="text" class="form-control phone" id="visitorPhone" name="visitorPhone">
				</div>
			</div>
			<div class="col-xs-12 col-md-6">
				<div class="form-group">
					<label for="selectVehicle">Please Select A Vehicle</label>
					<select id=selectVehicle name=selectVehicle class=form-control onchange='test()';>
						<option value='0'> -- Select Your Vehicle -- </option>
						<option value='addVehicle'>Add New Vehicle</option>
					</select>
				</div>
				<div id="extra" style="visibility: hidden">
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
require_once("../php/lib/footer.php");
?>
