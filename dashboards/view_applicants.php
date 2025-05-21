<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once '../includes/db.php';

// Check if employer is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$employer_id = $_SESSION['user_id'];
$message = "";

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['application_id'], $_POST['status'])) {
    $application_id = $_POST['application_id'];
    $new_status = $_POST['status'];

    // Validate the new status against allowed ENUM values
    $allowed_statuses = ['pending', 'accepted', 'rejected'];
    if (!in_array($new_status, $allowed_statuses)) {
        $message = "❌ Invalid status value.";
    } else {
        try {
            $stmt = $pdo->prepare("
                UPDATE applications 
                SET status = ? 
                WHERE application_id = ? 
                AND job_id IN (SELECT job_id FROM job_posts WHERE employer_id = ?)
            ");
            $stmt->execute([$new_status, $application_id, $employer_id]);

            if ($stmt->rowCount() > 0) {
                $message = "✅ Application status updated successfully!";
            } else {
                $message = "❌ Failed to update application status. Please try again.";
            }
        } catch (Exception $e) {
            $message = "❌ Error: " . $e->getMessage();
        }
    }
}

// Fetch applications for jobs posted by this employer
$sql = "
    SELECT 
        a.application_id,
        a.cover_letter,
        a.applied_at,
        a.status,
        u.user_id AS applicant_id,
        u.full_name AS applicant_name,
        u.email AS applicant_email,
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

// Insert profile view entry for each applicant viewed
foreach ($applications as $app) {
    try {
        $insert_stmt = $pdo->prepare("
            INSERT INTO profile_views (viewer_id, viewed_id, viewed_at) 
            VALUES (?, ?, CURRENT_TIMESTAMP)
            ON DUPLICATE KEY UPDATE viewed_at = CURRENT_TIMESTAMP
        ");
        $insert_stmt->execute([$employer_id, $app['applicant_id']]);
    } catch (Exception $e) {
        error_log("Error recording profile view: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Job Applications</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet"  href="../assets/main_nav.css">
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
        <h2 class="mb-4">Job Applications Received</h2>

        <?php if ($message): ?>
            <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <?php if (count($applications) === 0): ?>
            <div class="alert alert-info">No one has applied to your job posts yet.</div>
        <?php else: ?>
            <table class="table table-bordered table-hover bg-white shadow-sm">
                <thead class="table-dark">
                    <tr>
                        <th>Job Title</th>
                        <th>Applicant</th>
                        <th>Email</th>
                        <th>Cover Letter</th>
                        <th>Applied At</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($applications as $app): ?>
                        <tr>
                            <td><?= htmlspecialchars($app['job_title']) ?></td>
                            <td><?= htmlspecialchars($app['applicant_name']) ?></td>
                            <td><?= htmlspecialchars($app['applicant_email']) ?></td>
                            <td><?= nl2br(htmlspecialchars($app['cover_letter'])) ?></td>
                            <td><?= htmlspecialchars($app['applied_at']) ?></td>
                            <td><span class="badge bg-secondary"><?= htmlspecialchars($app['status']) ?></span></td>
                            <td>
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="application_id" value="<?= (int) $app['application_id'] ?>">
                                    <select name="status" class="form-select form-select-sm mb-2" required>
                                        <option value="pending" <?= $app['status'] === 'pending' ? 'selected' : '' ?>>Pending
                                        </option>
                                        <option value="accepted" <?= $app['status'] === 'accepted' ? 'selected' : '' ?>>Accepted
                                        </option>
                                        <option value="rejected" <?= $app['status'] === 'rejected' ? 'selected' : '' ?>>Rejected
                                        </option>
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>

</html>