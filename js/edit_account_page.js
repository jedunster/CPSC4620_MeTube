/* Javascript file for AJAX requests, form submission, and validation for
 * editing profile information.
 */

//Rus when the document is ready to set onclick funcitons
$(document).ready(function() {

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
            });
           
            //Page is not loaded successfully
            request.fail(function(jqXHR, textStatus, errorThrown) {
                    alert("Failed to load password update form..");
            });
        }
    });
});
