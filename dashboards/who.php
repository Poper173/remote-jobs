<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once '../includes/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Logged-in user
$viewers = [];

// Fetch the list of viewers
try {
    $stmt = $pdo->prepare("
        SELECT 
            u.user_id AS viewer_id, 
            u.full_name AS viewer_name, 
            u.email AS viewer_email, 
            pv.viewed_at 
        FROM profile_views pv
        JOIN users u ON pv.viewer_id = u.user_id
        WHERE pv.viewed_id = ?
        ORDER BY pv.viewed_at DESC
    ");
    $stmt->execute([$user_id]);
    $viewers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    error_log("Error fetching profile views: " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Profile Views</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet"href="../assets/main_nav.css">
</head>

<body>
      <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="../index.php">
                <img src="/templates/pwk.png" alt="Logo" style="max-width:150px;" />
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="job_seeker_dashboard.php">Dashboard</a></li>

                    <li class="nav-item"><a class="nav-link" href="../index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="../logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <h1 class="text-center">Profile Views</h1>
        <p class="text-center">See who has viewed your profile.</p>
    <div class="container py-5">
        <h2 class="mb-4">Who Viewed Your Profile</h2>

        <?php if (count($viewers) === 0): ?>
            <div class="alert alert-info">No one has viewed your profile yet.</div>
        <?php else: ?>
            <table class="table table-bordered table-hover bg-white shadow-sm">
                <thead class="table-dark">
                    <tr>
                        <th>Viewer Name</th>
                        <th>Email</th>
                        <th>Viewed At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($viewers as $viewer): ?>
                        <tr>
                            <td><?= htmlspecialchars($viewer['viewer_name']) ?></td>
                            <td><?= htmlspecialchars($viewer['viewer_email']) ?></td>
                            <td><?= htmlspecialchars($viewer['viewed_at']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>

</html>