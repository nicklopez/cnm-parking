//document ready event
$(document).ready(
	// inner function for the ready() event
	function() {

		$(".portalButton").click(function() {
			var adminProfileId = $(this).attr("id");
			$.ajax({
				type: "POST",
				url: "login-session.php",
				data: {adminProfileId: adminProfileId}
			}).done(function() {
				location.href = "/php/portal-home/index.php";
			});
		});

		// page is now ready, initialize the calendar...
		$('#fullCalendar').fullCalendar({
			theme: false,
			header: {
				left: "title",
				center: "",
				right: "today, prev, next"
			},
			//events: "../controllers/event.php",
			eventSources: [
				{
					url: "../controllers/all-day-event.php",
					color: 'grey'
					//textColor: 'black'
				},

				{
					url: "../controllers/event.php"
				}
			],
			allDay: true
		});
	});