<?php
session_start();
include_once "function.php";

/******************************************************
*
* download by username
*
*******************************************************/

$username=$_SESSION['username'];
$mediaid=$_REQUEST['id'];

//insert into download table
$insertDownload="insert into download(download_id,username,mediaid, download_date) values(NULL,'$username','$mediaid', NOW())";
$queryresult = mysql_query($insertDownload)
	
?>


