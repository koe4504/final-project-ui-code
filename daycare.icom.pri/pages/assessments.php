<?php
include '../config/db_connect.php';
include '../includes/header.php';

$stmt = $conn->query("SELECT assessments.id, children.name AS child_name, learning_activities.name AS activity_name, assessments.score, assessments.grade, assessments.feedback FROM assessments INNER JOIN children ON assessments.child_id = children.id INNER JOIN learning_activities ON assessments.activity_id = learning_activities.id");
$assessment_records = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Assessments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <!-- Main Content -->
    <div class="container mt-5">
        <h2 class="mb-4">Manage Assessments</h2>
        <a href="create_assessment.php" class="btn btn-primary mb-3">Add Assessment</a>
        <a href="../dashboard.php" class="btn btn-secondary mb-3">Back to Dashboard</a>
        
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Child</th>
                    <th>Activity</th>
                    <th>Score</th>
                    <th>Grade</th>
                    <th>Feedback</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($assessment_records as $record): ?>
                    <tr>
                        <td><?= $record['id'] ?></td>
                        <td><?= htmlspecialchars($record['child_name']) ?></td>
                        <td><?= htmlspecialchars($record['activity_name']) ?></td>
                        <td><?= htmlspecialchars($record['score']) ?></td>
                        <td><?= htmlspecialchars($record['grade']) ?></td>
                        <td><?= htmlspecialchars($record['feedback']) ?></td>
                        <td>
                            <a href="edit_assessment.php?id=<?= $record['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete_assessment.php?id=<?= $record['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php include '../includes/footer.php'; ?>

</body>
</html>

