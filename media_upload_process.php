<?php
ini_set('session.save_path', getcwd(). '/tmp');
session_start();
include_once "function.php";

/******************************************************
*
* upload document from user
*
*******************************************************/

$username=$_SESSION['username'];


//Create Directory if doesn't exist
if(!file_exists('uploads/'))
	mkdir('uploads/', 0757);
    $dirfile = 'uploads/'.$username.'/';
    if(!file_exists($dirfile))
	    mkdir($dirfile,0755);
	chmod($dirfile,0755);
	if($_FILES["file"]["error"] > 0 )
    {
        $result=$_FILES["file"]["error"]; //error from 1-4
    }
	else
    {

		$upfile = $dirfile.urlencode($_FILES["file"]["name"]);
	  
	    if(file_exists($upfile))
	    {
            $result="5"; //The file has been uploaded.
	    }
        else
        {
			if(is_uploaded_file($_FILES["file"]["tmp_name"]))
			{
				if(!move_uploaded_file($_FILES["file"]["tmp_name"],$upfile))
				{
					$result="6"; //Failed to move file from temporary directory
				}
				else /*Successfully upload file*/
                {
                    //Get the parts of the filepath to construct a new one
                    $pathinfo = pathinfo($upfile);
                    
                    //insert into media table
					$insert = "insert into media(mediaid, title, username, type, path, size, upload_date)".
                        "values(NULL,'". urlencode($_FILES["file"]["name"]) ."','$username','".
                        $_FILES["file"]["type"] ."', concat('". $pathinfo['dirname']."/', (select auto_increment
                        from information_schema.tables where table_name='media'), '.".
                        $pathinfo['extension'] ."'), ". $_FILES["file"]["size"] .", NOW())";
					$queryresult = mysqli_query( db_connect_id(), $insert )
                        or (unlink($upfile) and die("Insert into Media error in media_upload_process.php " .mysqli_error(db_connect_id())))
                        or die("Insert into Media error and Delete File Error in media_upload_process.php ".mysqli_error(db_connect_id()));
                    $result="0";
                    $insert_id = mysqli_insert_id(db_connect_id());
                    chmod($upfile, 0644);
                    rename($upfile, $pathinfo['dirname']."/".$insert_id.".".$pathinfo['extension']);
				}
			}
			else  
			{
					$result="7"; //upload file failed
			}
		}
	}
	
	//You can process the error code of the $result here.
?>

<meta http-equiv="refresh" content="0;url=browse.php?result=<?php echo $result;?>">
