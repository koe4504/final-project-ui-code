<?php

include '../includes/session.php';
include '../config/db_connect.php';
include '../includes/header.php';
include '../includes/role_check.php';
checkRole('admin');

if (!isset($_GET['id'])) {
    header("Location: teacher_assignments.php");
    exit();
}

$id = $_GET['id'];

// Fetch assignment details
$stmt = $conn->prepare("SELECT teacher_assignments.id, staff.name AS teacher_name, learning_activities.name AS activity_name 
                        FROM teacher_assignments 
                        INNER JOIN staff ON teacher_assignments.staff_id = staff.id 
                        INNER JOIN learning_activities ON teacher_assignments.activity_id = learning_activities.id
                        WHERE teacher_assignments.id = ?");
$stmt->execute([$id]);
$assignment = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$assignment) {
    header("Location: teacher_assignments.php");
    exit();
}

// Handle deletion confirmation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $delete_stmt = $conn->prepare("DELETE FROM teacher_assignments WHERE id = ?");
    $delete_stmt->execute([$id]);

    header("Location: teacher_assignments.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Teacher Assignment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-danger">Delete Teacher Assignment</h2>
        <p>Are you sure you want to delete the following assignment?</p>
        <div class="border p-3 mb-3">
            <p><strong>Teacher:</strong> <?= htmlspecialchars($assignment['teacher_name']) ?></p>
            <p><strong>Activity:</strong> <?= htmlspecialchars($assignment['activity_name']) ?></p>
        </div>
        <form method="POST">
            <button type="submit" class="btn btn-danger">Delete</button>
            <a href="teacher_assignments.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
<?php include '../includes/footer.php'; ?>
</html>

