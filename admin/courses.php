<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Check admin login
if (!isset($_SESSION['admin_id']) || !$_SESSION['is_admin']) {
    header("Location: ../admin_login.php");
    exit();
}

$action = $_GET['action'] ?? '';
$message = '';
$error = '';

// Handle delete
if ($action === 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM courses WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $message = "Course deleted successfully!";
    } else {
        $error = "Error deleting course!";
    }
    $stmt->close();
}

// Handle add/edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_name = sanitize($_POST['course_name'] ?? '');
    $stream = sanitize($_POST['stream'] ?? '');
    $description = sanitize($_POST['description'] ?? '');
    $duration = sanitize($_POST['duration'] ?? '');
    $eligibility = sanitize($_POST['eligibility'] ?? '');
    $salary_range = sanitize($_POST['salary_range'] ?? '');
    $career_opportunities = sanitize($_POST['career_opportunities'] ?? '');
    $about_course = sanitize($_POST['about_course'] ?? '');
    if (empty($course_name) || empty($stream)) {
        $error = "Course name and stream are required!";
    } else {
        $course_id = intval($_POST['course_id'] ?? 0);

        if ($course_id > 0) {
            // Update
            $stmt = $conn->prepare("UPDATE courses SET course_name=?, stream=?, description=?, duration=?, eligibility=?, salary_range=?, about_course=?, career_opportunities=? WHERE id=?");
            $stmt->bind_param("sssssssss", $course_name, $stream, $description, $duration, $eligibility, $salary_range, $about_course, $career_opportunities,  $course_id);
            if ($stmt->execute()) {
                $message = "Course updated successfully!";
            } else {
                $error = "Error updating course!";
            }
        } else {
            // Insert
           $stmt = $conn->prepare("INSERT INTO courses
(course_name, stream, description, duration, eligibility, salary_range, about_course, career_opportunities)
VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param("ssssssss",
$course_name,
$stream,
$description,
$duration,
$eligibility,
$salary_range,
$about_course,
$career_opportunities);
            if ($stmt->execute()) {
                $message = "Course added successfully!";
            } else {
                $error = "Error adding course!";
            }
        }
        $stmt->close();
    }
}

// Get course for edit
$edit_course = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $stmt = $conn->prepare("SELECT * FROM courses WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $edit_course = $result->fetch_assoc();
    }
    $stmt->close();
}

// Get all courses
$courses = [];
$result = $conn->query("SELECT * FROM courses ORDER BY created_at DESC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manage Courses - Admin</title>
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
                    Manage Courses
                </h1>
            </div>
        </div>

        <?php if ($message): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php echo $message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?php echo $error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">

                            <?php echo $edit_course ? 'Edit Course' : 'Add New Course'; ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <?php if ($edit_course): ?>
                            <input type="hidden" name="course_id" value="<?php echo $edit_course['id']; ?>">
                            <?php endif; ?>

                            <div class="mb-3">
                                <label class="form-label">Course Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="course_name"
                                    value="<?php echo $edit_course ? htmlspecialchars($edit_course['course_name']) : ''; ?>"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Stream <span class="text-danger">*</span></label>
                                <select class="form-control" name="stream" required>
                                    <option value="">-- Select --</option>
                                    <option value="Science"
                                        <?php echo ($edit_course && $edit_course['stream'] === 'Science') ? 'selected' : ''; ?>>
                                        Science</option>
                                    <option value="Commerce"
                                        <?php echo ($edit_course && $edit_course['stream'] === 'Commerce') ? 'selected' : ''; ?>>
                                        Commerce</option>
                                    <option value="Arts"
                                        <?php echo ($edit_course && $edit_course['stream'] === 'Arts') ? 'selected' : ''; ?>>
                                        Arts</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description"
                                    rows="3"><?php echo $edit_course ? htmlspecialchars($edit_course['description']) : ''; ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Duration</label>
                                <input type="text" class="form-control" name="duration" placeholder="E.g: 4 Years"
                                    value="<?php echo $edit_course ? htmlspecialchars($edit_course['duration']) : ''; ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Eligibility</label>
                                <textarea class="form-control" name="eligibility"
                                    rows="2"><?php echo $edit_course ? htmlspecialchars($edit_course['eligibility']) : ''; ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Salary Range</label>
                                <input type="text" class="form-control" name="salary_range"
                                    placeholder="E.g: 5-10 Lakhs"
                                    value="<?php echo $edit_course ? htmlspecialchars($edit_course['salary_range']) : ''; ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Career Opportunities</label>
                                <textarea class="form-control" name="career_opportunities" rows="4"
                                    placeholder="Software Engineer, Data Scientist, Research Scientist"><?php echo $edit_course ? htmlspecialchars($edit_course['career_opportunities']) : ''; ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">About Course</label>
                                <textarea class="form-control" name="about_course"
                                    rows="4"><?php echo $edit_course ? htmlspecialchars($edit_course['about_course']) : ''; ?></textarea>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <?php echo $edit_course ? 'Update' : 'Add'; ?>
                                </button>
                                <?php if ($edit_course): ?>
                                <a href="courses.php" class="btn btn-secondary">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </a>
                                <?php endif; ?>
                            </div>





                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            All Courses (Total: <?php echo count($courses); ?>)
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Course Name</th>
                                        <th>Stream</th>
                                        <th>Duration</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($courses) > 0): ?>
                                    <?php foreach ($courses as $course): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo htmlspecialchars($course['course_name']); ?></strong>
                                            <br>
                                            <small
                                                class="text-muted"><?php echo substr(htmlspecialchars($course['description'] ?? ''), 0, 50); ?>...</small>
                                        </td>
                                        <td>
                                            <span
                                                class="badge bg-info"><?php echo htmlspecialchars($course['stream']); ?></span>
                                        </td>
                                        <td><?php echo htmlspecialchars($course['duration'] ?? '-'); ?></td>
                                        <td>
                                            <a href="?edit=<?php echo $course['id']; ?>" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="?action=delete&id=<?php echo $course['id']; ?>"
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirm('Are you sure you want to delete this item?');">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            No courses found
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