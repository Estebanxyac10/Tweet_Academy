$(document).ready(function () {
    $("#register_form").submit(function (e) {
        e.preventDefault();

        const registerData = {
            username: $("#username").val(),
            lastname: $("#lastname").val(),
            firstname: $("#firstname").val(),
            gender: $("#gender").val(),
            birthdate: $("#birthdate").val(),
            email: $("#email").val(),
            password: $("#password").val(),
            confirmPassword: $("#confirm_password").val(),
        };
        console.log(registerData);

        const registerAge = new Date(registerData.birthdate);
        const now = new Date();
        const age = now.getFullYear() - registerAge.getFullYear();
        if (age < 18) {
            console.log("You must be at least 13 years old to register");
            return;
        }

        $.ajax({
            type: "POST",
            url: "../code/controller/register.php",
            data: registerData,
            dataType: "json",
            async: true,
            success: function (data) {
                if (data.success) {
                    console.log("Successful registration!");
                    window.location.href = "index.php";
                } else {
                    console.log(data.message);
                }
            },
            error: function (error) {
            }
        });
    });
});