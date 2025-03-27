

$(document).ready(function () {
    $("#sendMessageForm").submit(function (event) {
        event.preventDefault(); // Ngăn form reload trang

        $.ajax({
            url: "actions/send_message.php",
            type: "POST",
            data: $(this).serialize(),
            success: function (response) {
                $("#messageStatus").html(response);
                $("#sendMessageForm")[0].reset(); // Reset form sau khi gửi
            },
            error: function () {
                $("#messageStatus").html("<p class='error'>Message sending failed!</p>");
            }
        });
    });
});


$(document).ready(function () {
    $("#createPostForm").submit(function (event) {
        event.preventDefault();

        $.ajax({
            url: "actions/create_post.php",
            type: "POST",
            data: $(this).serialize(),
            success: function (response) {
                $("#postStatus").html(response);
                $("#createPostForm")[0].reset(); // Reset form sau khi gửi
            },
            error: function () {
                $("#postStatus").html("<p class='error'>Failed to create post!</p>");
            }
        });
    });
});
