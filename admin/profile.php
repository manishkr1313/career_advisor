<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

if (!isset($_SESSION['admin_id']) || !$_SESSION['is_admin']) {
    header("Location: ../admin_login.php");
    exit();
}

$user_id = intval($_GET['id'] ?? 0);

if ($user_id <= 0) {
    die("Invalid User");
}

$sql = "
SELECT
u.*,
COUNT(qr.id) AS quiz_attempts,
MAX(qr.science_score) AS science_score,
MAX(qr.commerce_score) AS commerce_score,
MAX(qr.arts_score) AS arts_score,
MAX(qr.suggested_stream) AS suggested_stream,
MAX(qr.date) AS quiz_date
FROM users u
LEFT JOIN quiz_results qr ON u.id = qr.user_id
WHERE u.id = $user_id
GROUP BY u.id";

$result = $conn->query($sql);

if($result->num_rows==0){
    die("User not found");
}

$user=$result->fetch_assoc();
?>

<!doctype html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width,initial-scale=1">

    <title>User Profile</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../css/admin.css">

</head>

<body class="bg-light">

    <?php include 'header.php'; ?>

    <div class="container mt-4">

        <div class="card shadow">

            <div class="card-header bg-primary text-white">

                <h4 class="mb-0">
                    <i class="fa fa-user"></i> User Profile
                </h4>

            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-3 text-center">

                        <?php
$image = !empty($user['profile_image'])
? "../uploads/".$user['profile_image']
: "../assets/default.png";
?>

                        <img src="<?= $image ?>" class="img-fluid rounded-circle border"
                            style="width:180px;height:180px;object-fit:cover;">

                        <h4 class="mt-3">
                            <?= htmlspecialchars($user['name']) ?>
                        </h4>

                    </div>

                    <div class="col-md-9">

                        <table class="table table-bordered">

                            <tr>
                                <th width="30%">Name</th>
                                <td><?= htmlspecialchars($user['name']) ?></td>
                            </tr>

                            <tr>
                                <th>Email</th>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                            </tr>

                            <tr>
                                <th>Mobile</th>
                                <td><?= $user['mobile'] ?: "-" ?></td>
                            </tr>

                            <tr>
                                <th>Gender</th>
                                <td><?= $user['gender'] ?: "-" ?></td>
                            </tr>

                            <tr>
                                <th>Date of Birth</th>
                                <td><?= $user['dob'] ?: "-" ?></td>
                            </tr>

                            <tr>
                                <th>Class</th>
                                <td><?= $user['class'] ?></td>
                            </tr>

                            <tr>
                                <th>School</th>
                                <td><?= $user['school_name'] ?: "-" ?></td>
                            </tr>

                            <tr>
                                <th>State</th>
                                <td><?= $user['state'] ?: "-" ?></td>
                            </tr>


                            <tr>
                                <th>Suggested Stream</th>
                                <td>

                                    <span class="badge bg-danger fs-6">

                                        <?= $user['suggested_stream'] ?? "-" ?>

                                    </span>

                                </td>
                            </tr>


                        </table>

                        <a href="users.php" class="btn btn-secondary">

                            <i class="fa fa-arrow-left"></i> Back

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>