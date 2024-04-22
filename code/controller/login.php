<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once "../model/database.php";
session_start();
$conn = (new MyDatabase())->connectToDatabase();

try {
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $postData = $_POST;
    $connMail = $postData["email"] ?? null;
    $connPassword = $postData["password"] ?? null;
    $connPassword = hash("ripemd160", $connPassword);

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email AND password_hash = :password");
    $stmt->bindParam(":email", $connMail);
    $stmt->bindParam(":password", $connPassword);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $_SESSION["user_data"] = $stmt->fetch();
        echo json_encode(array("success" => true, "message" => "Login successful."));
    } else {
        echo json_encode(array("success" => false, "message" => "Invalid email or password."));
    }
    exit();
} catch (PDOException $e) {
    echo "Log : " . $e->getMessage();
} finally {
    $conn = null;
}