<?php
require_once '../includes/db.php';
session_start();

// Optional filters
$whereClause = "";
$params = [];

if (!empty($_GET['date_from']) && !empty($_GET['date_to'])) {
    $whereClause .= " WHERE jv.viewed_at BETWEEN :date_from AND :date_to";
    $params['date_from'] = $_GET['date_from'] . " 00:00:00";
    $params['date_to'] = $_GET['date_to'] . " 23:59:59";
}

// Fetch total views per job
$totalViewsStmt = $pdo->prepare("
    SELECT jp.job_id, jp.title, COUNT(*) AS total_views
    FROM job_posts jp
    LEFT JOIN job_views jv ON jp.job_id = jv.job_id
    $whereClause
    GROUP BY jp.job_id, jp.title
    ORDER BY total_views DESC
");
$totalViewsStmt->execute($params);
$totalViews = $totalViewsStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch recent views per job (last 7 days)
$recentStmt = $pdo->prepare("
    SELECT jp.job_id, jp.title, COUNT(*) AS recent_views
    FROM job_posts jp
    LEFT JOIN job_views jv ON jp.job_id = jv.job_id
    WHERE jv.viewed_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
    GROUP BY jp.job_id, jp.title
    ORDER BY recent_views DESC
");
$recentStmt->execute();
$recentViews = $recentStmt->fetchAll(PDO::FETCH_ASSOC);

// Views per user
$userViewsStmt = $pdo->query("
    SELECT u.full_name, COUNT(*) AS views_count
    FROM users u
    JOIN job_views jv ON u.user_id = jv.user_id
    GROUP BY u.user_id, u.full_name
    ORDER BY views_count DESC
");
$userViews = $userViewsStmt->fetchAll(PDO::FETCH_ASSOC);

// Export to CSV
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename="job_views.csv"');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Job ID', 'Title', 'Total Views']);
    foreach ($totalViews as $view) {
        fputcsv($output, [$view['job_id'], $view['title'], $view['total_views']]);
    }
    fclose($output);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Job Views Report</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/main_nav.css">
    <style>
        body {
            background:rgb(27, 214, 205);
            padding: 20px;
        }

        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
        }

        table th,
        table td {
            vertical-align: middle;
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
                    <li class="nav-item"><a class="nav-link" href="company_dashboard.php">Dashboard</a></li>

                    <li class="nav-item"><a class="nav-link" href="../index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="../logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <h2 class="mb-4">Job Views Dashboard</h2>

        <form class="row g-3 mb-4" method="get">
            <div class="col-md-3">
                <label>Date From</label>
                <input type="date" name="date_from" class="form-control"
                    value="<?= htmlspecialchars($_GET['date_from'] ?? '') ?>">
            </div>
            <div class="col-md-3">
                <label>Date To</label>
                <input type="date" name="date_to" class="form-control"
                    value="<?= htmlspecialchars($_GET['date_to'] ?? '') ?>">
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <a href="?export=csv" class="btn btn-success">Export to CSV</a>
            </div>
        </form>

        <h4>Total Views Per Job</h4>
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>Job ID</th>
                    <th>Title</th>
                    <th>Total Views</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($totalViews as $view): ?>
                    <tr>
                        <td><?= htmlspecialchars($view['job_id']) ?></td>
                        <td><?= htmlspecialchars($view['title']) ?></td>
                        <td><?= htmlspecialchars($view['total_views']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h4>Recent Views (Last 7 Days)</h4>
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Job ID</th>
                    <th>Title</th>
                    <th>Views</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recentViews as $view): ?>
                    <tr>
                        <td><?= htmlspecialchars($view['job_id']) ?></td>
                        <td><?= htmlspecialchars($view['title']) ?></td>
                        <td><?= htmlspecialchars($view['recent_views']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h4>Views Per User</h4>
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>User</th>
                    <th>Total Views</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($userViews as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['full_name']) ?></td>
                        <td><?= htmlspecialchars($user['views_count']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>