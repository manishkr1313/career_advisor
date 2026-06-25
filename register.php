<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

if (isLoggedIn()) {
    redirect('dashboard.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $class = sanitize($_POST['class'] ?? '');

    // Validation
    if (empty($name) || empty($email) || empty($password) || empty($class)) {
        $error = "All fields are required!";
    } elseif (!validateEmail($email)) {
        $error = "Invalid email format!";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters!";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Email already registered!";
        } else {
            // Insert user
            $hashed_password = hashPassword($password);
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, class) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $hashed_password, $class);

            if ($stmt->execute()) {
                showSuccess("Registration successful! Please login.");
                redirect('login.php');
            } else {
                $error = "Registration failed. Please try again!";
            }
        }
        $stmt->close();
    }
}
?>


<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Register</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link rel="shortcut icon" href="favicon.ico?v=2">
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/register.css">
</head>

<body>
    <!-- Navbar -->
    <?php include 'includes/header.php'; ?>


    <!-- <div class="container"> -->
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh">
        <div class="register-box">
            <h2>Create Your Account</h2>

            <p>Fill in the details below to get started</p>

            <?php if (!empty($error)): ?>
            <div class="alert alert-danger d-flex align-items-center" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?php echo $error; ?>
            </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label">Full Name</label>

                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-user"></i>
                        </span>

                        <input type="text" class="form-control" placeholder="full name" name="name" required />
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email Address</label>

                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-envelope"></i>
                        </span>

                        <input type="email" class="form-control" placeholder="example@gmail.com" name="email"
                            required />
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Class</label>

                    <select class="form-select" name="class" required>
                        <option selected>Select Your Class</option>
                        <option>10th</option>
                        <option>12th</option>
                    </select>
                </div>

                <div class="row">
                    <div class="col-6">
                        <label class="form-label">Password</label>

                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fa-solid fa-lock"></i>
                            </span>

                            <input type="password" class="form-control" name="password" required />
                        </div>

                        <div class="small-text mt-1">Min. 6 characters</div>
                    </div>

                    <div class="col-6">
                        <label class="form-label">Confirm Password</label>

                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fa-solid fa-lock"></i>
                            </span>

                            <input type="password" class="form-control" name="confirm_password" required />
                        </div>
                    </div>
                </div>

                <button class="btn btn-register mt-4">
                    CREATE ACCOUNT
                </button>
            </form>
            <hr />
            <div class="bottom">
                Already have an account?
                <a href="login.php">Sign in here</a>
                <br />
                <a href="index.php" class="back-home">
                    Back to Home
                </a>
            </div>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>

</html>