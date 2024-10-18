<?php
// templates/add_task.php

include '../includes/config.php';
include '../includes/db.php';
include '../includes/auth.php';
requireLogin();
include '../includes/header.php';
include '../includes/functions.php';

// Initialize variables
$title = $description = $due_date = $priority = $status = "";
$title_err = $due_date_err = $priority_err = $status_err = "";
$add_success = "";

// Process form when POST request is made
if ($_SERVER["REQUEST_METHOD"] == "POST") {


    // Validate Title
    if (empty(trim($_POST["title"]))) {
        $title_err = "Please enter a title.";
    } else {
        $title = sanitize($_POST["title"]);
    }

    // Description is optional
    $description = sanitize($_POST["description"]);

    // Validate Due Date
    if (empty(trim($_POST["due_date"]))) {
        $due_date_err = "Please select a due date.";
    } else {
        $date = DateTime::createFromFormat('Y-m-d', $_POST["due_date"]);
        $errors = DateTime::getLastErrors();
        if ($date === false || $errors['warning_count'] > 0 || $errors['error_count'] > 0) {
            $due_date_err = "Invalid date format or date. Use YYYY-MM-DD.";
        } else {
            $due_date = $_POST["due_date"];
        }
    }

    // Validate Priority
    $valid_priorities = ['low', 'medium', 'high'];
    if (empty($_POST["priority"])) {
        $priority = 'low'; // Default value
    } else {
        $priority = $_POST["priority"];
        if (!in_array($priority, $valid_priorities)) {
            $priority_err = "Invalid priority selected.";
        }
    }

    // Validate Status
    $valid_statuses = ['pending', 'in progress', 'completed'];
    if (empty($_POST["status"])) {
        $status = 'pending'; // Default value
    } else {
        $status = $_POST["status"];
        if (!in_array($status, $valid_statuses)) {
            $status_err = "Invalid status selected.";
        }
    }

    // If no errors, insert into database
    if (empty($title_err) && empty($due_date_err) && empty($priority_err) && empty($status_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO tasks (user_id, title, description, due_date, priority, status) VALUES (?, ?, ?, ?, ?, ?)";

        if ($stmt = $pdo->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $user_id = $_SESSION['user_id'];
            // If 'created_at' is handled by the database with DEFAULT CURRENT_TIMESTAMP, no need to include it
            // Otherwise, include 'created_at' in the SQL and pass $current_date

            // Execute the statement
            if ($stmt->execute([$user_id, $title, $description, $due_date, $priority, $status])) {
                $add_success = "Task added successfully.";
                setToast($add_success, 'sucess');
                redirect('tasks.php');
                // Clear form fields
                $title = $description = $due_date = $priority = $status = "";
            } else {
                echo "<div class='error'>Something went wrong. Please try again.</div>";
            }
        }

        // Close statement
        unset($stmt);
    }

    // Close connection
    unset($pdo);
}
?>
<div class="main-container ">
    <h2>Add New Task</h2>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div>
            <label for="title">Title</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>">
            <span class="error"><?php echo $title_err; ?></span>
        </div>
        <div>
            <label for="description">Description</label>
            <textarea id="description" name="description"><?php echo htmlspecialchars($description); ?></textarea>
        </div>
        <div>
            <label for="due_date">Due Date</label>
            <input type="date" id="due_date" name="due_date" value="<?php echo htmlspecialchars($due_date); ?>">
            <span class="error"><?php echo $due_date_err; ?></span>
        </div>
        <div>
            <label for="priority">Priority</label>
            <select id="priority" name="priority">
                <option value="" disabled <?php echo empty($priority) ? 'selected' : ''; ?>>-- Select Priority --
                </option>
                <option value="low" <?php echo ($priority === 'low') ? 'selected' : ''; ?>>Low</option>
                <option value="medium" <?php echo ($priority === 'medium') ? 'selected' : ''; ?>>Medium</option>
                <option value="high" <?php echo ($priority === 'high') ? 'selected' : ''; ?>>High</option>
            </select>
            <span class="error"><?php echo $priority_err; ?></span>
        </div>
        <div>
            <label for="status">Status</label>
            <select id="status" name="status">
                <option value="" disabled <?php echo empty($status) ? 'selected' : ''; ?>>-- Select Status --</option>
                <option value="pending" <?php echo ($status === 'pending') ? 'selected' : ''; ?>>Pending</option>
                <option value="in progress" <?php echo ($status === 'in progress') ? 'selected' : ''; ?>>In Progress
                </option>
                <option value="completed" <?php echo ($status === 'completed') ? 'selected' : ''; ?>>Completed</option>
            </select>
            <span class="error"><?php echo $status_err; ?></span>
        </div>
        <div>
            <input type="submit" value="Add Task">
        </div>
    </form>
</div>

<?php include '../includes/footer.php'; ?>