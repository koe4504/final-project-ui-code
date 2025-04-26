<?php
include '../config/db_connect.php';
include '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $child_id = $_POST['child_id'];
    $activity_id = $_POST['activity_id'];
    $completion_percentage = $_POST['completion_percentage'];
    $notes = $_POST['notes'];

    $stmt = $conn->prepare("INSERT INTO progress (child_id, activity_id, completion_percentage, notes) VALUES (?, ?, ?, ?)");
    $stmt->execute([$child_id, $activity_id, $completion_percentage, $notes]);

    header("Location: progress.php");
    exit();
}

$children = $conn->query("SELECT id, name FROM children")->fetchAll(PDO::FETCH_ASSOC);
$activities = $conn->query("SELECT id, name FROM learning_activities")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Progress</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Add Progress</h2>
        <form method="POST" class="border p-4 rounded shadow">
            <div class="mb-3">
                <label class="form-label">Child:</label>
                <select name="child_id" class="form-control" required>
                    <?php foreach ($children as $child): ?>
                        <option value="<?= $child['id'] ?>"><?= htmlspecialchars($child['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Activity:</label>
                <select name="activity_id" class="form-control" required>
                    <?php foreach ($activities as $activity): ?>
                        <option value="<?= $activity['id'] ?>"><?= htmlspecialchars($activity['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Completion Percentage:</label>
                <input type="number" name="completion_percentage" class="form-control" min="0" max="100" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Notes:</label>
                <textarea name="notes" class="form-control"></textarea>
            </div>
            <button type="submit" class="btn btn-success">Create</button>
            <a href="progress.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
<?php include '../includes/footer.php'; ?>
</html>

