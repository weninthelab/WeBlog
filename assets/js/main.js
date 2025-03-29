

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


