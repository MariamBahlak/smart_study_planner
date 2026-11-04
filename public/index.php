<?php
// public/index.php

require_once __DIR__ . '/../config/database.php';

try {
    // Fetch all tasks ordered by latest first
    $stmt = $pdo->query("SELECT * FROM tasks ORDER BY id DESC");
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $tasks = [];
    $error_message = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Study Planner</title>
    <link rel="stylesheet" href="../assets/CSS/style.css">
</head>
<body>

    <div class="container">
        <h1>🚀 Smart Study Planner</h1>

        <!-- Add Task Form -->
        <form action="../controllers/add_task.php" method="POST" class="add-form">
            <input type="text" name="task_text" placeholder="Enter a new study task or topic..." required autocomplete="off">
            <button type="submit">Add Task</button>
        </form>

        <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">

        <h3>Study Tasks List:</h3>
        <ul>
            <?php if (!empty($tasks)): ?>
                <?php foreach ($tasks as $task): ?>
                    <li class="<?= $task['is_completed'] == 1 ? 'completed' : '' ?>">
    <a href="../controllers/update_task.php?id=<?= $task['id'] ?>" 
       style="text-decoration: none; color: inherit; display: flex; align-items: center; width: 100%;">
        <span><?= htmlspecialchars($task['title']) ?></span>
    </a>
    
    <div style="display: flex; gap: 10px; align-items: center;">
        <small style="color: #666; font-size: 12px;"><?= $task['created_at'] ?></small>
        <a href="../controllers/delete_task.php?id=<?= $task['id'] ?>" 
           style="background-color: #dc3545; color: white; padding: 4px 8px; border-radius: 4px; text-decoration: none; font-size: 12px;"
           onclick="return confirm('Are you sure you want to delete this task?');">
           Delete
        </a>
    </div>
</li>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-tasks">No tasks added yet. Start planning your study session!</p>
            <?php endif; ?>
        </ul>
    </div>

</body>
</html>