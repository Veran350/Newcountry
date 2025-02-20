<?php
session_start();

// Change these credentials for production use.
$adminUser = 'admin';
$adminPass = 'newcountry';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';

    if ($user === $adminUser && $pass === $adminPass) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<?php include_once __DIR__ . '/../../includes/header.php'; ?>
<h1>Admin Login</h1>
<?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
<form action="login.php" method="POST">
    <label>Username:</label><br>
    <input type="text" name="username" required><br>
    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>
    <button type="submit">Login</button>
</form>
<?php include_once __DIR__ . '/../../includes/footer.php'; ?>
