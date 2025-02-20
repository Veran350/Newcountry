<?php
include_once __DIR__ . '/../includes/db.php';
include_once __DIR__ . '/../includes/header.php';

// Fetch projects from the database
$sql = "SELECT * FROM projects ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<h1>Welcome to NEWCOUNTRY</h1>
<p>Explore our construction projects.</p>
<div class="projects">
    <?php while($row = $result->fetch_assoc()): ?>
        <div class="project">
            <h2><?php echo htmlspecialchars($row['title']); ?></h2>
            <p><?php echo htmlspecialchars($row['description']); ?></p>
            <p>Price: <?php echo htmlspecialchars($row['price']); ?></p>
            <p>Category: <?php echo htmlspecialchars($row['category']); ?></p>
            <?php if($row['media_type'] == 'image'): ?>
                <img src="/assets/uploads/<?php echo htmlspecialchars($row['media_path']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>" width="300">
            <?php else: ?>
                <video width="300" controls>
                    <source src="/assets/uploads/<?php echo htmlspecialchars($row['media_path']); ?>" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>
</div>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>
