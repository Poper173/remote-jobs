<?php
session_start();
require_once '../includes/db.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Fetch alerts
$stmt = $pdo->prepare("SELECT a.type, a.content, a.created_at, u.full_name AS posted_by 
                       FROM alerts a 
                       LEFT JOIN users u ON a.user_id = u.user_id 
                       ORDER BY a.created_at DESC");
$stmt->execute();
$alerts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>View Alerts - JobSearch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center">System Alerts</h2>

        <?php if ($alerts): ?>
            <div class="list-group mt-4">
                <?php foreach ($alerts as $alert): ?>
                    <div class="list-group-item">
                        <h5 class="mb-1"><?= htmlspecialchars(ucfirst($alert['type'])) ?> Alert</h5>
                        <p class="mb-1"><?= htmlspecialchars($alert['content']) ?></p>
                        <small class="text-muted">Posted by <?= htmlspecialchars($alert['posted_by'] ?? 'Unknown') ?> on <?= htmlspecialchars($alert['created_at']) ?></small>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-center mt-4">No alerts found.</p>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>