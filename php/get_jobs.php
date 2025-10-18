<?php
require_once 'config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Get user skills
$skills_result = $conn->query("SELECT skill FROM user_skills WHERE user_id = $user_id");
$user_skills = [];
while ($row = $skills_result->fetch_assoc()) {
    $user_skills[] = strtolower(trim($row['skill']));
}

// Get all active jobs
$jobs_result = $conn->query("SELECT * FROM jobs WHERE status = 'active' ORDER BY posted_date DESC");
$matched_jobs = [];

while ($job = $jobs_result->fetch_assoc()) {
    // Get job required skills
    $job_skills = array_map('trim', explode(',', strtolower($job['required_skills'])));
    
    // Calculate match
    $matched_skills = array_intersect($user_skills, $job_skills);
    $match_count = count($matched_skills);
    $total_skills = count($job_skills);
    
    $match_percentage = $total_skills > 0 ? round(($match_count / $total_skills) * 100) : 0;
    
    // Add to results
    $matched_jobs[] = [
        'id' => $job['id'],
        'title' => $job['title'],
        'company' => $job['company'],
        'location' => $job['location'],
        'salary' => $job['salary'],
        'description' => $job['description'],
        'required_skills' => explode(',', $job['required_skills']),
        'matched_skills' => array_values($matched_skills),
        'match_percentage' => $match_percentage
    ];
}

// Sort by match percentage
usort($matched_jobs, function($a, $b) {
    return $b['match_percentage'] - $a['match_percentage'];
});

echo json_encode(['success' => true, 'jobs' => $matched_jobs]);
?>