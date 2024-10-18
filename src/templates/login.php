<?php
// templates/login.php
include '../includes/config.php';
include '../includes/db.php';
include '../includes/header.php';
include '../includes/functions.php';
include '../includes/auth.php';

$email = $password = "";
$email_err = $password_err = $login_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if email is empty
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email.";
        setToast($email_err, 'error');
    } else {
        $email = sanitize($_POST["email"]);
        setToast($email_err, 'error');
    }

    // Check if password is empty
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
        setToast($password_err, 'error');
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if (empty($email_err) && empty($password_err)) {
        $sql = "SELECT id, name, email, password FROM users WHERE email = ?";

        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(1, $email, PDO::PARAM_STR);

            $data = $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);


            if ($user) {


                if (password_verify($password, $user['password'])) {
                    // Password is correct, start a new session
                    session_start();
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    // Redirect to profile page
                    redirect('tasks.php');
                } else {
                    $login_err = "Invalid email or password. def pass";
                }
            } else {
                $login_err = "Invalid email or password. def db";
            }
        }
    }
}
?>
<div class="main-container">

    <h2>Login</h2>
    <?php
    if (!empty($login_err)) {
        echo '<div>' . $login_err . '</div>';
    }
    ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
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
            <input type="submit" value="Login">
        </div>
        <p>Don't have an account? <a href="register.php">Register here</a>.</p>
    </form>
</div>

<?php include '../includes/footer.php'; ?>