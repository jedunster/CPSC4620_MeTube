<?php
	ini_set('session.save_path', getcwd(). '/tmp');
	session_start();
	include_once "function.php";

if(isset($_REQUEST['commentid']))
{
    if($query = mysqli_prepare(db_connect_id(), "SELECT comment_id, message FROM comment WHERE comment_id=?"))
    {
        mysqli_stmt_bind_param($query, "i", $_REQUEST['commentid']);
        $result = mysqli_stmt_execute($query);
        mysqli_stmt_bind_result($query, $comment_id, $message);
    }
    else
    {
        $result = false;
    }

    if($result && mysqli_stmt_fetch($query))
    {
        ?>
        <form class="edit-comment-form">
            <h5>Edit your comment:<h5>
            <textarea rows="2" maxlength="750" name="commentText" class="form-control comment-text"><?php echo $message; ?></textarea>
            <br>
            <input type="hidden" name="commentidField" value="<?php echo $comment_id; ?>">
            <input type="submit" name="commentEditSubmit" class="btn btn-primary btn-right-align btn-comment-edit-submit" value="Submit">
            <input type="button" name="commentEditCancel" class="btn btn-primary btn-right-align btn-comment-edit-cancel" value="Cancel">
            <p class="comment-validation"></p>
        </form>
        <?php

        mysqli_stmt_close($query);
    }
    else
    {
        if($query) mysqli_stmt_close($query);
        ?>      
        <h4>Error load comment edit window.</h4>      
        <?php
    }
}
else
{
    ?>      
    <h4>Error load comment edit window.</h4>      
    <?php
}

?>
