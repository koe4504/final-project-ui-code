<?php
include '../config/db_connect.php';
include '../includes/header.php';


$stmt = $conn->query("SELECT * FROM staff");
$staff_members = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Staff</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

   
    <!-- Main Content -->
    <div class="container mt-5">
        <h2 class="mb-4">Manage Staff</h2>
     <!---<a href="create_staff.php" class="btn btn-primary mb-3">Add Staff</a> --->
        <a href="../dashboard.php" class="btn btn-secondary mb-3">Back to Dashboard</a>
        
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($staff_members as $staff): ?>
                    <tr>
                        <td><?= $staff['id'] ?></td>
                        <td><?= htmlspecialchars($staff['name']) ?></td>
                        <td><?= htmlspecialchars($staff['role']) ?></td>
                        <td><?= htmlspecialchars($staff['email']) ?></td>
                        <td><?= htmlspecialchars($staff['phone']) ?></td>
                        <td>
                            <a href="edit_staff.php?id=<?= $staff['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete_staff.php?id=<?= $staff['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">Delete</a>
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

