$(document).ready(function () {
    $("#account_back").click(function () {
        window.location.href = "index.php";
    });

    $.ajax({
        type: "POST",
        url: "./code/controller/account.php",
        dataType: "json",
        async: true,
        success: function (response) {
            if (response.success) {
                console.log(response);
                const user = response.user;
                $(".account-name").text(user.firstname + " " + user.lastname);
                $("#account_amount").text(user.amount);
                $("#account_at").text("@" + user.username);
                $("#account_date").text("Joined in " + user.created_at);
            } else {
                console.error("Log : " + response.message);
            }
        },
        error: function (xhr, status, error) {
            console.error("Log : " + xhr.responseText);
        }
    });
});