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
$message = "";

// Fetch current settings
$stmt = $pdo->prepare("SELECT notification_emails, dark_mode FROM settings WHERE user_id = ?");
$stmt->execute([$user_id]);
$settings = $stmt->fetch(PDO::FETCH_ASSOC);

// If no settings exist for the user, create default settings
if (!$settings) {
    $stmt = $pdo->prepare("INSERT INTO settings (user_id, notification_emails, dark_mode) VALUES (?, 1, 0)");
    $stmt->execute([$user_id]);
    $settings = ['notification_emails' => 1, 'dark_mode' => 0];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $notification_emails = isset($_POST['notification_emails']) ? 1 : 0;
    $dark_mode = isset($_POST['dark_mode']) ? 1 : 0;

    $stmt = $pdo->prepare("UPDATE settings SET notification_emails = ?, dark_mode = ? WHERE user_id = ?");
    if ($stmt->execute([$notification_emails, $dark_mode, $user_id])) {
        $message = "✅ Settings updated successfully.";
        $settings['notification_emails'] = $notification_emails;
        $settings['dark_mode'] = $dark_mode;
    } else {
        $message = "❌ Failed to update settings. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Settings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet"  href="../assets/main_nav.css">
</head>

<body class="bg-light">
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
     
    <div class="container py-5">
        <h2 class="mb-4">User Settings</h2>

        <?php if ($message): ?>
            <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="notification_emails" id="notification_emails"
                    <?= $settings['notification_emails'] ? 'checked' : '' ?>>
                <label class="form-check-label" for="notification_emails">
                    Enable Notification Emails
                </label>
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="dark_mode" id="dark_mode"
                    <?= $settings['dark_mode'] ? 'checked' : '' ?>>
                <label class="form-check-label" for="dark_mode">
                    Enable Dark Mode
                </label>
            </div>

            <button type="submit" class="btn btn-primary">Save Settings</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>