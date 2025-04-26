<?php
include '../config/db_connect.php';
include '../includes/header.php';

if (!isset($_GET['id'])) {
    header("Location: teacher_assignments.php");
    exit();
}

$id = $_GET['id'];

// Fetch existing assignment data
$stmt = $conn->prepare("SELECT * FROM teacher_assignments WHERE id = ?");
$stmt->execute([$id]);
$assignment = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$assignment) {
    header("Location: teacher_assignments.php");
    exit();
}

// Fetch staff and activities for selection
$staff_stmt = $conn->query("SELECT id, name FROM staff");
$staff_members = $staff_stmt->fetchAll(PDO::FETCH_ASSOC);

$activity_stmt = $conn->query("SELECT id, name FROM learning_activities");
$activities = $activity_stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $staff_id = $_POST['staff_id'];
    $activity_id = $_POST['activity_id'];
    $assigned_date = $_POST['assigned_date'];

    $update_stmt = $conn->prepare("UPDATE teacher_assignments SET staff_id = ?, activity_id = ?, assigned_date = ? WHERE id = ?");
    $update_stmt->execute([$staff_id, $activity_id, $assigned_date, $id]);

    header("Location: teacher_assignments.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Teacher Assignment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Teacher Assignment</h2>
        <form method="POST" class="border p-4 rounded shadow">
            <div class="mb-3">
                <label class="form-label">Select Teacher:</label>
                <select name="staff_id" class="form-control" required>
                    <?php foreach ($staff_members as $staff): ?>
                        <option value="<?= $staff['id'] ?>" <?= $staff['id'] == $assignment['staff_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($staff['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Select Activity:</label>
                <select name="activity_id" class="form-control" required>
                    <?php foreach ($activities as $activity): ?>
                        <option value="<?= $activity['id'] ?>" <?= $activity['id'] == $assignment['activity_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($activity['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Assignment Date:</label>
                <input type="date" name="assigned_date" class="form-control" value="<?= $assignment['assigned_date'] ?>" required>
            </div>
            <button type="submit" class="btn btn-success">Update</button>
            <a href="teacher_assignments.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
<?php include '../includes/footer.php'; ?>
</html>

