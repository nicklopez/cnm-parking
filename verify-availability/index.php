<form id="verifyAvailabilityForm">
	<div class="container">
		<div class="col-xs-12">
			<div class="form-group">
				<h3>Choose a Location</h3>
				<label for="selectListLocation" class="control-label"></label>
				<?php require_once("../php/controllers/list-of-locations.php"); ?>
			</div>
		</div>
		<div class="col-xs-12 col-md-6">
			<div class="form-group">
				<h3>Arrival</h3>
				<label for="dateTimePickerArrival" class="control-label"></label>
				<div class="input-group date" id="dateTimePickerArrival">
					<input type="text" class="xdsoft_datetimepicker xdsoft_inline" name="dateTimePickerArrival" id="dateTimePickerArrival"/>
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
		<div class="col-xs-3">
			<button id="verifyAvailabilitySubmit" class="btn btn-primary btn-lg" type="submit" onclick="getAvailability();">Verify Availability</button>
		</div>
		<span id="outputArea" class="" role='alert'></span>
	</div>
</form>

