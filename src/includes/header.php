<!-- header.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?php echo $title ?? 'My PHP App'; ?></title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
    <header>
        <h1>My PHP App</h1>
        <nav>
            <a href="index.php">Home</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="templates/profile.php">Profile</a>
                <a href="templates/tasks.php">Tasks</a>
                <a href="templates/logout.php">Logout</a>
            <?php else: ?>
                <a href="templates/login.php">Login</a>
                <a href="templates/register.php">Register</a>
            <?php endif; ?>
        </nav>
    </header>
    <main>