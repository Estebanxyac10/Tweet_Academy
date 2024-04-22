<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once "../model/database.php";
session_start();
$conn = (new MyDatabase())->connectToDatabase();

try {
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $session_data = isset ($_SESSION["user_data"]) ? $_SESSION["user_data"] : null;
    if ($session_data) {
        $user_email = $session_data["email"] ?? null;
        if ($user_email) {
            $getIdQuery = "SELECT id FROM users WHERE email = :email";
            $stmt = $conn->prepare($getIdQuery);
            $stmt->bindParam(":email", $user_email);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                $accountId = $user["id"];
            }
        }
    }

    $postData = $_POST;
    $accountId = $session_data["id"] ?? null;

    $accountInfosQuery = "SELECT * FROM users WHERE id = :accountId";
    $stmt = $conn->prepare($accountInfosQuery);
    $stmt->bindParam(":accountId", $accountId);
    $stmt->execute();

    $userData = $stmt->fetch(PDO::FETCH_ASSOC);
    foreach ($userData as $key => $value) {
        if ($key === "created_at") {
            $userData[$key] = substr($value, 0, 4);
        }
    }

    if ($userData) {
        echo json_encode(array("success" => true, "user" => $userData));
    } else {
        echo json_encode(array("success" => false, "message" => "Cannot find user"));
    }
    exit();
} catch (PDOException $e) {
    echo "Log : " . $e->getMessage();
} finally {
    $conn = null;
}