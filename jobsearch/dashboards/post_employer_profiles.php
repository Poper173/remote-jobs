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
    $employer_name = trim($_POST["employer_name"]);
    $company_name = trim($_POST["company_name"]);
    $company_description = trim($_POST["company_description"]);
    $contact_info = trim($_POST["contact_info"]);

    try {
        $pdo->beginTransaction();

        // Insert or fetch company
        $stmt = $pdo->prepare("SELECT company_id FROM companies WHERE name = ?");
        $stmt->execute([$company_name]);
        $company_id = $stmt->fetchColumn();

        if (!$company_id) {
            $stmt = $pdo->prepare("INSERT INTO companies (name, description, created_at, verified) VALUES (?, ?, NOW(), 0)");
            $stmt->execute([$company_name, $company_description]);
            $company_id = $pdo->lastInsertId();
        }

        // Insert employer profile
        $user_id = $_SESSION['user_id'];
        $stmt = $pdo->prepare("INSERT INTO employer_profiles (employer_id, company_id, employer_name, contact_info, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$user_id, $company_id, $employer_name, $contact_info]);

        $pdo->commit();
        $message = "✅ Employer profile posted successfully!";
    } catch (Exception $e) {
        $pdo->rollBack();
        $message = "❌ Error posting employer profile: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Dashboard - Post Employer Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/main_nav.css">
    <link rel="stylesheet" href="../assets/sidebar.css">
    <style>
        body {
            background: #f0f2f5;
            font-family: 'Segoe UI', sans-serif;
        }

        .sidebar {
            height: 100vh;
            background-color: #ffffff;
            border-right: 1px solid #dee2e6;
            padding-top: 1rem;
        }

        .sidebar .nav-link {
            color: #333;
            font-weight: 500;
            padding: 10px 15px;
        }

        .sidebar .nav-link.active {
            background-color: #007bff;
            color: white !important;
            border-radius: 5px;
        }

        .main-content {
            padding: 2rem;
        }

        .navbar-brand img {
            max-height: 40px;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .alert-info {
            color: #0c5460;
            background-color: #d1ecf1;
            border-color: #bee5eb;
        }
    </style>
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
                        <li class="nav-item"><a class="nav-link active" href="post_employer_profiles.php">👤 Employer Profiles</a></li>
                        <li class="nav-item"><a class="nav-link" href="interview.php">📅 Schedule Interview</a></li>
                        <li class="nav-item"><a class="nav-link" href="complain.php">📢 Complaints</a></li>
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
                        <h5 class="mb-0">Post Employer Profile</h5>
                    </div>
                    <div class="card-body">
                        <form method="post">
                            <div class="mb-3">
                                <label class="form-label">Employer Name</label>
                                <input type="text" class="form-control" name="employer_name" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Company Name</label>
                                <input type="text" class="form-control" name="company_name" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Company Description</label>
                                <textarea class="form-control" name="company_description" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Contact Information</label>
                                <input type="text" class="form-control" name="contact_info" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Post Employer Profile</button>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>