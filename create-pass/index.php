<?php
$title = "Parking Pass";
$headerTitle = "Create a parking pass";
require_once("../php/lib/header.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
require_once("../php/classes/visitor.php");
require_once("../php/classes/invite.php");
require_once("../php/lib/csrf.php");

// start session
session_start();
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
<p id="outputArea"></p>

<div class="container-fluid">
	<form id="personal-vehicle" method="post" action="../php/controllers/personal-vehicle-post.php" novalidate="novalidate">
		<div class="row">
			<div class="col-xs-12 col-md-3">
				<div class="form-group">
					<?php echo generateInputTags(); ?>

					<!--hidden foreign key forms-->
					<input hidden="hidden" type="text" id="adminProfileId" name="adminProfileId">
					<input hidden="hidden" type="text" id="visitorId" name="visitorId">


					<label for="visitorFirstName">First Name:</label>
					<input type="text" class="form-control name" id="visitorFirstName" name="visitorFirstName">

					<!--					<div class="form-group">-->
					<label for="visitorLastName">Last Name:</label>
					<input type="text" class="form-control name" id="visitorLastName" name="visitorLastName">

					<!--					<div class="form-group">-->
					<label for="visitorEmail">Email:</label>
					<input type="text" class="form-control email" id="visitorEmail" name="visitorEmail" onchange="showVisitor(this.value);">

					<!--					<div class="form-group">-->
					<label for="visitorPhone">Phone Number:</label>
					<input type="text" class="form-control phone" id="visitorPhone" name="visitorPhone">

				</div>
			</div>

			<div class="col-xs-8 col-md-2">
				<div class="form-group">
					<label for="vehicleYear">Vehicle Year:</label>
					<input type="text" class="form-control name" id="vehicleYear" name="vehicleYear" size="128" maxlength="128">

					<label for="vehicleMake">Vehicle Make:</label>
					<input type="text" class="form-control name" id="vehicleMake" name="vehicleMake" size="128" maxlength="128">

					<label for="vehicleModel">Vehicle Model:</label>
					<input type="text" class="form-control name" id="vehicleModel" name="vehicleModel" size="128" maxlength="128">

					<label for="vehicleColor">Vehicle Color:</label>
					<input type="text" class="form-control name" id="vehicleColor" name="vehicleColor" size="128" maxlength="128">

					<div class="form-group">
						<label for="vehiclePlate">Vehicle Plate #:</label>
						<input type="text" class="form-control name" id="vehiclePlateNumber" name="vehiclePlateNumber" size="128" maxlength="128">
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
						</select>
					</div>
				</div>
			</div>

			<div class="col-xs-12 col-md-5">
				<div class="form-group">
					<label for="selectListLocation" class="control-label">Choose Location</label>
					<select id="selectListLocation" name="selectListLocation" class="form-control">
						<option name="intLocationInput" value="1">CNM - STEMulus Center</option>
						<option name="intLocationInput" value="2">CNM - Main Campus</option>
					</select>
				</div>
			</div>

			<div class="col-xs-12 col-md-4">
				<div class="form-group">
					<label for="dateTimePickerArrival" class="control-label">Arrival</label>
					<div class="input-group date" id="dateTimePickerArrival">
						<input type="text" class="xdsoft_datetimepicker xdsoft_inline" name="dateTimePickerArrival" id="dateTimePickerArrival">
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-md-3">
				<div class="form-group">
					<label id="departure" for="dateTimePickerDeparture" class="control-label">Departure</label>
					<div class="input-group date" id="dateTimePickerDeparture">
						<input type="text" class="xdsoft_datetimepicker xdsoft_inline" name="dateTimePickerDeparture" id="dateTimePickerDeparture">
					</div>
					<br>
					<div>
						<button id="verifyAvailabilitySubmit" class="btn btn-primary" type="submit">Verify Availability</button>
						<br>
						<br>
						<br>
						<br>
						<br>
						<br>
						<button id="sendRequest" class="btn btn-primary btn-lg pull-right" type="submit">Send Request</button>
					</div>
				</div>
			</div>
		</div>
		<script type="text/javascript" src="../js/datetimepicker.js"></script>
		<script type="text/javascript" src="../js/verify-availability.js"></script>
	</form>
</div>
<p id="outputArea"></p>

<?php
require_once("../php/lib/footer.php");
?>
