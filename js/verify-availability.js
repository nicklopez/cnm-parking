/**
 * @author kyle@kedlogic.com
 */
// document ready event
$(document).ready(
	// inner function for the ready() event
	function() {

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
				dateTimeVerifyAvailabilityInputArrival: {
					required: true
				},

				dateTimeVerifyAvailabilityInputDeparture: {
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

			// setup an AJAX call to submit the form without reloading
			submitHandler: function(form) {
				$(form).ajaxSubmit({
					// GET or POST
					type: "POST",
					// where to submit data
					url: "../php/controllers/verify-availability-post.php",
					// reformat POST data
					data: $(form).serialize(),
					// success is an event that happens when the server replies
					success: function(ajaxOutput) {
						// clear the output area's formatting
						$("#outputArea").css("display", "");
						// write the server's reply to the output area
						$("#outputArea").html(ajaxOutput);

						// reset the form if it was successful
						// this makes it easier to reuse the form again
						if($(".alert-success").length >= 1) {
							$(form)[0].reset();
						}
					}
				});
			}
		});

		$('#dateTimePickerArrival').datetimepicker();
		$('#dateTimePickerDeparture').datetimepicker();
		$("#dateTimePickerArrival").on("dp.change", function(e) {
			$('#dateTimePickerDeparture').data("DateTimePicker").minDate(e.date);
		});
		$("#dateTimePickerDeparture").on("dp.change", function(e) {
			$('#dateTimePickerArrival').data("DateTimePicker").maxDate(e.date);
			$('#dateTimePickerArrival').data("DateTimePicker").minDate(now);
		});
	});