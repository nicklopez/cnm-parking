// open a new window with the form under scrutiny
module("tabs", {
	setup: function() {
		F.open("../../send-invite");
	}
});

/**
 * test filling in only valid form data
 **/
function testValidFields() {
	// fill in the form values
	//F("#emailAddress").type(VALID_EMAIL);

	// click the button once all the fields are filled in
	F("#accept").click();

	// in forms, we want to assert the form worked as expected
	// here, we assert we got the success message from the AJAX call
	F(".alert").visible(function() {

		// create a regular expression that evaluates the successful text
		var successRegex = /Invite sent to (\w+) (\w+) successfully!/;

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
	//F("#emailAddress").type(INVALID_EMAIL);

	// click the button once all the fields are filled in
	F("#decline").click();

	// in forms, we want to assert the form worked as expected
	// here, we assert we got the success message from the AJAX call
	F(".alert").visible(function() {

		// create a regular expression that evaluates the successful text
		var successRegex = /Decline message sent to (\w+) (\w+) successfully!/;

		// the ok() function from qunit is equivalent to SimpleTest's assertTrue()
		ok(F(this).hasClass("alert-warning"), "warning alert CSS");
		ok(successRegex.test(F(this).html()), "successful message");
	});
}

// the test function *MUST* be called in order for the test to execute
test("test valid fields", testValidFields);
test("test invalid fields", testInvalidFields);