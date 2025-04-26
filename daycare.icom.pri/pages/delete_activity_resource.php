<?php
include '../config/db_connect.php';
include '../includes/header.php';

if (!isset($_GET['id'])) {
    die("Invalid request.");
}

$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare("DELETE FROM activity_resources WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: activity_resources.php");
    exit();
}

// Fetch the resource details for display
$stmt = $conn->prepare("SELECT activity_resources.id, learning_activities.name AS activity_name, activity_resources.resource_type, activity_resources.resource FROM activity_resources INNER JOIN learning_activities ON activity_resources.activity_id = learning_activities.id WHERE activity_resources.id = ?");
$stmt->execute([$id]);
$resource = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$resource) {
    die("Resource not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Activity Resource</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2 class="text-danger">Delete Activity Resource</h2>
    <p>Are you sure you want to delete the following resource?</p>
    
    <ul class="list-group mb-3">
        <li class="list-group-item"><strong>Activity:</strong> <?= htmlspecialchars($resource['activity_name']) ?></li>
        <li class="list-group-item"><strong>Type:</strong> <?= htmlspecialchars($resource['resource_type']) ?></li>
        <li class="list-group-item"><strong>Resource:</strong> <?= htmlspecialchars($resource['resource']) ?></li>
    </ul>

    <form method="POST">
        <button type="submit" class="btn btn-danger">Delete</button>
        <a href="activity_resources.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

</body>
<?php include '../includes/footer.php'; ?>
</html>

