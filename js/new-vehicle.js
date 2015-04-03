function test() {
	if (document.getElementById('selectVehicle').value == 'addVehicle') {
		document.getElementById('extra').style.display = 'block';
	} else {
		document.getElementById('extra').style.display = 'none';
	}
}
