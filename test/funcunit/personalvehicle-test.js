// open a new window with the form under scrutiny
module("tabs", {
	setup: function() {
		F.open("../../php/controllers/personalvehicle.php");
	}
});

// global variables for form values
var VALID_FIRST_NAME = "Dan";
var VALID_LAST_NAME = "Clark";
var VALID_EMAIL = "dclark123@gmail.com";
var VALID_PHONE = "5055555555";
var VALID_VEHICLE_MAKE = "Ford";
var VALID_VEHICLE_MODEL = "Focus";
var VALID_VEHICLE_YEAR = 2005;
var VALID_VEHICLE_COLOR = "red";
var VALID_VEHICLE_PLATE_NUMBER = "abc123";
var VALID_PLATE_STATE = "NM";

//global invalid for form values
var INVALID_FIRST_NAME = "";
var INVALID_LAST_NAME = "";
var INVALID_EMAIL = "";
var INVALID_PHONE = "";
var INVALID_VEHICLE_MAKE = "";
var INVALID_VEHICLE_MODEL = "";
var INVALID_VEHICLE_YEAR = null;
var INVALID_VEHICLE_COLOR = "";
var INVALID_VEHICLE_PLATE_NUMBER = "";
var INVALID_PLATE_STATE = "";

/**
 * test filling in only valid form data
 **/
function testValidFields() {
	// fill in the form values
	F("#visitorFirstName").type(VALID_FIRST_NAME);
	F("#visitorLastName").type(VALID_LAST_NAME);
	F("#visitorEmail").type(VALID_EMAIL);
	F("#visitorPhone").type(VALID_PHONE);
	F("#vehicleMake").type(VALID_VEHICLE_MAKE);
	F("#vehicleModel").type(VALID_VEHICLE_MODEL);
	F("#vehicleYear").type(VALID_VEHICLE_YEAR);
	F("#vehicleColor").type(VALID_VEHICLE_COLOR);
	F("#vehiclePlateNumber").type(VALID_VEHICLE_PLATE_NUMBER);
	F("#vehiclePlateState").type(VALID_PLATE_STATE);

	// click the button once all the fields are filled in
	F("#submit").click();

	// in forms, we want to assert the form worked as expected
	// here, we assert we got the success message from the AJAX call
	F(".alert").visible(function() {
		// create a regular expression that evaluates the successful text
		var successRegex = /Information sent! Please Check Your Email./;

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
	F("#visitorFirstName").type(INVALID_FIRST_NAME);
	F("#visitorLastName").type(INVALID_LAST_NAME);
	F("#visitorEmail").type(INVALID_EMAIL);
	F("#visitorPhone").type(INVALID_PHONE);
	F("#vehicleMake").type(INVALID_VEHICLE_MAKE);
	F("#vehicleModel").type(INVALID_VEHICLE_MODEL);
	F("#vehicleYear").type(INVALID_VEHICLE_YEAR);
	F("#vehicleColor").type(INVALID_VEHICLE_COLOR);
	F("#vehiclePlateNumber").type(INVALID_VEHICLE_PLATE_NUMBER);
	F("#vehiclePlateState").type(INVALID_PLATE_STATE);

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