<?php
require_once '../includes/functions.php';

// Logout admin
if (isset($_SESSION['admin_id'])) {
    session_destroy();
}

header("Location: ../admin_login.php");
exit();
?>