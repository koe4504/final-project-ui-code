<?php
session_start();

include '../config/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Redirect based on role
        if ($user['role'] === 'admin') {
            header("Location: ../dashboard.php");
        } elseif ($user['role'] === 'staff') {
            header("Location: ../pages/staff_dashboard.php");
        } else {
            // Unknown role fallback
            header("Location: login.php?error=Unauthorized role");
        }
        exit();
    } else {
        header("Location: login.php?error=Invalid username or password");
        exit();
    }
}
?>

