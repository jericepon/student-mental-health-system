<?php
// index.php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Mental Health System</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container" style="text-align: center; margin-top: 80px;">
        <h2>Student Mental Health Assessment and Referral System</h2>
        <p>Supports mental health screenings, referrals, and intervention tracking[cite: 1].</p>
        <hr style="margin: 20px 0;">
        
        <?php if (isset($_SESSION['user_id'])): ?>
            <p>Welcome back, <strong><?php echo htmlspecialchars($_SESSION['name']); ?></strong>!</p>
            <?php if ($_SESSION['role'] === 'student'): ?>
                <a href="student/take_assessment.php"><button type="button">Go to Student Dashboard</button></a>
            <?php else: ?>
                <a href="counselor/dashboard.php"><button type="button">Go to Counselor Dashboard</button></a>
            <?php endif; ?>
            <p style="margin-top: 15px;"><a href="auth/logout.php">Logout</a></p>
        <?php else: ?>
            <p>Please login or register to access mental health screenings and support tools.</p>
            <div style="margin-top: 20px; display: flex; gap: 10px; justify-content: center;">
                <a href="auth/login.php" style="width: 45%;"><button type="button">Login</button></a>
                <a href="auth/register.php" style="width: 45%;"><button type="button" style="background-color: #007BFF;">Register</button></a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>