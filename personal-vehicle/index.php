<?php
$pageTitle = "Visitor Vehicle Information";
require_once("../php/lib/header.php");

?>

<body>

	<h1>CNM Visitor Vehicle Parking Information</h1>
	<form method="post" action="../php/controllers/personal-vehicle-post.php">

		<label for="visitorFirstName">First Name</label>
		<input type="text" id="visitorFirstName" name="visitorFirstName"><br>

		<label for="visitorLastName">Last Name</label>
		<input type="text" id="visitorLastName" name="visitorLastName"><br>

		<label for="visitorEmail">Email</label>
		<input type="text" id="visitorEmail" name="visitorEmail"><br>

		<label for="visitorPhone">Phone Number</label>
		<input type="text" id="visitorPhone" name="visitorPhone"><br>
		<label for="vehicleVehicle">Vehicle</label>

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

