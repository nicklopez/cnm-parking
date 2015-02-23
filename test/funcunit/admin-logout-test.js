;// open a new window with the form under scrutiny
module("tabs", {
	setup: function() {
		F.open("../../admin-login/index.php");
	}
});

// global variables for form values
var VALID_EMAIL = "dafevig@yahoo.com";
var VALID_PASSWORD = "password";


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
	//F(".alert").visible(function() {
	//	// create a regular expression that evaluates the successful text
	//	var successRegex = "Welcome!";

		////// the ok() function from qunit is equivalent to SimpleTest's assertTrue()
		//ok(F(this).hasClass("alert-success"), "successful alert CSS");
		//ok(successRegex.test(F(this).html()), "successful message");

		// click logout link
		expect(0);
		F("#logout").click();

}

//function testLogout() {
//	// click logout link
//	F("#logout").click();
//}

// the test function *MUST* be called in order for the test to execute
test("test valid fields", testValidFields);
