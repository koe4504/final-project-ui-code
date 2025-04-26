<?php
#include '../includes/session.php';
include '../config/db_connect.php';
include '../includes/header.php';
#checkRole('admin');

$stmt = $conn->query("SELECT children.*, parents.name AS parent_name FROM children LEFT JOIN parents ON children.parent_id = parents.id");
$children = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Children</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Manage Children</h2>
	<a href="create_child.php" class="btn btn-primary mb-3">Add Child</a>
        <a href="../dashboard.php" class="btn btn-secondary mb-3">Back to Dashboard</a>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Parent</th>
                    <th>Phone</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($children as $child): ?>
                    <tr>
                        <td><?= $child['id'] ?></td>
                        <td><?= htmlspecialchars($child['name']) ?></td>
                        <td><?= $child['age'] ?></td>
                        <td><?= htmlspecialchars($child['parent_name'] ?? 'N/A') ?></td>
                        <td><?= $child['phone'] ?></td>
                        <td>
                            <a href="edit_child.php?id=<?= $child['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete_child.php?id=<?= $child['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">Delete</a>
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
