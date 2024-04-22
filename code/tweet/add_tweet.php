<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once "../model/database.php";
session_start();
$conn = (new MyDatabase())->connectToDatabase();

try {
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $session_data = isset($_SESSION["user_data"]) ? $_SESSION["user_data"] : null;
    if ($session_data) {
        $user_email = $session_data["email"] ?? null;
        if ($user_email) {
            $getIdQuery = "SELECT id FROM users WHERE email = :email";
            $stmt = $conn->prepare($getIdQuery);
            $stmt->bindParam(":email", $user_email);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                $connTweetId = $user["id"];
            }
        }
    }

    $postData = $_POST;
    $connTweetUserId = $session_data["id"] ?? null;
    $connTweetMessage = $postData["message"] ?? null;
    $connTweetMedia = isset($_FILES["media"]) ? file_get_contents($_FILES["media"]["tmp_name"]) : null;
    $connTweetMedia = base64_encode($connTweetMedia);

    $insertTweetQuery = "INSERT INTO tweet (user_id, message, media, created_at, updated_at)
                         VALUES (:user_id, :message, :media, NOW(), NOW())";
    $stmt = $conn->prepare($insertTweetQuery);
    $stmt->bindParam(":user_id", $connTweetUserId);
    $stmt->bindParam(":message", $connTweetMessage);
    $stmt->bindParam(":media", $connTweetMedia, PDO::PARAM_LOB);
    $stmt->execute();

    $response = array("success" => true, "message" => "Tweet published !");
    echo json_encode($response);
    exit();
} catch (PDOException $e) {
    echo "Log : " . $e->getMessage();
} finally {
    $conn = null;
}