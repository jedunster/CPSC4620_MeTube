/* Javascript file for AJAX requests for viewing media, allowing dynamic
 * reloading of content such as comments for the media view page.
 */



//Runs when the document is ready to set onclick functions
$(document).ready(function() {

    //Refresh the comments pane by loading it from the database
    function refreshComments()
    {
        var mediaid = $('#mediaidField').val();

        request = $.ajax({
            url: "comments.php",
            type: "POST",
            data: { 'id': mediaid }
        });

        //If done correctly, set the comment section to the returned refresh
        request.done(function(data, textStatus, jqXHR) {
            $('#commentSection').html(data);
        });

        //Warn user if the refresh fails
        request.fail(function(jqXHR, textStatus, errorThrown) {
            alert("Failed to refresh comments.");
        });

        return false; 
    }

    //Code for submitting comments via AJAX
    if($('#makeCommentForm').length)
    {
        $('#makeCommentForm').submit(function() {
            if($('#commentText').val().length < 10)
            {
                alert("Comment length cannot be under 10 characters.");
                return false;
            }
            else if($('#commentText').val().length > 1000)
            {
                alert("Comment length cannot exceed 1000 characters.");
                return false;
            }

            request = $.ajax({
                url: "commentSubmit.php",
                type: "POST",
                data: $('commentSubmit').serialize()
            });

            //If submit succeeds, refresh comments
            request.done(function(data, textStatus, jqXHR) {
                if(data === "success")
                    refreshComments();
                else
                    alert(data);//"Failed to submit comment.");
            });

            //Warn user if the submit fails
            request.fail(function(jqXHR, textStatus, errorThrown) {
                alert("Failed to submit comment.");
            });
            
            return false; 
        });
    }
});
