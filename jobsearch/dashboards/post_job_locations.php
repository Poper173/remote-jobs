<?php
// filepath: /jobsearch/jobsearch/dashboards/post_job_locations.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $location_name = trim($_POST["location_name"]);
    $city = trim($_POST["city"]);
    $region = trim($_POST["region"]);
    $country = trim($_POST["country"]);

    try {
        $stmt = $pdo->prepare("INSERT INTO locations (name, city, region, country) VALUES (?, ?, ?, ?)");
        $stmt->execute([$location_name, $city, $region, $country]);
        $message = "✅ Location added successfully!";
    } catch (Exception $e) {
        $message = "❌ Error adding location: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Job Locations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/main_nav.css">
    <link rel="stylesheet" href="../assets/sidebar.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="../index.php">Job Search</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="../index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="../logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <?php if ($message): ?>
            <div class="alert alert-info text-center"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5>Add Job Location</h5>
            </div>
            <div class="card-body">
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Location Name</label>
                        <input type="text" class="form-control" name="location_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">City</label>
                        <input type="text" class="form-control" name="city" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Region</label>
                        <input type="text" class="form-control" name="region">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Country</label>
                        <input type="text" class="form-control" name="country" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Location</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>