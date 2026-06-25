<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

requireLogin();

$course = sanitize($_GET['course'] ?? '');

if (empty($course)) {
    redirect('courses.php');
}

$stmt = $conn->prepare("SELECT * FROM courses WHERE course_name=?");
$stmt->bind_param("s", $course);
$stmt->execute();

$result = $stmt->get_result();
$details = $result->fetch_assoc();

if (!$details) {
    redirect('courses.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($details['course_name']); ?> - Career Advisor</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="favicon.ico?v=2">
    <link rel="stylesheet" href="css/course_details.css">
    <link rel="stylesheet" href="css/style.css">
    <style>

    </style>

</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php"> Career Advisor </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                title="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link " href="index.php">Home</a>
                    </li>

                    <?php if (isLoggedIn()): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>

                    <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">Register</a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>


    <div class="container py-5">
        <a href="courses.php" class="btn btn-primary mb-4">
            Back
        </a>

        <div class="course-header mb-4 ">
            <?php echo htmlspecialchars($course); ?>
        </div>

        <div class="row ">

            <div class="col-md-8">

                <!-- Duration -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="text-info">Duration</h5>
                        <p class="text-white">
                            <?php echo htmlspecialchars($details['duration']); ?>
                        </p>
                    </div>
                </div>

                <!-- Salary Range -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="text-info">Salary Range</h5>
                        <p class="text-white">
                            <?php echo htmlspecialchars($details['salary_range']); ?>
                        </p>
                    </div>
                </div>

                <!-- Eligibility -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="text-info">Eligibility</h5>
                        <p class="text-white">
                            <?php echo htmlspecialchars($details['eligibility']); ?>
                        </p>
                    </div>
                </div>

                <!-- Career Opportunities -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="text-info">Career Opportunities</h5>

                        <ul class="text-white">

                            <?php
                    $careers = explode(',', $details['career_opportunities']);

                    foreach ($careers as $career):
                    ?>

                            <li class="list-group-item">
                                <?php echo htmlspecialchars(trim($career)); ?>
                            </li>

                            <?php endforeach; ?>

                        </ul>

                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="text-info">About</h5>
                        <p class="text-white">
                            <?php echo nl2br(htmlspecialchars($details['about_course'])); ?>
                        </p>
                    </div>
                </div>

            </div>

            <div class="col-md-4">
                <div class="card ">
                    <div class="card-body">
                        <h5 class="text-info">Stream</h5>
                        <p class="card-text"><strong><?php echo htmlspecialchars($details['stream']); ?></strong>
                        </p>
                        <a href="colleges.php" class="btn btn-primary w-100">
                            Find Colleges
                        </a>
                    </div>
                </div>
            </div>

        </div>


    </div>

    <?php include 'includes/footer.php';?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>