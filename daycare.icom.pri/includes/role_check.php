

<?php
function checkRole($role) {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== $role) {
        header("Location: ../pages/staff_dashboard.php?error=unauthorized");
        exit();
    }
}
?>
