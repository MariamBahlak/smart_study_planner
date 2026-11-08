<?php
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$error = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Study Planner - Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Welcome Back 🎓</h2>
        <p class="subtitle">Sign in to access your study planner</p>
        
        <?php if (!empty($error)): ?>
            <div class="error-msg"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>

        <form action="../controllers/login_process.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" required placeholder="name@example.com">
            </div>
            
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="••••••••">
            </div>
            
            <button type="submit" style="width: 100%; margin-top: 0.5rem;">Login</button>
        </form>
        
        <p style="text-align: center; margin-top: 0.5rem; font-size: 0.875rem; color: var(--text-muted);">
            Don't have an account? <a href="register.php" style="color: var(--primary); text-decoration: none; font-weight: 600;">Register here</a>
        </p>
    </div>
</body>
</html>