<?php
include '../config/db_connect.php';
include '../includes/header.php';

if (!isset($_GET['id'])) {
    die("Invalid request.");
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM activity_resources WHERE id = ?");
$stmt->execute([$id]);
$resource = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$resource) {
    die("Resource not found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resource_type = $_POST['resource_type'];
    $resource_content = $_POST['resource'];

    $updateStmt = $conn->prepare("UPDATE activity_resources SET resource_type = ?, resource = ? WHERE id = ?");
    $updateStmt->execute([$resource_type, $resource_content, $id]);

    header("Location: activity_resources.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Activity Resource</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Activity Resource</h2>
        <form method="POST" class="border p-4 rounded shadow">
            <div class="mb-3">
                <label class="form-label">Resource Type:</label>
                <select name="resource_type" class="form-control" required>
                    <option value="link" <?= $resource['resource_type'] == 'link' ? 'selected' : '' ?>>Link</option>
                    <option value="file" <?= $resource['resource_type'] == 'file' ? 'selected' : '' ?>>File</option>
                    <option value="description" <?= $resource['resource_type'] == 'description' ? 'selected' : '' ?>>Description</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Resource:</label>
                <textarea name="resource" class="form-control" required><?= htmlspecialchars($resource['resource']) ?></textarea>
            </div>
            <button type="submit" class="btn btn-warning">Update</button>
            <a href="activity_resources.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
<?php include '../includes/footer.php'; ?>
</html>

