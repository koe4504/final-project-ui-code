<?php
include '../config/db_connect.php';
include '../includes/header.php';

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM staff WHERE id = ?");
$stmt->execute([$id]);
$staff = $stmt->fetch(PDO::FETCH_ASSOC);

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $role = trim($_POST['role']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    // Client-side validation fallback (in case JavaScript is disabled)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif (!preg_match('/^[+]?[0-9]{10,15}$/', $phone)) {
        $error = "Invalid phone number format.";
    } else {
        try {
            // Prepare update statement
            $stmt = $conn->prepare("UPDATE staff SET name = ?, role = ?, email = ?, phone = ? WHERE id = ?");
            $stmt->execute([$name, $role, $email, $phone, $id]);

            header("Location: staff.php");
            exit();
        } catch (PDOException $e) {
            // Catch database trigger errors
            $error = $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Staff</title>
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
        <h2>Edit Staff</h2>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" class="border p-4 rounded shadow" onsubmit="return validateForm()">
            <div class="mb-3">
                <label class="form-label">Name:</label>
                <input type="text" name="name" value="<?= htmlspecialchars($staff['name']) ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Role:</label>
                <input type="text" name="role" value="<?= htmlspecialchars($staff['role']) ?>" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Email:</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($staff['email']) ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Phone:</label>
                <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($staff['phone']) ?>" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="staff.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
<?php include '../includes/footer.php'; ?>
</html>

