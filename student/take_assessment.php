<?php
// student/take_assessment.php
session_start();
require_once '../config/database.php';

// Security check: ensure user is logged in as a student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../auth/login.php");
    exit;
}

$success = '';
$student_id = $_SESSION['user_id'];

// Handle Create Assessment
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stress = intval($_POST['stress_level']);
    $anxiety = intval($_POST['anxiety_score']);
    $notes = trim($_POST['notes']);

    $stmt = $pdo->prepare("INSERT INTO assessments (student_id, stress_level, anxiety_score, notes) VALUES (?, ?, ?, ?)");
    $stmt->execute([$student_id, $stress, $anxiety, $notes]);
    $success = "Assessment submitted successfully!";
}

// Fetch past assessments (Read)
$stmt = $pdo->prepare("SELECT * FROM assessments WHERE student_id = ? ORDER BY created_at DESC");
$stmt->execute([$student_id]);
$assessments = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Assessments</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h2>Student Dashboard - Mental Health Assessment</h2>
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?> | <a href="../auth/logout.php">Logout</a></p>
        
        <hr>

        <h3>Take New Assessment</h3>
        <?php if (!empty($success)): ?><p class="success"><?php echo $success; ?></p><?php endif; ?>
        <form method="POST">
            <label>Stress Level (1-10):</label>
            <input type="number" name="stress_level" min="1" max="10" required>

            <label>Anxiety Score (1-10):</label>
            <input type="number" name="anxiety_score" min="1" max="10" required>

            <label>Notes / How are you feeling today?</label>
            <textarea name="notes" rows="3" required></textarea>

            <button type="submit">Submit Assessment</button>
        </form>

        <hr>

        <h3>Assessment History</h3>
        <table>
            <tr>
                <th>Date</th>
                <th>Stress Level</th>
                <th>Anxiety Score</th>
                <th>Notes</th>
            </tr>
            <?php foreach ($assessments as $row): ?>
            <tr>
                <td><?php echo $row['created_at']; ?></td>
                <td><?php echo $row['stress_level']; ?>/10</td>
                <td><?php echo $row['anxiety_score']; ?>/10</td>
                <td><?php echo htmlspecialchars($row['notes']); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>