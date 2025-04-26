<?php
include '../config/db_connect.php';
include '../includes/header.php';

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM parents WHERE id = ?");
$stmt->execute([$id]);
$parent = $stmt->fetch(PDO::FETCH_ASSOC);

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif (!preg_match('/^[+]?[0-9]{10,15}$/', $phone)) {
        $error = "Invalid phone number format.";
    } else {
        try {
            $stmt = $conn->prepare("UPDATE parents SET name = ?, email = ?, phone = ? WHERE id = ?");
            $stmt->execute([$name, $email, $phone, $id]);

            header("Location: parents.php");
            exit();
        } catch (PDOException $e) {
            $error = $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Parent</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function validateForm() {
            let email = document.getElementById('email').value;
            let phone = document.getElementById('phone').value;
            let emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            let phonePattern = /^[+]?[0-9]{10,15}$/;

            if (!emailPattern.test(email)) {
                alert("Invalid email format.");
                return false;
            }
            if (!phonePattern.test(phone)) {
                alert("Invalid phone number format.");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Parent</h2>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" class="border p-4 rounded shadow" onsubmit="return validateForm()">
            <div class="mb-3">
                <label class="form-label">Name:</label>
                <input type="text" name="name" value="<?= htmlspecialchars($parent['name']) ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email:</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($parent['email']) ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Phone:</label>
                <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($parent['phone']) ?>" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="parents.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
<?php include '../includes/footer.php'; ?>
</html>

