<?php
// Database Connection File
$servername = "localhost";
$username = "root";
$password = "manish@1234";
$database = "career_advisor";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to utf8
$conn->set_charset("utf8");
?>
