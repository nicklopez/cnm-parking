/**
 * open a new window with the form under scrutiny
 */

module("tabs", {
	setup: function() {
		F.open("../../verify-availability/index.php");
	}
});


/**
 * define global variables for form values
 * assuming 3 locations with three placards for each
 * testing for time windows of 2-4, 2-6, and 2-8
 */

var LOCATION_1 = "1";
var LOCATION_2 = "2";
var LOCATION_3 = "3";

var ARRIVAL_1 = "2020-02-02 02:00:00";
var DEPARTURE_1 = "2020-02-02 04:00:00";
var DEPARTURE_2 = "2020-02-02 06:00:00";
var DEPARTURE_3 = "2020-02-02 08:00:00";


/**
 * test 1 - Not Available
 */
function testVerifyAvailability1() {
	$("#intLocationInput").val(LOCATION_1);

	$("#dateTimePickerArrival").val(ARRIVAL_1);

	$("#dateTimePickerDeparture").val(DEPARTURE_1);

	F("#verifyAvailabilitySubmit").click();

	F("#isAvailable").visible(function() {
		ok(F(this).html().indexOf("No") === 0, " Not Available");
	});
}

/**
 * test 2 - Available
 */
function testVerifyAvailability2() {
	$("#intLocationInput").val(LOCATION_2);

	$("#dateTimePickerArrival").val(ARRIVAL_1);

	$("#dateTimePickerDeparture").val(DEPARTURE_1);

	F("#verifyAvailabilitySubmit").click();

	F("#isAvailable").visible(function() {
		ok(F(this).html().indexOf("Yes!") === 0, "Available");
	});
}

/**
 * test 3 - Available
 */
function testVerifyAvailability3() {
	$("#intLocationInput").val(LOCATION_3);

	F("#dateTimePickerArrival").type(ARRIVAL_1);

	F("#dateTimePickerDeparture").type(DEPARTURE_1);

	F("#verifyAvailabilitySubmit").click();

	F("#isAvailable").visible(function() {
		ok(F(this).html().indexOf("Yes!") === 0, "Available");
	});
}

/**
 * test 4 - Available
 */
function testVerifyAvailability4() {
	$("#intLocationInput").val(LOCATION_3);

	$("#dateTimePickerArrival").type(ARRIVAL_1);

	$("#dateTimePickerDeparture").type(DEPARTURE_2);

	F("#verifyAvailabilitySubmit").click();

	F("#isAvailable").visible(function() {
		ok(F(this).html().indexOf("Yes!") === 0, "Available");
	});
}

/**
 * test 5 - Not Available
 */
function testVerifyAvailability5() {
	$("#intLocationInput").val(LOCATION_3);

	F("#dateTimePickerArrival").type(ARRIVAL_1);

	F("#dateTimePickerDeparture").type(DEPARTURE_3);

	F("#verifyAvailabilitySubmit").click();

	F("#isAvailable").visible(function() {
		ok(F(this).html().indexOf("No.") === 0, " Not Available");
	});
}

	test("test 1 - Not Available", testVerifyAvailability1);
	test("test 2 - Available", testVerifyAvailability2);
	test("test 3 - Available", testVerifyAvailability3);
	test("test 4 - Available", testVerifyAvailability4);
	test("test 5 - Not Available", testVerifyAvailability5);