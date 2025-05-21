<?php
require_once '../includes/db.php';
session_start();

if (!isset($_GET['post_number'])) {
    echo "Job post number is missing.";
    exit;
}

$post_number = $_GET['post_number'];

// Query the job post
$stmt = $pdo->prepare("
    SELECT 
        title,
        post_number,
        employer_name,
        DATE_FORMAT(application_start, '%Y-%m-%d') AS application_start,
        DATE_FORMAT(application_end, '%Y-%m-%d') AS application_end,
        duties,
        qualifications,
        salary_range
    FROM job_posts
    WHERE post_number = :post_number AND status = 'active'
");
$stmt->execute(['post_number' => $post_number]);
$job = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$job) {
    echo "Job not found or no longer active.";
    exit;
}

// 🔍 Track job view
$jobIdStmt = $pdo->prepare("SELECT job_id FROM job_posts WHERE post_number = :post_number LIMIT 1");
$jobIdStmt->execute(['post_number' => $post_number]);
$jobId = $jobIdStmt->fetchColumn();

if ($jobId && isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    // Check if the view already exists
    $checkViewStmt = $pdo->prepare("
        SELECT COUNT(*) FROM job_views 
        WHERE job_id = :job_id AND user_id = :user_id
    ");
    $checkViewStmt->execute([
        'job_id' => $jobId,
        'user_id' => $userId
    ]);

    if ($checkViewStmt->fetchColumn() == 0) {
        // Insert the view
        $insertViewStmt = $pdo->prepare("
            INSERT INTO job_views (job_id, user_id) 
            VALUES (:job_id, :user_id)
        ");
        $insertViewStmt->execute([
            'job_id' => $jobId,
            'user_id' => $userId
        ]);
    }
}

// Fetch the privacy policy
$policy_stmt = $pdo->query("SELECT content, effective_date FROM privacy_policy LIMIT 1");
$policy = $policy_stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Job Details - <?= htmlspecialchars($job['title']) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/main_nav.css">
    <style>
        body {
            background-color: #f4f8fb;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .job-container {
            margin: 40px auto;
            max-width: 850px;
            background: #fff;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #0056b3;
        }

        .label {
            font-weight: bold;
            color: #333;
        }

        .privacy-note {
            font-size: 0.95rem;
            margin-top: 25px;
            background: #e9f5ff;
            border-left: 5px solid #007bff;
            padding: 15px;
            border-radius: 5px;
            color: #333;
        }

        .btn-custom {
            background-color: #007bff;
            color: white;
            border-radius: 30px;
        }

        .btn-custom:hover {
            background-color: #0056b3;
        }

        .header-welcome {
            background: linear-gradient(90deg, #007bff, #6ec6ff);
            color: white;
            padding: 25px;
            text-align: center;
            border-radius: 0 0 20px 20px;
            margin-bottom: 30px;
        }
    </style>
</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="../index.php">Job Search</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="job_seeker_dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="vacancies.php">Vacancies</a></li>
                    <li class="nav-item"><a class="nav-link" href="employer_review.php">Employer Reviews</a></li>
                    <li class="nav-item"><a class="nav-link" href="../index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="../logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Welcome Message -->
    <div class="header-welcome">
        <h1>Welcome to Job Details Page!</h1>
        <p>Your future begins here — explore the job below and apply with integrity.</p>
    </div>

    <!-- Job Details -->
    <div class="container job-container">
        <h2><?= htmlspecialchars($job['title']) ?></h2>
        <p><span class="label">Post Number:</span> <?= htmlspecialchars($job['post_number']) ?></p>
        <p><span class="label">Employer:</span> <?= htmlspecialchars($job['employer_name']) ?></p>
        <p><span class="label">Application Period:</span> <?= htmlspecialchars($job['application_start']) ?> to
            <?= htmlspecialchars($job['application_end']) ?>
        </p>
        <p><span class="label">Duties:</span> <br><?= nl2br(htmlspecialchars($job['duties'])) ?></p>
        <p><span class="label">Qualifications:</span> <br><?= nl2br(htmlspecialchars($job['qualifications'])) ?></p>
        <p><span class="label">Salary Range:</span> <?= htmlspecialchars($job['salary_range']) ?></p>

        <!-- Buttons -->
        <div class="text-center mt-4">
            <a href="vacancies.php" class="btn btn-secondary me-3">Back to Vacancies</a>
            <a href="application.php?post_number=<?= urlencode($job['post_number']) ?>" class="btn btn-custom">Apply
                Now</a>
        </div>

        <!-- Privacy and Application Integrity Notice -->
        <div class="privacy-note mt-5">
            <strong>🔒 Privacy & Application Policy:</strong>
            <?php if ($policy): ?>
                <p><strong>Effective Date:</strong> <?= htmlspecialchars($policy['effective_date']) ?></p>
                <div><?= nl2br(htmlspecialchars($policy['content'])) ?></div>
            <?php else: ?>
                <p>No privacy policy available at the moment.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>