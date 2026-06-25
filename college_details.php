<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

requireLogin();

$college_id = intval($_GET['id'] ?? 0);

$stmt = $conn->prepare("SELECT * FROM colleges WHERE id = ?");
$stmt->bind_param("i", $college_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    redirect('colleges.php');
}

$college = $result->fetch_assoc();

$courses = explode(',', $college['courses_available']);
$facilities = explode(',', $college['facilities']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($college['college_name']); ?> - Career Advisor</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="favicon.ico?v=2">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/college_details.css">
</head>

<body>
    <?php include 'includes/header.php'; ?>

    <div class="container py-5">

        <a href="colleges.php" class="btn btn-primary mb-4">
            Back
        </a>

        <div class="college-banner mb-4">

            <img src="<?php echo !empty($college['image']) ? htmlspecialchars($college['image']) : 'img/college1.jpg'; ?>"
                class="college-detail-img">
            <div class="college-overlay">
                <h1><?php echo htmlspecialchars($college['college_name']); ?></h1>

                <p>
                    📍 <?php echo htmlspecialchars($college['location']); ?>
                    | Established:
                    <?php echo htmlspecialchars($college['established_year']); ?>
                </p>
            </div>

        </div>

        <div class="row">

            <div class="col-md-8">

                <!-- About -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="text-info">About</h5>
                        <p class="text-white">
                            <?php echo htmlspecialchars($college['about_college']); ?>
                        </p>
                    </div>
                </div>

                <!-- Courses -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="text-info">Courses & Departments</h5>

                        <ul class="list-group list-group-flush">

                            <?php foreach ($courses as $course): ?>

                            <li class="list-group-item">
                                <?php echo htmlspecialchars(trim($course)); ?>
                            </li>

                            <?php endforeach; ?>

                        </ul>
                    </div>
                </div>

                <!-- Facilities -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="text-info">Facilities</h5>

                        <ul class="list-group list-group-flush">

                            <?php foreach ($facilities as $facility): ?>

                            <li class="list-group-item">
                                <?php echo htmlspecialchars(trim($facility)); ?>
                            </li>

                            <?php endforeach; ?>

                        </ul>
                    </div>
                </div>

                <!-- Placement -->
                <!-- <div class="card">
                    <div class="card-body">
                        <h5 class="text-info">Placement</h5>

                        <p class="text-white">
                            Placement Percentage :
                            <?php echo htmlspecialchars($college['placement_percentage']); ?>%
                        </p>
                    </div>
                </div> -->

            </div>

            <!-- Right Side -->
            <div class="col-md-4">

                <div class="card">
                    <div class="card-body">

                        <h5 class="text-info ">College Overview</h5>

                        <ul class="list-unstyled">
                            <!-- 
                            <li>
                                <strong style="color: red;">Location:</strong>
                                <?php echo htmlspecialchars($college['location']); ?>
                            </li>

                            <li>
                                <strong>Established:</strong>
                                <?php echo htmlspecialchars($college['established_year']); ?>
                            </li>

                            <li>
                                <strong>Website:</strong>
                                <span>
                                    <a href=" <?php echo htmlspecialchars($college['website']); ?>" target="_blank"
                                        class="text-decoration-none text-primary">
                                        Visit Website
                                    </a>
                                </span>
                            </li>

                            <li>
                                <strong>Email:</strong>
                                <?php echo htmlspecialchars($college['email']); ?>
                            </li>

                            <li>
                                <strong>Phone:</strong>
                                <?php echo htmlspecialchars($college['phone']); ?>
                            </li> -->


                            <li class="mb-2">
                                <strong>Location:</strong>
                                <span class="text-light">
                                    <?php echo htmlspecialchars($college['location']); ?>
                                </span>
                            </li>

                            <li class="mb-2">
                                <strong>Established:</strong>
                                <span class="text-light">
                                    <?php echo htmlspecialchars($college['established_year']); ?>
                                </span>
                            </li>

                            <li class="mb-2">
                                <strong>Website:</strong>
                                <span class="text-light">
                                    <a href="<?php echo htmlspecialchars($college['website']); ?>" target="_blank"
                                        class="text-decoration-none text-primary">
                                        Visit Website
                                    </a>
                                </span>
                            </li>

                            <li class="mb-2">
                                <strong>Email:</strong>
                                <span class="text-light">
                                    <?php echo htmlspecialchars($college['email']); ?>
                                </span>
                            </li>

                            <li class="mb-2">
                                <strong>Phone:</strong>
                                <span class="text-light">
                                    <?php echo htmlspecialchars($college['phone']); ?>
                                </span>
                            </li>
                        </ul>

                        <button class="btn btn-primary w-100 mt-3">
                            Apply Now
                        </button>

                    </div>
                </div>

            </div>

        </div>

    </div>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

</body>

</html>