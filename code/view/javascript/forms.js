$(document).ready(function () {
    function callForm(param1, param2, param3, param4) {
        $(param1).click(function (e) {
            e.preventDefault();

            $.ajax({
                type: "POST",
                url: param2,
                data: {},
                async: true,
                success: function (response) {
                    $(param3).show();
                    $(param4).click(() => {
                        $(param3).hide();
                    });
                },
                error: function (error) {
                    console.error("AJAX error : ", error);
                }
            });
        });
    }
    callForm("#user_login", "../code/view/html/login.html", "#login_body", "#login_close");
    callForm("#user_register", "../code/view/html/register.html", "#register_body", "#register_close");
    callForm("#tweet_new", "../code/tweet/tweet.html", "#tweet_body", "#tweet_close");
    callForm("#dm_button", "../code/dm/dm.html", "#dm_body", "#dm_close");
});