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
$title_err = $due_date_err = "";
$update_success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate title
    if (empty(trim($_POST["title"]))) {
        $title_err = "Please enter a title.";
    } else {
        $title = sanitize($_POST["title"]);
    }

    // Description is optional
    $description = sanitize($_POST["description"]);

    // Validate due date
    if (empty(trim($_POST["due_date"]))) {
        $due_date_err = "Please enter a due date.";
    } elseif (!DateTime::createFromFormat('Y-m-d', $_POST["due_date"])) {
        $due_date_err = "Invalid date format. Use YYYY-MM-DD.";
    } else {
        $due_date = $_POST["due_date"];
    }

    // If no errors, update the database
    if (empty($title_err) && empty($due_date_err)) {
        $sql = "UPDATE tasks SET title = ?, description = ?, due_date = ? WHERE id = ? AND user_id = ?";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$title, $description, $due_date, $task_id, $user_id])) {
            $update_success = "Task updated successfully.";
        } else {
            echo "Something went wrong. Please try again.";
        }
    }
}
?>

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
        <input type="submit" value="Update Task">
    </div>
</form>

<?php include '../includes/footer.php'; ?>