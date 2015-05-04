/**
 * Created by nicklopez on 4/3/15.
 */

function addLocationSpots(locationName, locationDescription, locationSpotStart, locationSpotEnd) {
	$("#locationSpotModal").ajaxSubmit({
		type: "POST",
		url: "../php/controllers/manage-parking-post.php",
		data: {locationName: locationName, locationDescription: locationDescription, locationSpotStart: locationSpotStart, locationSpotEnd: locationSpotEnd},
		// success is an event that happens when the server replies
		success: function(ajaxOutput) {
			// clear the output area's formatting
			$("#LocationSpotOutputArea").css("display", "");
			// write the server's reply to the output area
			$("#LocationSpotOutputArea").html(ajaxOutput);
		}
	});
}

function addSpots(start, end, modalLocationId) {
	$("#myModal").ajaxSubmit({
		type: "POST",
		url: "../php/controllers/manage-parking-post.php",
		data: {start: start, end: end, modalLocationId: modalLocationId},
		// success is an event that happens when the server replies
		success: function(ajaxOutput) {
			// clear the output area's formatting
			$("#outputArea").css("display", "");
			// write the server's reply to the output area
			$("#outputArea").html(ajaxOutput);
		}
	});
}

function deleteSpots(start, end, modalLocationId, action) {
	$("#myModal").ajaxSubmit({
		type: "POST",
		url: "../php/controllers/manage-parking-post.php",
		data: {start: start, end: end, modalLocationId: modalLocationId, action: action},
		// success is an event that happens when the server replies
		success: function(ajaxOutput) {
			// clear the output area's formatting
			$("#outputArea").css("display", "");
			// write the server's reply to the output area
			$("#outputArea").html(ajaxOutput);
		}
	});
}

function fetchAvailablePlacards(locationId, startDate, endDate) {
	$.ajax({
		type: "POST",
		url: "../php/controllers/placard-lookup.php",
		data: {locationId: locationId, startDate: startDate, endDate: endDate}
	}).always(function(ajaxOutput) {
		$("#availablePlacards").empty();
		$('#availablePlacards').append(ajaxOutput);
	});
}

function insertPlacardAssignment() {
	$("#placardAssignmentForm").ajaxSubmit({
		type: "POST",
		url: "../php/controllers/assign-placard-post.php",
		data: $("#placardAssignmentForm").serialize(),
		// success is an event that happens when the server replies
		success: function(ajaxOutput) {
			// clear the output area's formatting
			$("#placardAssignmentOutputArea").css("display", "");
			// write the server's reply to the output area
			$("#placardAssignmentOutputArea").html(ajaxOutput);
		}
	});
}

function updatePlacardAssignment() {
	$("#placardAssignmentForm").ajaxSubmit({
		type: "POST",
		url: "../php/controllers/update-placard-post.php",
		data: $("#placardAssignmentForm").serialize(),
		// success is an event that happens when the server replies
		success: function(ajaxOutput) {
			// clear the output area's formatting
			$("#placardAssignmentOutputArea").css("display", "");
			// write the server's reply to the output area
			$("#placardAssignmentOutputArea").html(ajaxOutput);
		}
	});
}

