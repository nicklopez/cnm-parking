function showVisitor(emailAddress) {
	$.ajax({
		type: "POST",
		url: "../php/controllers/visitor-lookup.php",
		data: {emailAddress: emailAddress}
	}).done(function(ajaxOutput) {
		var result = ajaxOutput.split(",");
		$("#visitorFirstName").val(result[0]);
		$("#visitorLastName").val(result[1]);
		$("#visitorPhone").val(result[2]);
		$("#visitorId").val(result[3]);
		fetchVehicle(document.getElementById('visitorId').value);
	});
}

$(document).ready(function() {
	$("#visitorEmail").autocomplete({
		source: "../php/controllers/email-lookup.php",
		minLength: 1
	});

	$("#visitorEmail" ).on("autocompleteselect", function(event, ui) {
		showVisitor(ui.item.value);
	});

	$("#visitorEmail" ).on("input", function() {
		if(this.value === "") {
			return;
		} else {
			showVisitor(this.value);
		}
	});
});