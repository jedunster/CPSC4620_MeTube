<?php
ini_set('session.save_path', getcwd(). '/tmp');
session_start();
include_once "function.php";

/******************************************************
*
* upload avatar from user
*
*******************************************************/

$username=$_SESSION['username'];


//Create Directory if doesn't exist
if(!file_exists('avatars/'))
    mkdir('avatars/', 0755;
$dirfile = 'avatars/'

if($_FILES["file"]["error"] > 0 )
{
    $result=$_FILES["file"]["error"]; //error from 1-4
}
else
{
    //Get the parts of the filepath to construct a new one
    $pathinfo = pathinfo($_FILES['file']['name']);
    $upfile = $dirfile.$username."_avatar.".$pathinfo['extension'];
    if(is_uploaded_file($_FILES['file']['tmp_name']))
    {
        if(!move_uploaded_file($_FILES['file']['tmp_name'],$upfile))
        {
            $result="6"; //Failed to move file from temporary directory
        }
        else /*Successfully upload file*/
        {
                
            //insert into media table
            if($query = mysqli_prepare(db_connect_id(), "UPDATE account SET
                avatar_path=? WHERE username=?"))
            {
                $title = urlencode($_FILES['file']['name']);
                mysqli_stmt_bind_param($query, "ss", $upfile, $username);
                $insert = mysqli_stmt_execute($query)
                    or (unlink($upfile) and die("Insert into media error in avatar_upload_process.php " .mysqli_error(db_connect_id())))
                    or die("Insert into media error and delete file error in avatar_upload_process.php " .mysqli_error(db_connect_id()));
                mysqli_stmt_close($query);
            }
            else
            {
                die("Insert into media error in avatar_upload_process.php " .mysqli_error(db_connect_id()));
            }

            $result="0";
            chmod($upfile, 0644);
        }
    }
    else  
    {
        $result="7"; //upload file failed
    }
}

//You can process the error code of the $result here.
?>

<meta http-equiv="refresh" content="0;url=account.php?result=<?php echo $result;?>">
