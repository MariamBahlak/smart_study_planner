<?php
// controllers/update_task.php

require_once __DIR__ . '/../config/database.php';

if (isset($_GET['id'])) {
    $task_id = (int) $_GET['id'];

    try {
        // 1. First, fetch the current completion status of the task
        $stmt = $pdo->prepare("SELECT is_completed FROM tasks WHERE id = :id");
        $stmt->execute([':id' => $task_id]);
        $task = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($task) {
            // 2. Toggle the status: if 0 make it 1, if 1 make it 0
            $new_status = ($task['is_completed'] == 1) ? 0 : 1;

            // 3. Update the database with the new status
            $update_stmt = $pdo->prepare("UPDATE tasks SET is_completed = :status WHERE id = :id");
            $update_stmt->execute([
                ':status' => $new_status,
                ':id' => $task_id
            ]);
        }
    } catch (PDOException $e) {
        die("Error updating task: " . $e->getMessage());
    }
}

// Redirect back to the main interface
header('Location: ../public/index.php');
exit;
?>