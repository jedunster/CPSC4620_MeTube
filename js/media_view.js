/* Javascript file for AJAX requests for viewing media, allowing dynamic
 * reloading of content such as comments for the media view page.
 */


//Runs when the document is ready to set onclick functions
$(document).ready(function() {

    //This is separated so it can be called both on
    //the page load and the comment refresh
    function setDeleteCommentOnclick()
    {
        //Code for deleting comments via AJAX
        if($('.btn-delete-comment').length)
        {
            $('.btn-delete-comment').click(function() {
                var commentid = $(this).parent().find('[name="commentidField"]').val();
                
                request = $.ajax({
                    url: "commentDelete.php",
                    type: "POST",
                    data: {'commentid': commentid}
                });

                //If delete succeeds, refresh comments
                request.done(function(data, textStatus, jqXHR) {
                    if(data === "success")
                        refreshComments();
                    else
                        alert("Failed to delete comment.");
                });

                //Warn user if the delete fails
                request.fail(function(jqXHR, textStatus, errorThrown) {
                    alert("Failed to delete comment.");
                });
            });
        }
    }

    //This is separated so it can be called whenever a
    //comment is selected to be edited
    function setEditCommentOnSubmit()
    {
        //Submit comment edit and refresh page on submit click
        if($('.edit-comment-form').length)
        {
            $('.edit-comment-form').submit(function() {
                $(this).find('.comment-validation').text("");
                if($(this).find('[name="commentText"]').val().length < 10)
                {
                    $(this).find('.comment-validation').text("Comment length cannot be under 10 characters.");
                    return false;
                }
                else if($(this).find('[name="commentText"]').val().length > 1000)
                {
                    $(this).find('.comment-validation').text("Comment length cannot exceed 1000 characters.");
                    return false;
                }
                
                var form = $(this);

                request = $.ajax({
                    url: "commentEdit.php",
                    type: "POST",
                    data: form.serialize()
                });

                //If submit succeeds, refresh comments
                request.done(function(data, textStatus, jqXHR) {
                    if(data === "success")
                        refreshComments();
                    else
                        form.find('.comment-validation').text(data);
                });

                //Warn user if the submit fails
                request.fail(function(jqXHR, textStatus, errorThrown) {
                     form.find('.comment-validation').text("Failed to submit comment.");
                });
                
                return false;
            });
        }
            
        //If someone cancels editing a comment, refresh comments
        if($('.btn-comment-edit-cancel').length)
        {
            $('.btn-comment-edit-cancel').click(function() {
               refreshComments(); 
            });
        }
    }

    //This is separated so it can be called both on
    //the page load and the comment refresh
    function setEditCommentOnclick()
    {
        //Code for editing comments via AJAX
        if($('.btn-edit-comment').length)
        {
            $('.btn-edit-comment').click(function() {
                var commentid = $(this).parent().find('[name="commentidField"]').val();
                var element = $(this);
                
                request = $.ajax({
                    url: "commentEditInterface.php",
                    type: "POST",
                    data: {'commentid': commentid}
                });

                //If edit interface request succeeds, refresh comments
                request.done(function(data, textStatus, jqXHR) {
                    element.parent().addClass('edit-comment-container');
                    element.parent().html(data);
                    setEditCommentOnSubmit();
                });

                //Warn user if the edit interface request fails
                request.fail(function(jqXHR, textStatus, errorThrown) {
                    alert("Failed to load edit comment form.");
                });
            });
        }
    }

    //Refresh the comments pane by loading it from the database
    function refreshComments()
    {
        var mediaid = $('#mediaidJS').val();

        request = $.ajax({
            url: "comments.php",
            type: "POST",
            data: { 'id': mediaid }
        });

        //If done correctly, set the comment section to the returned refresh
        request.done(function(data, textStatus, jqXHR) {
            $('#commentSection').html(data);
            setDeleteCommentOnclick();
            setEditCommentOnclick();
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
            $('#makeCommentValidation').text("");
            if($('#commentText').val().length < 10)
            {
                $('#makeCommentValidation').text("Comment length cannot be under 10 characters.");
                return false;
            }
            else if($('#commentText').val().length > 1000)
            {
                $('#makeCommentValidation').text("Comment length cannot exceed 1000 characters.");
                return false;
            }

            request = $.ajax({
                url: "commentSubmit.php",
                type: "POST",
                data: $('#makeCommentForm').serialize()
            });

            //If submit succeeds, refresh comments
            request.done(function(data, textStatus, jqXHR) {
                if(data === "success")
                {
                    $('#commentText').val("");
                    refreshComments();
                }
                else
                    $('#makeCommentValidation').text(data);
            });

            //Warn user if the submit fails
            request.fail(function(jqXHR, textStatus, errorThrown) {
                 $('#makeCommentValidation').text("Failed to submit comment.");
            });
            
            return false; 
        });
    }

    //Set the delete button onclick
    setDeleteCommentOnclick();

    //Set the edit button onclick
    setEditCommentOnclick();
});
