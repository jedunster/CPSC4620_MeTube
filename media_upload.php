<?php
    if(session_id() == '')
    {
	    ini_set('session.save_path', getcwd(). '/tmp');
        session_start();
    }
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
<div style="margin-left: 15px;">


	<form class="form-horizontal" method="post" action="media_upload_process.php" enctype="multipart/form-data" >
		
   		
        	
        	<h3>Upload a File:</h3>
		
	<label class="btn btn-primary btn-file">
				
    			Browse 
		 <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
		<input name="file" type="file" style="display:none;" onchange="$('#upload-file-info').html($(this).val());">
		</label>
		<span class="label label-info" id="upload-file-info">Choose a file </span>
		<h4 style="margin-bottom:0px; margin-top: 20px;">Title</h4>
		<input name="title" type="text" class="form-control" style="width: 300px;">

		<h4 style="margin-bottom:0px; margin-top: 20px;">Description</h4>
  		<textarea name="description" class="form-control" rows="5" style="width: 300px;"></textarea>

		<h4 style="margin-bottom:0px; margin-top: 20px;">Category</h4>
		<select name="category"class="form-control" style="width: 175px;">
			<option>Funny</option>
			<option>Music</option> 
			<option>Sports</option> 
			<option>Informative</option> 
			<option>Other</option> 
		</select>			

        	
		<h4 style="margin-bottom:0px; margin-top: 20px;">Keywords (Space Separated)</h4>
                <input name="keywords" type="text" class="form-control" style="width: 300px;">


		<input style="margin-top: 20px;" value="Upload" name="submit" type="submit" class="btn btn-primary"/>
    		
 		
                
	</form>

<h5 style="margin-top: 20px">Supports File Formats supported by HTML5</h5>
</div>
</body>
</html>
<?php }?>
