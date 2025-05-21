<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once '../includes/db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch job applications made by the user
$sql = "
    SELECT 
        a.application_id,
        a.cover_letter,
        a.applied_at,
        a.status,
        j.title AS job_title,
        j.employer_name,
        j.application_end
    FROM applications a
    JOIN job_posts j ON a.job_id = j.job_id
    WHERE a.jobseeker_id = ?
    ORDER BY a.applied_at DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Applications</title>
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
                    <li class="nav-item"><a class="nav-link" href="job_seeker_dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="../index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="../logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-4">
        <h1 class="text-center">Job Applications</h1>
        <p class="text-center">View and manage your job applications.</p>
    </div>
    <div class="container py-5">
        <h2 class="mb-4">My Applications</h2>

        <?php if (count($applications) > 0): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Job Title</th>
                            <th>Employer</th>
                            <th>Applied At</th>
                            <th>Application Deadline</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($applications as $i => $app): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= htmlspecialchars($app['job_title']) ?></td>
                                <td><?= htmlspecialchars($app['employer_name']) ?></td>
                                <td><?= htmlspecialchars($app['applied_at']) ?></td>
                                <td><?= htmlspecialchars($app['application_end']) ?></td>
                                <td>
                                    <?php
                                    $status = $app['status'] ?? 'Pending';
                                    $badgeClass = match ($status) {
                                        'accepted' => 'success',
                                        'rejected' => 'danger',
                                        default => 'secondary'
                                    };
                                    ?>
                                    <span class="badge bg-<?= $badgeClass ?>"><?= htmlspecialchars(ucfirst($status)) ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center">You haven't applied for any jobs yet.</div>
        <?php endif; ?>
    </div>
</body>

</html>