<?php
    if(session_id() == '')
    {
	    ini_set('session.save_path', getcwd(). '/tmp');
        session_start();
    }
    include_once "function.php";
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon"/>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/default.css">
	<script src="js/jquery-3.2.0.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <title>Edit Account</title>
</head>

<body>
<?php
    if(!isset($_SESSION['username']) || user_exist_check($_SESSION['username']) != 1)
    {
        $_SESSION['prevpage'] = "editaccount.php";
        echo "<meta http-equiv='refresh' content='0;url=login.php'>";
    }

	include "header.php";
?>

</body>
