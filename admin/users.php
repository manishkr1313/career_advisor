<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

if (!isset($_SESSION['admin_id']) || !$_SESSION['is_admin']) {
    header("Location: ../admin_login.php");
    exit();
}

// Get pagination
$page = intval($_GET['page'] ?? 1);
$per_page = 10;
$offset = ($page - 1) * $per_page;

// Get total users
$total_result = $conn->query("SELECT COUNT(*) as count FROM users");
$total_row = $total_result->fetch_assoc();
$total_users = $total_row['count'];
$total_pages = ceil($total_users / $per_page);

// Get users with pagination
$users = [];
$result = $conn->query("SELECT u.*, COUNT(qr.id) as quiz_attempts FROM users u 
                        LEFT JOIN quiz_results qr ON u.id = qr.user_id 
                        GROUP BY u.id 
                        ORDER BY u.created_at DESC 
                        LIMIT $offset, $per_page");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

// Get statistics
$stream_stats = [];
$result = $conn->query("SELECT suggested_stream, COUNT(*) as count FROM quiz_results GROUP BY suggested_stream");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $stream_stats[] = $row;
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Users - Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/admin.css" />
    <link rel="shortcut icon" href="favicon.ico?v=2">
</head>

<body class="bg-light">
    <?php include 'header.php'; ?>

    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-12">
                <h1 class="h2 mb-4">
                    Users Management
                </h1>
            </div>
        </div>

        <div class="row mb-4">
            <!-- Total Users -->
            <div class="col-md-3 mb-3">
                <div class="card stat-card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small mb-1">Total Users</p>
                                <h3 class="mb-0 fw-bold"><?php echo $total_users; ?></h3>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Stream Stats -->
            <?php foreach ($stream_stats as $stat): ?>
            <div class="col-md-3 mb-3">
                <div class="card stat-card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small mb-1">
                                    <?php echo htmlspecialchars($stat['suggested_stream']); ?>
                                </p>
                                <h3 class="mb-0 fw-bold"><?php echo $stat['count']; ?></h3>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    Users List (Page <?php echo $page; ?> /
                    <?php echo $total_pages; ?>)
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Sno</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Class</th>
                                <th>Quiz Attempts</th>
                                <th>Registration Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($users) > 0): ?>
                            <?php foreach ($users as $index => $user): ?>
                            <tr>
                                <td><?php echo $offset + $index + 1; ?></td>
                                <td>
                                    <a href="profile.php?id=<?php echo $user['id']; ?>"
                                        class="fw-bold text-decoration-none">
                                        <?php echo htmlspecialchars($user['name']); ?>
                                    </a>
                                </td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo htmlspecialchars($user['class']); ?></td>
                                <td>
                                    <span class="badge bg-success"><?php echo $user['quiz_attempts']; ?></span>
                                </td>
                                <td>
                                    <small
                                        class="text-muted"><?php echo date('d M Y', strtotime($user['created_at'])); ?></small>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    No users found
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
            <div class="card-footer bg-light">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center mb-0">
                        <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=1">First</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page - 1; ?>">Previous</a>
                        </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                        <?php endfor; ?>

                        <?php if ($page < $total_pages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page + 1; ?>">Next</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $total_pages; ?>">Last</a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>