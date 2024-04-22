$(document).ready(function () {
    $("#user_logout").click(function () {
        fetch("../code/controller/logout.php", { method: "POST" })
            .then(response => {
                if (response.ok) {
                    window.location.href = "index.php";
                } else {
                    throw new Error('Erreur lors de la déconnexion');
                }
            }).catch(error => {
                console.error('Erreur:', error);
            });
    });
    $("#user_account").click(function () {
        window.location.href = "account.html";
    });
});