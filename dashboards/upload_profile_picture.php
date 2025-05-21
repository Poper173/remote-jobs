<?php

session_start();
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'] ?? null;
    $profile_picture = $_FILES['profile_picture'] ?? null;

    if (!$user_id || !$profile_picture) {
        echo "Invalid request.";
        exit();
    }

    if ($profile_picture['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/profile_pictures/';
        $filename = $user_id . '_' . basename($profile_picture['name']);
        $upload_path = $upload_dir . $filename;

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        move_uploaded_file($profile_picture['tmp_name'], $upload_path);

        $stmt = $pdo->prepare("UPDATE users SET profile_picture = ? WHERE user_id = ?");
        $stmt->execute([$upload_path, $user_id]);

        header("Location: user_dashboard.php");
        exit();
    } else {
        echo "Error uploading profile picture.";
    }
}
?>