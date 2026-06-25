<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

if (isLoggedIn()) {
    redirect('index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Email and password are required!";
    } else {
        // Get user from database
        $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email
= ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result =
            $stmt->get_result();
        if ($result->num_rows === 1) {
            $row =
                $result->fetch_assoc();
            if (verifyPassword($password, $row['password'])) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_name'] = $row['name'];
                showSuccess("Login successful!");
                redirect('index.php');
            } else {
                $error =
                    "Invalid password!";
            }
        } else {
            $error = "Email not found!";
        }
        $stmt->close();
    }
} ?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link rel="shortcut icon" href="favicon.ico?v=2">
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/login.css">

</head>

<body>

    <!-- Navbar -->
    <?php include 'includes/header.php'; ?>


    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="login-box">
            <h2>Welcome Back</h2>
            <p>Sign in to your account to continue</p>

            <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-circle-exclamation me-2"></i>
                <?php echo $error; ?>
            </div>

            <?php endif; ?> <?php $success = getSuccess();
               if ($success): ?>
            <div class="alert alert-success d-flex align-items-center" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <?php echo $success; ?>
            </div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Email Address</label>

                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-envelope"></i>
                        </span>

                        <input type="email" class="form-control" name="email" placeholder="example@gmail.com"
                            required />
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>

                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-lock"></i>
                        </span>

                        <input type="password" class="form-control" name="password" placeholder="Password" required />
                    </div>
                </div>

                <button class="btn btn-login mt-4">
                    SIGN IN
                </button>
            </form>
            <hr />
            <div class="bottom">
                Don't have an account?
                <a href="register.php">Create one now</a>
                <br /><br />
                <a href="index.php" class="back-home">
                    Back to Home
                </a>
                <br /><br />
                <a href="admin_login.php" class="back-home" style="font-size: 12px;">
                    <i class="fas fa-lock me-1"></i>Admin Login
                </a>
            </div>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>

</html>