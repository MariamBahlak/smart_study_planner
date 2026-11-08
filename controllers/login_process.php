<?php
session_set_cookie_params(0);
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $_SESSION['login_error'] = "Please fill in all fields.";
        header("Location: ../public/login.php");
        exit();
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];

            header("Location: ../public/dashboard.php");
            exit();
        } else {
            $_SESSION['login_error'] = "Invalid email or password.";
            header("Location: ../public/login.php");
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['login_error'] = "An error occurred. Please try again later.";
        header("Location: ../public/login.php");
        exit();
    }
}
?>