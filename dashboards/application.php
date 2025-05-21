<?php
// Display all errors during development (disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once '../includes/db.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Check for latest experience
$expStmt = $pdo->prepare("SELECT * FROM experience WHERE user_id = ? ORDER BY end_date DESC LIMIT 1");
$expStmt->execute([$user_id]);
$latestExperience = $expStmt->fetch(PDO::FETCH_ASSOC);

// Check for latest education
$eduStmt = $pdo->prepare("SELECT * FROM education WHERE user_id = ? ORDER BY  end_year DESC LIMIT 1");
$eduStmt->execute([$user_id]);
$latestEducation = $eduStmt->fetch(PDO::FETCH_ASSOC);

// Check for latest resume
$resumeStmt = $pdo->prepare("SELECT * FROM resumes WHERE user_id = ? ORDER BY uploaded_at DESC LIMIT 1");
$resumeStmt->execute([$user_id]);
$latestResume = $resumeStmt->fetch(PDO::FETCH_ASSOC);

// Validate completeness
if (!$latestExperience || !$latestEducation || !$latestResume) {
    echo "<div class='alert alert-danger text-center'>⚠️ Your profile is incomplete. Please ensure you have added valid Experience, Education, and a Resume before applying for a job.</div>";
    exit();
}

// Get job_id from URL or fetch the first job from job_posts if missing
$job_id = $_GET['job_id'] ?? '';

if (empty($job_id)) {
    $stmt = $pdo->query("SELECT job_id FROM job_posts LIMIT 1");
    $job = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$job) {
        echo "<div class='alert alert-danger'>No jobs found.</div>";
        exit();
    }
    $job_id = $job['job_id'];
}

// Fetch job details
$stmt = $pdo->prepare("
    SELECT job_id, title, employer_name, qualifications, salary_range, duties, application_end
    FROM job_posts
    WHERE job_id = ?
");
$stmt->execute([$job_id]);
$job = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$job) {
    echo "<div class='alert alert-danger'>Job not found.</div>";
    exit();
}

// Check if application deadline is passed
$today = date('Y-m-d');
if ($job['application_end'] < $today) {
    echo "<div class='alert alert-warning'>The application deadline has passed.</div>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Apply for <?= htmlspecialchars($job['title']) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container py-5">
        <h1 class="mb-4">Apply for: <?= htmlspecialchars($job['title']) ?></h1>

        <div class="card mb-4">
            <div class="card-body">
                <p><strong>Employer:</strong> <?= htmlspecialchars($job['employer_name']) ?></p>
                <p><strong>Qualifications:</strong> <?= nl2br(htmlspecialchars($job['qualifications'])) ?></p>
                <p><strong>Salary:</strong> <?= htmlspecialchars($job['salary_range']) ?></p>
                <p><strong>Duties:</strong> <?= nl2br(htmlspecialchars($job['duties'])) ?></p>
                <p><strong>Application Deadline:</strong> <?= htmlspecialchars($job['application_end']) ?></p>
            </div>
        </div>

        <?php if (isset($_GET['msg'])): ?>
            <div class="alert alert-<?= htmlspecialchars($_GET['type'] ?? 'info') ?>">
                <?= htmlspecialchars($_GET['msg']) ?>
            </div>
        <?php endif; ?>

        <form action="process_application.php" method="POST" enctype="multipart/form-data"
            class="card p-4 shadow-sm bg-white">
            <input type="hidden" name="job_id" value="<?= (int) $job['job_id'] ?>">
            <div class="mb-3">
                <label for="resume" class="form-label">Upload Resume (PDF/DOCX)</label>
                <input type="file" name="resume" id="resume" class="form-control" accept=".pdf,.doc,.docx" required>
            </div>
            <div class="mb-3">
                <label for="cover_letter" class="form-label">Cover Letter</label>
                <textarea name="cover_letter" id="cover_letter" class="form-control" rows="5" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit Application</button>
        </form>
    </div>
</body>

</html>