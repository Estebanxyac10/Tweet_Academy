<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

class MyDatabase
{
    private $dbHost = "";
    private $dbUser = "";
    private $dbPass = "";
    private $dbName = "tweetic";

    public function connectToDatabase()
    {
        try {
            $conn = new PDO("mysql:host=" . $this->dbHost . ";dbname=" . $this->dbName, $this->dbUser, $this->dbPass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return $conn;
        } catch (PDOException $e) {
            echo "Error : " . $e->getMessage() . PHP_EOL;
        }
    }
}