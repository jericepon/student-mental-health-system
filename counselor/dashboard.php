<?php
// counselor/dashboard.php
session_start();
require_once '../config/database.php';

// Security check: ensure user is logged in as a counselor
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'counselor') {
    header("Location: ../auth/login.php");
    exit;
}

$success = '';
$counselor_id = $_SESSION['user_id'];

// Handle Referral Status Update (CRUD: Update)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['referral_id'], $_POST['status'])) {
    $ref_id = intval($_POST['referral_id']);
    $new_status = trim($_POST['status']);

    $update = $pdo->prepare("UPDATE referrals SET status = ?, counselor_id = ? WHERE id = ?");
    $update->execute([$new_status, $counselor_id, $ref_id]);
    $success = "Referral status updated successfully!";
}

// Fetch all student mental health assessments
$assessmentsQuery = $pdo->query("
    SELECT assessments.*, users.name as student_name, users.email as student_email 
    FROM assessments 
    JOIN users ON assessments.student_id = users.id 
    ORDER BY assessments.created_at DESC
");
$assessments = $assessmentsQuery->fetchAll();

// Fetch all referrals
$referralsQuery = $pdo->query("
    SELECT referrals.*, s.name as student_name, c.name as counselor_name 
    FROM referrals 
    JOIN users s ON referrals.student_id = s.id 
    LEFT JOIN users c ON referrals.counselor_id = c.id 
    ORDER BY referrals.created_at DESC
");
$referrals = $referralsQuery->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Counselor Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container" style="max-width: 900px;">
        <h2>Counselor Dashboard</h2>
        <p>Welcome, Dr. <?php echo htmlspecialchars($_SESSION['name']); ?> | <a href="../auth/logout.php">Logout</a></p>
        
        <hr>

        <?php if (!empty($success)): ?><p class="success"><?php echo $success; ?></p><?php endif; ?>

        <h3>Student Referrals & Intervention Tracking</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Student</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Assigned Counselor</th>
                <th>Action</th>
            </tr>
            <?php foreach ($referrals as $ref): ?>
            <tr>
                <td><?php echo $ref['id']; ?></td>
                <td><?php echo htmlspecialchars($ref['student_name']); ?></td>
                <td><?php echo htmlspecialchars($ref['reason']); ?></td>
                <td><strong><?php echo $ref['status']; ?></strong></td>
                <td><?php echo htmlspecialchars($ref['counselor_name'] ?? 'Unassigned'); ?></td>
                <td>
                    <form method="POST" style="margin:0; display:flex; gap:5px;">
                        <input type="hidden" name="referral_id" value="<?php echo $ref['id']; ?>">
                        <select name="status" style="margin:0; padding:4px;">
                            <option value="Pending" <?php if($ref['status']=='Pending') echo 'selected'; ?>>Pending</option>
                            <option value="In Progress" <?php if($ref['status']=='In Progress') echo 'selected'; ?>>In Progress</option>
                            <option value="Resolved" <?php if($ref['status']=='Resolved') echo 'selected'; ?>>Resolved</option>
                        </select>
                        <button type="submit" style="width: auto; padding: 4px 8px; margin:0;">Save</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>

        <h3 style="margin-top: 30px;">All Student Mental Health Screenings</h3>
        <table>
            <tr>
                <th>Student Name</th>
                <th>Email</th>
                <th>Stress (1-10)</th>
                <th>Anxiety (1-10)</th>
                <th>Notes</th>
                <th>Date</th>
            </tr>
            <?php foreach ($assessments as $item): ?>
            <tr>
                <td><?php echo htmlspecialchars($item['student_name']); ?></td>
                <td><?php echo htmlspecialchars($item['student_email']); ?></td>
                <td><?php echo $item['stress_level']; ?></td>
                <td><?php echo $item['anxiety_score']; ?></td>
                <td><?php echo htmlspecialchars($item['notes']); ?></td>
                <td><?php echo $item['created_at']; ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>