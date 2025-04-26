<?php

include '../includes/session.php';
include '../config/db_connect.php';
include '../includes/header.php';
include '../includes/role_check.php';
checkRole('admin');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'], $_POST['child_id'], $_POST['amount'], $_POST['due_date'], $_POST['status'])) {
        $id = $_POST['id'];
        $child_id = $_POST['child_id'];
        $amount = $_POST['amount'];
        $due_date = $_POST['due_date'];
        $status = $_POST['status'];

        // Prepare and execute SQL query to update the record
        $stmt = $conn->prepare("UPDATE billing SET child_id = ?, amount = ?, due_date = ?, status = ? WHERE id = ?");
        if ($stmt->execute([$child_id, $amount, $due_date, $status, $id])) {
            header("Location: billing.php");
            exit();
        } else {
            echo "Error in updating the record.";
        }
    }
}

// Get the list of children for the dropdown
$children = $conn->query("SELECT id, name FROM children")->fetchAll(PDO::FETCH_ASSOC);

// Get the current billing record
$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM billing WHERE id = ?");
$stmt->execute([$id]);
$billing = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$billing) {
    echo "Billing record not found!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Billing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Billing</h2>
        <form method="POST" class="border p-4 rounded shadow">
            <input type="hidden" name="id" value="<?= $billing['id'] ?>">
            <div class="mb-3">
                <label class="form-label">Child:</label>
                <select name="child_id" class="form-control" required>
                    <?php foreach ($children as $child): ?>
                        <option value="<?= $child['id'] ?>" <?= $billing['child_id'] == $child['id'] ? 'selected' : '' ?>><?= htmlspecialchars($child['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Amount:</label>
                <input type="number" name="amount" class="form-control" step="0.01" value="<?= htmlspecialchars($billing['amount']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Due Date:</label>
                <input type="date" name="due_date" class="form-control" value="<?= $billing['due_date'] ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Status:</label>
                <select name="status" class="form-control" required>
                    <option value="unpaid" <?= $billing['status'] == 'unpaid' ? 'selected' : '' ?>>Unpaid</option>
                    <option value="paid" <?= $billing['status'] == 'paid' ? 'selected' : '' ?>>Paid</option>
                </select>
            </div>
            <button type="submit" class="btn btn-warning">Update</button>
            <a href="billing.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
<?php include '../includes/footer.php'; ?>
</html>

