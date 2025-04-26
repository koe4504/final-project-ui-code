<?php
include '../config/db_connect.php';
include '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $child_id = $_POST['child_id'];
    $activity_id = $_POST['activity_id'];
    $score = $_POST['score'];
    $grade = $_POST['grade'];
    $feedback = $_POST['feedback'];

    $stmt = $conn->prepare("UPDATE assessments SET child_id = ?, activity_id = ?, score = ?, grade = ?, feedback = ? WHERE id = ?");
    $stmt->execute([$child_id, $activity_id, $score, $grade, $feedback, $id]);

    header("Location: assessments.php");
    exit();
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM assessments WHERE id = ?");
$stmt->execute([$id]);
$assessment = $stmt->fetch(PDO::FETCH_ASSOC);

$children = $conn->query("SELECT id, name FROM children")->fetchAll(PDO::FETCH_ASSOC);
$activities = $conn->query("SELECT id, name FROM learning_activities")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Assessment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Assessment</h2>
        <form method="POST" class="border p-4 rounded shadow">
            <input type="hidden" name="id" value="<?= $assessment['id'] ?>">
            <div class="mb-3">
                <label class="form-label">Child:</label>
                <select name="child_id" class="form-control" required>
                    <?php foreach ($children as $child): ?>
                        <option value="<?= $child['id'] ?>" <?= $assessment['child_id'] == $child['id'] ? 'selected' : '' ?>><?= htmlspecialchars($child['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Activity:</label>
                <select name="activity_id" class="form-control" required>
                    <?php foreach ($activities as $activity): ?>
                        <option value="<?= $activity['id'] ?>" <?= $assessment['activity_id'] == $activity['id'] ? 'selected' : '' ?>><?= htmlspecialchars($activity['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Score:</label>
                <input type="number" name="score" class="form-control" step="0.01" value="<?= $assessment['score'] ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Grade:</label>
                <input type="text" name="grade" class="form-control" value="<?= htmlspecialchars($assessment['grade']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Feedback:</label>
                <textarea name="feedback" class="form-control"><?= htmlspecialchars($assessment['feedback']) ?></textarea>
            </div>
            <button type="submit" class="btn btn-success">Update</button>
            <a href="assessments.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
<?php include '../includes/footer.php'; ?>
</html>

