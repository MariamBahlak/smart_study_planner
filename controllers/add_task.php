<?php
// controllers/add_task.php

require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task_text = trim($_POST['task_text'] ?? '');

    if (!empty($task_text)) {
        try {
           
            $stmt = $pdo->prepare("INSERT INTO tasks (title, is_completed) VALUES (:title, 0)");
            $stmt->execute([
                ':title' => $task_text
            ]);
        } catch (PDOException $e) {
            die("Error adding task: " . $e->getMessage());
        }
    }
}

header('Location: ../public/index.php');
exit;
?>