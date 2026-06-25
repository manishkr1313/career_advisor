<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

requireLogin();

$careers_data = [];

$result = $conn->query("SELECT * FROM courses ORDER BY created_at DESC");

while ($row = $result->fetch_assoc()) {

    $stream = $row['stream'];

    $careers_data[$stream][] = [
        "name" => $row['course_name'],
        "description" => $row['description'],
        "duration" => $row['duration'],
        "eligibility" => $row['eligibility']
    ];
}

 ?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Career Paths - Career Advisor</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="shortcut icon" href="favicon.ico?v=2">
    <link rel="stylesheet" href="css/career.css" />
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <?php include 'includes/header.php'; ?>
    <div class="container py-5">
        <h1 class="mb-5 text-info">Courses</h1>

        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs mb-4" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="science-tab" data-bs-toggle="tab" data-bs-target="#science"
                    type="button">
                    Science
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="commerce-tab" data-bs-toggle="tab" data-bs-target="#commerce"
                    type="button">
                    Commerce
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="arts-tab" data-bs-toggle="tab" data-bs-target="#arts" type="button">
                    Arts
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content">
            <?php foreach (['Science', 'Commerce', 'Arts'] as $stream): ?>
            <div class="tab-pane fade <?php echo $stream === 'Science' ? 'show active' : ''; ?>"
                id="<?php echo strtolower($stream); ?>">
                <div class="row">
                    <?php foreach ($careers_data[$stream] as $course): ?>
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title text-info">

                                    <?php echo htmlspecialchars($course['name']); ?>
                                </h5>
                                <p class="card-text text-muted">
                                    <?php echo htmlspecialchars($course['description']); ?>
                                </p>
                                <h6 class="mt-3">Duration</h6>
                                <p class="card-text">
                                    <?php echo htmlspecialchars($course['duration']); ?>
                                </p>
                                <h6 class="mt-3">Eligibility</h6>
                                <p class="card-text"><?php echo htmlspecialchars($course['eligibility']); ?></p>
                                <a href="course_details.php?course=<?php echo urlencode($course['name']); ?>&stream=<?php echo urlencode($stream); ?>"
                                    class="btn btn-primary mt-2">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Back Button -->
        <div class="mt-4">
            <a href="dashboard.php" class="btn btn-primary">
                Back to Dashboard
            </a>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>