<?php
session_set_cookie_params(0);
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$error = $_SESSION['register_error'] ?? '';
unset($_SESSION['register_error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Study Planner - Register</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .password-requirements {
            margin-top: 0.5rem;
            display: flex;
            flex-direction: column;
            gap: 0.3rem;
            font-size: 0.82rem;
        }
        .requirement-item {
            color: var(--text-muted);
            transition: color 0.3s ease;
        }
        .requirement-item.valid {
            color: var(--success);
        }
        .requirement-item.invalid {
            color: var(--danger);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Create Account</h2>
        <p class="subtitle">Join Smart Study Planner today</p>

        <?php if (!empty($error)): ?>
            <div class="error-msg"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>

        <form action="../controllers/register_process.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="example@gmail.com" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="••••••••" required>
                <div class="password-requirements">
                    <span style="color: var(--text-muted);">Password must contain:</span>
                    <div id="length" class="requirement-item invalid">At least 8 characters</div>
                    <div id="uppercase" class="requirement-item invalid">At least 1 uppercase letter</div>
                    <div id="lowercase" class="requirement-item invalid">At least 2 lowercase letters</div>
                    <div id="special" class="requirement-item invalid">At least 2 special characters (!@#$%^&*(),.?":{}|<>)</div>
                </div>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="••••••••" required>
            </div>

            <button type="submit" style="width: 100%; margin-top: 0.5rem;">Sign Up</button>
        </form>

        <p style="text-align: center; margin-top: 0.5rem; font-size: 0.875rem; color: var(--text-muted);">
            Already have an account? <a href="login.php" style="color: var(--primary); text-decoration: none; font-weight: 600;">Sign In</a>
        </p>
    </div>

    <script>
        const passwordInput = document.getElementById('password');
        const lengthReq = document.getElementById('length');
        const uppercaseReq = document.getElementById('uppercase');
        const lowercaseReq = document.getElementById('lowercase');
        const specialReq = document.getElementById('special');

        passwordInput.addEventListener('input', function() {
            const value = passwordInput.value;

            if (value.length >= 8) {
                lengthReq.classList.remove('invalid');
                lengthReq.classList.add('valid');
            } else {
                lengthReq.classList.remove('valid');
                lengthReq.classList.add('invalid');
            }

            if (/[A-Z]/.test(value)) {
                uppercaseReq.classList.remove('invalid');
                uppercaseReq.classList.add('valid');
            } else {
                uppercaseReq.classList.remove('valid');
                uppercaseReq.classList.add('invalid');
            }

            const lowercaseMatches = value.match(/[a-z]/g);
            if (lowercaseMatches && lowercaseMatches.length >= 2) {
                lowercaseReq.classList.remove('invalid');
                lowercaseReq.classList.add('valid');
            } else {
                lowercaseReq.classList.remove('valid');
                lowercaseReq.classList.add('invalid');
            }

            const specialMatches = value.match(/[!@#$%^&*(),.?":{}|<>]/g);
            if (specialMatches && specialMatches.length >= 2) {
                specialReq.classList.remove('invalid');
                specialReq.classList.add('valid');
            } else {
                specialReq.classList.remove('valid');
                specialReq.classList.add('invalid');
            }
        });
    </script>
</body>
</html>