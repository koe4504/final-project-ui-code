<?php
include '../includes/session.php';
include '../config/db_connect.php';
include '../includes/role_check.php';
checkRole('admin');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM attendance WHERE id = ?");
    $stmt->execute([$id]);
}
header("Location: attendance.php");
exit();

