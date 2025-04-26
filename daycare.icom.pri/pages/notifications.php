<?php
include '../config/db_connect.php';
include '../includes/header.php';

$stmt = $conn->query("SELECT * FROM notifications ORDER BY created_at DESC");
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Notifications</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="container mt-5">
        <h2 class="mb-4">Manage Notifications</h2>
        <a href="create_notification.php" class="btn btn-primary mb-3">Add Notification</a>
        <a href="../dashboard.php" class="btn btn-secondary mb-3">Back to Dashboard</a>

        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Message</th>
                    <th>Date Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($notifications as $notification): ?>
                    <tr>
                        <td><?= $notification['id'] ?></td>
                        <td><?= htmlspecialchars($notification['title']) ?></td>
                        <td><?= htmlspecialchars($notification['message']) ?></td>
                        <td><?= htmlspecialchars($notification['created_at']) ?></td>
                        <td>
                            <a href="edit_notification.php?id=<?= $notification['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete_notification.php?id=<?= $notification['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php include '../includes/footer.php'; ?>

</body>
</html>

