<?php
#include '../includes/session.php';
include '../config/db_connect.php';
include '../includes/header.php';
#checkRole('admin');

if (!isset($_GET['id'])) {
    header("Location: activities.php");
    exit();
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM learning_activities WHERE id = ?");
$stmt->execute([$id]);
$activity = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$activity) {
    header("Location: activities.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $activity_date = $_POST['activity_date'];
    
    $stmt = $conn->prepare("UPDATE learning_activities SET name = ?, description = ?, activity_date = ? WHERE id = ?");
    if ($stmt->execute([$name, $description, $activity_date, $id])) {
        header("Location: activities.php");
        exit();
    } else {
        $error = "Failed to update activity.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Learning Activity</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Learning Activity</h2>
        <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Activity Name</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($activity['name']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" required><?= htmlspecialchars($activity['description']) ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Activity Date</label>
                <input type="date" name="activity_date" class="form-control" value="<?= $activity['activity_date'] ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Activity</button>
            <a href="activities.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
