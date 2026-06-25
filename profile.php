<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

requireLogin();
$success = "";

if(isset($_SESSION['success'])){
    $success = $_SESSION['success'];
    unset($_SESSION['success']);
}

$user_id = $_SESSION['user_id'];

$error = "";




$stmt = $conn->prepare("
SELECT *
FROM users
WHERE id=?
");

$stmt->bind_param("i", $user_id);
$stmt->execute();

$user = $stmt->get_result()->fetch_assoc();

$stmt->close();




$stmt = $conn->prepare("
SELECT COUNT(*) total_attempts
FROM quiz_results
WHERE user_id=?
");

$stmt->bind_param("i", $user_id);
$stmt->execute();

$quiz_count = $stmt->get_result()->fetch_assoc()['total_attempts'];

$stmt->close();




$stmt = $conn->prepare("
SELECT suggested_stream,date
FROM quiz_results
WHERE user_id=?
ORDER BY id DESC
LIMIT 1
");

$stmt->bind_param("i", $user_id);
$stmt->execute();

$latest_result = $stmt->get_result()->fetch_assoc();

$stmt->close();




if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name = sanitize($_POST['name']);
    $mobile = sanitize($_POST['mobile']);
    $gender = sanitize($_POST['gender']);
    $dob = !empty($_POST['dob']) ? $_POST['dob'] : NULL;
    $class = sanitize($_POST['class']);
    $school_name = sanitize($_POST['school_name']);
    $state = sanitize($_POST['state']);
    $preferred_stream = sanitize($_POST['preferred_stream']);

    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

   

    $profile_image = $user['profile_image'];

    if (
        isset($_FILES['profile_image']) &&
        $_FILES['profile_image']['error'] == 0
    ) {

        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        $ext = strtolower(
            pathinfo(
                $_FILES['profile_image']['name'],
                PATHINFO_EXTENSION
            )
        );

        if (in_array($ext, $allowed)) {

            $newImageName = time() . "_" . $user_id . "." . $ext;

            move_uploaded_file(
                $_FILES['profile_image']['tmp_name'],
                "uploads/" . $newImageName
            );

            $profile_image = $newImageName;
        }
    }


    if (!empty($new_password)) {

        if (!password_verify($current_password, $user['password'])) {

            $error = "Current password is incorrect.";

        } elseif ($new_password != $confirm_password) {

            $error = "Passwords do not match.";

        } else {

            $hashed_password = password_hash(
                $new_password,
                PASSWORD_DEFAULT
            );

            $stmt = $conn->prepare("
            UPDATE users
            SET
            name=?,
            mobile=?,
            gender=?,
            dob=?,
            class=?,
            school_name=?,
            state=?,
            preferred_stream=?,
            profile_image=?,
            password=?
            WHERE id=?
            ");

            $stmt->bind_param(
                "ssssssssssi",
                $name,
                $mobile,
                $gender,
                $dob,
                $class,
                $school_name,
                $state,
                $preferred_stream,
                $profile_image,
                $hashed_password,
                $user_id
            );

        }

    } else {

        $stmt = $conn->prepare("
        UPDATE users
        SET
        name=?,
        mobile=?,
        gender=?,
        dob=?,
        class=?,
        school_name=?,
        state=?,
        preferred_stream=?,
        profile_image=?
        WHERE id=?
        ");

        $stmt->bind_param(
            "sssssssssi",
            $name,
            $mobile,
            $gender,
            $dob,
            $class,
            $school_name,
            $state,
            $preferred_stream,
            $profile_image,
            $user_id
        );
    }

    if (empty($error)) {

    if ($stmt->execute()) {

    $_SESSION['success'] = "Your profile has been updated successfully.";

    header("Location: profile.php");
    exit();

} else {

    $error = "Something went wrong.";
}
        }

}


?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile | Career Advisor</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="favicon.ico?v=2">
    <link rel="stylesheet" href="css/profiles.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <?php include 'includes/header.php'; ?>

    <div class="container py-5">

        <form method="POST" enctype="multipart/form-data">

            <!-- PROFILE HEADER -->

            <div class="profile-header mb-4">

                <div class="profile-left">

                    <div class="profile-avatar">

                        <?php if (!empty($user['profile_image'])): ?>

                        <img src="uploads/<?php echo $user['profile_image']; ?>" alt="Profile">

                        <?php else: ?>

                        <img src="assets/default-user.png" alt="Profile">

                        <?php endif; ?>

                        <label class="upload-icon">

                            <i class="fas fa-camera"></i>

                            <input type="file" name="profile_image" hidden>

                        </label>

                    </div>

                    <div class="profile-info">

                        <h1>

                            <?php echo htmlspecialchars($user['name']); ?>

                        </h1>

                        <p>

                            Student Profile

                        </p>

                    </div>

                </div>

            </div>


            <!-- ALERTS -->

            <?php if($error): ?>

            <div class="alert alert-danger">

                <?php echo $error; ?>

            </div>

            <?php endif; ?>


            <?php if($success): ?>

            <div class="alert alert-success">

                <?php echo $success; ?>

            </div>

            <?php endif; ?>


            <div class="row">

                <!-- LEFT SIDE -->

                <div class="col-lg-8">
                    <!-- PERSONAL INFORMATION -->

                    <div class="glass-card mb-4">

                        <div class="card-header card-header-gradient">

                            Personal Information

                        </div>

                        <div class="card-body">

                            <!-- Full Name -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Full Name

                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">

                                        <i class="fas fa-user"></i>

                                    </span>

                                    <input type="text" name="name" class="form-control"
                                        value="<?php echo htmlspecialchars($user['name']); ?>" required>

                                </div>

                            </div>


                            <!-- Email -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Email Address

                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">

                                        <i class="fas fa-envelope"></i>

                                    </span>

                                    <input type="email" class="form-control"
                                        value="<?php echo htmlspecialchars($user['email']); ?>" disabled>

                                </div>

                            </div>


                            <!-- Class -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Class

                                </label>

                                <select name="class" class="form-select">

                                    <option value="10th" <?php if (($user['class'] ?? '')=="10th") echo "selected"; ?>>
                                        10th
                                    </option>

                                    <option value="12th" <?php if (($user['class'] ?? '')=="12th") echo "selected"; ?>>
                                        12th
                                    </option>

                                </select>

                            </div>


                            <!-- Mobile -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Mobile Number

                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">

                                        <i class="fas fa-phone"></i>

                                    </span>

                                    <input type="text" name="mobile" class="form-control"
                                        value="<?php echo $user['mobile'] ?? ''; ?>">

                                </div>

                            </div>


                            <!-- Gender -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Gender

                                </label>

                                <select name="gender" class="form-select">

                                    <option value="">

                                        Select Gender

                                    </option>

                                    <option value="Male" <?php if (($user['gender'] ?? '')=="Male") echo "selected"; ?>>

                                        Male

                                    </option>

                                    <option value="Female"
                                        <?php if (($user['gender'] ?? '')=="Female") echo "selected"; ?>>

                                        Female

                                    </option>

                                    <option value="Other"
                                        <?php if (($user['gender'] ?? '')=="Other") echo "selected"; ?>>

                                        Other

                                    </option>

                                </select>

                            </div>


                            <!-- Date of Birth -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Date of Birth

                                </label>

                                <input type="date" name="dob" class="form-control"
                                    value="<?php echo $user['dob'] ?? ''; ?>">

                            </div>

                            <!-- State -->

                            <div class="mb-3">

                                <label class="form-label">

                                    State

                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">

                                        <i class="fas fa-location-dot"></i>

                                    </span>

                                    <input type="text" name="state" class="form-control"
                                        value="<?php echo $user['state'] ?? ''; ?>">

                                </div>

                            </div>

                            <!-- Update Button -->

                            <button type="submit" class="btn submit-btn w-100">



                                Update Profile

                            </button>

                        </div>

                    </div>

                    <!-- ACCOUNT SECURITY -->

                    <div class="glass-card mb-4">

                        <div class="card-header card-header-gradient">

                            Account Security

                        </div>

                        <div class="card-body">

                            <!-- Current Password -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Current Password

                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">

                                        <i class="fas fa-shield-alt"></i>

                                    </span>

                                    <input type="password" name="current_password" class="form-control"
                                        placeholder="Enter current password">

                                </div>

                            </div>


                            <!-- New Password -->

                            <div class="mb-3">

                                <label class="form-label">

                                    New Password

                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">

                                        <i class="fas fa-lock"></i>

                                    </span>

                                    <input type="password" name="new_password" class="form-control"
                                        placeholder="Enter new password">

                                </div>

                            </div>


                            <!-- Confirm Password -->

                            <div class="mb-4">

                                <label class="form-label">

                                    Confirm Password

                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">

                                        <i class="fas fa-key"></i>

                                    </span>

                                    <input type="password" name="confirm_password" class="form-control"
                                        placeholder="Confirm new password">

                                </div>

                            </div>


                            <button type="submit" class="btn submit-btn w-100">

                                Update Password

                            </button>

                        </div>

                    </div>

                </div>


                <!-- RIGHT SIDE -->

                <div class="col-lg-4">

                    <!-- RECENT ACTIVITY -->

                    <div class="glass-card mb-4">

                        <div class="card-header card-header-gradient">

                            Recent Activity

                        </div>

                        <div class="card-body">

                            <p>

                                <strong>Joined :</strong>

                                <?php echo date("d M Y", strtotime($user['created_at'])); ?>

                            </p>

                            <p>

                                <strong>Last Quiz :</strong>

                                <?php
                if (!empty($latest_result['date'])) {
                    echo date("d M Y", strtotime($latest_result['date']));
                } else {
                    echo "Not Attempted";
                }
                ?>

                            </p>

                            <p>

                                <strong>Recommended Stream :</strong>

                                <?php echo $latest_result['suggested_stream'] ?? "Not Available"; ?>

                            </p>

                        </div>

                    </div>


                    <!-- ACCOUNT STATUS -->

                    <div class="glass-card">

                        <div class="card-header card-header-gradient">

                            Account Status

                        </div>

                        <div class="card-body">

                            <p>
                                Profile Active
                            </p>

                            <p>
                                Email Verified
                            </p>

                            <p>

                                <strong>Member Since :</strong>

                                <?php echo date("Y", strtotime($user['created_at'])); ?>

                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </form>

    </div>


    <?php include 'includes/footer.php'; ?>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <?php if(isset($_GET['updated'])): ?>

    <script>
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: 'Your profile has been updated successfully.'
    });
    </script>

    <?php endif; ?>
</body>

</html>