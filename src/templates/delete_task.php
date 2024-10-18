<?php
// templates/delete_task.php
include '../includes/config.php';
include '../includes/db.php';
include '../includes/auth.php';
requireLogin();
include '../includes/functions.php';

$user_id = $_SESSION['user_id'];
$task_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Delete the task
$sql = "DELETE FROM tasks WHERE id = ? AND user_id = ?";
$stmt = $pdo->prepare($sql);
if ($stmt->execute([$task_id, $user_id])) {
    setToast("Task Deleted.", 'error');
    redirect('tasks.php');
} else {
    echo "Something went wrong. Please try again.";
}
?>