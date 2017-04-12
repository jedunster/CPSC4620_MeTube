<?php
if(session_id() == '')
{
    ini_set('session.save_path', getcwd(). '/tmp');
    session_start();
}
include_once "function.php";

if(isset($_SESSION['username']) && user_exist_check($_SESSION['username']) == 1)
{
?>
    <h3>Update Password</h3>
    <form id="updatePasswordForm" method="post">
        <h4 style="margin-bottom:0px; margin-top: 20px;">Current Password</h4>
        <input name="currentPassword" type="password" class="form-control" style="width: 300px;">

        <h4 style="margin-bottom:0px; margin-top: 20px;">New Password</h4>
        <input name="newPassword1" type="password" class="form-control" style="width: 300px;">
        
        <h4 style="margin-bottom:0px; margin-top: 20px;">Retype New Password</h4>
        <input name="newPassword2" type="password" class="form-control" style="width: 300px;">
        
        <input style="margin-top: 20px;" value="Submit" name="submit" type="submit" class="btn btn-primary"/>
    </form>
<?php
}
else
{
    echo "<h3>Unable to retrieve account.</h3>";
}
?>
