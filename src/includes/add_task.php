<?php include_once __DIR__ . '/../includes/auth.php'; ?>

<?php if (isLoggedIn()): ?>
    <a href="<?php echo BASE_URL; ?>templates/add_task.php" class="add-task">
        +
    </a>
<?php endif; ?>