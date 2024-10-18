<?php
include '../includes/config.php';
include '../includes/db.php';
include '../includes/auth.php';

requireLogin();

$taskId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$status = isset($_GET['status']) ? $_GET['status'] : '';

$validStatuses = ['completed', 'in progress', 'pending'];

if ($taskId && in_array($status, $validStatuses)) {
    // Update task status
    $sql = "UPDATE tasks SET status = ? WHERE id = ? AND user_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$status, $taskId, $_SESSION['user_id']]);

    // Redirect back to tasks page
    header("Location: tasks.php");
    exit;
} else {
    header("Location: tasks.php");
    exit;
}
?>