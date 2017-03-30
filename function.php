<?php
include "mysqlClass.inc.php";

//Gets the number of rows matched by the last MySQL
//query sent to the database given by $link.
function get_matched_rows($link)
{
    return preg_match("!\d+!", mysqli_info($link));
}

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
            //Hash the password before storing it so it is not in plaintext
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

//Checks to see whether the supplied password is the correct one for the
//given username. Dies if it cannot connect to the database.
//Return codes:     0 - Correct password provided
//                  1 - Account does not exist
//                  2 - Incorrect password provided
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
    
    if (!$result)
    {
        mysqli_stmt_close($query);
	    die ("user_pass_check() failed. Could not query the database: <br />". mysqli_error( db_connect_id() ));
    }

    if(!mysqli_stmt_fetch($query))
    {
        mysqli_stmt_close($query);
        return 1;
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

//Updates the given user's password to the given new password, as long as the
//correct current password is supplied. Dies if it cannot connect to the database.
//Return Codes:     0 - Password successfully changed
//                  1 - Account does not exist
//                  2 - Wrong current password provided
function update_user_pass($username, $currpassword, $newpassword)
{
    $passcheck = user_pass_check($username, $currpassword);
    if($passcheck != 0)
        return $passcheck;
    
    //Hash the password before storing it so it is not in plaintext
	$hash = password_hash($newpassword, PASSWORD_DEFAULT);
        
    if($query = mysqli_prepare(db_connect_id(), "UPDATE account SET password=? WHERE username=?"))
    {
        mysqli_stmt_bind_param($query, "ss", $hash, $username);
        $result = mysqli_stmt_execute($query);
        $matched = get_matched_rows(db_connect_id());
        mysqli_stmt_close($query);
        if (!$result || $matched < 1)
	    {
	        die("update_user_pass() failed. Could not query the database: <br />". mysqli_error( db_connect_id() ));
        }
        else
        {
            return 0;
        }
    }
    else
    {
	    die("update_user_pass() failed. Could not query the database: <br />". mysqli_error( db_connect_id() ));
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
function update_account_info($username, $summary, $email)
{
    if($query = mysqli_prepare(db_connect_id(), "UPDATE account SET summary=?, email=? WHERE username=?"))
    {
        mysqli_stmt_bind_param($query, "sss", $summary, $email, $username);
        $result = mysqli_stmt_execute($query);
        $matched = get_matched_rows(db_connect_id());
        mysqli_stmt_close($query);
        if (!$result || $matched < 1)
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

//Sets the metadata for the media item with the given mediaid to have the given title,
//description, category, and keywords. The keywords should take the form of an array of
//strings of keywords. Returns true on a successfull change, false on an unsuccessful
//change.
function update_media_metadata($mediaid, $title, $description, $category, $keywords)
{
    if($query = mysqli_prepare(db_connect_id(), "UPDATE media SET title=?, description=?, category=? WHERE mediaid=?"))
    {
        mysqli_stmt_bind_param($query, "sssi", $title, $description, $category, $mediaid);
        $result = mysqli_stmt_execute($query);
        $matched = get_matched_rows(db_connect_id());
        mysqli_stmt_close($query);
        if (!$result || $matched < 1)
	    {
	        return false;
        }
    }
    else
    {
		return false;
    }

    if(!delete_media_keywords($mediaid))
        return false;

    $success = true;
    foreach($keywords as $currkeyword)
    {
        if(!add_media_keyword($mediaid, $currkeyword))
            $success = false;
    }

    return $success;
}

//Adds the given keyword to the media item with the given id. Returns true on
//success, false on failure.
function add_media_keyword($mediaid, $keyword)
{
    if($query = mysqli_prepare(db_connect_id(), "INSERT INTO media_keyword (mediaid, keyword) VALUES (?, ?)"))
    {
        mysqli_stmt_bind_param($query, "is", $mediaid, $keyword);
        $result = mysqli_stmt_execute($query);
        $affected = mysqli_affected_rows(db_connect_id());
        $errno = mysqli_errno(db_connect_id()); //Report success on error if it was just a duplicate entry warning
        mysqli_stmt_close($query);
        if ((!$result || $affected < 1) && ($errno != 1062))
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


//Deletes all the keywords for the medai item with the given mediaid. Returns true
//on success, false on failure.
function delete_media_keywords($mediaid)
{
    if($query = mysqli_prepare(db_connect_id(), "DELETE FROM media_keyword WHERE mediaid=?"))
    {
        mysqli_stmt_bind_param($query, "i", $mediaid);
        $result = mysqli_stmt_execute($query);
        $affected = mysqli_affected_rows(db_connect_id());
        mysqli_stmt_close($query);
        if (!$result || $affected < 0)
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
        username, mediaid, comment_date, message) VALUES (NULL, ?, ?, NOW(), ?)"))
    {
        mysqli_stmt_bind_param($query, "sis", $username, $mediaid, $message);
        $result = mysqli_stmt_execute($query);
        $affected = mysqli_affected_rows(db_connect_id());
        mysqli_stmt_close($query);
        if (!$result || $affected < 1)
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
        $affected = mysqli_affected_rows(db_connect_id());
        mysqli_stmt_close($query);
        if (!$result || $affected < 1)
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
        $matched = get_matched_rows(db_connect_id());
        mysqli_stmt_close($query);
        if (!$result || $matched < 1)
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
