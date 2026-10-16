<?php
// config/database.php

$host = 'localhost';
$db_name = 'mental_health_db';
$username = 'root';
$password = '';

try {
    // Added dbname=$db_name so MySQL knows which database to use automatically
    $pdo = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}
?>