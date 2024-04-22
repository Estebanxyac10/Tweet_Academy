$(document).ready(function () {
    let userId;
    let followingId;
    function getUserId() {
        $.get("/code/controller/get_user_id.php")
            .done(function (data) {
                userId = parseInt(data);
                followingId = parseInt(data);
                getFollows("#toggleFollowersBtn", "#followersList", "followers");
                getFollows("#toggleFollowingBtn", "#followingList", "following");
                makeFollows();
            })
            .fail(function (error) {
                console.error("Log : ", error);
            });
    }
    getUserId();

    function getFollows(param1, param2, param3) {
        $(param1).on("click", function () {
            $(param2).toggle();

            $.ajax({
                url: "/code/controller/followlist.php",
                type: "POST",
                data: { action: param3, userId: userId },
                success: function (response) {
                    $(param2).html(response);
                },
                error: function (error) {
                    console.error("Log : ", error);
                }
            });
        });
    }

    function makeFollows() {
        $("#result").on("click", "#followBtn", function () {
            const followingId = $(this).data("following-id");
            const action = $(this).text();

            $.ajax({
                url: "/code/controller/follow.php",
                type: "POST",
                data: { followingId: followingId, action: action },
                success: function (response) {
                    alert(response);
                },
                error: function (error) {
                    console.error("Log : ", error);
                }
            });
        });
    }
});