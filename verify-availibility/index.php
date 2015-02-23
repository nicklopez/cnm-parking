<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />

		<link type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet" />

		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.form/3.51/jquery.form.min.js"></script>
		<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/jquery.validate.min.js"></script>
		<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/additional-methods.min.js"></script>
		<script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="../js/verify-availability.js"></script>

		<title>Verify Availability</title>
	</head>
	<body>
		<form id="verifyAvailabilityForm" method="post" action="../php/controllers/verify-availability-post.php">
			<div class="form-group">
				<label for="selectListLocation" class="control-label">Choose Location</label><br/>
				<select id="selectListLocation" name="selectListLocation" class="form-control"  >
					<option value="1">City Lot</option>
				</select>
			</div>
			<div class="form-group">
				Arrival<input class="form-control" type="datetime-local" id="dateTimeVerifyAvailabilityInputArrival" name="dateTimeVerifyAvailabilityInputArrival" value="arrival"/>
				Departure<input class="form-control" type="datetime-local" id="dateTimeVerifyAvailabilityInputDeparture" name="dateTimeVerifyAvailabilityInputDeparture" value="departure"/>
			</div>
			<button id="verifyAvailabilitySubmit" type="submit">Verify Availability</button>
		</form>
		<div id="outputArea"></div>
	</body>
</html>
