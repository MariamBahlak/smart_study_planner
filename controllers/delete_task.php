<?php


require_once __DIR__ . '/../config/database.php';


if (isset($_GET['id'])) {
    $task_id = (int) $_GET['id'];

    try {
       
        $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = :id");
        $stmt->execute([':id' => $task_id]);
        
    } catch (PDOException $e) {
        die("Error deleting task: " . $e->getMessage());
    }
}


header('Location: ../public/index.php');
exit;
?>