function acceptInvite(token) {
	$.ajax({
		type: "POST",
		url: "../php/controllers/send-invite-post.php",
		data: {token:token, accept:1}
	}).done(function(ajaxOutput) {
		$("#outputArea").html(ajaxOutput)
	});
}

function declineInvite(token) {
	$.ajax({
		type: "POST",
		url: "../php/controllers/send-invite-post.php",
		data: {token: token, decline: 0}
	}).done(function(ajaxOutput) {
		$("#outputArea").html(ajaxOutput)
	});
}