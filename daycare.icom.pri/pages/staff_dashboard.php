<?php
include '../includes/session.php';
include '../config/db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Daycare Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .card h5 {
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
        }
        .card h3 {
            font-size: 2rem;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Daycare Management System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#">Dashboard</a></li>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <li class="nav-item"><a class="nav-link" href="admin_panel.php">Admin Panel</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="nav-link" href="../auth/logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Welcome Message -->
    <div class="container mt-5">
        <h1>Staff Dashboard</h1>
        <p>Welcome, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></p>
        <?php if ($_SESSION['role'] === 'admin'): ?>
            <div class="alert alert-info">Admin have full access to the system.</div>
        <?php else: ?>
            <div class="alert alert-warning">Limited access for staff...</div>
        <?php endif; ?>
    </div>

    <?php
    $children_count = $conn->query("SELECT COUNT(*) FROM children")->fetchColumn();
    $attendance_today = $conn->query("SELECT get_attendance_count(CURDATE(), 'present')")->fetchColumn();
    ?>

    <!-- Report Cards -->
    <div class="container mt-4">
        <div class="row justify-content-center text-center">
            <div class="col-md-4 mb-3">
                <div class="card text-white bg-primary shadow rounded-3">
                    <div class="card-body">
                        <h5 class="card-title">Children</h5>
                        <h3 class="card-text"><?= $children_count ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card text-white bg-success shadow rounded-3">
                    <div class="card-body">
                        <h5 class="card-title">Today's Attendance</h5>
                        <h3 class="card-text"><?= $attendance_today ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Breakdown Cards -->
    <div class="container mt-4">
        <div class="row justify-content-center text-center">
            <?php
            $stmt = $conn->query("SELECT * FROM attendance_today_summary");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $color = ($row['status'] === 'present') ? 'bg-success' : 'bg-danger';
                echo '<div class="col-md-4 mb-3">
                        <div class="card text-white ' . $color . ' shadow rounded-3">
                            <div class="card-body">
                                <h5 class="card-title text-capitalize">' . $row['status'] . '</h5>
                                <h3 class="card-text">' . $row['total'] . '</h3>
                            </div>
                        </div>
                      </div>';
            }
            ?>
        </div>
    </div>

    <!--This Section allow staff to perfom daily Activity for the Daycare Operation -->
    <div class="container mt-5">
        <div class="row text-center">
            <?php
            $sections = [
                "Parents Account" => "parents.php",
                "Children Information" => "children.php",
                "Attendance Taking" => "attendance.php",
                "Learning Activities" => "activities.php",
                "Progress Tracking" => "progress.php",
                "Assessments Grading" => "assessments.php",
                "Teacher Assignments" => "teacher_assignments.php",
                "Billing" => "billing.php",
                "Notifications" => "notifications.php"
            ];

            foreach ($sections as $name => $link) {
                echo '<div class="col-md-4 mb-4">
                        <div class="card text-center shadow-sm h-100">
                            <div class="card-body d-flex flex-column justify-content-center">
                                <h5 class="card-title">' . $name . '</h5>
                                <a href="' . $link . '" class="btn btn-primary">Manage ' . $name . '</a>
                            </div>
                        </div>
                      </div>';
            }
            ?>
        </div>
    </div>

    <!--This section is presenting the Billing Summary View -->
    <div class="container mt-5">
        <h4 class="text-center mb-4">BILLING SUMMARY PER CHILD</h4>
        <div class="table-responsive">
            <table class="table table-bordered table-striped text-center">
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
                    $billing_stmt = $conn->query("SELECT * FROM child_billing_summary");
                    while ($row = $billing_stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo '<tr>
                                <td>' . htmlspecialchars($row['child_name']) . '</td>
                                <td>' . $row['invoices'] . '</td>
                                <td>' . number_format($row['total_due'], 2) . '</td>
                                <td class="text-success">' . number_format($row['total_paid'], 2) . '</td>
                                <td class="text-danger">' . number_format($row['total_unpaid'], 2) . '</td>
                              </tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

<?php include '../includes/footer.php'; ?>
</body>
</html>
