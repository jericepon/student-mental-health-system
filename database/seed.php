<?php
// database/seed.php

$host = 'localhost';
$db_name = 'mental_health_db';
$username = 'root';
$password = '';

try {
    // Connect directly specifying the database name (dbname=$db_name)
    $pdo = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Clear existing seed data safely
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");
    $pdo->exec("TRUNCATE TABLE referrals;");
    $pdo->exec("TRUNCATE TABLE assessments;");
    $pdo->exec("TRUNCATE TABLE users;");
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");

    // Default passwords hashed securely using password_hash
    $student_pass = password_hash('password123', PASSWORD_DEFAULT);
    $counselor_pass = password_hash('password123', PASSWORD_DEFAULT);

    // Insert Sample Users
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    
    // Student
    $stmt->execute(['John Doe', 'student@college.edu', $student_pass, 'student']);
    $student_id = $pdo->lastInsertId();

    // Counselor
    $stmt->execute(['Dr. Jane Smith', 'counselor@college.edu', $counselor_pass, 'counselor']);
    $counselor_id = $pdo->lastInsertId();

    // Insert Sample Assessment
    $stmtAss = $pdo->prepare("INSERT INTO assessments (student_id, stress_level, anxiety_score, notes) VALUES (?, ?, ?, ?)");
    $stmtAss->execute([$student_id, 7, 8, 'Feeling overwhelmed with midterms and tight project deadlines.']);

    // Insert Sample Referral
    $stmtRef = $pdo->prepare("INSERT INTO referrals (student_id, counselor_id, reason, status) VALUES (?, ?, ?, ?)");
    $stmtRef->execute([$student_id, $counselor_id, 'Requested support for exam anxiety and sleep issues.', 'Pending']);

    echo "<h3 style='color: green;'>Database seeded successfully!</h3>";
    echo "<p><strong>Test Student Account:</strong> student@college.edu / password123</p>";
    echo "<p><strong>Test Counselor Account:</strong> counselor@college.edu / password123</p>";
    echo "<p><a href='../auth/login.php'>Proceed to Login</a></p>";

} catch (PDOException $e) {
    die("<h3 style='color: red;'>Seeding Error: " . $e->getMessage() . "</h3>");
}
?>