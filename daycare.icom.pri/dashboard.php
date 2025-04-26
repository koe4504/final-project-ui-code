<?php

include 'includes/session.php';
include 'config/config.php';
include 'config/db_connect.php';
include 'includes/header.php';
include 'includes/role_check.php';
checkRole('admin');

// Fetch dashboard data using prepared statements
$children_stmt = $conn->prepare("SELECT COUNT(*) FROM children");
$children_stmt->execute();
$children_count = $children_stmt->fetchColumn();

$attendance_stmt = $conn->prepare("SELECT get_attendance_count(CURDATE(), 'present')");
$attendance_stmt->execute();
$attendance_today = $attendance_stmt->fetchColumn();

$pending_stmt = $conn->prepare("SELECT COUNT(*) FROM billing WHERE status = 'unpaid'");
$pending_stmt->execute();
$pending_payments = $pending_stmt->fetchColumn();

// Unread notifications created in the last 24 hours
$notif_stmt = $conn->prepare("
    SELECT COUNT(*) 
    FROM notifications 
    WHERE created_at >= CURDATE()
");
$notif_stmt->execute();
$unread_notifications = $notif_stmt->fetchColumn();

?>

<div class="container mt-5">
    <h2>Admin Dashboard</h2>
    <p>You are logged in as <strong><?php echo $_SESSION['username']; ?></strong></p>
    <?php if ($_SESSION['role'] === 'admin'): ?>
        <div class="alert alert-info">Admin users have full access to the system.</div>
    <?php else: ?>
        <div class="alert alert-warning">Staff users have limited access.</div>
    <?php endif; ?>

    <!-- Dashboard Cards -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary shadow">
                <div class="card-body">
                    <h5>Children</h5>
                    <h3><?= $children_count ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success shadow">
                <div class="card-body">
                    <h5>Today's Attendance</h5>
                    <h3><?= $attendance_today ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning shadow">
                <div class="card-body">
                    <h5>Pending Payments</h5>
                    <h3><?= $pending_payments ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger shadow">
                <div class="card-body">
                    <h5>Unread Notifications</h5>
                    <h3><?= $unread_notifications ?></h3>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Access Sections -->
<div class="container mt-5">
    <div class="row text-center">
        <?php
        $sections = [
            "Users" => "pages/users.php",
            "Staff Account" => "pages/staff.php",
            "Parents Account" => "pages/parents.php",
            "Children Information" => "pages/children.php",
            "Attendance Taking" => "pages/attendance.php",
            "Learning Activities" => "pages/activities.php",
            "Activity Resources" => "pages/activity_resources.php",
            "Teacher Assignments" => "pages/teacher_assignments.php",
            "Progress Tracking" => "pages/progress.php",
            "Assessments Grading" => "pages/assessments.php",
            "Billing" => "pages/billing.php",
            "Notifications" => "pages/notifications.php"
        ];

        foreach ($sections as $name => $link) {
            echo '<div class="col-md-4 mb-3">
                    <div class="card text-center shadow">
                        <div class="card-body">
                            <h5 class="card-title">' . $name . '</h5>
                            <a href="' . $link . '" class="btn btn-primary">Manage ' . $name . '</a>
                        </div>
                    </div>
                  </div>';
        }
        ?>
    </div>
</div>

<!-- Billing Summary -->
<div class="container mt-5 mb-5">
    <h4 class="mb-4">CHILD BILLING SUMMARY</h4>
    <div class="card shadow-sm border-0">
        <div class="card-body table-responsive">
            <table class="table table-hover table-striped table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Child Name</th>
                        <th>Invoices</th>
                        <th>Total Due ($)</th>
                        <th>Total Paid ($)</th>
                        <th>Total Unpaid ($)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $summary_stmt = $conn->prepare("SELECT * FROM child_billing_summary");
                    $summary_stmt->execute();
                    $billing_summary = $summary_stmt->fetchAll();

                    if ($billing_summary):
                        foreach ($billing_summary as $row):
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($row['child_name']) ?></td>
                        <td><?= $row['invoices'] ?></td>
                        <td><?= number_format($row['total_due'], 2) ?></td>
                        <td class="text-success"><?= number_format($row['total_paid'], 2) ?></td>
                        <td class="text-danger"><?= number_format($row['total_unpaid'], 2) ?></td>
                    </tr>
                    <?php endforeach; else: ?>
                        <tr>
                            <td colspan="5" class="text-center">No billing records found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

