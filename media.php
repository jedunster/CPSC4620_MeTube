<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php
	ini_set('session.save_path', getcwd(). '/tmp');
	session_start();
	include_once "function.php";
?>	
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Media</title>
<script src="Scripts/AC_ActiveX.js" type="text/javascript"></script>
<script src="Scripts/AC_RunActiveContent.js" type="text/javascript"></script>
</head>

<body>
<?php
if(isset($_GET['id']))
{
    if($query = mysqli_prepare(db_connect_id(), "SELECT title, type, path FROM media WHERE mediaid=?"))
    {
        mysqli_stmt_bind_param($query, "i", $_GET['id']);
        $result = mysqli_stmt_execute($query);
        mysqli_stmt_bind_result($query, $title, $type, $filepath);
        $result = $result and mysqli_stmt_fetch($query);
        mysqli_stmt_close($query);
        
        if (!$result)
	    {
	        die ("Media lookup failed in media.php. Could not query the database: <br />". mysqli_error( db_connect_id() ));
        }
    }
    
    //updateMediaTime($_GET['id']);
	
	if(substr($type,0,5)=="image") //view image
	{
		echo "Viewing Picture:";
		echo $title;
		echo "<img src='".$filepath."'/>";
	}
	else if(substr($type,0,5)=="audio")
	{
		echo "Listening to: ";
		echo $title;
		echo 	"<br/>
			<audio controls>
				<source src='".$filepath."' type='".$type."'>
			</audio>";
	}
	else if(substr($type,0,5)=="video")
	{	
		echo "Viewing: ";
		echo $title;
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
