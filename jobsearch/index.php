<?php
session_start();
require_once 'includes/db.php';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboards/company_dashboard.php");
    exit;
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    $stmt = $pdo->prepare("SELECT user_id FROM users WHERE username = ? AND password = ?");
    $stmt->execute([$username, $password]);
    $user_id = $stmt->fetchColumn();

    if ($user_id) {
        $_SESSION['user_id'] = $user_id;
        header("Location: dashboards/company_dashboard.php");
        exit;
    } else {
        $message = "❌ Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/main_nav.css">
    <link rel="stylesheet" href="assets/sidebar.css">
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center">Login</h2>
        <?php if ($message): ?>
            <div class="alert alert-danger text-center"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <form method="post">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" name="username" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>