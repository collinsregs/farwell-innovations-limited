<?php

require_once 'includes/auth.php';
include 'includes/header.php';

$title = "Home";

?>
<div class="main-container">

    <h2>Welcome to TODO</h2>

    <?php if (isLoggedIn()): ?>
        <p>Hello, <?php echo htmlspecialchars($_SESSION['user_name']); ?>! Manage your <a href=" templates/tasks.php">
                tasks</a>
            or update your <a href="templates/profile.php">profile</a>.</p>
    <?php else: ?>
        <p>Please <a href="templates/login.php">login</a> or <a href="templates/register.php">register</a> to continue.</p>
    <?php endif; ?>
</div>
<?php include './includes/add_task.php'; ?>

<?php include 'includes/footer.php'; ?>