<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

requireLogin();

$user_id = $_SESSION['user_id'];

// Get latest quiz result
$stmt = $conn->prepare("SELECT science_score, commerce_score, arts_score,
suggested_stream, date FROM quiz_results WHERE user_id = ? ORDER BY id DESC
LIMIT 1"); $stmt->bind_param("i", $user_id); $stmt->execute(); $result =
$stmt->get_result(); if ($result->num_rows === 0) { redirect('quiz.php'); }
$quiz_data = $result->fetch_assoc(); $stmt->close(); // Define career paths
$career_paths = [ "Science" => [ "courses" => ["BSc Physics", "BSc Chemistry",
"BSc Biology", "BTech Engineering", "MBBS", "BDS"], "careers" => ["Engineer",
"Doctor", "Scientist", "Researcher", "Pharmacist", "Veterinarian"] ], "Commerce"
=> [ "courses" => ["B.Com", "BBA", "CA", "CS", "CMA"], "careers" =>
["Accountant", "Business Analyst", "Finance Manager", "Entrepreneur", "Stock
Broker"] ], "Arts" => [ "courses" => ["BA English", "BA History", "BA
Geography", "BA Psychology", "BA Journalism"], "careers" => ["Journalist",
"Lawyer", "Psychologist", "Teacher", "Civil Servant", "Graphic Designer"] ] ];
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Quiz Result - Career Advisor</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="shortcut icon" href="favicon.ico?v=2" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/result.css" />
</head>

<body>
    <?php include 'includes/header.php'; ?>

    <div class="container py-5">
        <h1 class="page-title">
            <span class="text-info">Your</span> Quiz Result
        </h1>

        <!-- Recommended Stream -->

        <div class="row mb-5">
            <div class="col-lg-12">
                <div class="glass-card">
                    <div class="card-header card-header-gradient">
                        Recommended Stream
                    </div>

                    <div class="card-body text-center">
                        <div class="stream-title">
                            Based on your quiz answers, the best stream for you is
                        </div>

                        <div class="stream-name">
                            <?php echo htmlspecialchars($quiz_data['suggested_stream']); ?>
                        </div>

                        <div class="result-badge">Best Choice For Your Future</div>

                        <p class="stream-desc">
                            This recommendation is generated from your aptitude, interests
                            and personality assessment.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- NEXT PART 2 STARTS FROM HERE -->
        <!-- Score Cards -->

        <!-- Score Cards -->

        <div class="row g-4 mb-5">
            <div class="col-lg-4 col-md-6">
                <div class="glass-card">
                    <div class="card-header card-header-gradient">Science Score</div>

                    <div class="card-body text-center">
                        <h1 class="fw-bold">
                            <?php echo intval($quiz_data['science_score']); ?>
                        </h1>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="glass-card">
                    <div class="card-header card-header-gradient">Commerce Score</div>

                    <div class="card-body text-center">
                        <h1 class="fw-bold">
                            <?php echo intval($quiz_data['commerce_score']); ?>
                        </h1>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="glass-card">
                    <div class="card-header card-header-gradient">Arts Score</div>

                    <div class="card-body text-center">
                        <h1 class="fw-bold">
                            <?php echo intval($quiz_data['arts_score']); ?>
                        </h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- Courses & Careers -->

        <div class="row g-4 mb-5">
            <div class="col-lg-6">
                <div class="glass-card h-100">
                    <div class="card-header card-header-gradient">
                        Suggested Courses
                    </div>

                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <?php foreach
                ($career_paths[$quiz_data['suggested_stream']]['courses'] as
                $course): ?>

                            <li class="list-group-item bg-transparent text-white border-secondary">
                                <?php echo htmlspecialchars($course); ?>
                            </li>

                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="glass-card h-100">
                    <div class="card-header card-header-gradient">
                        Career Opportunities
                    </div>

                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <?php foreach
                ($career_paths[$quiz_data['suggested_stream']]['careers'] as
                $career): ?>

                            <li class="list-group-item bg-transparent text-white border-secondary">
                                <?php echo htmlspecialchars($career); ?>
                            </li>

                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- PART 3 STARTS HERE -->

        <!-- Action Buttons -->

        <div class="text-center mb-5">
            <a href="career.php" class="btn result-btn me-3 mb-3">
                Explore Careers
            </a>

            <a href="colleges.php" class="btn result-btn me-3 mb-3">
                Find Colleges
            </a>

            <a href="dashboard.php" class="btn result-btn me-3 mb-3"> Dashboard </a>

            <a href="quiz.php" class="btn result-btn mb-3"> Retake Quiz </a>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>