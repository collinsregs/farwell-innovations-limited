<?php
include '../includes/config.php';
include '../includes/db.php';
include '../includes/auth.php';

requireLogin();

$taskId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$priority = isset($_GET['priority']) ? $_GET['priority'] : '';

$validPriorities = ['low', 'medium', 'high'];

if ($taskId && in_array($priority, $validPriorities)) {
    // Update task priority
    $sql = "UPDATE tasks SET priority = ? WHERE id = ? AND user_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$priority, $taskId, $_SESSION['user_id']]);

    // Redirect back to tasks page
    header("Location: tasks.php");
    exit;
} else {
    header("Location: tasks.php");
    exit;
}
?>