<head>
<link rel="stylesheet" type="text/css" href="css/default.css" />
<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
<script src="js/bootstrap.min.js"></script>
</head>

<?php
if($query = mysqli_prepare(db_connect_id(), "SELECT comment_id, username, comment_date, message FROM comment WHERE mediaid=? ORDER BY comment_date DESC"))
{
    mysqli_stmt_bind_param($query, "i", $_REQUEST['id']);
    $result = mysqli_stmt_execute($query);
    mysqli_stmt_bind_result($query, $comment_id, $commenter, $comment_date, $message);
}
else
{
    $result = false;
}

if($result && mysqli_stmt_fetch($query))
{
    echo "<div id='currentCommentsContainer' class='current-comments-container'><br>";
    
    do
    {
        ?>
        <div class='existing-comment-container'>
            <?php if(isset($_SESSION['username']) && $commenter === $_SESSION['username'])
            {
                echo "<span id='deleteCommentButton' class='glyphicon glyphicon-remove btn-edit-comment'></span>";
                echo "<span id='editCommentButton' class='glyphicon glyphicon-pencil btn-edit-comment'></span>";
            }
            ?>

            <p style='padding: 4px 5px 0px 5px'>
                <a href="account.php?username=<?php echo urlencode($commenter); ?>">
                    <?php echo $commenter; ?>
                </a>
                at <?php echo $comment_date; ?>:
            </p>

            
            <p class='comment-message'>
                <?php echo $message; ?>
            </p>
        </div><br>
        <?php
    }while(mysqli_stmt_fetch($query));

    mysqli_stmt_close($query);
    echo "</div>";
}
else
{
    if($query) mysqli_stmt_close($query);
    echo "<h4>No comments yet.</h4>";
}
?>