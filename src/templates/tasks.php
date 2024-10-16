<?php
// templates/tasks.php
include '../includes/config.php';
include '../includes/db.php';
include '../includes/auth.php';
requireLogin();
include '../includes/header.php';
include '../includes/functions.php';

$user_id = $_SESSION['user_id'];

// Fetch tasks
$sql = "SELECT * FROM tasks WHERE user_id = ? ORDER BY due_date ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$tasks = $stmt->fetchAll();
?>

<h2>Your Tasks</h2>
<a href="add_task.php">Add New Task</a>
<table border="1">
    <tr>
        <th>Title</th>
        <th>Description</th>
        <th>Due Date</th>
        <th>Actions</th>
    </tr>

    <?php
    if (count($tasks) != 0): ?>
        <?php foreach ($tasks as $task): ?>
            <tr>
                <td><?php echo htmlspecialchars($task['title']); ?></td>
                <td><?php echo htmlspecialchars($task['description']); ?></td>
                <td><?php echo htmlspecialchars($task['due_date']); ?></td>
                <td>
                    <a href="edit_task.php?id=<?php echo $task['id']; ?>">Edit</a> |
                    <a href="delete_task.php?id=<?php echo $task['id']; ?>"
                       onclick="return confirm('Are you sure you want to delete this task?');">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="4">No tasks found.</td>
        </tr>
    <?php endif; ?>
</table>

<?php include '../includes/footer.php'; ?>