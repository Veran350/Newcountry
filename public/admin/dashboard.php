<?php
// Admin protection snippet
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}
include_once __DIR__ . '/../../includes/db.php';
include_once __DIR__ . '/../../includes/header.php';

// Fetch projects for the dashboard
$sql = "SELECT * FROM projects ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<h1>Admin Dashboard</h1>
<nav class="admin-nav">
    <a href="upload.php">Upload New Project</a> |
    <a href="manage.php">Manage Projects</a> |
    <a href="categories.php">Categories</a> |
    <a href="settings.php">Settings</a> |
    <a href="logout.php">Logout</a>
</nav>
<div class="projects">
    <?php while($row = $result->fetch_assoc()): ?>
        <div class="project">
            <h2><?php echo htmlspecialchars($row['title']); ?></h2>
            <p><?php echo htmlspecialchars($row['description']); ?></p>
            <p>Price: <?php echo htmlspecialchars($row['price']); ?></p>
            <p>Category: <?php echo htmlspecialchars($row['category']); ?></p>
            <?php if($row['media_type'] == 'image'): ?>
                <img src="/assets/uploads/<?php echo htmlspecialchars($row['media_path']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>" width="150">
            <?php else: ?>
                <video width="150" controls>
                    <source src="/assets/uploads/<?php echo htmlspecialchars($row['media_path']); ?>" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>
</div>
<?php include_once __DIR__ . '/../../includes/footer.php'; ?>
