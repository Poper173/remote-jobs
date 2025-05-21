<?php
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);
session_start();
require_once '../includes/db.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user preferences if any
$prefStmt = $pdo->prepare("SELECT preferred_location_id, preferred_category_id FROM user_preferences WHERE user_id = ?");
$prefStmt->execute([$user_id]);
$preferences = $prefStmt->fetch(PDO::FETCH_ASSOC);

// Build SQL with optional filters
$sql = "
    SELECT 
      jp.title,
      jp.post_number,
      jp.employer_name,
      DATE_FORMAT(jp.application_start, '%Y-%m-%d') AS application_start,
      DATE_FORMAT(jp.application_end, '%Y-%m-%d') AS application_end,
      jp.duties,
      jp.qualifications,
      jp.salary_range
    FROM job_posts jp
";

$joins = [];
$conditions = ["jp.status = 'active'"];
$params = [];

if (!empty($preferences)) {
    if (!empty($preferences['preferred_location_id'])) {
        $joins[] = "INNER JOIN locations l ON jp.location_id = l.location_id";
        $conditions[] = "jp.location_id = ?";
        $params[] = $preferences['preferred_location_id'];
    }
    if (!empty($preferences['preferred_category_id'])) {
        $joins[] = "INNER JOIN categories c ON jp.category_id = c.category_id";
        $conditions[] = "jp.category_id = ?";
        $params[] = $preferences['preferred_category_id'];
    }
}

if (!empty($joins)) {
    $sql .= " " . implode(" ", $joins);
}

if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

$sql .= " ORDER BY jp.application_start DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// If no jobs match preferences, fetch all jobs
if (count($jobs) === 0) {
    $sql = "
        SELECT 
          jp.title,
          jp.post_number,
          jp.employer_name,
          DATE_FORMAT(jp.application_start, '%Y-%m-%d') AS application_start,
          DATE_FORMAT(jp.application_end, '%Y-%m-%d') AS application_end,
          jp.duties,
          jp.qualifications,
          jp.salary_range
        FROM job_posts jp
        WHERE jp.status = 'active'
        ORDER BY jp.application_start DESC
    ";
    $stmt = $pdo->query($sql);
    $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Job Vacancies</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="../assets/main_nav.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="../index.php">JobSearch</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                <li class="nav-item">
                        <a class="nav-link" href="job_seeker_dashboard.php" >
                           Dashboard
                        </a>
                    <li class="nav-item">
                        <a class="nav-link" href="preference.php" title="Set Preferences">
                            <i class="bi bi-gear-fill"></i> Preferences
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php" title="Contact Us">
                            <i class="bi bi-envelope-fill"></i> Contact
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <div class="container mt-5">
        <h1 class="text-center mb-4">Job Vacancies</h1>

        <?php if (!empty($preferences)): ?>
            <p class="text-center"><strong>Filtered by your preferences:</strong>
                Location ID: <?= htmlspecialchars($preferences['preferred_location_id'] ?? 'Any') ?>,
                Category ID: <?= htmlspecialchars($preferences['preferred_category_id'] ?? 'Any') ?>
            </p>
        <?php endif; ?>

        <table class="table table-bordered table-striped">
            <thead class="table-primary">
                <tr>
                    <th>Post Name</th>
                    <th>Post Number</th>
                    <th>Employer</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Salary</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($jobs) === 0): ?>
                    <tr>
                        <td colspan="6" class="text-center">No vacancies available.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($jobs as $job): ?>
                        <tr>
                            <td>
                                <a href="job_details.php?post_number=<?= urlencode($job['post_number']) ?>">
                                    <?= htmlspecialchars($job['title']) ?>
                                </a>
                            </td>
                            <td><?= htmlspecialchars($job['post_number']) ?></td>
                            <td><?= htmlspecialchars($job['employer_name']) ?></td>
                            <td><?= htmlspecialchars($job['application_start']) ?></td>
                            <td><?= htmlspecialchars($job['application_end']) ?></td>
                            <td><?= htmlspecialchars($job['salary_range']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="text-center mt-4">
            <a href="contact.php" class="btn btn-outline-primary">
                📩 Contact Us
            </a>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>