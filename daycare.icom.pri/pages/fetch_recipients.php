<?php
include '../config/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['recipient_type'];

    if ($type === 'Parent') {
        $stmt = $conn->query("SELECT id, name FROM parents");
    } elseif ($type === 'Staff') {
        $stmt = $conn->query("SELECT id, name FROM staff");
    } else {
        echo "<option value=''>No recipients found</option>";
        exit;
    }

    $options = "<option value=''>Select recipient...</option>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $options .= "<option value='{$row['id']}'>" . htmlspecialchars($row['name']) . "</option>";
    }

    echo $options;
}
?>

