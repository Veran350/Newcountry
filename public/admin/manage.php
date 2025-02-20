<?php
// Admin protection snippet
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}
include_once __DIR__ . '/../../includes/db.php';

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    
    // Retrieve media file name for deletion
    $stmt = $conn->prepare("SELECT media_path FROM projects WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($media_path);
    $stmt->fetch();
    $stmt->close();
    
    // Delete the file from uploads folder
    $uploadDir = __DIR__ . '/../../assets/uploads/';
    if (file_exists($uploadDir . $media_path)) {
        unlink($uploadDir . $media_path);
    }
    
    // Delete record from database
    $stmt = $conn->prepare("DELETE FROM projects WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: manage.php");
    exit();
}

include_once __DIR__ . '/../../includes/header.php';

$sql = "SELECT * FROM projects ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<h1>Manage Projects</h1>
<nav class="admin-nav">
    <a href="dashboard.php">Dashboard</a> |
    <a href="upload.php">Upload New Project</a> |
    <a href="logout.php">Logout</a>
</nav>
<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Description</th>
        <th>Price</th>
        <th>Category</th>
        <th>Media Type</th>
        <th>Actions</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?php echo $row['id']; ?></td>
        <td><?php echo htmlspecialchars($row['title']); ?></td>
        <td><?php echo htmlspecialchars($row['description']); ?></td>
        <td><?php echo htmlspecialchars($row['price']); ?></td>
        <td><?php echo htmlspecialchars($row['category']); ?></td>
        <td><?php echo htmlspecialchars($row['media_type']); ?></td>
        <td>
            <a href="edit.php?id=<?php echo $row['id']; ?>">Edit</a> |
            <a href="manage.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?');">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
<?php include_once __DIR__ . '/../../includes/footer.php'; ?>
