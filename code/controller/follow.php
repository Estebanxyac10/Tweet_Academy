<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once "../model/database.php";
session_start();
$session_data = isset ($_SESSION["user_data"]) ? $_SESSION["user_data"] : null;
if ($session_data) {
    $user_email = $session_data["email"] ?? null;
    if ($user_email) {
        $conn = (new MyDatabase())->connectToDatabase();
        $getIdQuery = "SELECT id FROM users WHERE email = :email";
        $stmt = $conn->prepare($getIdQuery);
        $stmt->bindParam(":email", $user_email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            $user_id = $user["id"];
        }
    }
}
$conn = (new MyDatabase())->connectToDatabase();

$followingId = $_POST["followingId"];
$action = $_POST["action"];

try {
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $loggedInUserId = $user_id;

    if ($action === "Follow") {
        $query = "INSERT INTO followers (follower_id, following_id) VALUES (:follower_id, :following_id)";
    } else if ($action === "Unfollow") {
        $query = "DELETE FROM followers WHERE follower_id = :follower_id AND following_id = :following_id";
    }

    $stmt = $conn->prepare($query);
    $stmt->bindParam(":follower_id", $loggedInUserId);
    $stmt->bindParam(":following_id", $followingId);

    if ($stmt->execute()) {
        $userInfoQuery = "SELECT username FROM users WHERE id = :following_id";
        $userInfoStmt = $conn->prepare($userInfoQuery);
        $userInfoStmt->bindParam(":following_id", $followingId);
        $userInfoStmt->execute();
        $userInfo = $userInfoStmt->fetch(PDO::FETCH_ASSOC);

        $message = ($action === "Follow") ? "Vous suivez maintenant {$userInfo["username"]}." : "Vous ne suivez plus {$userInfo["username"]}.";
        echo $message;
    } else {
        echo "Log : $action";
    }
} catch (PDOException $e) {
    echo "Log : " . $e->getMessage();
} finally {
    $conn = null;
}