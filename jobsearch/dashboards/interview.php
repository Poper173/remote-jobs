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
    $interview_date = trim($_POST["interview_date"]);
    $interview_time = trim($_POST["interview_time"]);
    $applicant_id = trim($_POST["applicant_id"]);
    $job_id = trim($_POST["job_id"]);
    
    try {
        $stmt = $pdo->prepare("INSERT INTO interviews (applicant_id, job_id, interview_date, interview_time, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$applicant_id, $job_id, $interview_date, $interview_time]);
        $message = "✅ Interview scheduled successfully!";
    } catch (Exception $e) {
        $message = "❌ Error scheduling interview: " . $e->getMessage();
    }
}

$applicants = $pdo->query("SELECT id, name FROM applicants")->fetchAll();
$jobs = $pdo->query("SELECT job_id, title FROM job_posts")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Interview</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/main_nav.css">
    <link rel="stylesheet" href="../assets/sidebar.css">
</head>

<body>
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

    <div class="container mt-4">
        <?php if ($message): ?>
            <div class="alert alert-info text-center"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5>Schedule an Interview</h5>
            </div>
            <div class="card-body">
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Select Applicant</label>
                        <select class="form-select" name="applicant_id" required>
                            <option value="">Select an Applicant</option>
                            <?php foreach ($applicants as $applicant): ?>
                                <option value="<?= $applicant['id'] ?>"><?= htmlspecialchars($applicant['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Select Job</label>
                        <select class="form-select" name="job_id" required>
                            <option value="">Select a Job</option>
                            <?php foreach ($jobs as $job): ?>
                                <option value="<?= $job['job_id'] ?>"><?= htmlspecialchars($job['title']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Interview Date</label>
                        <input type="date" class="form-control" name="interview_date" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Interview Time</label>
                        <input type="time" class="form-control" name="interview_time" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Schedule Interview</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>