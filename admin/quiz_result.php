<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

if (!isset($_SESSION['admin_id']) || !$_SESSION['is_admin']) {
    header("Location: ../admin_login.php");
    exit();
}

// Pagination
$page = intval($_GET['page'] ?? 1);
$per_page = 10;
$offset = ($page - 1) * $per_page;

// Total quiz results
$total_result = $conn->query("SELECT COUNT(*) as count FROM quiz_results");
$total_row = $total_result->fetch_assoc();
$total_results = $total_row['count'];
$total_pages = ceil($total_results / $per_page);

// Get quiz results with pagination
$results = [];

$result = $conn->query("
    SELECT qr.*, u.name
    FROM quiz_results qr
    JOIN users u ON qr.user_id = u.id
    ORDER BY qr.date DESC
    LIMIT $offset, $per_page
");

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $results[] = $row;
    }
}

// Stream statistics
$stream_stats = [];

$result = $conn->query("
    SELECT suggested_stream, COUNT(*) as count
    FROM quiz_results
    GROUP BY suggested_stream
");

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $stream_stats[] = $row;
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Results - Admin</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/admin.css">
</head>

<body class="bg-light">

    <?php include 'header.php'; ?>

    <div class="container-fluid mt-4">

        <div class="row">
            <div class="col-12">
                <h1 class="h2 mb-4">
                    Quiz Results
                </h1>
            </div>
        </div>

        <div class="row mb-4">

            <!-- Total Results -->
            <div class="col-md-3 mb-3">
                <div class="card stat-card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small mb-1">Total Results</p>
                                <h3 class="mb-0 fw-bold"><?php echo $total_results; ?></h3>
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
                                <h3 class="mb-0 fw-bold">
                                    <?php echo $stat['count']; ?>
                                </h3>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

        </div>

        <div class="card shadow-sm">

            <div class="card-header bg-success text-white">
                <h5 class="mb-0">

                    Quiz Results List (Page <?php echo $page; ?> / <?php echo $total_pages; ?>)
                </h5>
            </div>

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover mb-0">

                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Science Score</th>
                                <th>Commerce Score</th>
                                <th>Arts Score</th>
                                <th>Suggested Stream</th>
                                <th>Date</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php if (count($results) > 0): ?>

                            <?php foreach ($results as $index => $row): ?>

                            <tr>

                                <td>
                                    <?php echo $offset + $index + 1; ?>
                                </td>

                                <td>

                                    <strong><?php echo htmlspecialchars($row['name']); ?></strong>
                                </td>

                                <td>
                                    <?php echo $row['science_score']; ?>
                                </td>

                                <td>
                                    <?php echo $row['commerce_score']; ?>
                                </td>

                                <td>
                                    <?php echo $row['arts_score']; ?>
                                </td>

                                <td>
                                    <span class="badge bg-success">
                                        <?php echo htmlspecialchars($row['suggested_stream']); ?>
                                    </span>
                                </td>

                                <td>
                                    <small class="text-muted">
                                        <?php echo date('d M Y', strtotime($row['date'])); ?>
                                    </small>
                                </td>

                            </tr>

                            <?php endforeach; ?>

                            <?php else: ?>

                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    No quiz results found
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
                            <a class="page-link" href="?page=<?php echo $page - 1; ?>">
                                Previous
                            </a>
                        </li>

                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>

                        <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>

                        <?php endfor; ?>

                        <?php if ($page < $total_pages): ?>

                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page + 1; ?>">
                                Next
                            </a>
                        </li>

                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $total_pages; ?>">
                                Last
                            </a>
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