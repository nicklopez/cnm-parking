function showVisitor(emailAddress) {
	//if (str == "") {
	//	document.getElementById("txtHint").innerHTML = "";
	//	return;
	//} else {
	//	if (window.XMLHttpRequest) {
	//		// code for IE7+, Firefox, Chrome, Opera, Safari
	//		xmlhttp = new XMLHttpRequest();
	//	} else {
	//		// code for IE6, IE5
	//		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	//	}
	//	xmlhttp.onreadystatechange = function() {
	//		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
	//			document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
	//		}
	//	}
	//	xmlhttp.open("GET","visitorLookup.php?q="+str,true);
	//	xmlhttp.send();
	//}

	$.ajax({
		type: "POST",
		url: "../php/controllers/visitorLookup.php",
		data: {emailAddress:emailAddress}
	}).done(function(ajaxOutput) {
		//alert(data['firstName']);
		$("#outputArea").html(ajaxOutput),
		.select(function(ajaxOutput)
		{
			$("#visitorFirstName").val(ajaxOutput);
			//$("#visitorLastName").value(ajaxOutput);
		})
	});
}
