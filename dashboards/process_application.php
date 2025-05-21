<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

$user_id = $_SESSION['user_id'];
$job_id = $_POST['job_id'] ?? null;
$cover_letter = trim($_POST['cover_letter'] ?? '');

// Validate basic fields
if (!$job_id || !$cover_letter || !isset($_FILES['resume'])) {
    die("Missing fields. Make sure all inputs are provided.");
}

// Check if user already applied for this job
$check = $pdo->prepare("SELECT * FROM applications WHERE user_id = ? AND job_id = ?");
$check->execute([$user_id, $job_id]);
if ($check->rowCount() > 0) {
    header("Location: application.php?job_id=$job_id&msg=You+have+already+applied+for+this+job&type=warning");
    exit();
}

// Handle resume upload
$resume = $_FILES['resume'];
$resumeName = basename($resume['name']);
$resumeType = $resume['type'];
$resumeTmp = $resume['tmp_name'];

$allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
if (!in_array($resumeType, $allowedTypes)) {
    header("Location: apply.php?job_id=$job_id&msg=Invalid+resume+file+type&type=danger");
    exit();
}

$uploadDir = '../uploads/resumes/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$cleanName = preg_replace("/[^a-zA-Z0-9.]/", "", $resumeName);
$newFileName = $user_id . '_resume_' . time() . '_' . $cleanName;
$resumePath = $uploadDir . $newFileName;

if (!move_uploaded_file($resumeTmp, $resumePath)) {
    die("Failed to upload resume.");
}

// Insert application
try {
    $stmt = $pdo->prepare("
        INSERT INTO applications (user_id, job_id, jobseeker_id, cover_letter, applied_at, status)
        VALUES (?, ?, ?, ?, NOW(), 'pending')
    ");
    $stmt->execute([$user_id, $job_id, $user_id, $cover_letter]);

    header("Location: apply.php?job_id=$job_id&msg=Application+submitted+successfully&type=success");
    exit();
} catch (PDOException $e) {
    echo "<pre>Database Error: " . $e->getMessage() . "</pre>";
    exit();
}
