<?php
#include '../includes/session.php';
include '../config/db_connect.php';
include '../includes/header.php';

#checkRole('admin');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'];
    $email = $_POST['email'] ?? null;
    $phone = $_POST['phone'] ?? null;

    $stmt = $conn->prepare("INSERT INTO users (username, password, role, email, phone) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$username, $password, $role, $email, $phone]);

    header("Location: users.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add User</title>
</head>
<body>
    <h2>Add User</h2>

<div class="container mt-5">
    <form method="POST" class="border p-4 rounded shadow">
        <div class="mb-3">
            <label class="form-label">Username:</label>
            <input type="text" name="username" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Password:</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email:</label>
            <input type="email" name="email" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Phone:</label>
            <input type="text" name="phone" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Role:</label>
            <select name="role" class="form-select">
                <option value="admin">Admin</option>
                <option value="staff">Staff</option>
            </select>
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-success">Create</button>
            <a href="users.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

</body>
</html>

<?php include '../includes/footer.php'; ?>

