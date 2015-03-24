
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
				location.href = "//cnmparking.com/php/test-portal/test-portal.php";
			});
		});
	});