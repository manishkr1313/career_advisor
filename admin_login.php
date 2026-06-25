<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Check if admin already logged in
if (isset($_SESSION['admin_id'])) {
    header("Location: admin/index.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Email and Password both are required!";
    } else {
        // Check admin from database
        $stmt = $conn->prepare("SELECT id, name, password FROM admins WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            if (verifyPassword($password, $row['password'])) {
                $_SESSION['admin_id'] = $row['id'];
                $_SESSION['admin_name'] = $row['name'];
                $_SESSION['is_admin'] = true;
                header("Location: admin/index.php");
                exit();
            } else {
                $error = "Invalid password!";
            }
        } else {
            $error = "Email not found!";
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
    <title>Admin Login - Career Advisor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/login.css">
</head>

<body class="bg-dark">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6">
                <div class="card shadow-lg border-0" style="background: linear-gradient(135deg, #1a1a2e, #16213e);">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <h1 class="h3 text-light fw-bold">
                                Admin Login
                            </h1>
                            <p class="text-white small">Career Advisor Dashboard</p>
                        </div>

                        <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                        </div>
                        <?php endif; ?>

                        <form method="POST" class="needs-validation" novalidate>
                            <div class="mb-3">
                                <label class="form-label text-light">
                                    <i class="fas fa-envelope me-2"></i>Email
                                </label>
                                <input type="email" class="form-control form-control-lg" name="email"
                                    placeholder="admin@example.com" required
                                    style="background: rgba(255,255,255,0.1); border: 1px solid rgba(124, 58, 237, 0.3); color: #fff;">
                                <div class="invalid-feedback text-light">
                                    Please provide a valid email.
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label text-light">
                                    <i class="fas fa-lock me-2"></i>Password
                                </label>
                                <input type="password" class="form-control form-control-lg" name="password"
                                    placeholder="••••••••" required
                                    style="background: rgba(255,255,255,0.1); border: 1px solid rgba(124, 58, 237, 0.3); color: #fff;">
                                <div class="invalid-feedback text-light">
                                    Please enter password.
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold">
                                <i class="fas fa-sign-in-alt me-2"></i>Login
                            </button>
                        </form>

                        <hr class="border-secondary my-4">

                        <div class="text-center">
                            <p class="text-muted mb-0">
                                <a href="index.php" class="text-primary text-decoration-none">
                                    <i class="fas fa-arrow-left me-1"></i>Go to User Login
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Form validation
    (function() {
        'use strict';
        window.addEventListener('load', function() {
            var forms = document.getElementsByClassName('needs-validation');
            var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();
    </script>
</body>

</html>