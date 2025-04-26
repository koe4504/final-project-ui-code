<?php
#include '../includes/session.php';
include '../config/db_connect.php';
include '../includes/header.php';
#checkRole('admin');

$stmt = $conn->query("SELECT * FROM learning_activities");
$activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Learning Activities</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Manage Learning Activities</h2>
	<a href="create_activities.php" class="btn btn-primary mb-3">Add Activity</a>
        <a href="../dashboard.php" class="btn btn-secondary mb-3">Back to Dashboard</a>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Activity Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($activities as $activity): ?>
                    <tr>
                        <td><?= $activity['id'] ?></td>
                        <td><?= htmlspecialchars($activity['name']) ?></td>
                        <td><?= htmlspecialchars($activity['description']) ?></td>
                        <td><?= $activity['activity_date'] ?></td>
                        <td>
                            <a href="edit_activities.php?id=<?= $activity['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete_activities.php?id=<?= $activity['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
