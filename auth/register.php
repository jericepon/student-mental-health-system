<?php
// auth/register.php
session_start();
require_once '../config/database.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = 'student'; // Default public registration role

    if (!empty($name) && !empty($email) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->fetch()) {
            $error = "Email is already registered.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
            if ($insert->execute([$name, $email, $hashed_password, $role])) {
                $success = "Registration successful! You can now <a href='login.php'>login</a>.";
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    } else {
        $error = "Please fill in all fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Mental Health System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="auth-container">
        <h2>Student Registration</h2>
        <?php if (!empty($error)): ?><p class="error"><?php echo $error; ?></p><?php endif; ?>
        <?php if (!empty($success)): ?><p class="success"><?php echo $success; ?></p><?php endif; ?>
        
        <form action="register.php" method="POST">
            <label>Full Name:</label>
            <input type="text" name="name" required>

            <label>Email Address:</label>
            <input type="email" name="email" required>
            
            <label>Password:</label>
            <input type="password" name="password" required>
            
            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>