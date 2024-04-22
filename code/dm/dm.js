$(document).ready(function () {
    let sender_id;
    function getSenderId() {
        $.get("/code/controller/get_user_id.php")
            .done(function (data) {
                sender_id = parseInt(data);
                getMessages();
            })
            .fail(function (error) {
                console.error("Log : ", error);
            });
    }
    getSenderId();

    function getMessages() {
        const recipient = $("#recipient").val();
        $.get("/code/dm/dm.php?recipient=" + recipient + "&sender_id=" + sender_id)
            .done(function (data) {
            })
            .fail(function (error) {
                console.error("Log : ", error);
            });
    }

    function getMessages() {
        const recipient = $("#recipient").val();
        $.get("/code/dm/dm.php?recipient=" + recipient + "&sender_id=" + sender_id)
            .done(function (data) {
                let messageList = $("#message-list");
                messageList.empty();
                const messages = data.split(";").slice(-20);
                messages.forEach(function (message) {
                    const parts = message.split("|");
                    const messageItem = $("<li>").text(parts[1]);

                    if (parseInt(parts[0]) === sender_id) {
                        messageItem.addClass("sent");
                    } else {
                        messageItem.addClass("received");
                    }
                    messageList.append(messageItem);
                });
            })
            .fail(function (error) {
                console.error("Erreur AJAX : ", error);
            });
    }
    setInterval(getMessages, 1000);

    $("#message-form").on("submit", function (event) {
        event.preventDefault();
        sendMessage();
    });

    $("#message").keypress(function (event) {
        if (event.which === 13) {
            event.preventDefault();
            sendMessage();
        }
    });

    function sendMessage() {
        const recipient = $("#recipient").val();
        const message = $("#message").val();
        $.post("/code/dm/dm.php", { sender_id: sender_id, recipient: recipient, message: message })
            .done(function (response) {
                getMessages();
                $("#message").val("");
            })
            .fail(function (error) {
                console.error("Erreur AJAX : ", error);
            });
    }
    getMessages();
});