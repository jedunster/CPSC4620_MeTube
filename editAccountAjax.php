<?php
    if(session_id() == '')
    {
        ini_set('session.save_path', getcwd(). '/tmp');
        session_start();
    }
    include_once "function.php";

    //A PHP file to handle all ajax requests supplied by the edit account page
    if(isset($_REQUEST['action']))
    {
        switch($_REQUEST['action'])
        {
            case 0:
                //PHP code for updating profile information
                if(isset($_REQUEST['email']) && isset($_REQUEST['summary']) 
                && isset($_SESSION['username']))
                {
                    if(update_account_info($_SESSION['username'], $_REQUEST['summary'], $_REQUEST['email']))
                        echo "success";
                    else
                        echo "Failed to update profile.";
                }
                else if(!isset($_REQUEST['email']))
                {
                    echo "The email field is not set correctly.";
                }
                else if(!isset($_REQUEST['summary']))
                {
                    echo "The summary field is not set correctly";
                }
                else if(!isset($_SESSION['username']))
                {
                    echo "You must be logged in to edit your profile.";
                }
                break;
            case 1:
                //PHP code for updating account password
                if(isset($_REQUEST['currentPassword']) && isset($_REQUEST['newPassword1']) 
                && isset($_SESSION['username']))
                {
                    switch(update_user_pass($_SESSION['username'], $_REQUEST['currentPassword'],
                        $_REQUEST['newPassword1']))
                    {
                        case 0:
                            echo "success";
                            break;
                        case 1:
                            echo "Your account is set incorrectly.";
                            break;
                        case 2:
                            echo "Incorrect current password entered.";
                            break;
                        default:
                            break;
                    }
                }
                else if(!isset($_REQUEST['currentPassword']))
                {
                    echo "The current password is not set correctly.";
                }
                else if(!isset($_REQUEST['newPassword1']))
                {
                    echo "The new password is not set correctly";
                }
                else if(!isset($_SESSION['username']))
                {
                    echo "You must be logged in to change your password.";
                }
                break;
            default:
                echo "Invalid AJAX action supplied.";
                break;
        }
    }
    else
    {
        echo "The action was not set correctly.";
    }
?>
