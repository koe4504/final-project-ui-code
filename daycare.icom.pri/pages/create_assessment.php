<?php
include '../config/db_connect.php';
include '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure all required fields are set before proceeding
    if (isset($_POST['child_id'], $_POST['activity_id'], $_POST['score'], $_POST['grade'], $_POST['feedback'], $_POST['assessment_date'])) {
        $child_id = $_POST['child_id'];
        $activity_id = $_POST['activity_id'];
        $score = $_POST['score'];
        $grade = $_POST['grade'];
        $feedback = $_POST['feedback'];
        $assessment_date = $_POST['assessment_date']; // New field

        // Prepare the SQL query to insert data including the assessment_date
        $stmt = $conn->prepare("INSERT INTO assessments (child_id, activity_id, score, grade, feedback, assessment_date) VALUES (?, ?, ?, ?, ?, ?)");
        
        if ($stmt->execute([$child_id, $activity_id, $score, $grade, $feedback, $assessment_date])) {
            header("Location: assessments.php");
            exit();
        } else {
            echo "Error in executing SQL query.";
        }
    } else {
        echo "Required fields are missing.";
    }
}

$children = $conn->query("SELECT id, name FROM children")->fetchAll(PDO::FETCH_ASSOC);
$activities = $conn->query("SELECT id, name FROM learning_activities")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Assessment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Add Assessment</h2>
        <form method="POST" class="border p-4 rounded shadow">
            <div class="mb-3">
                <label class="form-label">Child:</label>
                <select name="child_id" class="form-control" required>
                    <?php foreach ($children as $child): ?>
                        <option value="<?= $child['id'] ?>"><?= htmlspecialchars($child['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Activity:</label>
                <select name="activity_id" class="form-control" required>
                    <?php foreach ($activities as $activity): ?>
                        <option value="<?= $activity['id'] ?>"><?= htmlspecialchars($activity['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Score:</label>
                <input type="number" name="score" class="form-control" step="0.01" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Grade:</label>
                <input type="text" name="grade" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Feedback:</label>
                <textarea name="feedback" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Assessment Date:</label>
                <input type="date" name="assessment_date" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">Create</button>
            <a href="assessments.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
<?php include '../includes/footer.php'; ?>
</html>

