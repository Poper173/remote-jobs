<?php
// filepath: /var/www/html/jobsearch/dashboards/post_privacy_policy.php

session_start();
require_once '../includes/db.php';

// Ensure only super admin (role_id = 1) can access
if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
    header("Location: ../login.php");
    exit();
}

$message = '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $content = trim($_POST['content']);
    $effective_date = $_POST['effective_date'];

    if (!empty($content) && !empty($effective_date)) {
        // Check if a policy already exists
        $stmt = $pdo->query("SELECT COUNT(*) FROM privacy_policy");
        $policy_exists = $stmt->fetchColumn() > 0;

        if ($policy_exists) {
            // Update the existing policy
            $stmt = $pdo->prepare("UPDATE privacy_policy SET content = ?, effective_date = ? WHERE policy_id = 1");
            if ($stmt->execute([$content, $effective_date])) {
                $message = "✅ Privacy policy updated successfully.";
            } else {
                $message = "❌ Failed to update the privacy policy.";
            }
        } else {
            // Insert a new policy
            $stmt = $pdo->prepare("INSERT INTO privacy_policy (content, effective_date) VALUES (?, ?)");
            if ($stmt->execute([$content, $effective_date])) {
                $message = "✅ Privacy policy added successfully.";
            } else {
                $message = "❌ Failed to add the privacy policy.";
            }
        }
    } else {
        $message = "⚠️ All fields are required.";
    }
}

// Fetch the current privacy policy
$stmt = $pdo->query("SELECT * FROM privacy_policy LIMIT 1");
$policy = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Privacy Policy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet"  href="../assets/main_nav.css">
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
        <h1 class="text-center">Post Privacy Policy</h1>

        <?php if ($message): ?>
            <div class="alert alert-info text-center"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST" class="mt-4">
            <div class="mb-3">
                <label for="content" class="form-label">Privacy Policy Content</label>
                <textarea name="content" id="content" class="form-control" rows="10" required><?= htmlspecialchars($policy['content'] ?? '') ?></textarea>
            </div>
            <div class="mb-3">
                <label for="effective_date" class="form-label">Effective Date</label>
                <input type="date" name="effective_date" id="effective_date" class="form-control" value="<?= htmlspecialchars($policy['effective_date'] ?? '') ?>" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Save Privacy Policy</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>