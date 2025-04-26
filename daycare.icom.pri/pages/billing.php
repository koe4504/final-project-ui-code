<?php
include '../config/db_connect.php';
include '../includes/header.php';

$stmt = $conn->query("SELECT billing.id, children.name AS child_name, billing.amount, billing.due_date, billing.status FROM billing INNER JOIN children ON billing.child_id = children.id");
$billing_records = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Billing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <!-- Main Content -->
    <div class="container mt-5">
        <h2 class="mb-4">Manage Billing</h2>
        <a href="create_billing.php" class="btn btn-primary mb-3">Add Billing</a>
        <a href="../dashboard.php" class="btn btn-secondary mb-3">Back to Dashboard</a>
        
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Child</th>
                    <th>Amount</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($billing_records as $record): ?>
                    <tr>
                        <td><?= $record['id'] ?></td>
                        <td><?= htmlspecialchars($record['child_name']) ?></td>
                        <td><?= htmlspecialchars($record['amount']) ?></td>
                        <td><?= htmlspecialchars($record['due_date']) ?></td>
                        <td><?= htmlspecialchars($record['status']) ?></td>
                        <td>
                            <a href="edit_billing.php?id=<?= $record['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete_billing.php?id=<?= $record['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

 <?php include '../includes/footer.php'; ?>

</body>
</html>

