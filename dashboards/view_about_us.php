<?php
require_once '../includes/db.php';
$stmt = $pdo->query("SELECT * FROM about_us ORDER BY created_at DESC");
$entries = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>About Us Entries</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <h2 class="mb-4 text-center">About Us Entries</h2>

        <?php if ($entries): ?>
            <?php foreach ($entries as $entry): ?>
                <div class="card mb-3 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($entry['title']) ?></h5>
                        <p class="card-text"><?= nl2br(htmlspecialchars($entry['description'])) ?></p>
                        <?php if ($entry['team_members']): ?>
                            <p><strong>Team Members:</strong> <?= nl2br(htmlspecialchars($entry['team_members'])) ?></p>
                        <?php endif; ?>
                        <?php if ($entry['contact_email']): ?>
                            <p><strong>Contact Email:</strong> <?= htmlspecialchars($entry['contact_email']) ?></p>
                        <?php endif; ?>
                        <p class="text-muted"><small>Posted on: <?= $entry['created_at'] ?></small></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">No entries found.</p>
        <?php endif; ?>
    </div>
</body>

</html>