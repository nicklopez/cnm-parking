/**
 * open a new window with the form under scrutiny
 */

module("tabs", {
	setup: function() {
		F.open("../../varify-availability/index.php");
	}
});

/**
 * define global variables for form values
 * assuming 3 locations with three placards for each
 * testing for time windows of 2-4, 2-6, and 2-8
 */

var LOCATION_1 = 1;
var LOCATION_2 = 2;
var LOCATION_3 = 3;

var ARRIVAL_1 = "2020-02-02 02:00:00";
var DEPARTURE_1 = "2020-02-02 04:00:00";
var DEPARTURE_2 = "2020-02-02 06:00:00";
var DEPARTURE_3 = "2020-02-02 08:00:00";


/**
 * test 1 - FAIL
 */
function testVerifyAvailability1() {
	F("#intLocationInput").val(LOCATION_1);

	F("#dateTimePickerArrival").type(ARRIVAL_1);

	F("#dateTimePickerDeparture").type(DEPARTURE_1);
}

F(".alert").visible(function() {

}




test("test 1 - FAIL", testVerifyAvailability1);
test("test 2 - PASS", testVerifyAvailability2);
test("test 3 - PASS", testVerifyAvailability3);
test("test 4 - PASS", testVerifyAvailability4);
test("test 5 - FAIL", testVerifyAvailability5);