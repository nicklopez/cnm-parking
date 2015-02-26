// open a new window with the form under scrutiny
module("tabs", {
	setup: function() {
		F.open("../../request-invite");
	}
});

// global variables for form values
var VALID_EMAIL = "nicklopez702@gmail.com";
var VALID_FIRST_NAME = "Nick";
var VALID_LAST_NAME = "Lopez";
var VALID_PHONE = "7027776666";

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
			// create successful message
			var success = /Request sent successfully! You will receive an email with additional details shortly./;

			// the ok() function from qunit is equivalent to SimpleTest's assertTrue()
			ok(F(this).hasClass("alert-success"), "successful alert CSS");
			ok(success.test(F(this).text()), "successful message");
		});
	}

/**
 * test filling in invalid form data
 **/
function testInvalidFields() {
	// fill in the form values
	F("#firstName").type(VALID_FIRST_NAME);
	F("#lastName").type(VALID_LAST_NAME);
	F("#phone").type(VALID_PHONE);

	// click the button once all the fields are filled in
	F("#sendRequest").click();

	// in forms, we want to assert the form worked as expected
	// here, we assert we got the success message from the AJAX call
	F(".label-danger").visible(function() {
		// the ok() function from qunit is equivalent to SimpleTest's assertTrue()
		ok(F(this).hasClass("label-danger"), "danger alert CSS");
		ok(F(this).text(/Please enter a valid email address/), "unsuccessful message");
	});
}

// the test function *MUST* be called in order for the test to execute
test("test valid fields", testValidFields);
test("test invalid fields", testInvalidFields);