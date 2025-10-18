<?php
require_once 'config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $job_id = intval($_POST['job_id']);
    $match_percentage = intval($_POST['match_percentage']);
    
    // Check if already applied
    $check = $conn->query("SELECT id FROM applications WHERE user_id = $user_id AND job_id = $job_id");
    
    if ($check->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Already applied to this job']);
        exit;
    }
    
    // Insert application
    $sql = "INSERT INTO applications (user_id, job_id, match_percentage) VALUES ($user_id, $job_id, $match_percentage)";
    
    if ($conn->query($sql)) {
        echo json_encode(['success' => true, 'message' => 'Application submitted successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Application failed']);
    }
}
?>