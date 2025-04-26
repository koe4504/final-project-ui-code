<?php
include '../includes/session.php';
include '../config/db_connect.php';
include '../includes/header.php';
include '../includes/role_check.php';
checkRole('admin');


if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM notifications WHERE id = ?");
    $stmt->execute([$id]);
    $notification = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$notification) {
        echo "<div class='alert alert-danger mt-4'>Notification not found!</div>";
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $deleteStmt = $conn->prepare("DELETE FROM notifications WHERE id = ?");
        if ($deleteStmt->execute([$id])) {
            header("Location: notifications.php");
            exit();
        } else {
            echo "<div class='alert alert-danger mt-4'>Error deleting the notification.</div>";
        }
    }
} else {
    echo "<div class='alert alert-danger mt-4'>Invalid request.</div>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Notification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Delete Notification</h2>
        <div class="alert alert-warning mt-4">
            <strong>Warning!</strong> Are you sure you want to delete this notification?
        </div>

        <p><strong>Title:</strong> <?= htmlspecialchars($notification['title']) ?></p>
        <p><strong>Message:</strong> <?= htmlspecialchars($notification['message']) ?></p>

        <form method="POST">
            <button type="submit" class="btn btn-danger">Delete</button>
            <a href="notifications.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>