$(document).ready(function() {
	$('#startDate').datepicker({
		dateFormat: "mm-dd-yy",
		onSelect: function() {
			if($("#availablePlacards").is(":hidden")) {
				return;
			}
			if(this.value === "" || $("#endDate").val() === "" || this.value > $("#endDate").val()) {
				$("#availablePlacards").empty();
			} else {
				fetchAvailablePlacards($("#locationId").val(), $("#startDate").val(), this.value);
			}
		}
	});

	$('#endDate').datepicker({
		dateFormat: "mm-dd-yy",
		onSelect: function() {
			if($("#availablePlacards").is(":hidden")) {
				return;
			}
			if(this.value === "" || $("#startDate").val() === "" || this.value < $("#startDate").val()) {
				$("#availablePlacards").empty();
			} else {
				fetchAvailablePlacards($("#locationId").val(), $("#startDate").val(), this.value);
			}
		}
	});

	$(document).on('click', '#deleteLink', function() {
		$("#deleteSpotsButton").show();
		$("#addSpotsButton").hide();
	});

	$(document).on('click', '#addLink', function() {
		$("#deleteSpotsButton").hide();
		$("#addSpotsButton").show();
	});

	$(document).on('click', '#addAssignmentLink', function() {
		$("#updateAssignmentButton").hide();
		$("#saveAssignmentButton").show();
	});

	$(document).on('click', '#updateAssignmentLink', function() {
		$("#updateAssignmentButton").show();
		$("#saveAssignmentButton").hide();
	});

	$("#myModal").on("hide.bs.modal", function() {
		if($("#outputArea div").hasClass('alert-success')) {
			location.reload();
		} else {
			$("#start").val("");
			$("#end").val("");
			$("#modalLocationId").val("");
			$("#outputArea").html("");
		}
	});

	$("#locationSpotModal").on("hide.bs.modal", function() {
		if($("#LocationSpotOutputArea div").hasClass('alert-success')) {
			location.reload();
		} else {
			$("#locationName").val("");
			$("#LocationDescription").val("");
			$("#locationSpotStart").val("");
			$("#locationSpotEnd").val("");
			$("#LocationSpotOutputArea").html("");
		}
	});

	$('#returned').on("click", function() {
		if($('#returned').is(':checked')) {
			var date = new Date();
			var newDate = date.dateFormat('Y-m-d H:i:s');
			$("#returnDate").val(newDate);
		} else {
			$("#returnDate").val("");
		}
	});

	// triggered when modal is about to be shown
	$('#placardAssignmentModal').on('show.bs.modal', function(e) {
		var action = $(e.relatedTarget).data('assignment');
		if(action === 'new') {
			var locationId = $(e.relatedTarget).data('location-id');
			$(e.currentTarget).find('input[id="locationId"]').val(locationId);

			// show placard list
			$("#availablePlacards").show();

		} else {

			// get table cell values for current row
			var info = $(e.relatedTarget).closest('tr').children('td:eq(2)').text();
			var placard = $(e.relatedTarget).closest('tr').children('td:eq(1)').text();
			var splitInfo = info.split(" ");
			var firstName = splitInfo[0].trim();
			var lastName = splitInfo[1].trim();
			var startDate = splitInfo[5].trim();
			var endDate = splitInfo[7].trim();


			// get data attribute values
			var assignId = $(e.relatedTarget).data('assign-id');
			var spotId = $(e.relatedTarget).data('spot-id');
			var locationId = $(e.relatedTarget).data('location-id');

			// populate modal form values
			$(e.currentTarget).find('input[id="firstName"]').val(firstName);
			$(e.currentTarget).find('input[id="lastName"]').val(lastName);
			$(e.currentTarget).find('input[id="startDate"]').val(startDate);
			$(e.currentTarget).find('input[id="endDate"]').val(endDate);
			$(e.currentTarget).find('input[id="assignId"]').val(assignId);
			$(e.currentTarget).find('input[id="locationId"]').val(locationId);
			$('#availablePlacards').append("<option value='" +spotId+ "'></option>");
			$(e.currentTarget).find('p[id="placardText"]').html(placard);

			// hide placard list if editing existing records
			$("#availablePlacards").hide();
		}
	});

	// triggered when modal is about to be hidden
	$('#placardAssignmentModal').on('hide.bs.modal', function(e) {
		if($("#placardAssignmentOutputArea div").hasClass('alert-success')) {
			location.reload();
		} else {
			$("#firstName").val("");
			$("#lastName").val("");
			$("#startDate").val("");
			$("#endDate").val("");
			$("#assignId").val("");
			$("#parkingSpotId").val("");
			$("#locationId").val("");
			$("#returnDate").val("");
			$("#availablePlacards").empty();
			$("#placardText").html("");
			$("#returned").removeAttr('checked');
			$("#placardAssignmentOutputArea").html("");
		}
	});
});