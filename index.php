<?php
    if(session_id() == '')
    {
	    ini_set('session.save_path', getcwd(). '/tmp');
        session_start();
    }
	include_once "function.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon"/>
<title>Media Browse</title>
<link rel="stylesheet" type="text/css" href="css/default.css" />
<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
<script type="text/javascript" src="js/jquery-3.2.0.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/browse_page.js"></script>
<script type="text/javascript">

function saveDownload(id)
{
	$.post("media_download_process.php",
	{
       id: id
    }
    );
} 
</script>
</head>

<body>

<?php
    include 'header.php';
?>
<div class="browse-sidenav-container">
    <h3 style="text-align: center">Browse Media</h3>
    <div id="homeTabButton" class='browse-sidenav-button home active'>
        Home
        <span class='glyphicon glyphicon-menu-right browse-sidenav-icon'></span>
    </div>
    <div id="funnyCategoryButton" class='browse-sidenav-button category'>
        <input type="hidden" name="categoryName" value="Funny">
        Funny Category
        <span class='glyphicon glyphicon-menu-right browse-sidenav-icon'></span>
    </div>
    <div id="musicCategoryButton" class='browse-sidenav-button category'>
        <input type="hidden" name="categoryName" value="Music">
        Music Category
        <span class='glyphicon glyphicon-menu-right browse-sidenav-icon'></span>
    </div>
    <div id="sportsCategoryButton" class='browse-sidenav-button category'>
        <input type="hidden" name="categoryName" value="Sports">
        Sports Category
        <span class='glyphicon glyphicon-menu-right browse-sidenav-icon'></span>
    </div>
    <div id="informativeCategoryButton" class='browse-sidenav-button category'>
        <input type="hidden" name="categoryName" value="Informative">
        Informative Category
        <span class='glyphicon glyphicon-menu-right browse-sidenav-icon'></span>
    </div>
    <div id="otherCategoryTabButton" class='browse-sidenav-button category bottom'>
        <input type="hidden" name="categoryName" value="Other">
        Other Category
        <span class='glyphicon glyphicon-menu-right browse-sidenav-icon'></span>
    </div>
</div>


<div id='bodyContent' class='browse-body-content'>
<?php 
    include 'browsehome.php';
?>
</div>
</body>
</html>
