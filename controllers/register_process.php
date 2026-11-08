<?php
session_set_cookie_params(0);
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['register_error'] = "Invalid email format.";
        header("Location: ../public/register.php");
        exit();
    }

    $domain = substr(strrchr($email, "@"), 1);
    
    $mx_records = [];
    $has_mx = false;
    $has_a = false;

    if (function_exists('getmxrr') && getmxrr($domain, $mx_records)) {
        $has_mx = true;
    }
    
    if (!$has_mx && checkdnsrr($domain, "MX")) {
        $has_mx = true;
    }

    if (checkdnsrr($domain, "A")) {
        $has_a = true;
    }

    if (!$has_mx && !$has_a) {
        $_SESSION['register_error'] = "The email domain does not exist or cannot receive emails. Please enter a real email.";
        header("Location: ../public/register.php");
        exit();
    }

    if (empty($name) || empty($password) || empty($confirmPassword)) {
        $_SESSION['register_error'] = "Please fill in all fields.";
        header("Location: ../public/register.php");
        exit();
    }

    if ($password !== $confirmPassword) {
        $_SESSION['register_error'] = "Passwords do not match.";
        header("Location: ../public/register.php");
        exit();
    }

    $hasLength = strlen($password) >= 8;
    $hasUpper = preg_match('/[A-Z]/', $password);
    $hasTwoLower = preg_match_all('/[a-z]/', $password) >= 2;
    $hasTwoSpecial = preg_match_all('/[!@#$%^&*(),.?":{}|<>]/', $password) >= 2;

    if (!$hasLength || !$hasUpper || !$hasTwoLower || !$hasTwoSpecial) {
        $_SESSION['register_error'] = "Password does not meet all complexity requirements.";
        header("Location: ../public/register.php");
        exit();
    }

    try {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $_SESSION['register_error'] = "This email is already registered.";
            header("Location: ../public/register.php");
            exit();
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $insertStmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $insertStmt->execute([$name, $email, $hashedPassword]);

        $_SESSION['success_message'] = "Account created successfully! Please sign in.";
        header("Location: ../public/login.php");
        exit();

    } catch (PDOException $e) {
        $_SESSION['register_error'] = "An error occurred. Please try again.";
        header("Location: ../public/register.php");
        exit();
    }
}
?>