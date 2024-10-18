<?php
// templates/register.php
include '../includes/config.php';
include '../includes/db.php';
include '../includes/header.php';
include '../includes/functions.php';

$name = $email = $password = $confirm_password = "";
$name_err = $email_err = $password_err = $confirm_password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate name
    if (empty(trim($_POST["name"]))) {
        $name_err = "Please enter your name.";
        setToast($name_err, 'error');
    } else {
        $name = sanitize($_POST["name"]);
    }

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email.";
        setToast($email_err, 'error');
    } elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $email_err = "Invalid email format.";
        setToast($email_err, 'error');
    } else {
        // Check if email already exists
        $sql = "SELECT id FROM users WHERE email = ?";
        if ($stmt = $pdo->prepare($sql)) {
            $stmt->execute([$_POST["email"]]);
            if ($stmt->rowCount() == 1) {
                $email_err = "This email is already registered.";
                setToast($email_err, 'error');
            } else {
                $email = sanitize($_POST["email"]);
            }
        }
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
        setToast($password_err, 'error');
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must be at least 6 characters.";
        setToast($password_err, 'error');
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm your password.";
        setToast($confirm_password_err, 'error');
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if ($password != $confirm_password) {
            $confirm_password_err = "Passwords do not match.";
            setToast($confirm_password_err, 'error');
        }
    }

    // Check input errors before inserting
    if (empty($name_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)) {
        $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
        if ($stmt = $pdo->prepare($sql)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            if ($stmt->execute([$name, $email, $hashed_password])) {
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                // Redirect to profile page
                redirect('tasks.php');
            } else {
                setToast("Something went wrong. Please try again.", 'error');
                echo "";
            }
        }
    }
}
?>
<div class="main-container">
    <h2>Register</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div>
            <label>Name</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>">
            <span><?php echo $name_err; ?></span>
        </div>
        <div>
            <label>Email</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
            <span><?php echo $email_err; ?></span>
        </div>
        <div>
            <label>Password</label>
            <input type="password" name="password">
            <span><?php echo $password_err; ?></span>
        </div>
        <div>
            <label>Confirm Password</label>
            <input type="password" name="confirm_password">
            <span><?php echo $confirm_password_err; ?></span>
        </div>
        <div>
            <input type="submit" value="Register">
        </div>
        <p>Already have an account? <a href="login.php">Login here</a>.</p>
    </form>
</div>

<?php include '../includes/footer.php'; ?>