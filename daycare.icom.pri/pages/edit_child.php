<?php
#include '../includes/session.php';
include '../config/db_connect.php';
include '../includes/header.php';
#checkRole('admin');

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM children WHERE id = ?");
$stmt->execute([$id]);
$child = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $conn->query("SELECT * FROM parents");
$parents = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $parent_id = $_POST['parent_id'];
    $phone = $_POST['phone'];
    
    $stmt = $pdo->prepare("UPDATE children SET name = ?, age = ?, parent_id = ?, phone = ? WHERE id = ?");
    $stmt->execute([$name, $age, $parent_id, $phone, $id]);
    header("Location: children.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Child</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Edit Child</h2>
        <form method="POST" class="card p-4 shadow">
            <div class="mb-3">
                <label class="form-label">Name:</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($child['name']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Age:</label>
                <input type="number" name="age" class="form-control" value="<?= $child['age'] ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Parent:</label>
                <select name="parent_id" class="form-control" required>
                    <?php foreach ($parents as $parent): ?>
                        <option value="<?= $parent['id'] ?>" <?= $child['parent_id'] == $parent['id'] ? 'selected' : '' ?>><?= htmlspecialchars($parent['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Phone:</label>
                <input type="text" name="phone" class="form-control" value="<?= $child['phone'] ?>" required>
            </div>
            <button type="submit" class="btn btn-success">Update</button>
            <a href="children.php" class="btn btn-secondary">Back to Children</a>
        </form>
    </div>
</div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>

