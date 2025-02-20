<?php
// Admin protection snippet
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}
include_once __DIR__ . '/../../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title       = $_POST['title'];
    $price       = $_POST['price'];
    $description = $_POST['description'];
    $category    = $_POST['category'];
    $media_type  = '';
    $media_path  = '';

    if (isset($_FILES['media']) && $_FILES['media']['error'] == 0) {
        $allowed_image = ['jpg', 'jpeg', 'png', 'gif'];
        $allowed_video = ['mp4', 'avi', 'mov'];
        $fileName = basename($_FILES['media']['name']);
        $fileTmp  = $_FILES['media']['tmp_name'];
        $fileExt  = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $uploadDir  = __DIR__ . '/../../assets/uploads/';
        $targetFile = $uploadDir . $fileName;

        if (in_array($fileExt, $allowed_image)) {
            $media_type = 'image';
        } elseif (in_array($fileExt, $allowed_video)) {
            $media_type = 'video';
        } else {
            die("Unsupported file type.");
        }

        if (move_uploaded_file($fileTmp, $targetFile)) {
            $media_path = $fileName;
            $stmt = $conn->prepare("INSERT INTO projects (title, price, description, category, media_path, media_type, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
            $stmt->bind_param("ssssss", $title, $price, $description, $category, $media_path, $media_type);
            if ($stmt->execute()) {
                echo "Project uploaded successfully.";
            } else {
                echo "Error: " . $stmt->error;
            }
        } else {
            echo "Error uploading file.";
        }
    }
}
?>

<?php include_once __DIR__ . '/../../includes/header.php'; ?>
<h1>Upload New Project</h1>
<nav class="admin-nav">
    <a href="dashboard.php">Dashboard</a> |
    <a href="manage.php">Manage Projects</a> |
    <a href="logout.php">Logout</a>
</nav>
<form action="upload.php" method="POST" enctype="multipart/form-data">
    <label>Title:</label><br>
    <input type="text" name="title" required><br>
    <label>Price:</label><br>
    <input type="text" name="price" required><br>
    <label>Description:</label><br>
    <textarea name="description" required></textarea><br>
    <label>Category:</label><br>
    <select name="category" required>
        <option value="">Select Category</option>
        <option value="Residential Construction">Residential Construction</option>
        <option value="Commercial Construction">Commercial Construction</option>
        <option value="Industrial Construction">Industrial Construction</option>
        <option value="Innovation & Remodeling">Innovation & Remodeling</option>
        <option value="Project Management">Project Management</option>
    </select><br>
    <label>Media (Image/Video):</label><br>
    <input type="file" name="media" accept="image/*,video/*" required><br><br>
    <button type="submit">Upload</button>
</form>
<?php include_once __DIR__ . '/../../includes/footer.php'; ?>
