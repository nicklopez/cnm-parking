// open a new window with the form under scrutiny
module("tabs", {
	setup: function() {
		F.open("../../admin-login/index.php");
	}
});

// global variables for form values
var VALID_EMAIL = "dafevig@yahoo.com";
var VALID_PASSWORD = "password";

//global invalid for form values
var INVALID_EMAIL = "";
var INVALID_PASSWORD = "";

/**
 * test filling in only valid form data
 **/
function testValidFields() {
	// fill in the form values
	F("#adminEmail").type(VALID_EMAIL);
	F("#password").type(VALID_PASSWORD);

	// click the button once all the fields are filled in
	F("#submit").click();

	// in forms, we want to assert the form worked as expected
	// here, we assert we got the success message from the AJAX call
	F(".alert").visible(function() {
		// create a regular expression that evaluates the successful text
		var successRegex = /Admin \(id = \d+\) logged in!/;

		// the ok() function from qunit is equivalent to SimpleTest's assertTrue()
		ok(F(this).hasClass("alert-success"), "successful alert CSS");
		ok(successRegex.test(F(this).html()), "successful message");
	});
}

/**
 * test filling in invalid form data
 **/
function testInvalidFields() {
	// fill in the form values
	F("#adminEmail").type(INVALID_EMAIL);
	F("#password").type(INVALID_PASSWORD);


	// click the button once all the fields are filled in
	F("#submit").click();

	// in forms, we want to assert the form worked as expected
	// here, we assert we got the success message from the AJAX call
	F(".alert").visible(function() {
		// the ok() function from qunit is equivalent to SimpleTest's assertTrue()
		ok(F(this).hasClass("alert-danger"), "danger alert CSS");
		ok(F(this).html().indexOf("Please enter or verify the form fields.") === 0, "unsuccessful message");
	});
}

// the test function *MUST* be called in order for the test to execute
test("test valid fields", testValidFields);
test("test invalid fields", testInvalidFields);