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
        <div class="alert alert-danger" style="text-align: center">
           <strong>Oops! That account doesn't exist. Why not try again?</strong>
        </div>
        <?php
    }
    else
    {
        echo "<div id='bodyContent' class='body-content'>";
        echo "Account page for: ".$_GET['username'];
        
        ?>
        <div class="container">
            <div class="row">
                <div class="col-sm-4">
                    Side pane
                </div>
                <div class="col-sm-8">
                    <div class="row-sm-4">
                        Right side row 1
                    </div>
                    <div class="row-sm-4">
                        Right side row 2
                    </div>
                    <div class="row-sm-4">
                        Right side row 3
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
