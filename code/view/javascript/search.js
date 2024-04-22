$(document).ready(function () {
    $("#search_form").submit(function (e) {
        e.preventDefault();

        const inputValue = $("#searchbar").val();
        $.ajax({
            type: "POST",
            url: "./code/controller/search.php",
            data: { inputValue: inputValue },
            dataType: "html",
            async: true,
            success: function (data) {
                if (data === "") {
                    alert("No data found");
                } else {
                    const firstChar = data.substring(0, 1);
                    if (firstChar === "#") {
                        if (!$("#result").is(":empty")) {
                            $("#result").empty();
                        }
                        const paragraph = $("<p>").text(data);
                        $("#result").append(paragraph);
                        console.log("# search");
                    } else if (firstChar === "@") {
                        if (!$("#result").is(":empty")) {
                            $("#result").empty();
                        }
                        const paragraph = $("<p>").text(data);
                        $("#result").append(paragraph);
                        console.log("@ search");
                    } else {
                        if (!$("#result").is(":empty")) {
                            $("#result").empty();
                        }
                        $("#result").html(data);
                        console.log("username search");
                    }
                }
            },
            error: function (error) {
                console.log("Log : ", error);
            }
        });
    });
});