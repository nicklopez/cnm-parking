// document ready event
$(document).ready(
	// inner function for the ready() event
	function() {

		// tell the validator to validate this form
		$("#request-invite").validate({
			// setup the formatting for the errors
			errorClass: "label-danger",
			errorLabelContainer: "#outputArea",
			wrapper: "li",

			// rules define what is good/bad input
			rules: {
				// each rule starts with the inputs name (NOT id)
				emailAddress: {
					maxlength: 128,
					required: true
				},
				firstName: {
					maxlength: 128,
					required: true
				},
				lastName: {
					maxlength: 128,
					required: true
				},
				phone: {
					maxlength: 24,
					required: true
				}
			},

			// error messages to display to the end user
			messages: {
				emailAddress: {
					maxlength: "email address is too long.",
					required: "Please enter a valid email address"
				},
				firstName: {
					maxlength: "First name is too long",
					required: "Missing first name"
				},
				lastName: {
					maxlength: "Last name is too long",
					required: "Missing last name"
				},
				phone: {
					maxlength: "Phone # is too long",
					required: "Missing phone number"
				}
			},

			// setup an AJAX call to submit the form without reloading
			submitHandler: function(form) {
				$(form).ajaxSubmit({
					// GET or POST
					type: "POST",
					// where to submit data
					url: "../php/controllers/request-invite-post.php",
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
	});