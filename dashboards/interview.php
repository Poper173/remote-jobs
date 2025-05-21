<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once '../includes/db.php';

// Ensure admin is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$employer_id = $_SESSION['user_id'];
$message = "";

// Handle interview scheduling
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['application_id'], $_POST['scheduled_time'], $_POST['mode'], $_POST['location'])) {
    $application_id = $_POST['application_id'];
    $scheduled_time = $_POST['scheduled_time'];
    $mode = $_POST['mode'];
    $location = $_POST['location'];

    // Validate interview mode
    $allowed_modes = ['online', 'in-person'];
    if (!in_array($mode, $allowed_modes)) {
        $message = "❌ Invalid interview mode.";
    } else {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO interviews (application_id, scheduled_time, mode, location) 
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([$application_id, $scheduled_time, $mode, $location]);

            if ($stmt->rowCount() > 0) {
                $message = "✅ Interview scheduled successfully!";
            } else {
                $message = "❌ Failed to schedule interview. Please try again.";
            }
        } catch (Exception $e) {
            $message = "❌ Error: " . $e->getMessage();
        }
    }
}

// Fetch applications related to jobs posted by this employer
$sql = "
    SELECT 
        a.application_id,
        u.full_name AS applicant_name,
        j.title AS job_title
    FROM applications a
    JOIN users u ON a.user_id = u.user_id
    JOIN job_posts j ON a.job_id = j.job_id
    WHERE j.employer_id = ?
    ORDER BY a.applied_at DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$employer_id]);
$applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Schedule Interview</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/main_nav.css">
</head>

<body class="bg-light">
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
                    <li class="nav-item"><a class="nav-link" href="company_dashboard.php">Dashboard</a></li>

                    <li class="nav-item"><a class="nav-link" href="../index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="../logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container py-5">
        <h2 class="mb-4">Schedule an Interview</h2>

        <?php if ($message): ?>
            <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST" class="p-4 bg-white shadow-sm rounded">
            <div class="mb-3">
                <label for="application_id" class="form-label">Select Applicant:</label>
                <select name="application_id" id="application_id" class="form-select" required>
                    <?php foreach ($applications as $app): ?>
                        <option value="<?= (int) $app['application_id'] ?>">
                            <?= htmlspecialchars($app['job_title']) ?> - <?= htmlspecialchars($app['applicant_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="scheduled_time" class="form-label">Interview Date & Time:</label>
                <input type="datetime-local" name="scheduled_time" id="scheduled_time" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="mode" class="form-label">Interview Mode:</label>
                <select name="mode" id="mode" class="form-select" required>
                    <option value="online">Online</option>
                    <option value="in-person">In-Person</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="location" class="form-label">Location (or Online Link):</label>
                <input type="text" name="location" id="location" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Schedule Interview</button>
        </form>
    </div>
</body>

</html>