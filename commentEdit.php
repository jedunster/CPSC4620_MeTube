<?php
    ini_set('session.save_path', getcwd(). '/tmp');
	session_start();
	include_once "function.php";
    
    //PHP file for handling AJAX request to submit comments
    if(isset($_REQUEST['commentidField']) && isset($_REQUEST['commentText']))
    {
        if(update_comment($_REQUEST['commentidField'], $_REQUEST['commentText']))
            echo "success";
        else
            echo "Failed to update comment.";
    }
    else if(!isset($_REQUEST['commentidField']))
    {
        echo "The comment id is not set correctly.";
    }
    else if(!isset($_REQUEST['commentText']))
    {
        echo "The comment text is not set correctly.";
    }
?>
