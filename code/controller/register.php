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
    $connUsername = $postData["username"] ?? null;
    $connLastname = $postData["lastname"] ?? null;
    $connFirstname = $postData["firstname"] ?? null;
    $connGender = $postData["gender"] ?? null;
    $connBirthdate = $postData["birthdate"] ?? null;
    $connMail = $postData["email"] ?? null;
    $connPassword = $postData["password"] ?? null;
    $connConfirmPassword = $postData["confirmPassword"] ?? null;
    $connCreationDate = date("Y-m-d H:i:s");

    if (empty ($connBirthdate) || !preg_match("/^(\d{4})-(\d{2})-(\d{2})$/", $connBirthdate)) {
        $response = array("success" => false, "message" => "Invalid date format. Please use the following format: YYYY-MM-DD.");
        echo json_encode($response);
        exit();
    }
    if (
        empty ($connUsername)
        || empty ($connLastname)
        || empty ($connFirstname)
        || empty ($connGender)
        || empty ($connBirthdate)
        || empty ($connMail)
        || empty ($connPassword)
        || empty ($connConfirmPassword)
    ) {
        $response = array("success" => false, "message" => "Please fill in all fields.");
        echo json_encode($response);
        exit();
    }
    if ($connPassword !== $connConfirmPassword) {
        $response = array("success" => false, "message" => "Passwords do not match.");
        echo json_encode($response);
        exit();
    }
    if (!filter_var($connMail, FILTER_VALIDATE_EMAIL)) {
        $response = array("success" => false, "message" => "Invalid email format.");
        echo json_encode($response);
        exit();
    }
    function registerCheckFunction(string $param1, string $param2, $param3, $param4)
    {
        $registerCheckQuery = "SELECT $param1 FROM users WHERE $param1 = $param2";
        $stmt = $param3->prepare($registerCheckQuery);
        $stmt->bindParam($param2, $param4);
        $stmt->execute();
        $result = $stmt->fetch();
        if ($result) {
            $param1 = ucfirst($param1);
            $response = array("success" => false, "message" => "$param1 already in use.");
            echo json_encode($response);
            exit();
        }
    }
    registerCheckFunction("email", ":email", $conn, $connMail);
    registerCheckFunction("username", ":username", $conn, $connUsername);

    $connPassword = hash("ripemd160", $connPassword);

    $registerQuery = "INSERT INTO users (username,
    lastname, firstname,
    genre, birthdate,
    email, password_hash,
    created_at)
    VALUES (:username,
    :lastname, :firstname,
    :gender, :birthdate,
    :email, :password,
    :creationdate)";

    $stmt = $conn->prepare($registerQuery);
    $stmt->bindParam(":username", $connUsername);
    $stmt->bindParam(":lastname", $connLastname);
    $stmt->bindParam(":firstname", $connFirstname);
    $stmt->bindParam(":gender", $connGender);
    $stmt->bindParam(":birthdate", $connBirthdate);
    $stmt->bindParam(":email", $connMail);
    $stmt->bindParam(":password", $connPassword);
    $stmt->bindParam(":creationdate", $connCreationDate);
    $stmt->execute();

    $response = array("success" => true, "message" => "User registered successfully.");
    echo json_encode($response);
    exit();
} catch (PDOException $e) {
    echo "Log : " . $e->getMessage();
} finally {
    $conn = null;
}