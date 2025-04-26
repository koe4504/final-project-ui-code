<?php

include '../includes/session.php';
include '../config/db_connect.php';
include '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $message = $_POST['message'];
    $recipient_type = $_POST['recipient_type'];
    $recipient_id = $_POST['recipient_id'] ?? null;
    $created_at = date("Y-m-d H:i:s");

    if ($recipient_type === 'All Parents') {
        $stmt = $conn->query("SELECT id FROM parents");
        $recipients = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } elseif ($recipient_type === 'All Staffs') {
        $stmt = $conn->query("SELECT id FROM staff");
        $recipients = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $recipients = [['id' => $recipient_id]];
    }

    $stmt = $conn->prepare("INSERT INTO notifications (title, message, recipient_id, recipient_type, created_at) VALUES (?, ?, ?, ?, ?)");
    foreach ($recipients as $recipient) {
        $stmt->execute([$title, $message, $recipient['id'], $recipient_type, $created_at]);
    }

    header("Location: notifications.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Notification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Create Notification</h2>
    <form method="POST" class="border p-4 rounded shadow">
        <div class="mb-3">
            <label class="form-label">Title:</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Message:</label>
            <textarea name="message" class="form-control" required></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Recipient Type:</label>
            <select name="recipient_type" id="recipient_type" class="form-control" required>
                <option value="">Select Type</option>
                <option value="Parent">Parent</option>
                <option value="Staff">Staff</option>
                <option value="All Parents">All Parents</option>
                <option value="All Staffs">All Staff</option>
            </select>
        </div>
        <div class="mb-3" id="recipient_select" style="display:none;">
            <label class="form-label">Select Recipient:</label>
            <select name="recipient_id" id="recipient_id" class="form-control">
                <option value="">Loading...</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Send Notification</button>
        <a href="notifications.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function () {
    $('#recipient_type').change(function () {
        let type = $(this).val();
        if (type === 'Parent' || type === 'Staff') {
            $('#recipient_select').show();
            $.post('fetch_recipients.php', { recipient_type: type }, function (data) {
                $('#recipient_id').html(data);
            });
        } else {
            $('#recipient_select').hide();
            $('#recipient_id').html('');
        }
    });
});
</script>

</body>
<?php include '../includes/footer.php'; ?>
</html>

