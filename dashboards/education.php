<?php
// Display all errors during development (disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../includes/db.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if keys exist in $_POST and assign default values if not
    $level = isset($_POST['level']) ? trim($_POST['level']) : '';
    $institution = isset($_POST['institution']) ? trim($_POST['institution']) : '';
    $start_year = isset($_POST['start_year']) ? $_POST['start_year'] : '';
    $end_year = isset($_POST['end_year']) ? $_POST['end_year'] : '';

    // Validate input
    if (empty($level) || empty($institution) || empty($start_year) || empty($end_year)) {
        $message = "All fields are required.";
    } elseif (!is_numeric($start_year) || !is_numeric($end_year) || $start_year > $end_year) {
        $message = "Invalid start or end year.";
    } elseif ($start_year < 1900 || $start_year > 2100 || $end_year < 1900 || $end_year > 2100) {
        $message = "Start and end year must be between 1900 and 2100.";
    } else {
        // Insert education into the database
        $stmt = $pdo->prepare("INSERT INTO education (user_id, level, institution, start_year, end_year) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$user_id, $level, $institution, $start_year, $end_year])) {
            header("Location: job_seeker_dashboard.php?education_added=1");
            exit();
        } else {
            $message = "Failed to add education. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Education - JobSearch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/main_nav.css">
    <style>
        body {
            background: #f0f2f5;
            font-family: 'Segoe UI', sans-serif;
        }

        .form-card {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        .form-label {
            font-weight: 500;
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
                    <li class="nav-item"><a class="nav-link" href="job_seeker_dashboard.php">Dashboard</a></li>

                    <li class="nav-item"><a class="nav-link" href="../index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="../logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Education Form -->
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="form-card">
                    <h4 class="text-center text-primary mb-3">Add Education</h4>

                    <?php if ($message): ?>
                        <div class="alert alert-danger"><?php echo $message; ?></div>
                    <?php endif; ?>

                    <form method="POST" action="education.php">
                        <div class="mb-3">
                            <label class="form-label">Level</label>
                            <input type="text" name="level" class="form-control"
                                placeholder="Enter education level (e.g., Bachelor's)" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Institution</label>
                            <input type="text" name="institution" class="form-control"
                                placeholder="Enter institution name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Start Year</label>
                            <input type="number" name="start_year" class="form-control"
                                placeholder="Enter start year (e.g., 2020)" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">End Year</label>
                            <input type="number" name="end_year" class="form-control"
                                placeholder="Enter end year (e.g., 2024)" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Add Education</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>