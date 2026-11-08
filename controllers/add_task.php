<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../public/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task_text = trim($_POST['task_text']);
    $user_id = $_SESSION['user_id'];

    if (!empty($task_text)) {
        $stmt = $pdo->prepare("INSERT INTO tasks (user_id, title, is_completed) VALUES (?, ?, 0)");
        $stmt->execute([$user_id, $task_text]);
    }

    header("Location: ../public/dashboard.php");
    exit();
}
?>