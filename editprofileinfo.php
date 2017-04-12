<?php
if(session_id() == '')
{
    ini_set('session.save_path', getcwd(). '/tmp');
    session_start();
}
include_once "function.php";

if(isset($_SESSION['username']) && user_exist_check($_SESSION['username']) == 1)
{
    if($query = mysqli_prepare(db_connect_id(), "SELECT email, summary FROM account WHERE username=?"))
    {
        mysqli_stmt_bind_param($query, "s", $_SESSION['username']);
        $result = mysqli_stmt_execute($query);
        mysqli_stmt_bind_result($query, $email, $summary);
        $result = $result && mysqli_stmt_fetch($query);
        mysqli_stmt_close($query);
    }
    else
    {
        $result = false;
    }

    if($result)
    {
?>
        <h3>Edit Profile Information</h3>
        <form id="updateProfileForm" method="post">
            <h4 style="margin-bottom:0px; margin-top: 20px;">Email</h4>
            <input name="email" type="text" class="form-control" style="width: 300px;" value="<?php echo $email; ?>">

            <h4 style="margin-bottom:0px; margin-top: 20px;">Bio</h4>
            <textarea name="summary" class="form-control" rows="3" style="width: 300px;" maxlength="750"><?php echo $summary; ?></textarea>
            
            <input style="margin-top: 20px;" value="Submit" name="submit" type="submit" class="btn btn-primary"/>
        </form>
<?php
    }
    else
    {
        echo "<h3>Unable to retrieve account.</h3>";
    }
}
else
{
    echo "<h3>Unable to retrieve account.</h3>";
}
?>
