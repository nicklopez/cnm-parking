function test() {
	if (document.getElementById('selectVehicle').value === 'addVehicle') {
		document.getElementById('extra').style.visibility="visible";
	} else {
		document.getElementById('extra').style.visibility="hidden";
	}
}
