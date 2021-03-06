// open a new window with the form under scrutiny
module("tabs", {
	setup: function() {
		F.open("../../admin-registration/index.php");
	}
});

// global variables for form values
var VALID_FIRST_NAME = "Mike";
var VALID_LAST_NAME = "Jones";
var VALID_EMAIL = "mjones3@gmail.com";
var VALID_PASSWORD = "password";

//global invalid for form values
var INVALID_FIRST_NAME = "";
var INVALID_LAST_NAME = "";
var INVALID_EMAIL = "";
var INVALID_PASSWORD = "";

/**
 * test filling in only valid form data
 **/
function testValidFields() {
	// fill in the form values
	F("#adminFirstName").type(VALID_FIRST_NAME);
	F("#adminLastName").type(VALID_LAST_NAME);
	F("#adminEmail").type(VALID_EMAIL);
	F("#password").type(VALID_PASSWORD);

	// click the button once all the fields are filled in
	F("#register").click();

	// in forms, we want to assert the form worked as expected
	// here, we assert we got the success message from the AJAX call
	F(".alert").visible(function() {

		// the ok() function from qunit is equivalent to SimpleTest's assertTrue()
		ok(F(this).hasClass("alert-success"), "successful alert CSS");
		ok(F(this).text("Success! Please check your email to finish registration."), "successful message");
	});
}

/**
 * test filling in invalid form data
 **/
function testInvalidFields() {
	// fill in the form values
	F("#adminFirstName").type(INVALID_FIRST_NAME);
	F("#adminLastName").type(INVALID_LAST_NAME);
	F("#adminEmail").type(INVALID_EMAIL);
	F("#password").type(INVALID_PASSWORD);


	// click the button once all the fields are filled in
	F("#register").click();

	// in forms, we want to assert the form worked as expected
	// here, we assert we got the success message from the AJAX call
	F(".alert").visible(function() {
		// the ok() function from qunit is equivalent to SimpleTest's assertTrue()
		ok(F(this).hasClass("alert-danger"), "danger alert CSS");
		ok(F(this).html().indexOf("Unable to complete request. Try again.") === 0, "unsuccessful message");
	});
}

// the test function *MUST* be called in order for the test to execute
test("test valid fields", testValidFields);
test("test invalid fields", testInvalidFields);