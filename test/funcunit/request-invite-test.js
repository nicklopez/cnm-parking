// open a new window with the form under scrutiny
module("tabs", {
	setup: function() {
		F.open("../../php/controllers/request-invite.php");
	}
});

// global variables for form values
var VALID_EMAIL = "nicklopez702@gmail.com";
var VALID_FIRST_NAME = "Nick";
var VALID_LAST_NAME = "Lopez";
var VALID_PHONE = "7027776666";
var INVALID_EMAIL = "";

https://bootcamp-coders.cnm.edu/classmaterials/week5/funcunit/
/**
 * test filling in only valid form data
 **/
function testValidFields() {
	// fill in the form values
	F("#emailAddress").type(VALID_EMAIL);
	F("#firstName").type(VALID_FIRST_NAME);
	F("#lastName").type(VALID_LAST_NAME);
	F("#phone").type(VALID_PHONE);

	// click the button once all the fields are filled in
	F("#sendRequest").click();

	// in forms, we want to assert the form worked as expected
	// here, we assert we got the success message from the AJAX call
	F(".alert").visible(function() {
		// create a regular expression that evaluates the successful text
		var successRegex = /Sign up successful! You will receive an email with additional info soon./;

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
	F("#emailAddress").type(INVALID_EMAIL);

	// click the button once all the fields are filled in
	F("#sendInvite").click();

	// in forms, we want to assert the form worked as expected
	// here, we assert we got the success message from the AJAX call
	F(".alert").visible(function() {
		// the ok() function from qunit is equivalent to SimpleTest's assertTrue()
		ok(F(this).hasClass("alert-danger"), "danger alert CSS");
		ok(F(this).html().indexOf("Please enter or verify the email address.") === 0, "unsuccessful message");
	});
}

// the test function *MUST* be called in order for the test to execute
test("test valid fields", testValidFields);
//test("test invalid fields", testInvalidFields);