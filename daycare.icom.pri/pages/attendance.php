<?php
#include '../includes/session.php';
include '../config/db_connect.php';
include '../includes/header.php';
#checkRole('admin');

$stmt = $conn->query("SELECT attendance.*, children.name AS child_name, staff.name AS staff_name FROM attendance 
                     LEFT JOIN children ON attendance.child_id = children.id 
                     LEFT JOIN staff ON attendance.staff_id = staff.id");
$attendance_records = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Attendance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Manage Attendance</h2>
	<a href="create_attendance.php" class="btn btn-primary mb-3">Record Attendance</a>
        <a href="../dashboard.php" class="btn btn-secondary mb-3">Back to Dashboard</a>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Child</th>
                    <th>Staff</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($attendance_records as $record): ?>
                    <tr>
                        <td><?= $record['id'] ?></td>
                        <td><?= $record['date'] ?></td>
                        <td><?= htmlspecialchars($record['child_name']) ?></td>
                        <td><?= htmlspecialchars($record['staff_name']) ?></td>
                        <td><?= $record['status'] ?></td>
                        <td>
                            <a href="edit_attendance.php?id=<?= $record['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete_attendance.php?id=<?= $record['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
