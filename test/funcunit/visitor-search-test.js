// open a new window with the form under scrutiny
module("tabs", {
	setup: function() {
		F.open("../../visitor-search/index.php");
	}
});

// global variables for form values
var VALID_NAME = "Jimmy Bob";
var VALID_EMAIL = "abcd@123.com";
var VALID_PLATE = "abc-123";

var INVALID_NAME = "Jack Smack";
var INVALID_EMAIL = "jack@smack.com";
var INVALID_PLATE = "jacksmak";

/**
 * test valid search by name
 **/
function testValidSearchByName() {
	// select the search by name radio button
	F("#radioVisitorSearchByName").click();

	// fill in the form values
	F("#textVisitorSearchInput").type(VALID_NAME);

	// click the search button
	F("#visitorSearchSubmit").click();

	// in forms, we want to assert the form worked as expected
	// here, we assert we got the success message from the AJAX call
	F(".table").visible(function() {
		// create a regular expression that evaluates the successful text
		var successRegex = /Jimmy/;

		// the ok() function from qunit is equivalent to SimpleTest's assertTrue()
		ok(successRegex.test(F(this).html()), "successful message");
	});
}

/**
 * test valid search by email
 **/
function testValidSearchByEmail() {
	// select the search by email radio button
	F("#radioVisitorSearchByEmail").click();

	// fill in the form values
	F("#textVisitorSearchInput").type(VALID_EMAIL);

	// click the search button
	F("#visitorSearchSubmit").click();

	// in forms, we want to assert the form worked as expected
	// here, we assert we got the success message from the AJAX call
	F(".table").visible(function() {
		// create a regular expression that evaluates the successful text
		var successRegex = /Jimmy/;

		// the ok() function from qunit is equivalent to SimpleTest's assertTrue()
		ok(successRegex.test(F(this).html()), "successful message");
	});
}

/**
 * test valid search by plate
 **/
function testValidSearchByPlate() {
	// select the search by plate radio button
	F("#radioVisitorSearchByPlate").click();

	// fill in the form values
	F("#textVisitorSearchInput").type(VALID_PLATE);

	// click the search button
	F("#visitorSearchSubmit").click();

	// in forms, we want to assert the form worked as expected
	// here, we assert we got the success message from the AJAX call
	F(".table").visible(function() {
		// create a regular expression that evaluates the successful text
		var successRegex = /Jimmy/;

		// the ok() function from qunit is equivalent to SimpleTest's assertTrue()
		ok(successRegex.test(F(this).html()), "successful message");
	});
}


/**
 * test invalid search by name
 **/
function testInvalidSearchByName() {
	// select the search by name radio button
	F("#radioVisitorSearchByName").click();

	// fill in the form values
	F("#textVisitorSearchInput").type(INVALID_NAME);

	// click the search button
	F("#visitorSearchSubmit").click();

	// in forms, we want to assert the form worked as expected
	// here, we assert we got the success message from the AJAX call
	F(".alert").visible(function() {
		// the ok() function from qunit is equivalent to SimpleTest's assertTrue()
		ok(F(this).hasClass("alert-info"), "danger alert CSS");
		ok(F(this).html().indexOf("No results") === 0, "unsuccessful message");
	});
}

/**
 * test invalid search by email
 **/
function testInvalidSearchByEmail() {
	// select the search by email radio button
	F("#radioVisitorSearchByEmail").click();

	// fill in the form values
	F("#textVisitorSearchInput").type(INVALID_EMAIL);

	// click the search button
	F("#visitorSearchSubmit").click();

	// in forms, we want to assert the form worked as expected
	// here, we assert we got the success message from the AJAX call
	F(".alert").visible(function() {
		// the ok() function from qunit is equivalent to SimpleTest's assertTrue()
		ok(F(this).hasClass("alert-info"), "danger alert CSS");
		ok(F(this).html().indexOf("No results") === 0, "unsuccessful message");
	});
}

/**
 * test invalid search by plate
 **/
function testInvalidSearchByPlate() {
	// select the search by plate radio button
	F("#radioVisitorSearchByPlate").click();

	// fill in the form values
	F("#textVisitorSearchInput").type(INVALID_PLATE);

	// click the search button
	F("#visitorSearchSubmit").click();

	// in forms, we want to assert the form worked as expected
	// here, we assert we got the success message from the AJAX call
	F(".alert").visible(function() {
		// the ok() function from qunit is equivalent to SimpleTest's assertTrue()
		ok(F(this).hasClass("alert-info"), "danger alert CSS");
		ok(F(this).html().indexOf("No results") === 0, "unsuccessful message");
	});
}

// the test function *MUST* be called in order for the test to execute
test("test valid search by name", testValidSearchByName);
test("test valid search by email", testValidSearchByEmail);
test("test valid search by plate", testValidSearchByPlate);
test("test invalid search by name", testInvalidSearchByName);
test("test invalid search by email", testInvalidSearchByEmail);
test("test invalid search by plate", testInvalidSearchByPlate);