// document ready event
$(document).ready(
//	// inner function for the ready() event
	function() {
		$('#log').dataTable();

		$('#vp').dataTable();

		$('#reportStartDate').datepicker({
			dateFormat: "mm-dd-yy"
		});

		$('#reportEndDate').datepicker({
			dateFormat: "mm-dd-yy"
		});

		$.validator.addMethod("customDate", function(value, element) {
			return value.match(/^(\d{1,2})-(\d{1,2})-(\d{4})$/);
		},
		 "Please enter a date in the format mm-dd-yyyy"
		);

		// tell the validator to validate this form
		$("#reports-form").validate({
			// setup the formatting for the errors
			errorClass: "label-danger",
			errorLabelContainer: "#outputArea",
			wrapper: "li",

			// rules define what is good/bad input
			rules: {
				// each rule starts with the inputs name (NOT id)
				reportStartDate: {
					required: true,
					date: false,
					customDate: true
				},
				reportEndDate: {
					required: true,
					date:false,
					customDate: true
				}
			},

			// error messages to display to the end user
			messages: {
				reportStartDate: {
					required: "Start date required"
				},
				reportEndDate: {
					required: "End date required"
				}
			}
		});
	});