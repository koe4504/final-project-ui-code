<?php
#include '../includes/session.php';
include '../config/db_connect.php';
include '../includes/header.php';
#checkRole('admin');

$id = $_GET['id'];

// Fetch current user data
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $role = $_POST['role'];

    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, phone = ?, role = ? WHERE id = ?");
    $stmt->execute([$username, $email, $phone, $role, $id]);

    header("Location: users.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Edit User</h2>
    <form method="POST" class="border p-4 rounded shadow">
        <div class="mb-3">
            <label class="form-label">Username:</label>
            <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Phone:</label>
            <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Role:</label>
            <select name="role" class="form-select">
                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                <option value="staff" <?= $user['role'] === 'staff' ? 'selected' : '' ?>>Staff</option>
            </select>
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-success">Update</button>
            <a href="users.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
</body>
</html>

<?php include '../includes/footer.php'; ?>

