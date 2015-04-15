function test() {
	if (document.getElementById('selectVehicle').value === 'addVehicle') {
		document.getElementById('extra').style.visibility="visible";
	} else {
		document.getElementById('extra').style.visibility="hidden";
	}
}

function fetchVehicle(visitorId) {
		$.ajax({
			type: "POST",
			url: "../php/controllers/vehicle-lookup.php",
			data: {visitorId: visitorId}
		}).always(function(ajaxOutput) {
			$("#selectVehicle").empty();
			$('#selectVehicle').append("<option value='0' selected> -- Select Your Vehicle -- </option>");
			$('#selectVehicle').append(ajaxOutput);
			$('#selectVehicle').append("<option value='addVehicle'>Add New Vehicle</option>");
		});
}

