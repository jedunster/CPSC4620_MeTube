<?php
	if(session_id() == '')
	{
		ini_set('session.save_path', getcwd(). '/tmp');
		session_start();
	}

	include_once "function.php";

if(isset($_REQUEST['action']))
{
	switch($_REQUEST['action'])
	{
		case 0://subscribe
			if(isset($_REQUEST['pageusername']) && isset($_SESSION['username']) && $query = mysqli_prepare(db_connect_id(), "INSERT INTO subscription (subscriber_username, subscribee_username) VALUES (?, ?)"))
			{
				mysqli_stmt_bind_param($query, "ss", $_SESSION['username'], $_REQUEST['pageusername']);
				$result = mysqli_stmt_execute($query);
				if($result)
					echo "success";
				else 
					echo "failed";
				mysqli_stmt_close($query);
			}
			else echo "failed";
			break;
		
		case 1://unsubscribe
			if(isset($_REQUEST['pageusername']) && isset($_SESSION['username']) && $query = mysqli_prepare(db_connect_id(), "DELETE FROM subscription WHERE subscriber_username = ? AND subscribee_username = ?"))
			{
				mysqli_stmt_bind_param($query, "ss", $_SESSION['username'], $_REQUEST['pageusername']);
				$result = mysqli_stmt_execute($query);
				if($result)
					echo "success";
				else
					echo "failed here";
				mysqli_stmt_close($query);
			}
			//else echo "failed";

			break;

		case 2://send message
			break;
		default:
			echo "default";
			break;
	}
}
else
	echo "The action was not set correctly";

?>
