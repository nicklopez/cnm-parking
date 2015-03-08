$(document).ready(
	function() {
		$('#reports').dataTable();
		$('#invite').dataTable();
	} );

// document ready event
$(document).ready(
	// inner function for the ready() event
	function() {
		// tell the validator to validate this form
		$("#reports-form").validate({
			// setup the formatting for the errors
			errorClass: "label-danger",
			errorLabelContainer: "#outputArea",
			wrapper: "li",

			// rules define what is good/bad input
			rules: {
				// each rule starts with the inputs name (NOT id)
				startDate: {
					required: true
				},
				endDate: {
					required: true
				}
			},

			// error messages to display to the end user
			messages: {
				startDate: {
					required: "Start date required"
				},
				endDate: {
					required: "End date required"
				}
			}
		});
	});