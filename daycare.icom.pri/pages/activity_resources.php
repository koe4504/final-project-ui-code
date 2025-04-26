<?php
include '../config/db_connect.php';
include '../includes/header.php';

$stmt = $conn->query("SELECT activity_resources.id, learning_activities.name AS activity_name, activity_resources.resource_type, activity_resources.resource, activity_resources.created_at FROM activity_resources INNER JOIN learning_activities ON activity_resources.activity_id = learning_activities.id ORDER BY activity_resources.created_at DESC");
$resources = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Activity Resources</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="container mt-5">
        <h2 class="mb-4">Manage Activity Resources</h2>
        <a href="create_activity_resource.php" class="btn btn-primary mb-3">Add Resource</a>
        <a href="../dashboard.php" class="btn btn-secondary mb-3">Back to Dashboard</a>
        
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Activity</th>
                    <th>Type</th>
                    <th>Resource</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($resources as $resource): ?>
                    <tr>
                        <td><?= $resource['id'] ?></td>
                        <td><?= htmlspecialchars($resource['activity_name']) ?></td>
                        <td><?= htmlspecialchars($resource['resource_type']) ?></td>
                        <td><?= htmlspecialchars($resource['resource']) ?></td>
                        <td><?= htmlspecialchars($resource['created_at']) ?></td>
                        <td>
                            <a href="edit_activity_resource.php?id=<?= $resource['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete_activity_resource.php?id=<?= $resource['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>

<?php include '../includes/footer.php'; ?>

</body>
</html>

