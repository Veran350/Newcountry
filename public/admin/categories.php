<?php
// Admin protection snippet
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}
include_once __DIR__ . '/../../includes/header.php';
?>
<h1>Manage Categories</h1>
<nav class="admin-nav">
    <a href="dashboard.php">Dashboard</a> |
    <a href="logout.php">Logout</a>
</nav>
<p>Current categories:</p>
<ul>
    <li>Residential Construction</li>
    <li>Commercial Construction</li>
    <li>Industrial Construction</li>
    <li>Innovation & Remodeling</li>
    <li>Project Management</li>
</ul>
<?php include_once __DIR__ . '/../../includes/footer.php'; ?>
