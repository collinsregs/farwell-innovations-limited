<?php
// templates/profile.php
include '../includes/config.php';
include '../includes/db.php';
include '../includes/auth.php';
requireLogin();
include '../includes/header.php';
include '../includes/functions.php';

$user_id = $_SESSION['user_id'];
$name = $email = "";
$name_err = $email_err = $update_success = "";

// Fetch current user data
$sql = "SELECT name, email FROM users WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$user = $stmt->fetch();
$name = $user['name'];
$email = $user['email'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate name
    if (empty(trim($_POST["name"]))) {
        $name_err = "Please enter your name.";
    } else {
        $name = sanitize($_POST["name"]);
    }

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email.";
    } elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $email_err = "Invalid email format.";
    } else {
        $new_email = sanitize($_POST["email"]);
        if ($new_email != $email) {
            // Check if new email already exists
            $sql = "SELECT id FROM users WHERE email = ? AND id != ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$new_email, $user_id]);
            if ($stmt->rowCount() > 0) {
                $email_err = "This email is already taken.";
            } else {
                $email = $new_email;
            }
        }
    }

    // If no errors, update the database
    if (empty($name_err) && empty($email_err)) {
        $sql = "UPDATE users SET name = ?, email = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$name, $email, $user_id])) {
            $update_success = "Profile updated successfully.";
            $_SESSION['user_name'] = $name; // Update session name
        } else {
            echo "Something went wrong. Please try again.";
        }
    }
}
?>
<div class="main-container">
    <h2>Your Profile</h2>
    <?php
    if (!empty($update_success)) {
        echo '<div>' . $update_success . '</div>';
    }
    ?>
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
            <input type="submit" value="Update Profile">
        </div>
    </form>
</div>

<?php include '../includes/footer.php'; ?>