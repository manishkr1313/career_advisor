<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

requireLogin();

$user_id = $_SESSION['user_id'];

// Initialize scores
$science_score = 0;
$commerce_score = 0;
$arts_score = 0;

// Fetch all questions
$questions = [];

$result = $conn->query("SELECT * FROM quiz_questions ORDER BY id ASC");

while ($row = $result->fetch_assoc()) {
    $questions[] = $row;
}

// Calculate scores based on answers
foreach ($questions as $index => $q) {

    $answer = $_POST["q$index"] ?? '';

    if ($answer == "agree") {

        if ($q['category'] == "Science") {

            $science_score++;

        } elseif ($q['category'] == "Commerce") {

            $commerce_score++;

        } elseif ($q['category'] == "Arts") {

            $arts_score++;

        }

    }

}

// Determine suggested stream
$suggested_stream = getStreamFromScore(
    $science_score,
    $commerce_score,
    $arts_score
);

// Save result
$stmt = $conn->prepare("
INSERT INTO quiz_results
(user_id, science_score, commerce_score, arts_score, suggested_stream)
VALUES (?, ?, ?, ?, ?)
");

$stmt->bind_param(
    "iddds",
    $user_id,
    $science_score,
    $commerce_score,
    $arts_score,
    $suggested_stream
);

if ($stmt->execute()) {

    $_SESSION['last_quiz_result'] = [

        'science_score' => $science_score,
        'commerce_score' => $commerce_score,
        'arts_score' => $arts_score,
        'suggested_stream' => $suggested_stream

    ];

    header("Location: result.php");
    exit();

} else {

    showError("Error saving quiz results!");
    header("Location: quiz.php");
    exit();

}

$stmt->close();
$conn->close();
?>