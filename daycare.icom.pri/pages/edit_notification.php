<?php
include '../config/db_connect.php';
include '../includes/header.php';

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
        $title = $_POST['title'];
        $message = $_POST['message'];

        $updateStmt = $conn->prepare("UPDATE notifications SET title = ?, message = ? WHERE id = ?");
        $updateStmt->execute([$title, $message, $id]);

        header("Location: notifications.php");
        exit();
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
    <title>Edit Notification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Notification</h2>
        <form method="POST" class="border p-4 rounded shadow">
            <div class="mb-3">
                <label class="form-label">Title:</label>
                <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($notification['title']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Message:</label>
                <textarea name="message" class="form-control" required><?= htmlspecialchars($notification['message']) ?></textarea>
            </div>
            <button type="submit" class="btn btn-warning">Update</button>
            <a href="notifications.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>

