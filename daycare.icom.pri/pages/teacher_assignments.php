<?php
include '../config/db_connect.php';
include '../includes/header.php';



$stmt = $conn->query("SELECT teacher_assignments.id, staff.name AS teacher_name, learning_activities.name AS activity_name, teacher_assignments.assigned_date FROM teacher_assignments INNER JOIN staff ON teacher_assignments.staff_id = staff.id INNER JOIN learning_activities ON teacher_assignments.activity_id = learning_activities.id");
$assignments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Teacher Assignments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <!-- this is the main Content -->
    <div class="container mt-5">
        <h2 class="mb-4">Manage Teacher Assignments</h2>
        <a href="create_teacher_assignment.php" class="btn btn-primary mb-3">Assign Teacher</a>
        <a href="../dashboard.php" class="btn btn-secondary mb-3">Back to Dashboard</a>
        
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Teacher</th>
                    <th>Activity</th>
                    <th>Assigned Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($assignments as $assignment): ?>
                    <tr>
                        <td><?= $assignment['id'] ?></td>
                        <td><?= htmlspecialchars($assignment['teacher_name']) ?></td>
                        <td><?= htmlspecialchars($assignment['activity_name']) ?></td>
                        <td><?= htmlspecialchars($assignment['assigned_date']) ?></td>
                        <td>
                            <a href="edit_teacher_assignment.php?id=<?= $assignment['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete_teacher_assignment.php?id=<?= $assignment['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div/>

<?php include '../includes/footer.php'; ?>

</body>
</html>
