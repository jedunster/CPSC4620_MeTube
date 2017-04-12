<?php
if(session_id() == '')
{
	ini_set('session.save_path', getcwd(). '/tmp');
	session_start();
}
include_once "function.php";

$_SESSION['prevpage'] = "messages.php";
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Messages</title>
<script src="js/jquery-3.2.0.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/default.css" />
<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
<script src="js/bootstrap.min.js"></script>

</head>

<body>
<?php
include 'header.php';

$loggedin = isset($_SESSION['username']);


if($loggedin)
{
	$currentuser = $_SESSION['username'];
?>

<div class="container-fluid">
	<div class="row">
		<div class="col-sm-4" style="height: 90vh; overflow-y: auto">
			<h3 style="margin-left: 30px; float: left">Received Messages</h3><br/>
			<br/><br/>
			<?php
			if($query = mysqli_prepare(db_connect_id(), "SELECT message.message_id, sender_username, send_date, message_contents FROM message_recipient JOIN message ON message.message_id = message_recipient.message_id WHERE recipient_username = ? ORDER BY send_date DESC"))
			{
				mysqli_stmt_bind_param($query, "s", $currentuser);
				$result = mysqli_stmt_execute($query);
				$rows = 0;
				mysqli_stmt_bind_result($query, $messageid, $sender, $messagedate, $message);
				while($result = mysqli_stmt_fetch($query))
				{
					$rows++;
				?>
					<div class="inbox-message-pane">
					<?php
					echo "<div style='font-size: 16px'>From: <a href=account.php?username=".$sender.">".$sender."</a> ";
					echo "sent: ".$messagedate;
					echo "<button type='button' id='reply' value='".$messageid."' class='btn btn-primary' style='float: right; margin-right: 10px; margin-top: 5px'>Reply</button></div><br/>";
					echo "<div class='inbox-message'>".$message."</div>";
					?>	
					</div>

				<?php
				}
				if($rows == 0)
					echo "<h3 style='margin-left: 30px'>No messages</h3>";
			}
			?>
		</div>
		<div class="col-sm-4" style="height: 90vh; overflow-y: auto">
			<h3 style="margin-left: 30px; float: left">Sent Messages</h3><br/><br/><br/>
			<?php
			if($query = mysqli_prepare(db_connect_id(), "SELECT message.message_id, recipient_username, send_date, message_contents FROM message_recipient JOIN message ON message.message_id = message_recipient.message_id WHERE sender_username = ? ORDER BY send_date DESC"))
			{
				mysqli_stmt_bind_param($query, "s", $currentuser);
				$result = mysqli_stmt_execute($query);
				$rows = 0;
				mysqli_stmt_bind_result($query, $messageid, $recipient, $messagedate, $message);
				while($result = mysqli_stmt_fetch($query))
				{
					$rows++;
				?>
					<div class="inbox-message-pane">
					<?php
					echo "<div style='font-size: 16px'>Sent to: <a href=account.php?username=".$recipient.">".$recipient."</a> ";
					echo "on: ".$messagedate."</div><br/>";
					echo "<div class='inbox-message'>".$message."</div>";
					?>	
					</div>

				<?php
				}
				if($rows == 0)
					echo "<h3 style='margin-left: 30px'>No messages</h3>";
			}

			?>
		</div>
		<div class="col-sm-4" style="height: 90vh; overflow-y:auto">
			<h3 style="margin-left: 30px; float: left">Create a message</h3><br/><br/><br/>
			<div style="font-size: 16px">Recipients:</div><div style="font-size: 11px">&nbsp;&nbsp;&nbsp;separate usernames with commas</div>
			<input type="text" class="form-control" id="recipients" style="width: 30vw">
			<div style="font-size: 16px">Message:</div>
			<textarea class="form-control" rows="10" style="width: 30vw; resize: none"></textarea><br/>
			<button class="btn btn-primary" id="sendmessage">Send</button> 
		</div>
	</div>
</div>

<?php
}
else
{
?>
<meta http-equiv="refresh" content="0;url=login.php">
<?php
}
?>
</body>
</html>