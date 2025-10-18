<?php
require_once 'config.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $education = $conn->real_escape_string($_POST['education'] ?? '');
    $experience = intval($_POST['experience'] ?? 0);
    $location = $conn->real_escape_string($_POST['location'] ?? '');
    
    // Check if email already exists
    $check = $conn->query("SELECT id FROM users WHERE email = '$email'");
    if ($check->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Email already registered']);
        exit;
    }
    
    // Insert user
    $sql = "INSERT INTO users (name, email, password, education, experience, preferred_location) 
            VALUES ('$name', '$email', '$password', '$education', $experience, '$location')";
    
    if ($conn->query($sql)) {
        $user_id = $conn->insert_id;
        
        // Add skills if provided
        if (!empty($_POST['skills'])) {
            $skills = explode(',', $_POST['skills']);
            foreach ($skills as $skill) {
                $skill = trim($skill);
                if (!empty($skill)) {
                    $skill = $conn->real_escape_string($skill);
                    $conn->query("INSERT INTO user_skills (user_id, skill) VALUES ($user_id, '$skill')");
                }
            }
        }
        
        echo json_encode(['success' => true, 'message' => 'Registration successful!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Registration failed']);
    }
}
?>