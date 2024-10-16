<?php
// templates/add_task.php
include '../includes/config.php';
include '../includes/db.php';
include '../includes/auth.php';
requireLogin();
include '../includes/header.php';
include '../includes/functions.php';

$title = $description = $due_date = "";
$title_err = $due_date_err = "";
$add_success = "";

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

    // If no errors, insert into database
    if (empty($title_err) && empty($due_date_err)) {
        $sql = "INSERT INTO tasks (user_id, title, description, due_date) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$user_id, $title, $description, $due_date])) {
            $add_success = "Task added successfully.";
            // Clear form fields
            $title = $description = $due_date = "";
        } else {
            echo "Something went wrong. Please try again.";
        }
    }
}
?>

<h2>Add New Task</h2>
<?php
if (!empty($add_success)) {
    echo '<div>' . $add_success . '</div>';
}
?>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
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
        <input type="submit" value="Add Task">
    </div>
</form>

<?php include '../includes/footer.php'; ?>