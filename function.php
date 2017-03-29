<?php
include "mysqlClass.inc.php";

function user_exist_check ($username, $password)
{
    if($query = mysqli_prepare(db_connect_id(), "SELECT username FROM account WHERE username=?"))
    {
        mysqli_stmt_bind_param($query, "s", $username);
        $result = mysqli_stmt_execute($query);
        mysqli_stmt_bind_result($query, $fetchedUsername);
    }
    else
    {
		die ("user_exist_check() failed. Could not query the database: <br />". mysqli_error());
    }

    if (!$result)
    {
        mysqli_stmt_close($query);
		die ("user_exist_check() failed. Could not query the database: <br />". mysqli_error());
	}	
    else
    {
        if(!mysqli_stmt_fetch($query))
        {
            mysqli_stmt_close($query);
			$hash = password_hash($password, PASSWORD_DEFAULT);
			
            if($query = mysqli_prepare(db_connect_id(), "INSERT INTO account (username, password) VALUES (?, ?)"))
            {
                mysqli_stmt_bind_param($query, "ss", $username, $hash);
                $insert = mysqli_stmt_execute($query);
                mysqli_stmt_close($query);

                if($insert)
                    return 1; //New user correctly inserted
                else
				    die ("Could not insert into the database: <br />". mysqli_error( db_connect_id() ));
            }
            else
            {
                die ("Could not insert into the database: <br />". mysqli_error( db_connect_id() ));
            }
        }
        else
        {
            mysqli_stmt_close($query);
			return 2; //Username already exists
		}
	}
}


function user_pass_check($username, $password)
{
    if($query = mysqli_prepare(db_connect_id(), "SELECT password FROM account WHERE username=?"))
    {
        mysqli_stmt_bind_param($query, "s", $username);
        $result = mysqli_stmt_execute($query);
        mysqli_stmt_bind_result($query, $fetchedPassword);
    }
    else
    {
		die ("user_pass_check() failed. Could not query the database: <br />". mysqli_error());
    }
    
    if (!$result or !mysqli_stmt_fetch($query))
    {
        mysqli_stmt_close($query);
	    die ("user_pass_check() failed. Could not query the database: <br />". mysqli_error( db_connect_id() ));
	}
    else
    {
        mysqli_stmt_close($query);
		if(password_verify($password, $fetchedPassword))
			return 0; //Correct password
		else
			return 2; //Wrong
	}
}

//Sets the last access time for the given media to now
//This was provided by the TA. It is not consistent with the current
//structure of the database.
function updateMediaTime($mediaid)
{
    if($query = mysqli_prepare(db_connect_id(), "UPDATE media SET lastaccesstime=NOW() WHERE mediaid=?"))
    {
        mysqli_stmt_bind_param($query, "i", $mediaid);
        $result = mysqli_stmt_execute($query);
        mysqli_stmt_close($query);
        if (!$result)
	    {
	        die ("updateMediaTime() failed. Could not query the database: <br />". mysqli_error( db_connect_id() ));
        }
    }
    else
    {
		die ("updateMediaTime() failed. Could not query the database: <br />". mysqli_error( db_connect_id() ));
    }
}

function upload_error($result)
{
	//view erorr description in http://us2.php.net/manual/en/features.file-upload.errors.php
	switch ($result){
	case 1:
		return "UPLOAD_ERR_INI_SIZE";
	case 2:
		return "UPLOAD_ERR_FORM_SIZE";
	case 3:
		return "UPLOAD_ERR_PARTIAL";
	case 4:
		return "UPLOAD_ERR_NO_FILE";
	case 5:
		return "File has already been uploaded";
	case 6:
		return  "Failed to move file from temporary directory";
	case 7:
		return  "Upload file failed";
	}
}

function other()
{
	//You can write your own functions here.
}
	
?>
