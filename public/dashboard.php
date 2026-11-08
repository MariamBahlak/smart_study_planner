<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_task') {
    $task_title = trim($_POST['task_title']);
    if (!empty($task_title)) {
        $stmt = $pdo->prepare("INSERT INTO tasks (user_id, title, is_completed) VALUES (?, ?, 0)");
        $stmt->execute([$user_id, $task_title]);
    }
    header("Location: dashboard.php");
    exit();
}

if (isset($_GET['delete_id'])) {
    $task_id = $_GET['delete_id'];
    $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
    $stmt->execute([$task_id, $user_id]);
    header("Location: dashboard.php");
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM tasks WHERE user_id = ? ORDER BY id DESC");
$stmt->execute([$user_id]);
$tasks = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Study Planner - Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Welcome back, <?php echo htmlspecialchars($_SESSION['user_name'], ENT_QUOTES, 'UTF-8'); ?>! 🎓</h2>
        <p class="subtitle">Your Smart Study Planner & AI Assistant Dashboard</p>

        <div class="glass-card">
            <h3>📌 Add New Study Task</h3>
            <form action="dashboard.php" method="POST" style="display: flex; gap: 0.75rem;">
                <input type="hidden" name="action" value="add_task">
                <input type="text" name="task_title" placeholder="What do you want to study today?" required style="margin: 0;">
                <button type="submit" style="width: 140px; flex-shrink: 0;">Add Task</button>
            </form>
        </div>

        <div class="glass-card">
            <h3>📋 Your Study Tasks</h3>
            <?php if (empty($tasks)): ?>
                <p style="color: var(--text-muted); font-size: 0.9rem; font-style: italic;">No tasks found. Add your first study task above!</p>
            <?php else: ?>
                <ul class="task-list">
                    <?php foreach ($tasks as $task): ?>
                        <li class="task-item">
                            <span style="<?php echo ($task['is_completed'] == 1) ? 'text-decoration: line-through; color: var(--text-muted);' : ''; ?>">
                                <?php echo htmlspecialchars($task['title'], ENT_QUOTES, 'UTF-8'); ?>
                            </span>
                            <div style="display: flex; gap: 0.5rem; align-items: center;">
                                <a href="../controllers/update_task.php?id=<?php echo $task['id']; ?>" class="btn-outline" style="padding: 0.3rem 0.6rem; font-size: 0.8rem; border-radius: 6px; text-decoration: none;">
                                    <?php echo ($task['is_completed'] == 1) ? 'Undo' : 'Complete'; ?>
                                </a>
                                <a href="dashboard.php?delete_id=<?php echo $task['id']; ?>" class="delete-btn">Delete</a>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <div class="glass-card">
            <h3>🤖 AI Study Assistant</h3>
            <div id="chatMessages" class="chat-messages">
                <div class="chat-msg-ai"><b>AI:</b> Hello! Ask me anything about your study plan or subjects...</div>
            </div>
            <div style="display: flex; gap: 0.75rem;">
                <input type="text" id="userInput" placeholder="Ask your AI study assistant..." onkeypress="handleKeyPress(event)" style="margin: 0;">
                <button onclick="sendToAI()" style="width: 120px; flex-shrink: 0;">Send</button>
            </div>
        </div>

        <form action="../controllers/logout.php" method="POST">
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </div>

    <script>
        function handleKeyPress(event) {
            if (event.key === 'Enter') {
                sendToAI();
            }
        }

        async function sendToAI() {
            const input = document.getElementById('userInput');
            const messagesDiv = document.getElementById('chatMessages');
            const message = input.value.trim();
            
            if (!message) return;

            messagesDiv.innerHTML += `<div class="chat-msg-user"><b>You:</b> ${htmlEscape(message)}</div>`;
            input.value = '';
            messagesDiv.scrollTop = messagesDiv.scrollHeight;

            const loadingId = 'loading_' + Date.now();
            messagesDiv.innerHTML += `<div id="${loadingId}" class="chat-msg-ai" style="font-style: italic; color: var(--text-muted);"><b>AI:</b> Thinking...</div>`;
            messagesDiv.scrollTop = messagesDiv.scrollHeight;

            try {
                const response = await fetch('../controllers/ai_process.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ message: message })
                });
                
                const data = await response.json();
                document.getElementById(loadingId).remove();

                if (data.status === 'success') {
                    messagesDiv.innerHTML += `<div class="chat-msg-ai"><b>AI:</b> ${data.reply}</div>`;
                } else {
                    messagesDiv.innerHTML += `<div class="chat-msg-ai" style="color: var(--danger);"><b>Error:</b> ${data.message}</div>`;
                }
            } catch (error) {
                document.getElementById(loadingId).remove();
                messagesDiv.innerHTML += `<div class="chat-msg-ai" style="color: var(--danger);"><b>Error:</b> Connection failed.</div>`;
            }
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        }

        function htmlEscape(str) {
            return str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        }
    </script>
</body>
</html>
