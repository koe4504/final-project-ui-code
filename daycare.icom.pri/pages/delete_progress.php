<?php
include '../includes/session.php';
include '../config/db_connect.php';
include '../includes/header.php';
include '../includes/role_check.php';
checkRole('admin');



if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare SQL statement to delete the record
    $stmt = $conn->prepare("DELETE FROM progress WHERE id = ?");
    $stmt->execute([$id]);

    // Redirect to the progress management page
    header("Location: progress.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Progress</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Delete Progress Record</h2>
        <p>Are you sure you want to delete this progress record? This action cannot be undone.</p>
        <a href="progress.php" class="btn btn-secondary">Cancel</a>
        <a href="delete_progress.php?id=<?= $_GET['id'] ?>" class="btn btn-danger">Delete</a>
    </div>
</body>
<?php include '../includes/footer.php'; ?>
</html>

