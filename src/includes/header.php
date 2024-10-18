<?php
include 'config.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?php echo $title ?? 'TODO'; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>templates/assets/css/styles.css">
    <script src="assets/js/scripts.js"></script>
</head>

<body>
    <header>
        <!-- <h1>TODO</h1> -->
        <nav>

            <a href="<?php echo BASE_URL; ?>index.php">TODO</a>
            <div class="nav-links"> <!-- New div for the right-aligned links -->
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="<?php echo BASE_URL; ?>templates/profile.php">Profile</a>
                    <a href="<?php echo BASE_URL; ?>templates/tasks.php">Tasks</a>
                    <a href="<?php echo BASE_URL; ?>templates/logout.php">Logout</a>
                <?php else: ?>
                    <a href="<?php echo BASE_URL; ?>templates/login.php">Login</a>
                    <a href="<?php echo BASE_URL; ?>templates/register.php">Register</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>
    <main>