$(document).ready(function () {
    function fetchTweet() {
        $.ajax({
            type: "POST",
            url: "./code/controller/timeline.php",
            data: {},
            dataType: "json",
            async: true,
            success: function (response) {
                if (response.success && response.message.length > 0) {
                    let tweetHTML = "";
                    response.message.forEach((item) => {
                        tweetHTML += '<div class="tweet">';
                        if (item.type === "text") {
                            tweetHTML += item.content;
                        }
                        if (item.type === "image" && item.content) {
                            tweetHTML += '<img class="tweet_img" src="data:image/jpeg;base64,' + item.content + '" />';
                        }
                        tweetHTML += '</div>';
                    });
                    $("#timeline_content").html(tweetHTML);
                } else {
                    console.log("No tweet to display");
                    $("#timeline_content").html("No tweet to display");
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }
    fetchTweet();
    function fetchTweetTimer() {
        fetchTweet();
    }
    setInterval(fetchTweetTimer, 500);
});