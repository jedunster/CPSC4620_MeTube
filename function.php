<?php
include "mysqlClass.inc.php";

// Check if the given username exists in the database.
// Return codes:    0 - User does not exist
//                  1 - User does exist
//                  2 - Could not connect to server
//                  3 - Could not execute query
function user_exist_check($username)
{
    if($query = mysqli_prepare(db_connect_id(), "SELECT username FROM account WHERE username=?"))
    {
        mysqli_stmt_bind_param($query, "s", $username);
        if(!mysqli_stmt_execute($query)) return 2; //Query failed
        mysqli_stmt_bind_result($query, $fetchedUsername);
        $exists = mysqli_stmt_fetch($query);
        mysqli_stmt_close($query);

        if($exists)
            return 1; //User exists
        else
            return 0; //User does not exist
    }
    else
    {
        return 2; //Could not connect
    }
}

function add_account_to_db($username, $password)
{
    $userExists = user_exist_check($username);

    if ($userExists > 1)
    {
		die ("user_exist_check() failed. Could not query the database: <br />". mysqli_error());
	}	
    else
    {
        if($userExists == 0)
        {
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

//Change the summary and email for an existing account given by username. Returns true
//on a successful change, false on an unsuccessful change.
function update_account_information($username, $summary, $email)
{
    if($query = mysqli_prepare(db_connect_id(), "UPDATE account SET summary=?, email=? WHERE username=?"))
    {
        mysqli_stmt_bind_param($query, "sss", $summary, $email, $username);
        $result = mysqli_stmt_execute($query);
        mysqli_stmt_close($query);
        if (!$result || mysqli_affected_rows(db_connect_id()) < 1)
	    {
	        return false;
        }
        else
        {
            return true;
        }
    }
    else
    {
		return false;
    }
}

//Adds a comment from the given username on the given media with the given
//message. Returns true on success, false on failure.
function add_comment($username, $mediaid, $message)
{
    if($query = mysqli_prepare(db_connect_id(), "INSERT INTO comment (comment_id,
        username, media_id, comment_date, message) VALUES (NULL, ?, ?, NOW(), ?)"))
    {
        mysqli_stmt_bind_param($query, "sis", $username, $mediaid, $message);
        $result = mysqli_stmt_execute($query);
        mysqli_stmt_close($query);
        if (!$result || mysqli_affected_rows(db_connect_id()) < 1)
	    {
	        return false;
        }
        else
        {
            return true;
        }
    }
    else
    {
		return false;
    }
}

//Removes the comment with the given commentid. Returns true on success,
//false on failure.
function remove_comment($commentid)
{
    if($query = mysqli_prepare(db_connect_id(), "DELETE FROM comment WHERE comment_id=?"))
    {
        mysqli_stmt_bind_param($query, "i", $commentid);
        $result = mysqli_stmt_execute($query);
        mysqli_stmt_close($query);
        if (!$result || mysqli_affected_rows(db_connect_id()) < 1)
	    {
	        return false;
        }
        else
        {
            return true;
        }
    }
    else
    {
		return false;
    }
}

//Updates the comment with the given commentid to have the given message.
//Returns true on success, false on failure.
function update_comment($commentid, $message)
{
    if($query = mysqli_prepare(db_connect_id(), "UPDATE comment SET message=? WHERE comment_id=?"))
    {
        mysqli_stmt_bind_param($query, "si", $message, $commentid);
        $result = mysqli_stmt_execute($query);
        mysqli_stmt_close($query);
        if (!$result || mysqli_affected_rows(db_connect_id()) < 1)
	    {
	        return false;
        }
        else
        {
            return true;
        }
    }
    else
    {
		return false;
    }
}


function other()
{
	//You can write your own functions here.
}
	
?>
