<?php

include '../includes/session.php';
include '../config/db_connect.php';
include '../includes/header.php';
include '../includes/role_check.php';
// checkRole('admin');

//This code is to get the staff_id based on the logged-in user
$staff_stmt = $conn->prepare("SELECT id FROM staff WHERE user_id = ?");
$staff_stmt->execute([$_SESSION['user_id']]);
$staff = $staff_stmt->fetch(PDO::FETCH_ASSOC);
$staff_id = $staff ? $staff['id'] : null;

//This code is to get list of children for the dropdown
$children = $conn->query("SELECT id, name FROM children ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);

$success = null;
$error = null;

//This code is to handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date = $_POST['date'];
    $child_id = $_POST['child_id'];
    $status = $_POST['status'];

    try {
        $stmt = $conn->prepare("INSERT INTO attendance (date, child_id, staff_id, status) VALUES (?, ?, ?, ?)");
        $stmt->execute([$date, $child_id, $staff_id, $status]);
        $success = "Attendance record successfully added.";
    } catch (PDOException $e) {
        if ($e->errorInfo[1] == 1062) {
            $error = "Attendance for this child on this date already exists.";
        } else {
            $error = "Failed to add attendance record. Please ensure you select the right child name.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Attendance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Create Attendance Record</h2>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php elseif ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Date</label>
            <input type="date" name="date" class="form-control" value="<?= date('Y-m-d') ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Select Child</label>
            <select name="child_id" class="form-control" required>
                <option value="">-- Select Child --</option>
                <?php foreach ($children as $child): ?>
                    <option value="<?= $child['id'] ?>"><?= htmlspecialchars($child['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <input type="hidden" name="staff_id" value="<?= htmlspecialchars($staff_id) ?>">

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-control" required>
                <option value="present">Present</option>
                <option value="absent">Absent</option>
            </select>
        </div>

        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-primary">Add Record</button>
            <a href="attendance.php" class="btn btn-outline-secondary">Back to Attendance</a>
        </div>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>

