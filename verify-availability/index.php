		<form id="verifyAvailabilityForm">
			<div class="container">
				<div class="col-xs-12">
					<div class="form-group">
						<h3>Choose Location</h3>
						<label for="selectListLocation" class="control-label"></label>
						<select id="selectListLocation" name="selectListLocation" class="form-control">
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
				<div class="row">
					<div class="col-xs-3">
						<button id="verifyAvailabilitySubmit" class="btn btn-primary btn-lg" type="submit" onclick="getAvailability();">Verify Availability</button>
					</div>
					<div id="outputArea" class="col-xs-9">
<!--							Output from post-->
					</div>
				</div>
			</div>
		</form>

