$(document).ready(function() {

	$('#editsub').click(function() {
		var subval = $(this).val();
		var pageusername = $('#username').val();

		switch(subval)
		{
			case "0"://not subbed
				request = $.ajax({
					url: "accountViewAjax.php",
					type: "POST",
					data: {'action': 0, 'pageusername': pageusername}
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
					data: {'action': 1, 'pageusername': pageusername}
					
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
});
