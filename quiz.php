<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

requireLogin();

$user_id = $_SESSION['user_id'];

// Quiz questions
$questions = []; $result = $conn->query("SELECT * FROM quiz_questions ORDER BY id ASC"); while ($row = $result->fetch_assoc()) { $questions[] = $row; }
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Aptitude Quiz | Career Advisor</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet" />
    <link rel="shortcut icon" href="favicon.ico?v=2">
    <link rel="stylesheet" href="css/quiz.css" />
    <link rel="stylesheet" href="css/style.css" />
</head>

<body>
    <?php include 'includes/header.php'; ?>

    <div class="container py-5">
        <div class="text-center mb-5">
            <h1 class="text-info"><span>Aptitude</span> Quiz</h1>

            <p class="page-subtitle">
                Answer every question honestly to discover the most suitable career
                stream.
            </p>
        </div>

        <form method="POST" action="process_quiz.php" id="quizForm">
            <div class="row">
                <div class="col-lg-8">
                    <!-- QUESTIONS SECTION -->
                    <?php foreach ($questions as $index => $q): ?>

                    <div class="glass-card mb-4">
                        <div class="card-header card-header-gradient">
                            Question <?php echo $index + 1; ?>
                        </div>

                        <div class="card-body">

                            <h5 class="fw-semibold mb-4 text-info">
                                <?php echo htmlspecialchars($q['question']); ?>
                            </h5>

                            <!-- Hidden Question ID -->
                            <input type="hidden" name="question_id[]" value="<?php echo $q['id']; ?>">

                            <!-- Agree -->
                            <label class="quiz-option mb-3 d-flex align-items-center">
                                <input class="form-check-input" type="radio" name="q<?php echo $index; ?>" value="agree"
                                    required>

                                <span class="ms-2">Agree</span>
                            </label>

                            <!-- Disagree -->
                            <label class="quiz-option mb-3 d-flex align-items-center">
                                <input class="form-check-input" type="radio" name="q<?php echo $index; ?>"
                                    value="disagree" required>

                                <span class="ms-2">Disagree</span>
                            </label>

                        </div>
                    </div>

                    <?php endforeach; ?>

                </div>
                <!-- col-lg-8 close -->

                <div class="col-lg-4">
                    <div class="glass-card sticky-top" style="top: 100px">
                        <div class="card-header card-header-gradient">
                            Quiz Information
                        </div>

                        <div class="card-body quiz-info">
                            <p>
                                <strong>Total Questions :</strong>

                                <?php echo count($questions); ?>
                            </p>

                            <p>
                                <strong>Time Limit :</strong>

                                No Limit
                            </p>

                            <p>
                                <strong>Result :</strong>

                                Instant
                            </p>

                            <p>
                                <strong>Difficulty :</strong>

                                Medium
                            </p>

                            <hr style="border-color: rgba(255, 255, 255, 0.08)" />

                            <p>
                                This assessment analyzes your interests and recommends the
                                most suitable stream for your future career.
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="question-count">
                                    <?php echo count($questions); ?> Questions
                                </div>

                                <div>
                                    <a href="result.php" class="btn btn-sm submit-btn">
                                        Results
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- row close -->

            <!-- SUBMIT BUTTONS -->

            <div class="text-center mt-5">
                <button type="submit" class="btn submit-btn me-3 mb-3">
                    Submit Quiz
                </button>

                <a href="dashboard.php" class="btn cancel-btn mb-3"> Cancel </a>
            </div>
        </form>
    </div>
    <!-- container close -->

    <!-- FOOTER -->
    <?php include 'includes/footer.php'; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>