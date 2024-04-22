$(document).ready(function () {
    $("#tweet_form").submit(function (e) {
        e.preventDefault();

        const message = $("#tweet_message").val();
        const media = $("#tweet_media")[0].files[0];
        const tweetData = new FormData(this);
        tweetData.append("message", message);
        if ($("#tweet_media")[0].files[0]) {
            tweetData.append("media", media);
        }
        console.log(message, media);

        $.ajax({
            type: "POST",
            url: "./code/tweet/add_tweet.php",
            data: tweetData,
            processData: false,
            contentType: false,
            async: true,
            success: function (data) {
                $("#tweet_message").val("");
                $("#tweet_body").hide();
            },
            error: function (error) {
                console.error("Erreur AJAX : ", error);
            }
        });
    });

    $("#tweet_message, #tweet_media").on("input", function () {
        const message = $("#tweet_message").val();
        const media = $("#tweet_media")[0].files[0];
        if (message !== "" || media) {
            $("#tweet_message, #tweet_media").removeAttr("required");
            if (media) {
                const imgPrev = '<img src="' + URL.createObjectURL(media) + '"/>';
                $("#media_preview").html(imgPrev);
            } else {
                $("#media_preview").html("");
            }
        } else {
            $("#tweet_message, #tweet_media").prop("required", true);
        }
    });
});