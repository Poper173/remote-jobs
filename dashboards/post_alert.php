<?php
session_start();
require_once '../includes/db.php';

// Ensure only super admin (role_id = 1) can access
if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
    header("Location: ../login.php");
    exit();
}

$message = '';

// Handle alert submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $type = $_POST['type'];
    $content = trim($_POST['content']);
    $user_id = $_SESSION['user_id']; // Super admin's user ID

    if (!empty($type) && !empty($content)) {
        $stmt = $pdo->prepare("INSERT INTO alerts (user_id, type, content) VALUES (?, ?, ?)");
        if ($stmt->execute([$user_id, $type, $content])) {
            $message = "Alert posted successfully.";
        } else {
            $message = "Failed to post alert. Please try again.";
        }
    } else {
        $message = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Post Alert - JobSearch</title>
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
        <h2 class="text-center">Post a New Alert</h2>

        <?php if ($message): ?>
            <div class="alert alert-info text-center"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="POST" action="post_alert.php">
            <div class="mb-3">
                <label for="type" class="form-label">Alert Type</label>
                <select name="type" id="type" class="form-select" required>
                    <option value="">Select Type</option>
                    <option value="job">Job</option>
                    <option value="system">System</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Alert Content</label>
                <textarea name="content" id="content" class="form-control" rows="5" placeholder="Enter alert content" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary w-100">Post Alert</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>