<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

if (!isset($_SESSION['admin_id']) || !$_SESSION['is_admin']) {
    header("Location: ../admin_login.php");
    exit();
}

$action = $_GET['action'] ?? '';
$message = '';
$error = '';

// Delete
if ($action === 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $conn->prepare("DELETE FROM colleges WHERE id=?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $message = "College deleted successfully!";
    } else {
        $error = "Delete Error: " . $stmt->error;
    }

    $stmt->close();
}

// Add / Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $college_name = sanitize($_POST['college_name'] ?? '');
    $location = sanitize($_POST['location'] ?? '');
    $established_year = intval($_POST['established_year'] ?? 0);
    $about_college = sanitize($_POST['about_college'] ?? '');
    $courses_available = sanitize($_POST['courses_available'] ?? '');
    $cutoff = sanitize($_POST['cutoff'] ?? '');
    $facilities = sanitize($_POST['facilities'] ?? '');
    $placement_percentage = floatval($_POST['placement_percentage'] ?? 0);
    $website = sanitize($_POST['website'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    $image = '';

if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {

    $upload_dir = '../uploads/';

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $image_name = time() . '_' . basename($_FILES['image']['name']);

    move_uploaded_file(
        $_FILES['image']['tmp_name'],
        $upload_dir . $image_name
    );

    $image = 'uploads/' . $image_name;
}

    if (empty($college_name) || empty($location)) {

        $error = "College name and location are required!";

    } else {

        $college_id = intval($_POST['college_id'] ?? 0);

        // UPDATE
        if ($college_id > 0) {

            $stmt = $conn->prepare("
                UPDATE colleges SET
                college_name=?,
                location=?,
                established_year=?,
                about_college=?,
                courses_available=?,
                cutoff=?,
                facilities=?,
                placement_percentage=?,
                website=?,
                email=?,
                phone=?,
                image=?
                WHERE id=?
            ");

            $stmt->bind_param(
                "ssissssdssssi",
                $college_name,
                $location,
                $established_year,
                $about_college,
                $courses_available,
                $cutoff,
                $facilities,
                $placement_percentage,
                $website,
                $email,
                $phone,
                $image,
                $college_id
            );

            if ($stmt->execute()) {
                $message = "College updated successfully!";
            } else {
                $error = "Update Error: " . $stmt->error;
            }

        }

        // INSERT
        else {

            $stmt = $conn->prepare("
                INSERT INTO colleges
                (
                    college_name,
                    location,
                    established_year,
                    about_college,
                    courses_available,
                    cutoff,
                    facilities,
                    placement_percentage,
                    website,
                    email,
                    phone,
                    image
                )
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            $stmt->bind_param(
                "ssissssdssss",
                $college_name,
                $location,
                $established_year,
                $about_college,
                $courses_available,
                $cutoff,
                $facilities,
                $placement_percentage,
                $website,
                $email,
                $phone,
                $image
            );

            if ($stmt->execute()) {
                $message = "College added successfully!";
            } else {
                // $error = "Insert Error: " . $stmt->error;
                die("Insert Error: " . $stmt->error);

            }
        }

        $stmt->close();
    }
}

// Edit college
$edit_college = null;

if (isset($_GET['edit'])) {

    $id = intval($_GET['edit']);

    $stmt = $conn->prepare("SELECT * FROM colleges WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $edit_college = $result->fetch_assoc();
    }

    $stmt->close();
}

// College list
$colleges = [];

$result = $conn->query("SELECT * FROM colleges ORDER BY created_at DESC");

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $colleges[] = $row;
    }
}
?>







<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manage Colleges - Admin</title>
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
                    Manage Colleges
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
                    <div class="card-header bg-warning text-white">
                        <h5 class="mb-0">

                            <?php echo $edit_college ? 'Edit College' : 'Add New College'; ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <?php if ($edit_college): ?>
                            <input type="hidden" name="college_id" value="<?php echo $edit_college['id']; ?>">
                            <?php endif; ?>

                            <div class="mb-3">
                                <label class="form-label">College Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="college_name"
                                    value="<?php echo $edit_college ? htmlspecialchars($edit_college['college_name']) : ''; ?>"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Location <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="location"
                                    value="<?php echo $edit_college ? htmlspecialchars($edit_college['location']) : ''; ?>"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Established Year</label>
                                <input type="number" class="form-control" name="established_year"
                                    value="<?php echo $edit_college ? $edit_college['established_year'] : ''; ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">About College</label>
                                <textarea class="form-control" name="about_college"
                                    rows="2"><?php echo $edit_college ? htmlspecialchars($edit_college['about_college']) : ''; ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Available Courses</label>
                                <textarea class="form-control" name="courses_available"
                                    rows="2"><?php echo $edit_college ? htmlspecialchars($edit_college['courses_available']) : ''; ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Cutoff</label>
                                <input type="text" class="form-control" name="cutoff"
                                    value="<?php echo $edit_college ? htmlspecialchars($edit_college['cutoff']) : ''; ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Facilities</label>
                                <textarea class="form-control" name="facilities"
                                    rows="2"><?php echo $edit_college ? htmlspecialchars($edit_college['facilities']) : ''; ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Placement Percentage</label>
                                <input type="number" step="0.01" class="form-control" name="placement_percentage"
                                    value="<?php echo $edit_college ? $edit_college['placement_percentage'] : ''; ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Website</label>
                                <input type="url" class="form-control" name="website"
                                    value="<?php echo $edit_college ? htmlspecialchars($edit_college['website']) : ''; ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email"
                                    value="<?php echo $edit_college ? htmlspecialchars($edit_college['email']) : ''; ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" class="form-control" name="phone"
                                    value="<?php echo $edit_college ? htmlspecialchars($edit_college['phone']) : ''; ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">College Image</label>
                                <input type="file" class="form-control" name="image" accept="image/*">
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-warning">
                                    <?php echo $edit_college ? 'Update' : 'Add'; ?>
                                </button>
                                <?php if ($edit_college): ?>
                                <a href="colleges.php" class="btn btn-secondary">
                                    Cancel
                                </a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-warning text-white">
                        <h5 class="mb-0">
                            All Colleges (Total: <?php echo count($colleges); ?>)
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>College Name</th>
                                        <th>Location</th>
                                        <th>Cutoff</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($colleges) > 0): ?>
                                    <?php foreach ($colleges as $college): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo htmlspecialchars($college['college_name']); ?></strong>
                                        </td>
                                        <td><?php echo htmlspecialchars($college['location']); ?></td>
                                        <td><?php echo htmlspecialchars($college['cutoff'] ?? '-'); ?></td>
                                        <td>
                                            <a href="?edit=<?php echo $college['id']; ?>"
                                                class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="?action=delete&id=<?php echo $college['id']; ?>"
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
                                            No colleges found
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