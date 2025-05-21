<?php
session_start();
require_once '../includes/db.php';

// Ensure only super admin (role_id = 1) can access
if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
    header("Location: ../login.php");
    exit();
}

// Fetch audit logs
$stmt = $pdo->prepare("SELECT al.log_id, al.action, al.log_time, u.full_name AS performed_by 
                       FROM audit_logs al 
                       LEFT JOIN users u ON al.user_id = u.user_id 
                       ORDER BY al.log_time DESC");
$stmt->execute();
$audit_logs = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>View System Activities - JobSearch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/main_nav.css">
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <img src="/templates/pwk.png" alt="Logo" style="max-width:150px;" /> 
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="super_admin_dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="../logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>
     
    <div class="container mt-5">
        <h2 class="text-center">System Activities</h2>

        <?php if ($audit_logs): ?>
            <table class="table table-bordered bg-white shadow-sm mt-4">
                <thead class="table-primary">
                    <tr>
                        <th>Action</th>
                        <th>Performed By</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($audit_logs as $log): ?>
                        <tr>
                            <td><?= htmlspecialchars($log['action']) ?></td>
                            <td><?= htmlspecialchars($log['performed_by'] ?? 'Unknown') ?></td>
                            <td><?= htmlspecialchars($log['log_time']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-center mt-4">No system activities found.</p>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>