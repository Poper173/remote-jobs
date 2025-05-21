<?php
// Display all errors during development (disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../includes/db.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $skill_name = isset($_POST['skill_name']) ? trim($_POST['skill_name']) : '';

    // Validate input
    if (empty($skill_name)) {
        $message = "Skill name is required.";
    } else {
        // Insert skill into the database
        $stmt = $pdo->prepare("INSERT INTO skills (user_id, skill_name) VALUES (?, ?)");
        if ($stmt->execute([$user_id, $skill_name])) {
            header("Location: job_seeker_dashboard.php?skill_added=1");
            exit();
        } else {
            $message = "Failed to add skill. Please try again.";
        }
    }
}
?>