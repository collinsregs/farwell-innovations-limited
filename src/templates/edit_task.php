<?php
// templates/edit_task.php

include '../includes/config.php';
include '../includes/db.php';
include '../includes/auth.php';
requireLogin();
include '../includes/header.php';
include '../includes/functions.php';

$user_id = $_SESSION['user_id'];
$task_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch task data
$sql = "SELECT * FROM tasks WHERE id = ? AND user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$task_id, $user_id]);
$task = $stmt->fetch();

if (!$task) {
    echo "Task not found.";
    include '../includes/footer.php';
    exit();
}

$title = $task['title'];
$description = $task['description'];
$due_date = $task['due_date'];
$priority = $task['priority'];
$status = $task['status'];

$title_err = $due_date_err = $priority_err = $status_err = "";
$update_success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate title
    if (empty(trim($_POST["title"]))) {
        $title_err = "Please enter a title.";
        setToast($title_err, 'error');
    } else {
        $title = sanitize($_POST["title"]);
    }

    // Description is optional
    $description = sanitize($_POST["description"]);

    // Validate due date
    if (empty(trim($_POST["due_date"]))) {
        $due_date_err = "Please enter a due date.";
        setToast($due_date_err, 'error');
    } elseif (!DateTime::createFromFormat('Y-m-d', $_POST["due_date"])) {
        $due_date_err = "Invalid date format. Use YYYY-MM-DD.";
        setToast($due_date_err, 'error');
    } else {
        $due_date = $_POST["due_date"];
    }

    // Validate priority
    $valid_priorities = ['low', 'medium', 'high'];
    if (!in_array($_POST['priority'], $valid_priorities)) {
        $priority_err = "Invalid priority.";
        setToast($priority_err, 'error');
    } else {
        $priority = $_POST['priority'];
    }

    // Validate status
    $valid_statuses = ['pending', 'in progress', 'completed'];
    if (!in_array($_POST['status'], $valid_statuses)) {
        $status_err = "Invalid status.";
    } else {
        $status = $_POST['status'];
    }

    // If no errors, update the database
    if (empty($title_err) && empty($due_date_err) && empty($priority_err) && empty($status_err)) {
        $sql = "UPDATE tasks SET title = ?, description = ?, due_date = ?, priority = ?, status = ? WHERE id = ? AND user_id = ?";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$title, $description, $due_date, $priority, $status, $task_id, $user_id])) {
            $update_success = "Task updated successfully.";
            setToast("Task updated successfully.", 'success');
            header("Location: tasks.php");
            exit();
        } else {
            echo "Something went wrong. Please try again.";
        }
    }
}
?>
<div class="main-container">

    <h2>Edit Task</h2>
    <?php
    if (!empty($update_success)) {
        echo '<div>' . $update_success . '</div>';
    }
    ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $task_id; ?>" method="post">
        <div>
            <label>Title</label>
            <input type="text" name="title" value="<?php echo htmlspecialchars($title); ?>">
            <span><?php echo $title_err; ?></span>
        </div>
        <div>
            <label>Description</label>
            <textarea name="description"><?php echo htmlspecialchars($description); ?></textarea>
        </div>
        <div>
            <label>Due Date</label>
            <input type="date" name="due_date" value="<?php echo htmlspecialchars($due_date); ?>">
            <span><?php echo $due_date_err; ?></span>
        </div>
        <div>
            <label>Priority</label>
            <select name="priority">
                <option value="low" <?php echo $priority == 'low' ? 'selected' : ''; ?>>Low</option>
                <option value="medium" <?php echo $priority == 'medium' ? 'selected' : ''; ?>>Medium</option>
                <option value="high" <?php echo $priority == 'high' ? 'selected' : ''; ?>>High</option>
            </select>
            <span><?php echo $priority_err; ?></span>
        </div>
        <div>
            <label>Status</label>
            <select name="status">
                <option value="pending" <?php echo $status == 'pending' ? 'selected' : ''; ?>>Pending</option>
                <option value="in progress" <?php echo $status == 'in progress' ? 'selected' : ''; ?>>In Progress</option>
                <option value="completed" <?php echo $status == 'completed' ? 'selected' : ''; ?>>Completed</option>
            </select>
            <span><?php echo $status_err; ?></span>
        </div>
        <div>
            <input type="submit" value="Update Task">
        </div>
    </form>
</div>
<?php include '../includes/footer.php'; ?>