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

try {
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $action = $_POST["action"];
        $userId = $_POST["userId"];

        if ($action === "followers") {
            $query = "SELECT u.username FROM users u JOIN followers f ON u.id = f.follower_id WHERE f.following_id = ?";
        } elseif ($action === "following") {
            $query = "SELECT u.username FROM users u JOIN followers f ON u.id = f.following_id WHERE f.follower_id = ?";
        }

        $stmt = $conn->prepare($query);
        $stmt->execute([$userId]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (in_array($action, ["followers", "following"])) {
            echo "<ul>";
            foreach ($results as $result) {
                echo "<li>" . $result["username"] . "</li>";
            }
            echo "</ul>";
        }
        exit();
    }
} catch (PDOException $e) {
    $response = array("success" => false, "message" => $e->getMessage());
    echo json_encode($response);
    exit();
} finally {
    $conn = null;
}