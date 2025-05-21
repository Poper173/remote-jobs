<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ob_start();
session_start();
require_once 'includes/db.php';

$message = '';

$timeout_duration = 9000;
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    session_unset();
    session_destroy();
    header("Location: login.php?timeout=1");
    exit();
}
$_SESSION['LAST_ACTIVITY'] = time();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['password'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT user_id, full_name, password_hash, role_id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['role_id'] = $user['role_id']; // might be NULL
        $_SESSION['LAST_ACTIVITY'] = time();

        $ip_address = $_SERVER['REMOTE_ADDR'];
        $log_stmt = $pdo->prepare("INSERT INTO login_logs (user_id, ip_address) VALUES (?, ?)");
        $log_stmt->execute([$user['user_id'], $ip_address]);

        if (!is_null($user['role_id'])) {
            switch ($user['role_id']) {
                case 1:
                    header("Location: dashboards/super_admin_dashboard.php");
                    exit();
                case 2:
                    header("Location: dashboards/company_dashboard.php");
                    exit();
                case 3:
                    header("Location: dashboards/job_seeker_dashboard.php");
                    exit();
                default:
                    $message = "❌ Unauthorized role.";
            }
        } else {
            $message = "✅ Login successful. Please wait for admin to assign your role.";
        }
    } else {
        $message = "❌ Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login - JobSearch</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/main_nav.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }

        .btn-message {
            background-color: #6f42c1;
            color: white;
        }

        .btn-message:hover {
            background-color: #5a379e;
        }
    </style>
</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="templates/pwk.png" alt="JobSearch Logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Login Form -->
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card p-4 shadow-sm">
                    <h4 class="text-center text-primary mb-3">Login to your account</h4>

                    <?php if ($message): ?>
                        <div class="alert alert-info text-center"><?= htmlspecialchars($message) ?></div>
                    <?php endif; ?>

                    <?php if (isset($_GET['timeout'])): ?>
                        <div class="alert alert-warning text-center">Session expired. Please login again.</div>
                    <?php endif; ?>


                    <form method="POST" action="login.php">
                        <div class="mb-3">
                            <label class="form-label">Email address</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>

                    <p class="mt-3 text-center">
                        Don't have an account? <a href="register.php">Register</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php ob_end_flush(); ?>