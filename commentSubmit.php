<?php
    ini_set('session.save_path', getcwd(). '/tmp');
	session_start();
	include_once "function.php";
    
    //PHP file for handling AJAX request to submit comments
    if(isset($_REQUEST['commentText']) && isset($_REQUEST['mediaidField']) 
    && isset($_SESSION['username']))
    {
        if(add_comment($_SESSION['username'], $_REQUEST['mediaidField'], $_REQUEST['commentText']))
            echo "success";
        else
            echo "Failed to submit comment.";
    }
    else if(!isset($_REQUEST['commentText']))
    {
        echo "The comment text is not set correctly.";
    }
    else if(!isset($_REQUEST['mediaidField']))
    {
        echo "The media id is not set correctly.";
    }
    else if(!isset($_SESSION['username']))
    {
        echo "You must be logged in to submit a comment.";
    }
?>