/* Javascript file for AJAX requests, form submission, and validation for
 * editing profile information.
 */

var emailValid = true;
var summaryValid = true;
var currPasswordValid = false;
var newPasswordValid = false;

//Function to validate an email address, returning true if the given
//variable is a properly formatted email address
function emailValidation(email)
{
    var regex = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
    return regex.test(email);
}

//Runs when the document is ready to set onclick functions
$(document).ready(function() {

    //A function to set the form submission and validation effects
    function setFormEvents() {
        
        emailValid = true;
        summaryValid = true;
        currPasswordValid = false;
        newPasswordValid = false;

        if($('#updateProfileForm').length)
        {
            $('#updateEmail').on('change', function() {
                $("#updateProfileValidation").text('');
                if(!emailValidation($(this).val()))
                {
                    $('#updateEmailValidation').text('Invalid email entered.');
                    emailValid = false;
                }
                else if($(this).val().length > 255)
                {
                    $('#updateEmailValidation').text('Email address too long.');
                    emailValid = false;
                }
                else
                {
                    emailValid = true;
                    $('#updateEmailValidation').text('');
                }
            });

            $('#updateSummary').on('change', function() {
                $("#updateProfileValidation").text('');
                if($(this).val().length > 750)
                {
                    $('#updateSummaryValidation').text('Bio cannot be longer than 750 characters.');
                    summaryValid = false;
                }
                else
                {
                    $('#updateSummaryValidation').text('');
                    summaryValid = true;
                }
            });

            //On submit, update profile information if valid
            //information is entered
            $('#updateProfileForm').submit(function(){
                $("#updateProfileValidation").text('');
                if(emailValid && summaryValid)
                {
                    var serializedForm = $(this).serialize() + "&action=0";
                    request = $.ajax({
                        url: "editAccountAjax.php",
                        type: "POST",
                        data: serializedForm
                    });
                    
                    //Let the user know the status of their request
                    request.done(function(data, textStatus, jqXHR) {
                        if(data === "success")
                            $("#updateProfileValidation").text("Successfully updated profile information.");
                        else
                            $("#updateProfileValidation").text("Profile not updated successfully.");
                    });
                    
                    request.fail(function(jqXHR, textStatus, errorThrown) {
                        $("#updateProfileValidation").text("Profile not updated successfully.");
                    });
                }
                return false;
            });
        }


        if($('#updatePasswordForm').length)
        {
            //Make sure the user enters their current password
            $('#currentPassword').on('change', function() {
                $("#updatePasswordValidation").text('');
                if($(this).val().length == 0)
                {
                    $('#currentPasswordValidation').text('You must enter your current password.');
                    currPasswordValid = false;
                }
                else
                {
                    $('#currentPasswordValidation').text('');
                    currPasswordValid = true;
                }
            });

            //Make sure new passwords are valid and match
            $('#newPassword1').on('change', function() {
                $("#updatePasswordValidation").text('');
                if($(this).val().length < 8)
                {
                    $('#newPassword1Validation').text('Passwords must be at least 8 characters long.');
                    newPasswordValid = false;
                }
                else if($('#newPassword2').val().length >= 8 &&
                        $(this).val() != $('#newPassword2').val())
                {
                    $('#newPassword1Validation').text('');
                    $('#newPassword2Validation').text('New passwords do not match.');
                    newPasswordValid = false;
                }
                else if($('#newPassword2').val().length >= 8 &&
                        $(this).val() == $('#newPassword2').val())
                {
                    $('#newPassword1Validation').text('');
                    $('#newPassword2Validation').text('');
                    newPasswordValid = true;
                }
                else if($('#newPassword2').val().length < 8)
                {
                    $('#newPassword1Validation').text('');
                    newPasswordValid = false;
                }
                else
                {
                    $('#newPassword1Validation').text('');
                    $('#newPassword2Validation').text('');
                    newPasswordValid = false;
                }
            });

            //Make sure new passwords are valid and match
            $('#newPassword2').on('change', function() {
                $("#updatePasswordValidation").text('');
                if($(this).val().length < 8)
                {
                    $('#newPassword2Validation').text('Passwords must be at least 8 characters long.');
                    newPasswordValid = false;
                }
                else if($('#newPassword1').val().length >= 8 &&
                        $(this).val() != $('#newPassword1').val())
                {
                    $('#newPassword1Validation').text('');
                    $('#newPassword2Validation').text('New passwords do not match.');
                    newPasswordValid = false;
                }
                else if($('#newPassword1').val().length >= 8 &&
                        $(this).val() == $('#newPassword1').val())
                {
                    $('#newPassword1Validation').text('');
                    $('#newPassword2Validation').text('');
                    newPasswordValid = true;
                }
                else if($('#newPassword1').val().length < 8)
                {
                    $('#newPassword2Validation').text('');
                    newPasswordValid = false;
                }
                else
                {
                    $('#newPassword1Validation').text('');
                    $('#newPassword2Validation').text('');
                    newPasswordValid = false;
                }
            });

            //On submit, update password if valid information
            //is entered and let the user know the result.
            $('#updatePasswordForm').submit(function(){
                $("#updatePasswordValidation").text('');
                $("#currentPassword").change();
                $("#newPassword1").change();
                $("#newPassword2").change();
                if(currPasswordValid && newPasswordValid)
                {
                    var serializedForm = $(this).serialize() + "&action=1";
                    request = $.ajax({
                        url: "editAccountAjax.php",
                        type: "POST",
                        data: serializedForm
                    });
                    
                    //Let the user know the status of their request
                    request.done(function(data, textStatus, jqXHR) {
                        if(data === "success")
                        {
                            $("#updatePasswordValidation").text("Successfully updated password.");
                            //Clear password form
                            currPasswordValid = false;
                            newPasswordValid = false;
                            $("#currentPassword").val("");
                            $("#newPassword1").val("");
                            $("#newPassword2").val("");
                        }
                        else
                            $("#updatePasswordValidation").text(data);
                    });
                    
                    request.fail(function(jqXHR, textStatus, errorThrown) {
                        $("#updatePasswordValidation").text("Password not updated successfully.");
                    });
                }
                return false;
            });
        }
    }


    //Set the onclick for the Edit Profile Information nav button
    $('#editProfileTabButton').click(function() {
        if(!($(this).hasClass('active')))
        {
            var button = $(this);
                
            request = $.ajax({
                url: "editprofileinfo.php",
                type: "POST"
            });

            //Page is successfully loaded
            request.done(function(data, textStatus, jqXHR) {
                $('.account-edit-sidenav-button.active').removeClass('active');
                button.addClass('active');
                $('#accountEditForm').html(data);
                setFormEvents();
            });
           
            //Page is not loaded successfully
            request.fail(function(jqXHR, textStatus, errorThrown) {
                    alert("Failed to load profile edit form.");
            });        
        }
    }); 

    //Set the onclick for the Update Password nav button
    $('#updatePasswordTabButton').click(function() {
        if(!($(this).hasClass('active')))
        {
            var button = $(this);
                
            request = $.ajax({
                url: "updatepassword.php",
                type: "POST"
            });

            //Page is successfully loaded
            request.done(function(data, textStatus, jqXHR) {
                $('.account-edit-sidenav-button.active').removeClass('active');
                button.addClass('active');
                $('#accountEditForm').html(data);
                setFormEvents();
            });
           
            //Page is not loaded successfully
            request.fail(function(jqXHR, textStatus, errorThrown) {
                    alert("Failed to load password update form.");
            });
        }
    });

    //Set the initial events for the form
    setFormEvents();
});
