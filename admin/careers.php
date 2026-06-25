<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

if (!isset($_SESSION['admin_id']) || !$_SESSION['is_admin']) {
    header("Location: ../admin_login.php");
    exit();
}

$action = $_GET['action'] ?? '';
$message = '';

// Handle delete
if ($action === 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM careers WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $message = "Career deleted successfully!";
    }
    $stmt->close();
}

// Handle add/edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_id = intval($_POST['course_id'] ?? 0);
    $career_name = sanitize($_POST['career_name'] ?? '');
    $details = sanitize($_POST['details'] ?? '');
    $salary = floatval($_POST['salary'] ?? 0);
    $job_roles = sanitize($_POST['job_roles'] ?? '');

    if (empty($career_name)) {
        $error = "Career name is required!";
    } else {
        $career_id = intval($_POST['career_id'] ?? 0);
        $course_id_param = $course_id > 0 ? $course_id : null;

        if ($career_id > 0) {
            $stmt = $conn->prepare("UPDATE careers SET course_id=?, career_name=?, details=?, salary=?, job_roles=? WHERE id=?");
            $stmt->bind_param("issdsi", $course_id_param, $career_name, $details, $salary, $job_roles, $career_id);
            if ($stmt->execute()) {
        $message = "Career updated successfully!";
            }
        } else {
            $stmt = $conn->prepare("INSERT INTO careers (course_id, career_name, details, salary, job_roles) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("issds", $course_id_param, $career_name, $details, $salary, $job_roles);
            if ($stmt->execute()) {
                $message = "Career added successfully!";
            }
        }
        $stmt->close();
    }
}

$edit_career = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $stmt = $conn->prepare("SELECT * FROM careers WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $edit_career = $result->fetch_assoc();
    }
    $stmt->close();
}

// Get courses for dropdown
$courses = [];
$result = $conn->query("SELECT id, course_name FROM courses ORDER BY course_name");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }
}

// Get careers with course names
$careers = [];
$result = $conn->query("SELECT c.*, co.course_name FROM careers c LEFT JOIN courses co ON c.course_id = co.id ORDER BY c.created_at DESC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $careers[] = $row;
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manage Careers - Admin</title>
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
                    <i class="fas fa-briefcase text-primary me-2"></i>Manage Careers
                </h1>
            </div>
        </div>

        <?php if ($message): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i><?php echo $message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-plus-circle me-2"></i>
                            <?php echo isset($edit_career) ? 'Edit Career' : 'Add New Career'; ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <?php if (isset($edit_career)): ?>
                            <input type="hidden" name="career_id" value="<?php echo $edit_career['id']; ?>">
                            <?php endif; ?>

                            <div class="mb-3">
                                <label class="form-label">Course</label>
                                <select class="form-control" name="course_id">
                                    <option value="">-- Select --</option>
                                    <?php foreach ($courses as $course): ?>
                                    <option value="<?php echo $course['id']; ?>"
                                        <?php echo (isset($edit_career) && $edit_career['course_id'] == $course['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($course['course_name']); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Career Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="career_name"
                                    value="<?php echo isset($edit_career) ? htmlspecialchars($edit_career['career_name']) : ''; ?>"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Details</label>
                                <textarea class="form-control" name="details"
                                    rows="3"><?php echo isset($edit_career) ? htmlspecialchars($edit_career['details']) : ''; ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Salary (Annual)</label>
                                <input type="number" step="0.01" class="form-control" name="salary"
                                    value="<?php echo isset($edit_career) ? $edit_career['salary'] : ''; ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Job Roles</label>
                                <textarea class="form-control" name="job_roles"
                                    rows="2"><?php echo isset($edit_career) ? htmlspecialchars($edit_career['job_roles']) : ''; ?></textarea>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success">
                                    <i
                                        class="fas fa-save me-2"></i><?php echo isset($edit_career) ? 'Update' : 'Add'; ?>
                                </button>
                                <?php if (isset($edit_career)): ?>
                                <a href="careers.php" class="btn btn-secondary">
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
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2"></i>All Careers (Total: <?php echo count($careers); ?>)
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Career</th>
                                        <th>Course</th>
                                        <th>Salary</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($careers) > 0): ?>
                                    <?php foreach ($careers as $career): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo htmlspecialchars($career['career_name']); ?></strong>
                                        </td>
                                        <td>
                                            <small><?php echo $career['course_name'] ? htmlspecialchars($career['course_name']) : 'None'; ?></small>
                                        </td>
                                        <td>
                                            <?php echo $career['salary'] ? '₹ ' . number_format($career['salary']) : '-'; ?>
                                        </td>
                                        <td>
                                            <a href="?edit=<?php echo $career['id']; ?>" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="?action=delete&id=<?php echo $career['id']; ?>"
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
                                            No careers found
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