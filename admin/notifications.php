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
    $stmt = $conn->prepare("DELETE FROM notifications WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $message = "Notification deleted successfully!";
    }
    $stmt->close();
}

// Handle add/edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitize($_POST['title'] ?? '');
    $description = sanitize($_POST['description'] ?? '');
    $notification_date = sanitize($_POST['notification_date'] ?? '');
    $notification_type = sanitize($_POST['notification_type'] ?? '');
    $deadline_date = sanitize($_POST['deadline_date'] ?? '');
    $importance = intval($_POST['importance'] ?? 1);

    if (empty($title) || empty($notification_date)) {
        $error = "Title and date are required!";
    } else {
        $notif_id = intval($_POST['notification_id'] ?? 0);
        $deadline_date_param = !empty($deadline_date) ? $deadline_date : null;

        if ($notif_id > 0) {
            $stmt = $conn->prepare("UPDATE notifications SET title=?, description=?, notification_date=?, notification_type=?, deadline_date=?, importance=? WHERE id=?");
            $stmt->bind_param("sssssii", $title, $description, $notification_date, $notification_type, $deadline_date_param, $importance, $notif_id);
            if ($stmt->execute()) {
                $message = "Notification updated successfully!";
            }
        } else {
            $stmt = $conn->prepare("INSERT INTO notifications (title, description, notification_date, notification_type, deadline_date, importance) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssi", $title, $description, $notification_date, $notification_type, $deadline_date_param, $importance);
            if ($stmt->execute()) {
                $message = "Notification added successfully!";
            }
        }
        $stmt->close();
    }
}

$edit_notif = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $stmt = $conn->prepare("SELECT * FROM notifications WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $edit_notif = $result->fetch_assoc();
    }
    $stmt->close();
}

$notifications = [];
$result = $conn->query("SELECT * FROM notifications ORDER BY notification_date DESC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $notifications[] = $row;
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manage Notifications - Admin</title>
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
                    <i class="fas fa-bell text-primary me-2"></i>Manage Notifications
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
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-plus-circle me-2"></i>
                            <?php echo isset($edit_notif) ? 'Edit Notification' : 'Add New Notification'; ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <?php if (isset($edit_notif)): ?>
                            <input type="hidden" name="notification_id" value="<?php echo $edit_notif['id']; ?>">
                            <?php endif; ?>

                            <div class="mb-3">
                                <label class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="title"
                                    value="<?php echo isset($edit_notif) ? htmlspecialchars($edit_notif['title']) : ''; ?>"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description"
                                    rows="3"><?php echo isset($edit_notif) ? htmlspecialchars($edit_notif['description']) : ''; ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="notification_date"
                                    value="<?php echo isset($edit_notif) ? $edit_notif['notification_date'] : ''; ?>"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Type</label>
                                <select class="form-control" name="notification_type">
                                    <option value="">-- Select --</option>
                                    <option value="exam"
                                        <?php echo (isset($edit_notif) && $edit_notif['notification_type'] === 'exam') ? 'selected' : ''; ?>>
                                        Exam
                                    </option>
                                    <option value="admission"
                                        <?php echo (isset($edit_notif) && $edit_notif['notification_type'] === 'admission') ? 'selected' : ''; ?>>
                                        Admission
                                    </option>
                                    <option value="event"
                                        <?php echo (isset($edit_notif) && $edit_notif['notification_type'] === 'event') ? 'selected' : ''; ?>>
                                        Event</option>
                                    <option value="other"
                                        <?php echo (isset($edit_notif) && $edit_notif['notification_type'] === 'other') ? 'selected' : ''; ?>>
                                        Other</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Deadline (Optional)</label>
                                <input type="date" class="form-control" name="deadline_date"
                                    value="<?php echo isset($edit_notif) && $edit_notif['deadline_date'] ? $edit_notif['deadline_date'] : ''; ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Importance</label>
                                <select class="form-control" name="importance">
                                    <option value="1"
                                        <?php echo (isset($edit_notif) && $edit_notif['importance'] == 1) ? 'selected' : ''; ?>>
                                        Low</option>
                                    <option value="2"
                                        <?php echo (isset($edit_notif) && $edit_notif['importance'] == 2) ? 'selected' : ''; ?>>
                                        Medium</option>
                                    <option value="3"
                                        <?php echo (isset($edit_notif) && $edit_notif['importance'] == 3) ? 'selected' : ''; ?>>
                                        High</option>
                                </select>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-save me-2"></i><?php echo isset($edit_notif) ? 'Update' : 'Add'; ?>
                                </button>
                                <?php if (isset($edit_notif)): ?>
                                <a href="notifications.php" class="btn btn-secondary">
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
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2"></i>All Notifications (Total:
                            <?php echo count($notifications); ?>)
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Title</th>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Importance</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($notifications) > 0): ?>
                                    <?php foreach ($notifications as $notif): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo htmlspecialchars($notif['title']); ?></strong>
                                        </td>
                                        <td><?php echo date('d M Y', strtotime($notif['notification_date'])); ?></td>
                                        <td>
                                            <small
                                                class="badge bg-info"><?php echo htmlspecialchars($notif['notification_type'] ?? '-'); ?></small>
                                        </td>
                                        <td>
                                            <?php
                                                    $importance_text = ['', 'Low', 'Medium', 'High'];
                                                    $importance_color = ['', 'success', 'warning', 'danger'];
                                                    ?>
                                            <span
                                                class="badge bg-<?php echo $importance_color[$notif['importance']]; ?>">
                                                <?php echo $importance_text[$notif['importance']]; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="?edit=<?php echo $notif['id']; ?>" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="?action=delete&id=<?php echo $notif['id']; ?>"
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirm('Are you sure you want to delete this item?');">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            No notifications found
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