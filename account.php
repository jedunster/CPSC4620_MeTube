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
<script src="js/jquery-3.2.0.min.js"></script>
<script src="js/account_page.js"></script>

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
					<br/>
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
					elseif(!isset($_SESSION['username']))
						$issubbed = 2;
					elseif($_SESSION['username'] == $_GET['username'])
						$issubbed = 3;

					echo "<br/><br/><br/>";
					echo "<button type=\"button\" id=\"editsub\" class=\"btn btn-primary\" style=\"float: left\" value=".$issubbed.">";
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
                    <div class="row" style="height: 28vh; overflow-y: auto; margin-bottom: 10px">
						<h4>Uploads
						<?php
						if($_SESSION['username'] == $_GET['username'])
							echo "<button type=\"button\" onclick=\"window.location.href='./media_upload.php'\" class=\"btn btn-primary\" id=\"newupload\">New upload</button></h4>";
						else
							echo "</h4>";

						if($query = mysqli_prepare(db_connect_id(), "SELECT title, type, mediaid, upload_date, category FROM media WHERE username=? ORDER BY upload_date DESC"))
						{
							mysqli_stmt_bind_param($query, "s", $_GET['username']);
							$result = mysqli_stmt_execute($query);
							mysqli_stmt_bind_result($query, $mediatitle, $mediatype, $mediaid, $mediadate, $mediacat);
							while(mysqli_stmt_fetch($query))
							{
								echo "<div class=\"account-media-details-container\">";

								switch(substr($mediatype,0,5))
								{
									case "video":
										echo "<span class=\"glyphicon glyphicon-film\"></span> ";
										break;
									case "audio":
										echo "<span class=\"glyphicon glyphicon-music\"></span> ";
										break;
									case "image":
										echo "<span class=\"glyphicon glyphicon-picture\"></span> ";
										break;
									default: echo substr($mediatype,0,5);
								}
								echo "<a href=\"media.php?id=".$mediaid."\">".$mediatitle."</a><br/>";
								echo "Uploaded: ".$mediadate."<br/>";
								echo "Category: ".$mediacat; if($mediacat == NULL) echo "None";
								echo "</div>";
							}
							mysqli_stmt_close($query);
						}

						?>
                    </div>
                    <div class="row" style="height: 30.5vh; overflow-y: auto; margin-bottom: 10px; border-top: solid grey">
						<h4>Playlists
						<?php
						if($_SESSION['username'] == $_GET['username'])
							echo "<button type=\"button\" class=\"btn btn-primary\" id=\"newplaylist\">New playlist</button></h4>";
						else
							echo "</h4>";





						if($query = mysqli_prepare(db_connect_id(), "SELECT name, playlist_id FROM playlist WHERE playlist.username = ? ORDER BY creation_date DESC"))
						{
							mysqli_stmt_bind_param($query, "s", $_GET['username']);
							$result = mysqli_stmt_execute($query);
							$query->store_result();
							mysqli_stmt_bind_result($query, $listname, $listid);
							while(mysqli_stmt_fetch($query))
							{
								echo "<div style=\"float: left; margin-bottom: 10px\">";
								echo "<a href=\"playlist.php?id=".$listid."\" style=\"font-size: 16px\">".$listname."</a><br/>";
								if($query1 = mysqli_prepare(db_connect_id(), "SELECT title, username, media.mediaid, upload_date, category FROM playlist_media LEFT JOIN media ON playlist_media.mediaid = media.mediaid WHERE playlist_id = ? ORDER BY upload_date DESC"))	
								{
									mysqli_stmt_bind_param($query1, "i", $listid);
									$result = mysqli_stmt_execute($query1);
									$query1->store_result();
									mysqli_stmt_bind_result($query1, $mediatitle, $mediauser, $mediaid, $mediadate, $mediacat);
									while(mysqli_stmt_fetch($query1))
									{
										echo "<div class=\"account-media-details-container\">";

										switch(substr($mediatype,0,5))
										{
											case "video":
												echo "<span class=\"glyphicon glyphicon-film\"></span> ";
												break;
											case "audio":
												echo "<span class=\"glyphicon glyphicon-music\"></span> ";
												break;
											case "image":
												echo "<span class=\"glyphicon glyphicon-picture\"></span> ";
												break;
											default: echo substr($mediatype,0,5);
										}
										echo "<a href=\"media.php?id=".$mediaid."\">".$mediatitle."</a><br/>";
										echo "Uploaded: ".$mediadate."<br/>";
										echo "By: ".$mediauser."<br/>";
										echo "Category: ".$mediacat; if($mediacat == NULL) echo "None";
										echo "</div>";

									}
									
								}
								echo "</div>";
							}
							mysqli_stmt_close($query);

						}


						?>

                    </div>
                    <div class="row" style="height: 30.4vh; overflow-y: auto; border-top: solid grey">
						<h4>Favorites
						<?php
						if($_SESSION['username'] == $_GET['username'])
							echo "<button type=\"button\" class=\"btn btn-primary\" id=\"editfavorites\">Edit</button></h4>";
						else
							echo "</h4>";

						if($query = mysqli_prepare(db_connect_id(), "SELECT title, media.username, type, media.mediaid, upload_date, category FROM media JOIN favorited_media ON media.mediaid = favorited_media.mediaid WHERE favorited_media.username=? ORDER BY upload_date DESC"))
						{
							mysqli_stmt_bind_param($query, "s", $_GET['username']);
							$result = mysqli_stmt_execute($query);
							mysqli_stmt_bind_result($query, $mediatitle, $mediauser, $mediatype, $mediaid, $mediadate, $mediacat);
							while(mysqli_stmt_fetch($query))
							{
								echo "<div class=\"account-media-details-container\">";

								switch(substr($mediatype,0,5))
								{
									case "video":
										echo "<span class=\"glyphicon glyphicon-film\"></span> ";
										break;
									case "audio":
										echo "<span class=\"glyphicon glyphicon-music\"></span> ";
										break;
									case "image":
										echo "<span class=\"glyphicon glyphicon-picture\"></span> ";
										break;
									default: echo substr($mediatype,0,5);
								}
								echo "<a href=\"media.php?id=".$mediaid."\">".$mediatitle."</a><br/>";
								echo "Uploaded: ".$mediadate."<br/>";
								echo "By: ".$mediauser."<br/>";
								echo "Category: ".$mediacat; if($mediacat == NULL) echo "None";
								echo "</div>";
							}
							mysqli_stmt_close($query);
						}

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
