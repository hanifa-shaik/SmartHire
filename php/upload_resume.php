<?php
require_once 'config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

if (isset($_FILES['resume'])) {
    $user_id = $_SESSION['user_id'];
    $file = $_FILES['resume'];
    
    // Check file type
    $allowed = ['pdf', 'doc', 'docx', 'txt'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if (!in_array($ext, $allowed)) {
        echo json_encode(['success' => false, 'message' => 'Invalid file type']);
        exit;
    }
    
    // Create uploads folder if not exists
    $upload_dir = '../uploads/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    // Save file
    $filename = 'resume_' . $user_id . '_' . time() . '.' . $ext;
    $filepath = $upload_dir . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        // Extract skills from file
        $content = '';
        if ($ext === 'txt') {
            $content = file_get_contents($filepath);
        }
        
        // Common skills to search for
        $all_skills = ['Python', 'Java', 'JavaScript', 'PHP', 'HTML', 'CSS', 'SQL', 'React', 
                       'Angular', 'Node.js', 'Django', 'Flask', 'Spring', 'MySQL', 'MongoDB',
                       'Machine Learning', 'Data Analysis', 'AWS', 'Docker', 'Git'];
        
        $found_skills = [];
        foreach ($all_skills as $skill) {
            if (stripos($content, $skill) !== false || stripos($file['name'], $skill) !== false) {
                $found_skills[] = $skill;
            }
        }
        
        // If no skills found, add some default ones
        if (empty($found_skills)) {
            $found_skills = ['Communication', 'Teamwork', 'Problem Solving'];
        }
        
        // Delete old skills and add new ones
        $conn->query("DELETE FROM user_skills WHERE user_id = $user_id");
        
        foreach ($found_skills as $skill) {
            $skill = $conn->real_escape_string($skill);
            $conn->query("INSERT INTO user_skills (user_id, skill) VALUES ($user_id, '$skill')");
        }
        
        // Update resume path
        $conn->query("UPDATE users SET resume_path = '$filename' WHERE id = $user_id");
        
        echo json_encode(['success' => true, 'message' => 'Resume uploaded!', 'skills' => $found_skills]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Upload failed']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No file uploaded']);
}
?>