<?php
    if(session_id() == '')
    {
            ini_set('session.save_path', getcwd(). '/tmp');
        session_start();
    }
include_once "function.php";
$comments = 0;
if(isset($_POST['allowComments'])) $comments = 1;
if(update_media_metadata($_POST['mediaid'],$_POST['title'],$_POST['description'],$_POST['category'],array_from_keywords($_POST['keywords']), $comments)){
	$result = 0;
}else $result = "Media edit failed";
?>
<meta http-equiv="refresh" content="0;url=index.php<?php if($result != 0) echo "?result=", $result;?>">

