<?php
include '../config/db_connect.php';
include '../includes/header.php';

// Fetch staff and activities for selection
$staff_stmt = $conn->query("SELECT id, name FROM active_teachers");
$staff_members = $staff_stmt->fetchAll(PDO::FETCH_ASSOC);

$activity_stmt = $conn->query("SELECT id, name FROM learning_activities");
$activities = $activity_stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $staff_id = $_POST['staff_id'];
    $activity_id = $_POST['activity_id'];
    $assigned_date = $_POST['assigned_date'];

    $stmt = $conn->prepare("INSERT INTO teacher_assignments (staff_id, activity_id, assigned_date) VALUES (?, ?, ?)");
    $stmt->execute([$staff_id, $activity_id, $assigned_date]);

    header("Location: teacher_assignments.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Assign Teacher</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Assign Teacher to Activity</h2>
        <form method="POST" class="border p-4 rounded shadow">
            <div class="mb-3">
                <label class="form-label">Select Teacher:</label>
                <select name="staff_id" class="form-control" required>
                    <option value="">-- Select Teacher --</option>
                    <?php foreach ($staff_members as $staff): ?>
                        <option value="<?= $staff['id'] ?>"><?= htmlspecialchars($staff['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Select Activity:</label>
                <select name="activity_id" class="form-control" required>
                    <option value="">-- Select Activity --</option>
                    <?php foreach ($activities as $activity): ?>
                        <option value="<?= $activity['id'] ?>"><?= htmlspecialchars($activity['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Assignment Date:</label>
                <input type="date" name="assigned_date" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">Assign</button>
            <a href="teacher_assignments.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
<?php include '../includes/footer.php'; ?>
</html>

