<?php
#include '../includes/session.php';
include '../config/db_connect.php';
include '../includes/header.php';
#checkRole('admin');

if (!isset($_GET['id'])) {
    header("Location: attendance.php");
    exit();
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM attendance WHERE id = ?");
$stmt->execute([$id]);
$attendance = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$attendance) {
    header("Location: attendance.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date = $_POST['date'];
    $status = $_POST['status'];
    
    $stmt = $conn->prepare("UPDATE attendance SET date = ?, status = ? WHERE id = ?");
    if ($stmt->execute([$date, $status, $id])) {
        header("Location: attendance.php");
        exit();
    } else {
        $error = "Failed to update attendance record.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Attendance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Attendance Record</h2>
        <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Date</label>
                <input type="date" name="date" class="form-control" value="<?= $attendance['date'] ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-control" required>
                    <option value="present" <?= $attendance['status'] == 'present' ? 'selected' : '' ?>>Present</option>
                    <option value="absent" <?= $attendance['status'] == 'absent' ? 'selected' : '' ?>>Absent</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Record</button>
            <a href="attendance.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
