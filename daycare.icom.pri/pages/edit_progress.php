<?php
include '../config/db_connect.php';
include '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $child_id = $_POST['child_id'];
    $activity_id = $_POST['activity_id'];
    $completion_percentage = $_POST['completion_percentage'];
    $notes = $_POST['notes'];

    $stmt = $conn->prepare("UPDATE progress SET child_id = ?, activity_id = ?, completion_percentage = ?, notes = ? WHERE id = ?");
    $stmt->execute([$child_id, $activity_id, $completion_percentage, $notes, $id]);

    header("Location: progress.php");
    exit();
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM progress WHERE id = ?");
$stmt->execute([$id]);
$progress = $stmt->fetch(PDO::FETCH_ASSOC);

$children = $conn->query("SELECT id, name FROM children")->fetchAll(PDO::FETCH_ASSOC);
$activities = $conn->query("SELECT id, name FROM learning_activities")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Progress</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Progress</h2>
        <form method="POST" class="border p-4 rounded shadow">
            <input type="hidden" name="id" value="<?= $progress['id'] ?>">
            <div class="mb-3">
                <label class="form-label">Child:</label>
                <select name="child_id" class="form-control" required>
                    <?php foreach ($children as $child): ?>
                        <option value="<?= $child['id'] ?>" <?= $progress['child_id'] == $child['id'] ? 'selected' : '' ?>><?= htmlspecialchars($child['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Activity:</label>
                <select name="activity_id" class="form-control" required>
                    <?php foreach ($activities as $activity): ?>
                        <option value="<?= $activity['id'] ?>" <?= $progress['activity_id'] == $activity['id'] ? 'selected' : '' ?>><?= htmlspecialchars($activity['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Completion Percentage:</label>
                <input type="number" name="completion_percentage" class="form-control" min="0" max="100" value="<?= htmlspecialchars($progress['completion_percentage']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Notes:</label>
                <textarea name="notes" class="form-control"><?= htmlspecialchars($progress['notes']) ?></textarea>
            </div>
            <button type="submit" class="btn btn-success">Save Changes</button>
            <a href="progress.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
<?php include '../includes/footer.php'; ?>
</html>

