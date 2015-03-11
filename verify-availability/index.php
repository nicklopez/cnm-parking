<!--<!DOCTYPE html>-->
<!--<html>-->
<!--	<head>-->
<!--		<meta charset="UTF-8" />-->
<!--		<meta name="viewport" content="width=device-width, initial-scale=1" />-->
<!---->
<!--		<link type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet" />-->
<!---->
<!--		<link type="text/css" href="../css/datetimepicker.css" rel="stylesheet" />-->
<!---->
<!--		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>-->
<!--		<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.form/3.51/jquery.form.min.js"></script>-->
<!--		<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/jquery.validate.min.js"></script>-->
<!--		<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/additional-methods.min.js"></script>-->
<!--		<script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>-->
<!--		<script type="text/javascript" src="../js/datetimepicker.js"></script>-->
<!--		<script type="text/javascript" src="../js/verify-availability.js"></script>-->
<!--		<link type="text/css" href="../css/datetimepicker.css" rel="stylesheet" />-->
<!--		<title>Verify Availability</title>-->
<!--	</head>-->

<!--//$headerTitle = "Visitor Vehicle Information";-->
<!--//$title = "CNM Visitor Vehcile Parking Information";-->
<!--//require_once("../php/lib/header.php");-->

<!--	<body>-->
		<form id="verifyAvailabilityForm">
			<!--		<form id="verifyAvailabilityForm" method="post" action="../php/controllers/verify-availability-post.php">-->
			<div class="container">
				<div class="row">
					<div class="col-xs-12 col-md-12">
						<div class="form-group">
							<h3>Choose Location</h3>
							<label for="selectListLocation" class="control-label"></label>
							<select id="selectListLocation" name="selectListLocation" class="form-control"  >
								<?php require_once("../php/controllers/list-of-locations.php") ?>
							</select>
						</div>
					</div>
					<div class="col-xs-12 col-md-6">
						<div class="form-group">
							<h3>Arrival</h3>
							<label for="dateTimePickerArrival" class="control-label"></label>
							<div class="input-group date" id="dateTimePickerArrival">
								<input hidden="hidden" type="text" class="xdsoft_datetimepicker xdsoft_inline" name="dateTimePickerArrival" id="dateTimePickerArrival"/>
							</div>
						</div>
					</div>
					<div class="col-xs-12 col-md-6">
						<div class="form-group">
							<h3>Departure</h3>
							<label for="dateTimePickerDeparture" class="control-label"></label>
							<div class="input-group date" id="dateTimePickerDeparture">
								<input type="text"class="xdsoft_datetimepicker xdsoft_inline" name="dateTimePickerDeparture" id="dateTimePickerDeparture"/>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12">
						<button id="verifyAvailabilitySubmit" class="btn btn-primary btn-lg" type="submit" onclick="getAvailability();">Verify Availability</button>
					</div>
				</div>
			</div>
		</form>
		<div id="outputArea"></div>
<!--	</body>-->

