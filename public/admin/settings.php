<?php
// Admin protection snippet
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}
include_once __DIR__ . '/../../includes/header.php';
?>
<h1>Admin Settings</h1>
<nav class="admin-nav">
    <a href="dashboard.php">Dashboard</a> |
    <a href="logout.php">Logout</a>
</nav>
<p>Settings functionality goes here.</p>
<?php include_once __DIR__ . '/../../includes/footer.php'; ?>
