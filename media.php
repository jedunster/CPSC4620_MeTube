<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php
	ini_set('session.save_path', getcwd(). '/tmp');
	session_start();
	include_once "function.php";
?>	
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Media</title>
<script src="Scripts/AC_ActiveX.js" type="text/javascript"></script>
<script src="Scripts/AC_RunActiveContent.js" type="text/javascript"></script>
</head>

<body>
<?php
if(isset($_GET['id'])) {
	$query = "SELECT * FROM media WHERE mediaid='".$_GET['id']."'";
	$result = mysql_query( $query );
	$result_row = mysql_fetch_row($result);
	
	//updateMediaTime($_GET['id']);
	
	$filename=$result_row[0];   ////0, 4, 2
	$filepath=$result_row[4]; 
	$type=$result_row[2];
	if(substr($type,0,5)=="image") //view image
	{
		echo "Viewing Picture:";
		echo $result_row[4];
		echo "<img src='".$filepath."'/>";
	}
	else if(substr($type,0,5)=="audio")
	{
		echo "Listening to: ";
		echo $result_row[0];
		echo 	"<br/>
			<audio controls>
				<source src='".$filepath."' type='".$type."'>
			</audio>";
	}
	else if(substr($type,0,5)=="video")
	{	
		echo "Viewing: ";
		echo $result_row[0];
		echo	"<br/>
                        <video width='".'854'."' height='".'480'."' controls>
                                <source src='".$filepath."' type='".$type."'>
                        </video>";
	}
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
