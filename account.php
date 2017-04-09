<?php
	ini_set('session.save_path', getcwd(). '/tmp');
	session_start();
	include_once "function.php";
?>	
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Account</title>
<link rel="stylesheet" type="text/css" href="css/default.css" />
<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
<script src="js/bootstrap.min.js"></script>
<script src="Scripts/AC_ActiveX.js" type="text/javascript"></script>
<script src="Scripts/AC_RunActiveContent.js" type="text/javascript"></script>
</head>

<body>
<?php
    include 'header.php';

if(isset($_GET['username']))
{
    if(!user_exist_check($_GET['username']))
    {
        ?>
        <div class="alert alert-danger" style="text-align: center; margin-bottom: 5px">
           <strong>Oops! That account doesn't exist. Why not try again?</strong>
        </div>
        <?php
    }
    else
    {
		echo "<div style=\"margin-left: 15px\">";
        
        ?>
        <div class="container-fluid">
			<div class="row">
				<div class="col-sm-3" style="height: 90.7vh; overflow-y: auto">
					<?php
					echo "<h3 class=\"media-title\">";
					echo $_GET['username']."'s Profile &nbsp;";
					
					$issubbed = 0;
					if($query = mysqli_prepare(db_connect_id(), "SELECT * FROM subscription WHERE subscriber_username=? AND subscribee_username=?"))
					{
						mysqli_stmt_bind_param($query, "ss", $_SESSION['username'], $_GET['username']);
						$result = mysqli_stmt_execute($query);
						$result = mysqli_stmt_fetch($query);
						mysqli_stmt_close($query);
					}
					if($result)
						$issubbed = 1;


					echo "<button type=\"button\" id=\"editsub\" class=\"btn btn-primary\" value=".$issubbed.">";
					if(isset($_SESSION['username']))
					{
						if($_GET['username'] == $_SESSION['username'])
						{
							echo "Edit profile";
						}
						else
						{
							if(!$issubbed)
								echo "Subscribe";
							else
								echo "Unsubscribe";
						}
					}
					else
					{
						echo "Login to subscribe";
					}

					echo "</button></h3><br/><br/>";
					?>
					<h4>
						About me:
					</h4>
					<div style="font-size: 15px">
						<?php
						$email = "";
						$bio = "";
						if($query = mysqli_prepare(db_connect_id(), "SELECT email, summary FROM account WHERE username=?"))
						{
							mysqli_stmt_bind_param($query, "s", $_GET['username']);
							$result = mysqli_stmt_execute($query);
							mysqli_stmt_bind_result($query, $email, $bio);
							$result = $result && mysqli_stmt_fetch($query);
							mysqli_stmt_close($query);
						}
						echo "Email: ";
						echo $email;
						echo "<br/>";
						echo "Bio:";
						echo "<br/>";
						echo "<div class=\"account-details-container\">";
						echo "<p style=\"margin: 10px 10px 10px 10px\">";
						if(strlen($bio) > 0)
							echo $bio;
						else
							echo "No bio given";
						echo "</p>";
						echo "</div><br/><br/>";

						if(isset($_SESSION['username']) && $_SESSION['username'] != $_GET['username'])
						{
							echo "Send " . $_GET['username'] . " a message:<br/>";
							?>
							<textarea rows="4" maxlength="750" class="form-control commment-text" style="resize: none">Type your message here.</textarea>
							<br/>
							<button type="button" class="btn btn-primary" id="messagesend">Send</button>
							<br/>
							<?php
						}
						
						echo "<br/><br/><h4>My subscriptions:</h4><br/>";
						$subbeduser = "";
						if($query = mysqli_prepare(db_connect_id(), "SELECT subscribee_username FROM subscription WHERE subscriber_username=?"))
						{
							mysqli_stmt_bind_param($query, "s", $_GET['username']);
							$result = mysqli_stmt_execute($query);
							mysqli_stmt_bind_result($query, $subbeduser);
							while(mysqli_stmt_fetch($query))
							{
								echo "<a href=\"account.php?username=".$subbeduser."\">".$subbeduser."</a><br/>";
							}
							mysqli_stmt_close($query);
						}
						?>
					</div>
				</div>
                <div class="col-sm-9">
                    <div class="row" style="height: 30.2vh; overflow-y: auto">
						<h4>Uploads
						<?php
						if($_SESSION['username'] == $_GET['username'])
							echo "<button type=\"button\" class=\"btn btn-primary\" id=\"newupload\">New upload</button></h4>";
						else
							echo "</h4>";
						?>
                    </div>
                    <div class="row" style="height: 30.2vh; overflow-y: auto">
						<h4>Playlists
						<?php
						if($_SESSION['username'] == $_GET['username'])
							echo "<button type=\"button\" class=\"btn btn-primary\" id=\"newplaylist\">New playlist</button></h4>";
						else
							echo "</h4>";

						if($query = mysqli_prepare(db_connect_id(), "SELECT playlist_id, name FROM playlist WHERE username=?"))
						{
							mysqli_stmt_bind_param($query, "s", $_GET['username']);
							$result = mysqli_stmt_execute($query);
							mysqli_stmt_bind_result($query, $listid, $listname);
							while(mysqli_stmt_fetch($query))
							{
								echo "<a href=\"playlist.php?id=".$listid."\">".$listname."</a><br/>";
								echo "The media list will be here<br/><br/>";
							}
							mysqli_stmt_close($query);
						}

						?>

                    </div>
                    <div class="row" style="height: 30.3vh; overflow-y: auto">
						<h4>Favorites
						<?php
						if($_SESSION['username'] == $_GET['username'])
							echo "<button type=\"button\" class=\"btn btn-primary\" id=\"editfavorites\">Edit</button></h4>";
						else
							echo "</h4>";
						?>

                    </div>
                </div>
            </div>
        </div>
        <?php

        echo "</div>";
    }
}
elseif(isset($_SESSION['username']))
{

    echo "<meta http-equiv=\"refresh\" content=\"0;url=account.php?username=".$_SESSION['username']."\">";
}
else
{
?>
<meta http-equiv="refresh" content="0;url=browse.php">
<?php
}
?>
</body>
</html>
