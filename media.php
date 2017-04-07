<?php
	ini_set('session.save_path', getcwd(). '/tmp');
	session_start();
	include_once "function.php";
?>	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Media</title>
<link rel="stylesheet" type="text/css" href="css/default.css" />
<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
<script src="js/bootstrap.min.js"></script>
<script src="Scripts/AC_ActiveX.js" type="text/javascript"></script>
<script src="Scripts/AC_RunActiveContent.js" type="text/javascript"></script>
</head>

<body>
<?php
    include 'header.php';

if(isset($_GET['id']))
{
    echo "<div id='bodyContent' class='body-content'>";
    //Get the media's information from the database
    if($query = mysqli_prepare(db_connect_id(), "SELECT username, title, type, path, upload_date, description, category FROM media WHERE mediaid=?"))
    {
        mysqli_stmt_bind_param($query, "i", $_GET['id']);
        $result = mysqli_stmt_execute($query);
        mysqli_stmt_bind_result($query, $username, $title, $type, $filepath, $date, $description, $category);
        $result = $result && mysqli_stmt_fetch($query);
        mysqli_stmt_close($query);
    }
    else
    {
        $result = false;
    }

    $keywords = get_media_keywords($_GET['id']);

    //If the media was found, display it for the user, otherwise show a warning.
    if($result)
    {
        echo "<h3 class='media-title'>$title</h3><br>";
        echo "<div id='mediaContainer' class='media-container'>";

        if(substr($type,0,5)=="image") //view image
        {
            echo "<img class='media-item' src='".$filepath."'/>";
        }
        else if(substr($type,0,5)=="audio")
        {
            echo 	"<audio controls>
                        <source class='media-item' src='".$filepath."' type='".$type."'>
                    </audio>";
        }
        else if(substr($type,0,5)=="video")
        {	
            echo	"<video class='media-item' width='".'854'."' height='".'480'."' controls>
                        <source src='".$filepath."' type='".$type."'>
                    </video>";
        }
?>
        </div>
        <div id='mediaDetailsContainer' class='media-details-container'>
            <p class='media-description-value'>
                <strong>Uploaded By:</strong>
                <a href="account.php?username=<?php echo urlencode($username); ?>">
                    <?php echo $username; ?>
                </a>
            </p>
            <p class='media-description-value'>
                <strong>Upload Time:</strong> <?php echo $date; ?>
            </p>
            <p class='media-description-value'>
                <strong>Description:</strong>
                <?php
                    if($description != NULL)
                        echo $description;
                    else
                        echo "No description.";
                ?>
            </p>
            <p class='media-description-value'>
                <strong>Category:</strong>
                <?php
                    if($category != NULL)
                        echo $category;
                    else
                        echo "No category.";
                ?>
            </p>
            <p class='media-description-value'>
                <strong>Keywords:</strong>
                <?php
                    if($keywords != NULL)
                        echo $keywords;
                    else
                        echo "No keywords.";
                ?>
            </p>
        </div>
        <br>
        <h3 class='media-title'>Comments</h3>
        <br>
        <?php
        if(isset($_SESSION['username']))
        {
            ?>
            <div id="submitCommentContainer" class="submit-comment-container">
                <form id="makeCommentForm" class="submit-comment-form">
                    <h5>Write a comment:<h5>
                    <textarea rows="2" maxlength="750" id="commentText" name="commentText" class="form-control comment-text">
                    </textarea>
                    <br>
                    <input type="submit" id="commentSubmit" name="commentSubmit" class="btn btn-primary btn-right-align" value="Submit">
                </form>
            </div>
            <br>
            <?php
        }
        
        echo "<div id='commentSection'>";
        include "comments.php"; 
        echo "</div>";
?>

<?php
    }
    else
    {
?>
        <div class="alert alert-danger" style="text-align: center">
            <strong>Error:</strong> The selected media item could not be found.
        </div>
<?php
    }
    echo "</div>";
}
else
{
?>
<meta http-equiv="refresh" content="0;url=browse.php">
<?php
}
?>
</body>
</html>
