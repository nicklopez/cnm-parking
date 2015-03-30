/**
 * Created by nicklopez on 3/30/15.
 */
$('#dateTimePickerArrival').datetimepicker({
	format: 'Y/m/d H:i',
	formatTime: 'g:i A',
	allowTimes: ['8:00 AM', '9:00 AM', '10:00 AM', '11:00 AM', '12:00 PM', '1:00 PM', '2:00 PM', '3:00 PM', '4:00 PM'
		, '5:00 PM', '6:00 PM'],
	step: 60,
	defaultSelect: false,
	defaultTime: '8:00 AM'
});

$('#dateTimePickerDeparture').datetimepicker({
	format: 'Y/m/d H:i',
	formatTime: 'g:i A',
	allowTimes: ['9:00 AM', '10:00 AM', '11:00 AM', '12:00 PM', '1:00 PM', '2:00 PM', '3:00 PM', '4:00 PM'
		, '5:00 PM', '6:00 PM', '7:00 PM'],
	step: 60,
	defaultSelect: false,
	defaultTime: '9:00 AM'
});