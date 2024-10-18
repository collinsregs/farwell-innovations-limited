<?php
include '../includes/config.php';
include '../includes/db.php';
include '../includes/auth.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $taskId = $_POST['id'];
    $status = isset($_POST['status']) ? 'completed' : 'pending'; // Set status based on checkbox

    // Update task status
    $sql = "UPDATE tasks SET status = ? WHERE id = ? AND user_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$status, $taskId, $_SESSION['user_id']]);

    // Redirect back to tasks page
    header("Location: tasks.php");
    exit;
} else {
    // Handle invalid request
    header("Location: tasks.php");
    exit;
}
?>