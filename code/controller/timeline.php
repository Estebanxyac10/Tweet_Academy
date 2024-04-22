<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once "../model/database.php";
session_start();
$conn = (new MyDatabase())->connectToDatabase();

try {
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $getTweetQuery = "SELECT * FROM tweet ORDER BY created_at DESC";
    $stmt = $conn->prepare($getTweetQuery);
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $tweetArray = [];
    foreach ($result as $tweet) {
        if (isset ($tweet["message"])) {
            $tweetArray[] = [
                "type" => "text",
                "content" => $tweet["message"]
            ];
        }
        if (isset ($tweet["media"])) {
            $tweetArray[] = [
                "type" => "image",
                "content" => $tweet["media"]
            ];
        }
    }


    $response = array("success" => true, "message" => $tweetArray);
    echo json_encode($response);
    exit();
} catch (PDOException $e) {
    echo "Log : " . $e->getMessage();
} finally {
    $conn = null;
}