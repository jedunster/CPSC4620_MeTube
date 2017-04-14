/* Javascript file for AJAX requests, form submission, and validation for
 * editing playlist information.
 */

var nameValid = true;
var descriptionValid = true;

//Runs when the document is ready to set onclick functions
$(document).ready(function() {

    //If the form is loaded, set its validation and submission functions
    if($('#editPlaylistForm').length)
    {
        //Make sure a name is entered and not too long
        $('#name').on('change', function() {
            $("#nameValidation").text('');
            if($(this).val().length == 0)
            {
                $('#nameValidation').text('You must enter a name for the playlist.');
                nameValid = false;
            }
            else if($(this).val().length > 40)
            {
                $('#nameValidation').text('Playlist names cannot exceed 40 characers.');
                nameValid = false;
            }
            else
            {
                $('#nameValidation').text('');
                nameValid = true;
            }
        });

        //Make sure the description is not too long
        $('#description').on('change', function() {
            $("#descriptionValidation").text('');
            if($(this).val().length > 1000)
            {
                $('#descriptionValidation').text('Playlist descriptions cannot exceed 1000 characers.');
                descriptionValid = false;
            }
            else
            {
                $('#descriptionValidation').text('');
                descriptionValid = true;
            }

        });

        //On submit, update playlist metadata if valid information
        //is entered and redirect the user back if successful.
        $('#editPlaylistForm').submit(function(){
            if(nameValid && descriptionValid)
            {
                var playlistid = $('#playlistid').val();
                var serializedForm = $(this).serialize() + "&action=0";
                request = $.ajax({
                    url: "editPlaylistAjax.php",
                    type: "POST",
                    data: serializedForm
                });
                
                //Redirect the user if successful, or alert them if not
                request.done(function(data, textStatus, jqXHR) {
                    if(data === "success")
                        window.location.href = 'playlist.php?id='.concat(playlistid);
                    else
                        alert("Playlist metadata was not updated successfully.");
                });
                
                request.fail(function(jqXHR, textStatus, errorThrown) {
                    alert("Playlist metadata was not updated successfully.");
                });
            }
            return false;
        });
    }
});
