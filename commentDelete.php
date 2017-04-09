<?php
    ini_set('session.save_path', getcwd(). '/tmp');
	session_start();
	include_once "function.php";
    
    //PHP file for handling AJAX request to submit comments
    if(isset($_REQUEST['commentid']))
    {
        if(remove_comment($_REQUEST['commentid']))
            echo "success";
        else
            echo "Failed to delete comment.";
    }
    else if(!isset($_REQUEST['commentid']))
    {
        echo "The comment id is not set correctly.";
    }
?>
