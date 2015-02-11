// open a new window with the form under scrutiny
module("tabs", {
	setup: function() {
		F.open("../../php/controllers/send-invite.php");
	}
});

// global variables for form values
var VALID_EMAIL = "nicklopez702@gmail.com";
var INVALID_EMAIL = "";

/**
 * test filling in only valid form data
 **/
function testValidFields() {
	// fill in the form values
	F("#emailAddress").type(VALID_EMAIL);

	// click the button once all the fields are filled in
	F("#sendInvite").click();

	// in forms, we want to assert the form worked as expected
	// here, we assert we got the success message from the AJAX call
	F(".alert").visible(function() {
		// create a regular expression that evaluates the successful text
		var successRegex = /Invite sent!/;

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
test("test invalid fields", testInvalidFields);