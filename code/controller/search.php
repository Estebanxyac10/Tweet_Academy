<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once "../model/database.php";
$conn = (new MyDatabase())->connectToDatabase();

try {
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $value = $_POST["inputValue"];

    $stmt_users = $conn->prepare("SELECT * FROM `users` WHERE username = :username");
    $stmt_users->bindParam(":username", $value);
    $stmt_users->execute();
    $result_users = $stmt_users->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result_users as $row) {
        echo $row["firstname"] . " ";
        echo $row["lastname"] . " ";
        echo '<button id="followBtn" data-following-id="' . $row["id"] . '">Follow</button>';
        echo '<button id="followBtn" data-following-id="' . $row["id"] . '">Unfollow</button>';
        $response = array("success" => true, "message" => $result_users);
    }    

    $stmt_hashtag = $conn->prepare("SELECT * FROM `hashtag` WHERE name = :username");
    $stmt_hashtag->bindParam(":username", $value);
    $stmt_hashtag->execute();
    $result_hashtag = $stmt_hashtag->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result_hashtag as $row) {
        echo $row["name"] . " ";
        $response = array("success" => true, "message" => $result_hashtag);
    }
    exit();
} catch (PDOException $e) {
    echo "Log : " . $e->getMessage();
} finally {
    $conn = null;
}