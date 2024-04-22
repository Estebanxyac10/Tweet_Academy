<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once "../model/database.php";
session_start();
$session_data = isset($_SESSION["user_data"]) ? $_SESSION["user_data"] : null;
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sender_id = $_POST["sender_id"];
    $recipient_username = $_POST["recipient"];
    $message = $_POST["message"];

    if (empty($sender_id) || empty($recipient_username) || empty($message)) {
        echo "All fields must be filled";
    } else {
        $conn = (new MyDatabase())->connectToDatabase();
        try {
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            $stmt = $conn->prepare("SELECT id FROM users WHERE username = :username");
            $stmt->bindParam(":username", $recipient_username);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $recipient_id = $row["id"];

                $stmt = $conn->prepare("INSERT INTO users_messages (sender_id, recipient_id, messages) VALUES (:sender_id, :recipient_id, :messages)");
                $stmt->bindParam(":sender_id", $sender_id);
                $stmt->bindParam(":recipient_id", $recipient_id);
                $stmt->bindParam(":messages", $message);
                $stmt->execute();

                echo "Message successfully sent !";
            } else {
                echo "Recipient not found";
            }
        } catch (PDOException $e) {
            echo "Log : " . $e->getMessage();
        }
    }
} elseif (isset($_GET["recipient"]) && isset($user_id)) {
    $recipient_username = $_GET["recipient"];
    $sender_id = $user_id;

    $conn = (new MyDatabase())->connectToDatabase();
    try {
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        $stmt = $conn->prepare("SELECT id FROM users WHERE username = :username");
        $stmt->bindParam(":username", $recipient_username);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $recipient_id = $row["id"];

            $stmt = $conn->prepare("SELECT sender_id, messages FROM users_messages WHERE (sender_id = :sender_id AND recipient_id = :recipient_id) OR (sender_id = :recipient_id AND recipient_id = :sender_id) ORDER BY created_at ASC");
            $stmt->bindParam(":sender_id", $_GET["sender_id"]);
            $stmt->bindParam(":recipient_id", $recipient_id);
            $stmt->execute();
            $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($messages as $message) {
                echo $message["sender_id"] . "|" . $message["messages"] . ";";
            }
        } else {
            echo "Recipient not found";
        }
    } catch (PDOException $e) {
        echo "Log : " . $e->getMessage();
    } finally {
        $conn = null;
    }
} else {
    echo "No recipient found";
}