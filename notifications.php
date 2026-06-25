<?php
require_once 'includes/functions.php';
require_once 'includes/db.php';

requireLogin();

$notifications = [];

$result = $conn->query("
    SELECT *
    FROM notifications
    ORDER BY importance DESC, notification_date DESC
");

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $notifications[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Important Dates & Notifications - Career Advisor</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/notification.css">
</head>

<body>

    <?php include 'includes/header.php'; ?>

    <div class="container py-5">

        <h1 class="mb-4 text-info">
            Important Dates & Notifications
        </h1>

        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs mb-4" role="tablist">

            <li class="nav-item">
                <button class="nav-link active filter-btn" data-filter="all" type="button">
                    All
                </button>
            </li>

            <li class="nav-item">
                <button class="nav-link filter-btn" data-filter="exam" type="button">
                    Exam
                </button>
            </li>

            <li class="nav-item">
                <button class="nav-link filter-btn" data-filter="admission" type="button">
                    Admission
                </button>
            </li>

            <li class="nav-item">
                <button class="nav-link filter-btn" data-filter="event" type="button">
                    Event
                </button>
            </li>

            <li class="nav-item">
                <button class="nav-link filter-btn" data-filter="other" type="button">
                    Other
                </button>
            </li>

        </ul>

        <div class="row">

            <?php if(count($notifications)>0): ?>

            <?php foreach($notifications as $notif): ?>

            <?php

                $type = strtolower($notif['notification_type']);

                switch($type){
                    case 'exam':
                        $color='danger';
                        break;

                    case 'admission':
                        $color='success';
                        break;

                    case 'event':
                        $color='warning';
                        break;

                    default:
                        $color='secondary';
                }

                $importanceText = [
                    1 => 'Low',
                    2 => 'Medium',
                    3 => 'High'
                ];

                $importanceColor = [
                    1 => 'success',
                    2 => 'warning',
                    3 => 'danger'
                ];

                ?>

            <div class="col-md-6 mb-4 notification-card"
                data-type="<?php echo strtolower($notif['notification_type']); ?>">

                <div class="card h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center">

                            <h5 class="notification-title">
                                <?php echo htmlspecialchars($notif['title']); ?>
                            </h5>

                            <span class="badge bg-primary">
                                <?php echo ucfirst($notif['notification_type']); ?>
                            </span>

                        </div>

                        <p class="notification-desc mt-3">
                            <?php echo htmlspecialchars($notif['description']); ?>
                        </p>

                        <div class="mt-3">
                            <small class="notification-date">
                                📅 <?php echo date('d M Y', strtotime($notif['notification_date'])); ?>
                            </small>
                        </div>

                        <?php if(!empty($notif['deadline_date'])): ?>
                        <div class="mt-2">
                            <small class="text-danger">
                                ⏰ Deadline:
                                <?php echo date('d M Y', strtotime($notif['deadline_date'])); ?>
                            </small>
                        </div>
                        <?php endif; ?>

                    </div>

                </div>

            </div>

            <?php endforeach; ?>

            <?php else: ?>

            <div class="col-12">

                <div class="alert alert-info text-center">
                    No notifications available.
                </div>

            </div>

            <?php endif; ?>

        </div>

        <div class="mt-4">
            <a href="dashboard.php" class="btn btn-primary back-btn">
                Back to Dashboard
            </a>
        </div>

    </div>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <script>
    document.querySelectorAll(".filter-btn").forEach(btn => {

        btn.addEventListener("click", function() {

            // active class remove
            document.querySelectorAll(".filter-btn").forEach(tab => {
                tab.classList.remove("active");
            });

            // active class add
            this.classList.add("active");

            // filter cards
            let filter = this.dataset.filter;

            document.querySelectorAll(".notification-card").forEach(card => {

                if (filter === "all" || card.dataset.type === filter) {
                    card.style.display = "";
                } else {
                    card.style.display = "none";
                }

            });

        });

    });
    </script>

</body>

</html>