<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Study Planner - Welcome</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="card" style="text-align: center;">
        <h1>Smart Study Planner</h1>
        <p>Organize your academic tasks, boost your productivity, and manage your study schedule efficiently in one secure place.</p>
        <div style="display: flex; gap: 1rem; justify-content: center; margin-top: 1rem;">
            <a href="register.php" class="btn">Create Account</a>
            <a href="login.php" class="btn btn-outline">Sign In</a>
        </div>
    </div>
</body>
</html>