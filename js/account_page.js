$(document).ready(function() {

	$('#editsub').click(function() {
		var subval = $(this).val();
		var username = $('#username').val();

		switch(subval)
		{
			case "0"://not subbed
				request = $.ajax({
					url: "accountViewAjax.php",
					type: "POST",
					data: {'action': 0, 'username': username}
				});

				request.done(function(data, textStatus, jqXHR) {
					if(data === "success")
					{	
						$('#editsub').attr("value", "1");
						$('#editsub').text("Unsubscribe");
					}
					else if(data === "default")
					{
						alert("Failed to subscribe");
					}
					else
						alert("Failed to subscribe");
				});

				request.fail(function(jqXHR, textStatus, errorThrown) {
					alert("Failed to subscribe");
				});

				break;
			case "1"://subbed

				request = $.ajax({
					url: "accountViewAjax.php",
					type: "POST",
					data: {'action': 1, 'username': username}
					
				});

				request.done(function(data, textStatus, jqXHR) {
					if(data === "success")
					{	
						$('#editsub').attr("value", "0");
						$('#editsub').text("Subscribe");
					}
					else if(data === "default")
					{
						alert("Failed to unsubscribe");
					}
					else
						alert("Failed to unsubscribe");
				});

				request.fail(function(jqXHR, textStatus, errorThrown) {
					alert("Failed to unsubscribe");
				});


				break;
			case "2"://login
				window.location = './login.php';
				break;
			case "3"://edit account
				window.location = './editaccount.php';
				break;
			default:
				alert("Something went wrong");
				break;
		}
	});

	$('#messagebox').click(function(){
		$('#messagesuccess').text("");
		$('#messageerror').text("");
		if($(this).val() == "Type your message here.")
			$(this).val("");
	});

	$('#messagesend').click(function(){
		var message = $('#messagebox').val();
		var username = $('#username').val();

		request = $.ajax({
			url: "accountViewAjax.php",
			type: "POST",
			data: {'action': 2, 'username': username, 'message': message}
		});

		request.done(function(data, textStatus, jqXHR) {
			if(data === "success")
			{
				$('#messageerror').text("");
				$('#messagesuccess').text("Message sent");
				$('#messagebox').val("");
			}
			else if(data === "empty")
			{
				$('#messagesuccess').text("");
				$('#messageerror').text("Cannot send empty message");
			}
			else if(data === "long")
			{
				$('#messagesuccess').text("");
				$('#messageerror').text("Message too long");
			}
			else if(data === "short")
			{
				$('#messagesuccess').text("");
				$('#messageerror').text("Message must be longer than 10 characters");
			}
			else
				alert("Failed to send message");
		});

		request.fail(function(jqXHR, textStatus, errorThrown) {

		});


	});
});
