<?php
#include '../includes/session.php';
include '../config/db_connect.php';
include '../includes/header.php';
#checkRole('admin');

$stmt = $conn->query("SELECT id, name, phone FROM parents");
$parents = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $parent_id = $_POST['parent_id'];
    $phone = $_POST['phone'];

    $stmt = $conn->prepare("INSERT INTO children (name, age, parent_id, phone) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $age, $parent_id, $phone]);

    header("Location: children.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Child</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function updatePhone() {
            let parentSelect = document.getElementById("parent_id");
            let phoneInput = document.getElementById("phone");
            let parentPhones = JSON.parse(parentSelect.dataset.phones);
            
            let selectedParentId = parentSelect.value;
            phoneInput.value = parentPhones[selectedParentId] || "";
        }
    </script>
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Add Child</h2>
        <form method="POST" class="card p-4 shadow">
            <div class="mb-3">
                <label class="form-label">Name:</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Age:</label>
                <input type="number" name="age" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Parent:</label>
                <select name="parent_id" id="parent_id" class="form-control" onchange="updatePhone()" data-phones='<?= json_encode(array_column($parents, 'phone', 'id')) ?>' required>
                    <option value="" disabled selected>Select a Parent</option>
                    <?php foreach ($parents as $parent): ?>
                        <option value="<?= $parent['id'] ?>"><?= htmlspecialchars($parent['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Phone:</label>
                <input type="text" name="phone" id="phone" class="form-control" readonly>
            </div>
            <button type="submit" class="btn btn-success">Create</button>
            <a href="children.php" class="btn btn-secondary">Back to Children</a>
        </form>
    </div>
</div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>

