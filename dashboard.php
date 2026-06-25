<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

requireLogin();

$user_id = $_SESSION['user_id'];

// Get user profile
$stmt = $conn->prepare("SELECT name, email, class FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Get latest quiz result
$stmt = $conn->prepare("SELECT suggested_stream, date FROM quiz_results WHERE user_id = ? ORDER BY date DESC LIMIT 1");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$quiz_result = $stmt->get_result()->fetch_assoc();
$stmt->close();
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard - Career Advisor</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <link rel="shortcut icon" href="favicon.ico?v=2">
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/dashboard.css" />
    <style>

    </style>
</head>

<body class="dashboard-body">

    <?php include 'includes/header.php'; ?>

    <div class="welcome-banner">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="display-5 fw-bold mb-2 ">
                        <span class="text-info">Welcome back,</span>
                        <?php echo htmlspecialchars($user['name']); ?>
                    </h1>
                    <p style="font-size: 1.1rem; color: rgba(255, 255, 255, 0.9)">
                        Continue your journey towards your perfect career path
                    </p>
                </div>
                <div class="col-md-4 text-md-end text-start mt-3 mt-md-0">
                    <p style="color: rgba(255, 255, 255, 0.7); margin-bottom: 0">
                        Class:
                        <strong><?php echo htmlspecialchars($user['class']); ?></strong>
                    </p>
                    <p style="color: rgba(255, 255, 255, 0.7); margin-bottom: 0">
                        Email:
                        <strong><?php echo htmlspecialchars($user['email']); ?></strong>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="container pb-5">
        <!-- Stats Cards -->
        <div class="row g-3 mb-5">
            <div class="col-md-6 col-lg-3">

                <div class="stats-card">

                    <div class="stats-top">
                        <i class="fas fa-user-check"></i>
                    </div>

                    <div class="stats-body">
                        <p>Profile Status</p>
                        <h3 class="text-info">Complete</h3>
                    </div>

                </div>

            </div>

            <div class="col-md-6 col-lg-3">
                <div class="stats-card">
                    <div class="stats-top">
                        <i class="fas fa-brain"></i>
                    </div>

                    <div class="stats-body">
                        <p>Quiz Status</p>
                        <h3 class="text-info"><?php echo $quiz_result ? 'Completed' : 'Pending'; ?></h3>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="stats-card">
                    <div class="stats-top">
                        <i class="fas fa-building"></i>
                    </div>

                    <div class="stats-body">
                        <p>Colleges</p>
                        <h3 class="text-info">50+</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="stats-card">
                    <div class="stats-top">
                        <i class="fas fa-book"></i>
                    </div>

                    <div class="stats-body">
                        <p>Courses</p>
                        <h3 class="text-info">100+</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile & Stream Section -->
        <h2 class="mb-4 text-info" style="font-weight: 700">Your Information</h2>
        <div class="row g-4 mb-5">
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header" style="
                background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
                border: none;
                color: white;
              ">
                        <h5 class="card-title m-0">
                            Profile Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div style="margin-bottom: 1.5rem">
                            <p style="
                    color: var(--text-secondary);
                    font-size: 0.9rem;
                    margin-bottom: 0.25rem;
                  ">
                                Full Name
                            </p>
                            <p style="font-weight: 600; font-size: 1.1rem; margin-bottom: 0">
                                <?php echo htmlspecialchars($user['name']); ?>
                            </p>
                        </div>
                        <div style="margin-bottom: 1.5rem">
                            <p style="
                    color: var(--text-secondary);
                    font-size: 0.9rem;
                    margin-bottom: 0.25rem;
                  ">
                                Email Address
                            </p>
                            <p style="font-weight: 600; font-size: 1.1rem; margin-bottom: 0">
                                <?php echo htmlspecialchars($user['email']); ?>
                            </p>
                        </div>
                        <div style="margin-bottom: 1.5rem">
                            <p style="
                    color: var(--text-secondary);
                    font-size: 0.9rem;
                    margin-bottom: 0.25rem;
                  ">
                                Current Class
                            </p>
                            <p style="font-weight: 600; font-size: 1.1rem; margin-bottom: 0">
                                <?php echo htmlspecialchars($user['class']); ?>
                            </p>
                        </div>
                        <a href="profile.php" class="btn btn-sm btn-theme">
                            Edit Profile
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header" style="
                 background:linear-gradient(135deg, #d95cec 0%, #d540ec 100%);
                border: none;
                color: white;
              ">
                        <h5 class="card-title m-0">
                            </i>Your Stream
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if ($quiz_result): ?>
                        <div style="margin-bottom: 1.5rem">
                            <p style="
                    color: var(--text-secondary);
                    font-size: 0.9rem;
                    margin-bottom: 0.25rem;
                  ">
                                Suggested Stream
                            </p>
                            <p style="font-weight: 600; font-size: 1.1rem; margin-bottom: 0">
                                <span class="badge" style="
                      background:linear-gradient(135deg, #d95cec 0%, #d540ec 100%);
                      color: white;
                      font-size: 0.9rem;
                      padding: 0.5rem 1rem;
                    ">
                                    <?php echo
                                            htmlspecialchars($quiz_result['suggested_stream']); ?>
                                </span>
                            </p>
                        </div>
                        <div style="margin-bottom: 1.5rem">
                            <p style="
                    color: var(--text-secondary);
                    font-size: 0.9rem;
                    margin-bottom: 0.25rem;
                  ">
                                Test Date
                            </p>
                            <p style="font-weight: 600; font-size: 1.1rem; margin-bottom: 0">
                                <?php echo date('M d, Y', strtotime($quiz_result['date'])); ?>
                            </p>
                        </div>
                        <p style="color: var(--text-secondary); margin-bottom: 1rem">
                            Based on your aptitude assessment, this is your ideal stream to
                            pursue.
                        </p>
                        <a href="result.php" class="btn btn-sm btn-theme">
                            View Full Results
                        </a>
                        <?php else: ?>
                        <div style="text-align: center; padding: 2rem 0">
                            <i class="fas fa-question-circle" style="
                    font-size: 2.5rem;
                    color: var(--text-secondary);
                    margin-bottom: 1rem;
                    display: block;
                  "></i>
                            <p style="color: var(--text-secondary); margin-bottom: 1.5rem">
                                You haven't taken the aptitude quiz yet.
                            </p>
                            <a href="quiz.php" class="btn btn-sm btn-theme">
                                <i class="fas fa-brain me-1"></i>Take Aptitude Quiz
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <!-- <h2 class="mb-4" style="font-weight: 700">Quick Actions</h2> -->
        <h2 class="section-title text-info">Quick Actions</h2>
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <a href="quiz.php" class="action-card">
                    <div class="action-top">
                        <i class="fas fa-brain"></i>
                    </div>

                    <div class="action-body">
                        <h5 class="text-info">Aptitude Quiz</h5>

                        <p>Test your aptitude and get stream recommendation.</p>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-lg-3">
                <a href="courses.php" class="action-card">
                    <div class="action-top">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <div class="action-body">
                        <h5 class="text-info">Explore Courses</h5>
                        <p>Discover career paths and job opportunities.</p>
                    </div>
                </a>


            </div>

            <div class="col-md-6 col-lg-3">
                <a href="colleges.php" class="action-card">
                    <div class="action-top">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="action-body">
                        <h5 class="text-info">Find Colleges</h5>
                        <p>Browse top colleges and their courses</p>
                    </div>
                </a>

            </div>

            <div class="col-md-6 col-lg-3">

                <a href="notifications.php" class="action-card">
                    <div class="action-top">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="action-body">
                        <h5 class="text-info">Updates & Dates</h5>
                        <p>Stay updated with important notifications and exam dates.</p>
                    </div>
                </a>


            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>