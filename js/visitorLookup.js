function showVisitor(emailAddress) {
	$.ajax({
		type: "POST",
		url: "../php/controllers/visitorLookup.php",
		data: {emailAddress: emailAddress}
	}).done(function(ajaxOutput) {
		//$("#outputArea").html(ajaxOutput);
		var result = ajaxOutput.split(",");
		$("#visitorFirstName").val(result[0]);
		$("#visitorLastName").val(result[1]);
		$("#visitorPhone").val(result[2]);
		$("#visitorId").val(result[3]);
	});
}