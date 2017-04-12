$(document).ready(function(){

	/* sets reply buttons to add recipient name to messaging area and
	 * sets typing foucs to the message box
	 */
	$('#reply').click(function(){
		var sender = $(this).val();

		$('#recipients').val(sender);
		$('#messagecontents').focus();
	});

	$('#sendmessage').click(function(){
		var recipientlist = $('#recipients').val();
		var message = $('#messagecontents').val();

		request = $.ajax({
			url: "messagePageAjax.php",
			type: "POST",
			data: {'action': 0, 'recipients': recipientList, 'message': message}
		});

		request.done(function(data, textStatus, jqXHR){
			switch(data)
			{
				case "success":
					$('#messageerror').text("");
					$('#messagesuccess').text("Message sent successfully");
					break;
				case "empty":
					$('#messagesuccess').text("");
					$('#messageerror').text("Message cannot be empty");
					break;
				case "short":
					$('#messagesuccess').text("");
					$('#messageerror').text("Message must be over 10 characters");
					break;
				case "long":
					$('#messagesuccess').text("");
					$('#messageerror').text("Message cannot be over 1000 characters");
					break;
			}
		});

		request.fail(function(jqXHR, textStatus, errorThrown){
			alert("Failed to send message");
		});

	});
});
