<?php
// Database connection settings
$host = 'localhost';
$user = 'root';
$pass = '';  // Empty for XAMPP default
$dbname = 'smarthire';

// Connect to database
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start session
session_start();
?>