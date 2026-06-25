<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id']) || !$_SESSION['is_admin']) {
    header("Location: ../admin_login.php");
    exit();
}

// Get statistics
$stats = [
    'total_users' => 0,
    'total_courses' => 0,
    'total_careers' => 0,
    'total_colleges' => 0,
    'total_notifications' => 0
];

// Get total users
$result = $conn->query("SELECT COUNT(*) as count FROM users");
if ($result) {
    $row = $result->fetch_assoc();
    $stats['total_users'] = $row['count'];
}

// Get total courses
$result = $conn->query("SELECT COUNT(*) as count FROM courses");
if ($result) {
    $row = $result->fetch_assoc();
    $stats['total_courses'] = $row['count'];
}

// Get total careers
$result = $conn->query("SELECT COUNT(*) as count FROM careers");
if ($result) {
    $row = $result->fetch_assoc();
    $stats['total_careers'] = $row['count'];
}

// Get total colleges
$result = $conn->query("SELECT COUNT(*) as count FROM colleges");
if ($result) {
    $row = $result->fetch_assoc();
    $stats['total_colleges'] = $row['count'];
}

// Get total notifications
$result = $conn->query("SELECT COUNT(*) as count FROM notifications");
if ($result) {
    $row = $result->fetch_assoc();
    $stats['total_notifications'] = $row['count'];
}

// Get recent users
$recent_users = [];
$result = $conn->query("SELECT id, name, email, class, created_at FROM users ORDER BY created_at DESC LIMIT 5");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $recent_users[] = $row;
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard - Career Advisor</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/admin.css" />
    <link rel="shortcut icon" href="favicon.ico?v=2">
</head>

<body class="bg-light">
    <?php include '../admin/header.php'; ?>

    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-12">
                <h1 class="h2 mb-4">
                    Dashboard
                </h1>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card stat-card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small mb-1">Total Users</p>
                                <h3 class="mb-0 fw-bold"><?php echo $stats['total_users']; ?></h3>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card stat-card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small mb-1">Total Courses</p>
                                <h3 class="mb-0 fw-bold"><?php echo $stats['total_courses']; ?></h3>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card stat-card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small mb-1">Total Careers</p>
                                <h3 class="mb-0 fw-bold"><?php echo $stats['total_careers']; ?></h3>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card stat-card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small mb-1">Total Colleges</p>
                                <h3 class="mb-0 fw-bold"><?php echo $stats['total_colleges']; ?></h3>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Recent Users Table -->
            <div class="col-lg-8 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Recent Users</h5>
                        <a href="users.php" class="btn btn-sm btn-light">View All</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Class</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($recent_users) > 0): ?>
                                    <?php foreach ($recent_users as $user): ?>
                                    <tr>
                                        <td>
                                            <i class="fas fa-user-circle text-primary me-2"></i>
                                            <?php echo htmlspecialchars($user['name']); ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                                        <td><?php echo htmlspecialchars($user['class']); ?></td>
                                        <td>
                                            <small class="text-muted">
                                                <?php echo date('d M Y', strtotime($user['created_at'])); ?>
                                            </small>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            No users found
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>



                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="col-lg-4 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Quick Links</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="courses.php" class="btn btn-outline-primary">
                                Add Course
                            </a>
                            <a href="careers.php" class="btn btn-outline-info">
                                Add Career
                            </a>
                            <a href="colleges.php" class="btn btn-outline-warning">
                                Add College
                            </a>
                            <a href="notifications.php" class="btn btn-outline-danger">
                                Add Notifications
                            </a>
                            <a href="quiz_result.php" class="btn btn-outline-success">
                                Quiz Results
                            </a>

                            <a href="quiz.php" class="btn btn-outline-primary">
                                Quiz Management
                            </a>

                            <a href="users.php" class="btn btn-outline-secondary">
                                View All Users
                            </a>
                        </div>
                    </div>
                </div>

                <!-- <div class="card shadow-sm mt-3">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">Information</h5>
                    </div>
                    <div class="card-body small text-muted">
                        <p>Welcome <strong><?php echo htmlspecialchars($_SESSION['admin_name']); ?></strong></p>
                        <p class="mb-0">You can manage all data from here.</p>
                    </div>
                </div> -->
            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>