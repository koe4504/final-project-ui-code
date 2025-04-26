<?php
include '../config/db_connect.php';
include '../includes/header.php';

// Fetch default billing amount using a stored function
$stmt = $conn->query("SELECT get_default_billing_amount() AS default_amount");
$defaultAmount = $stmt->fetch(PDO::FETCH_ASSOC)['default_amount'] ?? '';

// Set default due date to one week from today
$defaultDueDate = date('Y-m-d', strtotime('+7 days'));

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['child_id'], $_POST['amount'], $_POST['due_date'], $_POST['status'])) {
        $child_id = $_POST['child_id'];
        $amount = $_POST['amount'];
        $due_date = $_POST['due_date'];
        $status = $_POST['status'];

        $stmt = $conn->prepare("INSERT INTO billing (child_id, amount, due_date, status) VALUES (?, ?, ?, ?)");

        if ($stmt->execute([$child_id, $amount, $due_date, $status])) {
            $success = "Billing created successfully.";
        } else {
            $error = "Error creating billing record.";
        }
    } else {
        $error = "Please fill all required fields.";
    }
}

$children = $conn->query("SELECT id, name FROM children")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Billing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Create Billing</h2>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php elseif ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" class="border p-4 rounded shadow">
        <div class="mb-3">
            <label class="form-label">Child:</label>
            <select name="child_id" class="form-control" required>
                <option value="">Select child...</option>
                <?php foreach ($children as $child): ?>
                    <option value="<?= $child['id'] ?>"><?= htmlspecialchars($child['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Amount:</label>
            <input type="number" name="amount" class="form-control" step="0.01" value="<?= htmlspecialchars($defaultAmount) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Due Date:</label>
            <input type="date" name="due_date" class="form-control" value="<?= $defaultDueDate ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Status:</label>
            <select name="status" class="form-control" required>
                <option value="unpaid">Unpaid</option>
                <option value="paid">Paid</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Create</button>
        <a href="billing.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
<?php include '../includes/footer.php'; ?>
</html>

