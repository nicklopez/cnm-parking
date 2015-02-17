<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet">
		<link type="text/css" href="/lib/bootcamp-coders.css" rel="stylesheet">
		<link type="text/css" href="/images/favicon.ico" rel="shortcut icon">
		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.form/3.51/jquery.form.min.js"></script>
		<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/jquery.validate.min.js"></script>
		<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/additional-methods.min.js"></script>
		<script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>		<title>Controller: Personal Vehicle Registration</title>
		<style type="text/css"></style></head>
	<body>
		<h1>CNM Visitor Vehicle Parking Information</h1>
		<form method="post" action="personal-vehicle-post.php">

			<label for="visitorFirstName">First Name</label>
			<input type="text" id="visitorFirstName" name="visitorFirstName"><br>

			<label for="visitorLastName">Last Name</label>
			<input type="text" id="visitorLastName" name="visitorLastName"><br>

			<label for="visitorEmail">Email</label>
			<input type="text" id="visitorEmail" name="visitorEmail"><br>

			<label for="visitorPhone">Phone Number</label>
			<input type="text" id="visitorPhone" name="visitorPhone"><br>

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

			<label for="startDateTime">Start Date/Time</label>
			<input type="text" id="startDateTime" name="startDateTime"><br>

			<label for="endDateTime">End Date/Time</label>
			<input type="text" id="vehiclePlateState" name="vehiclePlateState"><br>


			<button id="submit" type="submit">Submit</button>
		</form>

	</body>
</html>