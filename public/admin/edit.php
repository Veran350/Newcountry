<?php
// Admin protection snippet
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}
include_once __DIR__ . '/../../includes/db.php';

if (!isset($_GET['id'])) {
    header("Location: manage.php");
    exit();
}
$id = intval($_GET['id']);

// Fetch project details
$stmt = $conn->prepare("SELECT title, price, description, category FROM projects WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($title, $price, $description, $category);
$stmt->fetch();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_title       = $_POST['title'];
    $new_price       = $_POST['price'];
    $new_description = $_POST['description'];
    $new_category    = $_POST['category'];
    
    $stmt = $conn->prepare("UPDATE projects SET title = ?, price = ?, description = ?, category = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $new_title, $new_price, $new_description, $new_category, $id);
    if ($stmt->execute()){
        header("Location: manage.php");
        exit();
    } else {
        echo "Error updating record.";
    }
}

include_once __DIR__ . '/../../includes/header.php';
?>
<h1>Edit Project</h1>
<nav class="admin-nav">
    <a href="dashboard.php">Dashboard</a> |
    <a href="manage.php">Manage Projects</a> |
    <a href="logout.php">Logout</a>
</nav>
<form action="edit.php?id=<?php echo $id; ?>" method="POST">
    <label>Title:</label><br>
    <input type="text" name="title" value="<?php echo htmlspecialchars($title); ?>" required><br>
    <label>Price:</label><br>
    <input type="text" name="price" value="<?php echo htmlspecialchars($price); ?>" required><br>
    <label>Description:</label><br>
    <textarea name="description" required><?php echo htmlspecialchars($description); ?></textarea><br>
    <label>Category:</label><br>
    <select name="category" required>
        <option value="">Select Category</option>
        <option value="Residential Construction" <?php if($category=="Residential Construction") echo 'selected'; ?>>Residential Construction</option>
        <option value="Commercial Construction" <?php if($category=="Commercial Construction") echo 'selected'; ?>>Commercial Construction</option>
        <option value="Industrial Construction" <?php if($category=="Industrial Construction") echo 'selected'; ?>>Industrial Construction</option>
        <option value="Innovation & Remodeling" <?php if($category=="Innovation & Remodeling") echo 'selected'; ?>>Innovation & Remodeling</option>
        <option value="Project Management" <?php if($category=="Project Management") echo 'selected'; ?>>Project Management</option>
    </select><br><br>
    <button type="submit">Update</button>
</form>
<?php include_once __DIR__ . '/../../includes/footer.php'; ?>
