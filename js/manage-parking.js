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

	$("#locationSpotModal").on("hide.bs.modal", function(){
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

	$("#myModal").on("hide.bs.modal", function(){
		if($("#outputArea div").hasClass('alert-success')) {
			location.reload();
		} else {
			$("#start").val("");
			$("#end").val("");
			$("#modalLocationId").val("");
			$("#outputArea").html("");
		}
	});
};