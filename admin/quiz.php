<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

if (!isset($_SESSION['admin_id']) || !$_SESSION['is_admin']) {
    header("Location: ../admin_login.php");
    exit();
}

$message = "";

/* DELETE */
if (isset($_GET['delete'])) {

    $id = intval($_GET['delete']);

    $stmt = $conn->prepare(
        "DELETE FROM quiz_questions WHERE id=?"
    );

    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $message = "Question deleted successfully!";
    }

    $stmt->close();
}


/* ADD / UPDATE */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $question = sanitize($_POST['question']);
    $category = sanitize($_POST['category']);

    $id = intval($_POST['id'] ?? 0);

    if ($id > 0) {

        // UPDATE

        $stmt = $conn->prepare(
            "UPDATE quiz_questions
             SET question=?, category=?
             WHERE id=?"
        );

        $stmt->bind_param(
            "ssi",
            $question,
            $category,
            $id
        );

        if ($stmt->execute()) {
            $message = "Question updated successfully!";
        }

    } else {

        // INSERT

        $stmt = $conn->prepare(
            "INSERT INTO quiz_questions
            (question, category)
            VALUES (?, ?)"
        );

        $stmt->bind_param(
            "ss",
            $question,
            $category
        );

        if ($stmt->execute()) {
            $message = "Question added successfully!";
        }

    }

    $stmt->close();
}


/* EDIT DATA */

$edit = null;

if (isset($_GET['edit'])) {

    $id = intval($_GET['edit']);

    $stmt = $conn->prepare(
        "SELECT * FROM quiz_questions WHERE id=?"
    );

    $stmt->bind_param("i", $id);

    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        $edit = $result->fetch_assoc();

    }

    $stmt->close();

}


/* FETCH ALL QUESTIONS */

$questions = [];

$result = $conn->query(
    "SELECT * FROM quiz_questions ORDER BY id DESC"
);

while ($row = $result->fetch_assoc()) {

    $questions[] = $row;

}
?>




<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Management</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../css/admin.css">
</head>

<body class="bg-light">

    <?php include 'header.php'; ?>

    <div class="container-fluid mt-4">

        <h2 class="mb-4">
            <i class="fas fa-question-circle text-primary"></i>
            Quiz Management
        </h2>

        <?php if($message): ?>
        <div class="alert alert-success">
            <?php echo $message; ?>
        </div>
        <?php endif; ?>

        <div class="row">

            <!-- FORM -->
            <div class="col-lg-4 mb-4">

                <div class="card shadow">

                    <div class="card-header bg-primary text-white">
                        <?php echo $edit ? "Edit Question" : "Add Question"; ?>
                    </div>

                    <div class="card-body">

                        <form method="POST">

                            <?php if($edit): ?>
                            <input type="hidden" name="id" value="<?php echo $edit['id']; ?>">
                            <?php endif; ?>


                            <div class="mb-3">

                                <label class="form-label">
                                    Question
                                </label>

                                <textarea name="question" class="form-control" rows="4"
                                    required><?php echo $edit['question'] ?? ''; ?></textarea>

                            </div>


                            <div class="mb-3">

                                <label class="form-label">
                                    Category
                                </label>

                                <select name="category" class="form-control" required>

                                    <option value="Science"
                                        <?php echo (isset($edit) && $edit['category']=='Science') ? 'selected' : ''; ?>>
                                        Science
                                    </option>

                                    <option value="Commerce"
                                        <?php echo (isset($edit) && $edit['category']=='Commerce') ? 'selected' : ''; ?>>
                                        Commerce
                                    </option>

                                    <option value="Arts"
                                        <?php echo (isset($edit) && $edit['category']=='Arts') ? 'selected' : ''; ?>>
                                        Arts
                                    </option>

                                </select>

                            </div>


                            <button class="btn btn-primary w-100">
                                <?php echo $edit ? "Update Question" : "Add Question"; ?>
                            </button>

                        </form>

                    </div>

                </div>

            </div>


            <!-- TABLE -->

            <div class="col-lg-8">

                <div class="card shadow">

                    <div class="card-header bg-primary text-white">
                        All Questions (<?php echo count($questions); ?>)
                    </div>

                    <div class="card-body p-0">

                        <div class="table-responsive">

                            <table class="table table-hover mb-0">

                                <thead class="table-light">

                                    <tr>
                                        <th>ID</th>
                                        <th>Question</th>
                                        <th>Category</th>
                                        <th>Action</th>
                                    </tr>

                                </thead>

                                <tbody>

                                    <?php foreach($questions as $q): ?>

                                    <tr>

                                        <td>
                                            <?php echo $q['id']; ?>
                                        </td>

                                        <td>
                                            <?php echo htmlspecialchars($q['question']); ?>
                                        </td>

                                        <td>

                                            <?php
                                $badge = "primary";

                                if ($q['category'] == "Science") {
                                    $badge = "success";
                                }
                                elseif ($q['category'] == "Commerce") {
                                    $badge = "warning";
                                }
                                elseif ($q['category'] == "Arts") {
                                    $badge = "info";
                                }
                                ?>

                                            <span class="badge bg-<?php echo $badge; ?>">
                                                <?php echo htmlspecialchars($q['category']); ?>
                                            </span>

                                        </td>

                                        <td>

                                            <a href="?edit=<?php echo $q['id']; ?>" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <a href="?delete=<?php echo $q['id']; ?>" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Delete this question?')">

                                                <i class="fas fa-trash"></i>

                                            </a>

                                        </td>

                                    </tr>

                                    <?php endforeach; ?>

                                    <?php if(count($questions)==0): ?>

                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            No Questions Found
                                        </td>
                                    </tr>

                                    <?php endif; ?>

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>



        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>