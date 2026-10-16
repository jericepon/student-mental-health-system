<?php
// database/migrate.php
$host = 'localhost';
$username = 'root';
$password = '';

try {
    // Connect without database name to create it
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create Database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS mental_health_db");
    $pdo->exec("USE mental_health_db");

    // 1. Users Table (Supports Students, Counselors, Admins)
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        role ENUM('student', 'counselor', 'admin') DEFAULT 'student',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // 2. Assessments Table (Student Mental Health Questionnaires)
    $pdo->exec("CREATE TABLE IF NOT EXISTS assessments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        student_id INT NOT NULL,
        stress_level INT NOT NULL, -- Scale 1-10
        anxiety_score INT NOT NULL, -- Scale 1-10
        notes TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE
    )");

    // 3. Referrals Table (Counselor referrals & tracking)
    $pdo->exec("CREATE TABLE IF NOT EXISTS referrals (
        id INT AUTO_INCREMENT PRIMARY KEY,
        student_id INT NOT NULL,
        counselor_id INT DEFAULT NULL,
        reason TEXT NOT NULL,
        status ENUM('Pending', 'In Progress', 'Resolved') DEFAULT 'Pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (counselor_id) REFERENCES users(id) ON DELETE SET NULL
    )");

    echo "<h3 style='color: green;'>Database migration completed successfully! All tables created.</h3>";
    echo "<p><a href='../auth/login.php'>Go to Login</a> | <a href='seed.php'>Run Seed Data</a></p>";

} catch (PDOException $e) {
    die("<h3 style='color: red;'>Migration Error: " . $e->getMessage() . "</h3>");
}
?>