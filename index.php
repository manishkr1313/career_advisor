<?php 
require_once 'includes/functions.php';
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Home - Career Advisor | Choose Your Perfect Career Path</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <link rel="shortcut icon" href="favicon.ico?v=2">
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/index.css">

</head>

<body>
    <?php include 'includes/header.php'; ?>

    <!--HERO SECTION -->
    <section class="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="hero-title">
                        Your <span class="text-info">Future,</span><br />
                        Your <span class="text-info">Choice</span>
                    </h1>

                    <p class="hero-text">
                        Get personalized guidance for your stream, courses, colleges, and
                        career opportunities after
                        <span>10th</span> or
                        <span>12th</span> grade.
                    </p>

                    <div class="d-flex flex-wrap gap-3">
                        <?php if (isLoggedIn()): ?>
                        <a href="dashboard.php" class="btn btn-custom">Go to Dashboard</a>
                        <?php else: ?>

                        <a href="register.php" class="btn btn-custom"> Get Started </a>
                        <a href="login.php" class="btn btn-custom">Login</a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-lg-6 mt-5 mt-lg-0">
                    <img src="img/career-bg.png" alt="Career Guidance" class="hero-image" />
                </div>
            </div>
        </div>
    </section>

    <section class="feature-section" id="services">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-4 fw-bold text-info">
                    Our Key Features</span>
                </h2>

                <p class="lead hero-text">
                    Comprehensive tools to guide your educational journey.
                </p>
            </div>

            <div class="row g-3">
                <!-- Feature 1 -->

                <div class="col-md-6 col-lg-3">
                    <div class="card feature-card h-100">

                        <div class="feature-icon">
                            <i class="fas fa-brain text-white fa-3x"></i>
                        </div>

                        <div class="card-body">
                            <h4 class="card-title text-info">Aptitude Quiz</h4>

                            <p class="card-text">
                                Take our intelligent quiz to discover your ideal stream based
                                on your aptitude.
                            </p>

                            <a href="<?php echo isLoggedIn() ? 'quiz.php' : 'login.php'; ?>" class="btn btn-primary">
                                Start Quiz
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Feature 2 -->

                <div class="col-md-6 col-lg-3">
                    <div class="card feature-card h-100">
                        <div class="feature-icon">
                            <i class="fas fa-book text-white fa-3x"></i>
                        </div>

                        <div class="card-body">
                            <h4 class="card-title text-info">Course Guide</h4>

                            <p class="card-text">
                                Explore detailed information about courses, duration,
                                eligibility and careers.
                            </p>

                            <a href="<?php echo isLoggedIn() ? 'course_details.php' : 'login.php'; ?>"
                                class="btn btn-primary">
                                Explore
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Feature 3 -->

                <div class="col-md-6 col-lg-3">
                    <div class="card feature-card h-100">
                        <div class="feature-icon">
                            <i class="fas fa-building text-white fa-3x"></i>
                        </div>

                        <div class="card-body">
                            <h4 class="card-title text-info">College Directory</h4>

                            <p class="card-text">
                                Browse colleges, facilities, cutoffs and admission
                                requirements.
                            </p>

                            <a href="<?php echo isLoggedIn() ? 'colleges.php' : 'login.php'; ?>"
                                class="btn btn-primary">
                                View Colleges
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Feature 4 -->

                <!-- <div class="col-md-6 col-lg-3">
                    <div class="card feature-card h-100">
                        <div class="feature-icon">
                            <i class="fas fa-briefcase text-white fa-3x"></i>
                        </div>

                        <div class="card-body">
                            <h4 class="card-title">Career Paths</h4>

                            <p class="card-text">
                                Know job roles, salary packages and future opportunities.
                            </p>

                            <a href="<?php echo isLoggedIn() ? 'career.php' : 'login.php'; ?>" class="btn btn-primary">
                                Explore
                            </a>
                        </div>
                    </div>
                </div> -->

                <!-- Feature 5 -->

                <div class="col-md-6 col-lg-3">
                    <div class="card feature-card h-100">
                        <div class="feature-icon">
                            <i class="fas fa-user text-white fa-3x"></i>
                        </div>

                        <div class="card-body">
                            <h4 class="card-title text-info ">Personal Profile</h4>

                            <p class="card-text">
                                Track quiz results and receive personalized recommendations.
                            </p>

                            <a href="<?php echo isLoggedIn() ? 'profile.php' : 'login.php'; ?>" class="btn btn-primary">
                                My Profile
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Feature 6 -->

                <!-- <div class="col-md-6 col-lg-3">
                    <div class="card feature-card h-100">
                        <div class="feature-icon">
                            <i class="fas fa-user-graduate text-white fa-3x"></i>
                        </div>

                        <div class="card-body">
                            <h4 class="card-title">Personalized Guide</h4>

                            <p class="card-text">
                                Get customized recommendations according to your interests and
                                profile.
                            </p>

                            <a href="<?php echo isLoggedIn() ? 'profile.php' : 'login.php'; ?>" class="btn btn-primary">
                                Get Started
                            </a>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
    </section>


    <!-- FOOTER SECTION -->
    <footer class="footer-section">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-lg-3 footer-col">
                    <h4 class="footer-title">Career Advisor</h4>
                    <p class="footer-text">
                        Your perfect guide to choose the best career path based on your
                        interests and aptitude.
                    </p>
                </div>

                <div class="col-md-6 col-lg-3 footer-col">
                    <h4 class="footer-title">Quick Links</h4>
                    <ul class="footer-links">
                        <li><a href="#">Home</a></li>
                        <li><a href="login.php">Take Quiz</a></li>
                        <li><a href="login.php">Courses</a></li>
                        <li><a href="login.php">Colleges</a></li>
                    </ul>
                </div>

                <div class="col-md-6 col-lg-3 footer-col">
                    <h4 class="footer-title">Resources</h4>
                    <ul class="footer-links">
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Contact</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms & Conditions</a></li>
                    </ul>
                </div>

                <div class="col-md-6 col-lg-3 footer-col">
                    <h4 class="footer-title">Follow Us</h4>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>

            <hr class="footer-divider" />

            <div class="footer-bottom">
                <p>&copy; 2026 Career Advisor. All rights reserved.</p>
            </div>
        </div>
    </footer>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
</body>

</html>