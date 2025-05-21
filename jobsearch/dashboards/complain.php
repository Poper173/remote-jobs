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

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $complaint = trim($_POST["complaint"]);
    $user_id = $_SESSION['user_id'];

    try {
        $stmt = $pdo->prepare("INSERT INTO complaints (user_id, complaint, created_at) VALUES (?, ?, NOW())");
        $stmt->execute([$user_id, $complaint]);
        $message = "✅ Complaint submitted successfully!";
    } catch (Exception $e) {
        $message = "❌ Error submitting complaint: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Dashboard - Submit Complaint</title>
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
                        <li class="nav-item"><a class="nav-link" href="views.php">👁️ Job Viewers</a></li>
                        <li class="nav-item"><a class="nav-link" href="post_employer_profiles.php">👤 Employer Profiles</a></li>
                        <li class="nav-item"><a class="nav-link" href="interview.php">📅 Schedule Interview</a></li>
                        <li class="nav-item"><a class="nav-link active" href="complain.php">📢 Complaints</a></li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 main-content">
                <?php if ($message): ?>
                    <div class="alert alert-info text-center"><?= htmlspecialchars($message) ?></div>
                <?php endif; ?>

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Submit a Complaint</h5>
                    </div>
                    <div class="card-body">
                        <form method="post">
                            <div class="mb-3">
                                <label class="form-label">Your Complaint</label>
                                <textarea class="form-control" name="complaint" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Submit Complaint</button>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>