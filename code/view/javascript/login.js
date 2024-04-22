$(document).ready(function () {
    $("#login_form").submit(function (e) {
        e.preventDefault();

        const loginData = {
            email: $("#login_email").val(),
            password: $("#login_password").val(),
        };

        $.ajax({
            type: "POST",
            url: "../code/controller/login.php",
            data: loginData,
            dataType: "json",
            async: true,
            success: function (data) {
                if (data.success) {
                    console.log("Login successful!");
                    window.location.href = "index.php";
                } else {
                    console.log(data.message);
                }
            },
            error: function (error) {
                console.error("Erreur AJAX : ", error);
            }
        });
    });
});