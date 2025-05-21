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

$user_id = $_SESSION['user_id'];

// Fetch job applications by the user
$sql = "
    SELECT 
        a.application_id,
        a.cover_letter,
        a.applied_at,
        a.status,
        j.title AS job_title,
        j.employer_name,
        j.application_end,
        r.resume_url
    FROM applications a
    JOIN job_posts j ON a.job_id = j.job_id
    LEFT JOIN resumes r ON a.user_id = r.user_id
    WHERE a.user_id = ?
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
    <title>My Job Applications</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
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
                            <th>Resume</th>
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
                                    <?php if (!empty($app['resume_url'])): ?>
                                        <a href="../uploads/<?= htmlspecialchars($app['resume_url']) ?>" target="_blank"
                                            class="btn btn-sm btn-primary">View</a>
                                    <?php else: ?>
                                        <span class="text-muted">No Resume</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                    $status = $app['status'] ?? 'Pending';
                                    $badgeClass = match ($status) {
                                        'Approved' => 'success',
                                        'Rejected' => 'danger',
                                        default => 'secondary'
                                    };
                                    ?>
                                    <span class="badge bg-<?= $badgeClass ?>"><?= htmlspecialchars($status) ?></span>
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