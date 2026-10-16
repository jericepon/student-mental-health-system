<?php
// student/dashboard.php
session_start();
require_once '../config/database.php';

// Security check: ensure user is logged in as a student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../auth/login.php");
    exit;
}

$student_id = $_SESSION['user_id'];

// Fetch past assessments to show a summary
$stmt = $pdo->prepare("SELECT * FROM assessments WHERE student_id = ? ORDER BY created_at DESC");
$stmt->execute([$student_id]);
$assessments = $stmt->fetchAll();

// Fetch student's referrals/intervention tracking
$refStmt = $pdo->prepare("
    SELECT referrals.*, users.name as counselor_name 
    FROM referrals 
    LEFT JOIN users ON referrals.counselor_id = users.id 
    WHERE referrals.student_id = ? 
    ORDER BY referrals.created_at DESC
");
$refStmt->execute([$student_id]);
$referrals = $refStmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard - Mental Health System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container" style="max-width: 800px;">
        <h2>Student Dashboard</h2>
        <p>Welcome back, <strong><?php echo htmlspecialchars($_SESSION['name']); ?></strong>! | <a href="../auth/logout.php">Logout</a></p>
        
        <hr style="margin: 20px 0;">

        <div style="display: flex; gap: 15px; margin-bottom: 25px;">
            <a href="take_assessment.php" style="flex: 1;"><button type="button">Take New Mental Health Assessment</button></a>
        </div>

        <h3>Your Recent Assessments</h3>
        <?php if (count($assessments) > 0): ?>
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
        <?php else: ?>
            <p>You haven't submitted any assessments yet. Click the button above to get started.</p>
        <?php endif; ?>

        <h3 style="margin-top: 30px;">Your Referrals & Intervention Tracking</h3>
        <?php if (count($referrals) > 0): ?>
            <table>
                <tr>
                    <th>Date Requested</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Assigned Counselor</th>
                </tr>
                <?php foreach ($referrals as $ref): ?>
                <tr>
                    <td><?php echo $ref['created_at']; ?></td>
                    <td><?php echo htmlspecialchars($ref['reason']); ?></td>
                    <td><strong><?php echo $ref['status']; ?></strong></td>
                    <td><?php echo htmlspecialchars($ref['counselor_name'] ?? 'Pending Assignment'); ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No active referrals found.</p>
        <?php endif; ?>
    </div>
</body>
</html>