/**
 * @author kyle@kedlogic.com
 */
function getAvailability() {

	// tell the validator to validate this form
	$("#verifyAvailabilityForm").validate({
		// setup the formatting for the errors
		errorClass: "label-danger",
		errorLabelContainer: "#outputArea",
		wrapper: "li",

		// rules define what is good/bad input
		rules: {
			// each rule starts with the inputs name (NOT id)
			intLocationInput: {
				required: true
			},
			dateTimePickerArrival: {
				required: true
			},

			dateTimePickerDeparture: {
				required: true
			}
		},

		// error messages to display to the end user
		messages: {
			// each rule starts with the inputs name (NOT id)
			intLocationInput: {
				required: "Field is required."
			},

			dateTimePickerArrival: {
				required: "Field is required."
			},

			dateTimePickerDeparture: {
				required: "Field is required."
			}
		},

		submitHandler: function(form) {
			$(form).ajaxSubmit({
				// GET or POST
				type: "POST",
				// where to submit data
				url: "../php/controllers/verify-availability-post.php",
				// reformat POST data
				data: {
					selectListLocation: $("#intLocationInput").val(),
					dateTimePickerArrival: $("#dateTimePickerArrival").val(),
					dateTimePickerDeparture: $("#dateTimePickerDeparture").val()
				},
				// success is an event that happens when the server replies
				success: function(ajaxOutput) {
					var result = ajaxOutput.split(",")
					// clear the output area's formatting
					$("#outputArea").css("display", "");
					// write the server's reply to the output area
					$("#outputArea").html(result[0]);

					if(result[0].indexOf('Yes') !== -1) {
						$("#outputArea").removeClass("alert alert-danger");
						$("#outputArea").addClass("alert alert-info");
					} else {
						$("#outputArea").removeClass("alert alert-info");
						$("#outputArea").addClass("alert alert-danger");
					}
					$("#arrivalDate").val($("#dateTimePickerArrival").val());
					$("#departureDate").val($("#dateTimePickerDeparture").val());
					$("#parkingSpotId").val(result[1]);
					// reset the form if it was successful
					// this makes it easier to reuse the form again
					if($(".alert-success").length >= 1) {
						$(form)[0].reset();
					}
				}
			});
		}
	});
};
