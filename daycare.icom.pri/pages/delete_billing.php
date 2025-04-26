<?php

include '../includes/session.php';
include '../config/db_connect.php';
include '../includes/header.php';
include '../includes/role_check.php';
checkRole('admin');

// Check if the 'id' is provided in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the billing record to confirm the details before deleting
    $stmt = $conn->prepare("SELECT * FROM billing WHERE id = ?");
    $stmt->execute([$id]);
    $billing = $stmt->fetch(PDO::FETCH_ASSOC);

    // If no record is found, show an error message
    if (!$billing) {
        echo "<div class='alert alert-danger mt-4'>Record not found!</div>";
        exit();
    }

    // Handle the deletion when confirmed
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Prepare and execute delete query
        $deleteStmt = $conn->prepare("DELETE FROM billing WHERE id = ?");
        if ($deleteStmt->execute([$id])) {
            header("Location: billing.php");
            exit();
        } else {
            echo "<div class='alert alert-danger mt-4'>Error deleting the record.</div>";
        }
    }
} else {
    echo "<div class='alert alert-danger mt-4'>Invalid request. No ID provided.</div>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Billing Record</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Delete Billing Record</h2>
        
        <div class="alert alert-warning mt-4">
            <strong>Warning!</strong> Are you sure you want to delete this billing record?
        </div>

        <div class="mb-3">
            <p><strong>Child:</strong> <?= htmlspecialchars($billing['child_name']) ?></p>
            <p><strong>Amount:</strong> <?= htmlspecialchars($billing['amount']) ?></p>
            <p><strong>Due Date:</strong> <?= htmlspecialchars($billing['due_date']) ?></p>
            <p><strong>Status:</strong> <?= htmlspecialchars($billing['status']) ?></p>
        </div>

        <!-- Delete confirmation form -->
        <form method="POST">
            <button type="submit" class="btn btn-danger">Delete Record</button>
            <a href="billing.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3 mt-5">
        &copy; <?= date("Y") ?> Daycare Management System. All rights reserved.
    </footer>
</body>
</html>

