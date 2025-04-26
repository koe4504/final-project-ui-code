<?php
include '../config/db_connect.php';
include '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $activity_id = isset($_POST['activity_id']) ? $_POST['activity_id'] : null;
    $resource_type = isset($_POST['resource_type']) ? $_POST['resource_type'] : null;
    $resource = '';

    if (empty($activity_id) || empty($resource_type)) {
        echo "<p class='text-danger'>Activity and Resource Type are required.</p>";
    } else {
        // Handle file upload if resource_type is 'file'
        if ($resource_type === 'file' && isset($_FILES['resource_file']) && $_FILES['resource_file']['error'] === 0) {
            $upload_dir = '../uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true); // Ensure the uploads folder exists
            }
            
            $file_name = basename($_FILES['resource_file']['name']);
            $target_file = $upload_dir . time() . "_" . $file_name; // Prevent duplicate filenames

            if (move_uploaded_file($_FILES['resource_file']['tmp_name'], $target_file)) {
                $resource = $target_file; // Store file path in DB
            } else {
                echo "<p class='text-danger'>Error uploading file.</p>";
            }
        } else {
            // If resource_type is not file, use the text input value
            $resource = isset($_POST['resource_text']) ? $_POST['resource_text'] : '';
        }

        // Insert into database only if resource_type is set
        $stmt = $conn->prepare("INSERT INTO activity_resources (activity_id, resource_type, resource) VALUES (?, ?, ?)");
        $stmt->execute([$activity_id, $resource_type, $resource]);

        header("Location: activity_resources.php");
        exit();
    }
}

// Fetch activities for dropdown
$stmt = $conn->query("SELECT id, name FROM learning_activities");
$activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Activity Resource</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function toggleResourceInput() {
            var resourceType = document.getElementById('resource_type').value;
            document.getElementById('text_resource').style.display = (resourceType === 'description' || resourceType === 'link') ? 'block' : 'none';
            document.getElementById('file_resource').style.display = (resourceType === 'file') ? 'block' : 'none';
        }
    </script>
</head>
<body>
    <div class="container mt-5">
        <h2>Add Activity Resource</h2>
        <form method="POST" enctype="multipart/form-data" class="border p-4 rounded shadow">
            <div class="mb-3">
                <label class="form-label">Select Activity:</label>
                <select name="activity_id" class="form-control" required>
                    <?php foreach ($activities as $activity): ?>
                        <option value="<?= $activity['id'] ?>"><?= htmlspecialchars($activity['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Resource Type:</label>
                <select name="resource_type" id="resource_type" class="form-control" onchange="toggleResourceInput()" required>
                    <option value="link" selected>Link</option>
                    <option value="file">File</option>
                    <option value="description">Description</option>
                </select>
            </div>
            <div class="mb-3" id="text_resource">
                <label class="form-label">Resource:</label>
                <input type="text" name="resource_text" class="form-control">
            </div>
            <div class="mb-3" id="file_resource" style="display: none;">
                <label class="form-label">Upload File:</label>
                <input type="file" name="resource_file" class="form-control">
            </div>
            <button type="submit" class="btn btn-success">Create</button>
            <a href="activity_resources.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
<?php include '../includes/footer.php'; ?>
</html>

