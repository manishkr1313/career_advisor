<?php
// Common Functions File

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Redirect to login if not logged in
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}

// Sanitize input
function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Validate email
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Hash password
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Verify password
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

// Get stream from quiz results
function getStreamFromScore($science, $commerce, $arts) {
    $max = max($science, $commerce, $arts);
    
    if ($max == $science) {
        return "Science";
    } elseif ($max == $commerce) {
        return "Commerce";
    } else {
        return "Arts";
    }
}

// Redirect function
function redirect($url) {
    header("Location: " . $url);
    exit();
}

// Success message
function showSuccess($message) {
    $_SESSION['success'] = $message;
}

// Error message
function showError($message) {
    $_SESSION['error'] = $message;
}

// Get success message
function getSuccess() {
    if (isset($_SESSION['success'])) {
        $msg = $_SESSION['success'];
        unset($_SESSION['success']);
        return $msg;
    }
    return null;
}

// Get error message
function getError() {
    if (isset($_SESSION['error'])) {
        $msg = $_SESSION['error'];
        unset($_SESSION['error']);
        return $msg;
    }
    return null;
}
?>
