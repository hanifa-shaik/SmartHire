<?php
require_once 'config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Get user data
$sql = "SELECT id, name, email, education, experience, preferred_location FROM users WHERE id = $user_id";
$result = $conn->query($sql);

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    
    // Get user skills
    $skills_sql = "SELECT skill FROM user_skills WHERE user_id = $user_id";
    $skills_result = $conn->query($skills_sql);
    
    $skills = [];
    while ($row = $skills_result->fetch_assoc()) {
        $skills[] = $row['skill'];
    }
    
    $user['skills'] = $skills;
    
    echo json_encode(['success' => true, 'user' => $user]);
} else {
    echo json_encode(['success' => false, 'message' => 'User not found']);
}
?>