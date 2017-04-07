<?php
ini_set('session.save_path', getcwd(). '/tmp');
session_start();
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon"/>
<title>Upload</title>
</head>

<body>
<?php
	include "header.php";
if(!isset($_SESSION['username']))
{?>
<meta http-equiv="refresh" content="0;url=browse.php">
<?php    
}
else
{
?>

<form method="post" action="media_upload_process.php" enctype="multipart/form-data" >
 
    <p style="margin:0; padding:0">
        <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
        Add a Media: <label style="color:#663399"><em> (Each file limit 10M)</em></label><br/>
        <input  name="file" type="file" size="50" />
  
        <input value="Upload" name="submit" type="submit" />
    </p>
 
                
</form>

</body>
</html>
<?php }?>
