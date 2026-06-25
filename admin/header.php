<?php
// Admin Header
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<nav class="navbar navbar-expand-lg navbar-dark"
    style="background: linear-gradient(135deg, #020617, #081042, #4c1d95);">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="index.php">
            Career Admin
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="adminNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">
                        Home
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dataDropdown" role="button"
                        data-bs-toggle="dropdown">
                        Manage Data
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="dataDropdown">
                        <li><a class="dropdown-item" href="courses.php"><i class="fas fa-book me-2"></i>Courses</a></li>
                        <li><a class="dropdown-item" href="careers.php">Careers</a>
                        </li>
                        <li><a class="dropdown-item" href="colleges.php">Colleges</a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="notifications.php">Notifications</a></li>
                        <li><a class="dropdown-item" href="users.php">Users</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button"
                        data-bs-toggle="dropdown">
                        <?php echo isset($_SESSION['admin_name']) ? htmlspecialchars($_SESSION['admin_name']) : 'Admin'; ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end" aria-labelledby="profileDropdown">
                        <li><a class="dropdown-item" href="admin_logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>