 
<?php
#include '../includes/session.php';
include '../config/db_connect.php';
include '../includes/header.php';
#checkRole('admin');

$stmt = $conn->query("SELECT * FROM parents");
$parents = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Parents</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Manage Parents</h2>
	<a href="create_parent.php" class="btn btn-primary mb-3">Add Parent</a>
        <a href="../dashboard.php" class="btn btn-secondary mb-3">Back to Dashboard</a>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($parents as $parent): ?>
                    <tr>
                        <td><?= $parent['id'] ?></td>
                        <td><?= htmlspecialchars($parent['name']) ?></td>
                        <td><?= $parent['email'] ?></td>
                        <td><?= $parent['phone'] ?></td>
                        <td>
                            <a href="edit_parent.php?id=<?= $parent['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete_parent.php?id=<?= $parent['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
       
    </div>
    
</body>

<?php include '../includes/footer.php'; ?>
</html>

