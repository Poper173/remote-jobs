<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$job_views = [];

try {
    $stmt = $pdo->query("SELECT job_posts.title, COUNT(job_views.id) AS view_count 
                          FROM job_posts 
                          LEFT JOIN job_views ON job_posts.id = job_views.job_id 
                          GROUP BY job_posts.id 
                          ORDER BY view_count DESC");
    $job_views = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $message = "❌ Error fetching job views: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Viewer Statistics</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/main_nav.css">
    <link rel="stylesheet" href="../assets/sidebar.css">
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
                    <li class="nav-item"><a class="nav-link" href="../index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="../logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Structure -->
    <div class="container-fluid">
        <div class="row">

            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar">
                <div class="position-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item"><a class="nav-link" href="company_dashboard.php">📤 Post Job</a></li>
                        <li class="nav-item"><a class="nav-link" href="view_applicants.php">📄 View Applications</a></li>
                        <li class="nav-item"><a class="nav-link" href="post_job_locations.php">📍 Job Locations</a></li>
                        <li class="nav-item"><a class="nav-link active" href="views.php">👁️ Job Viewers</a></li>
                        <li class="nav-item"><a class="nav-link" href="post_employer_profiles.php">👤 Employer Profiles</a></li>
                        <li class="nav-item"><a class="nav-link" href="interview.php">📅 Schedule Interview</a></li>
                        <li class="nav-item"><a class="nav-link" href="complain.php">📢 Complaints</a></li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 main-content">
                <h1 class="mt-4">Job Viewer Statistics</h1>
                <?php if (isset($message)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($message) ?></div>
                <?php endif; ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Job Title</th>
                            <th>View Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($job_views as $view): ?>
                            <tr>
                                <td><?= htmlspecialchars($view['title']) ?></td>
                                <td><?= htmlspecialchars($view['view_count']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>