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
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $issuer = isset($_POST['issuer']) ? trim($_POST['issuer']) : '';
    $issued_date = $_POST['issued_date'] ?? '';
    $file = $_FILES['certificate_file'];

    // Validate input
    if (empty($title) || empty($issuer) || empty($issued_date)) {
        $message = "All fields are required.";
    } elseif ($file['error'] !== UPLOAD_ERR_OK) {
        $message = "Error uploading file.";
    } elseif (pathinfo($file['name'], PATHINFO_EXTENSION) !== 'pdf') {
        $message = "Only PDF files are allowed.";
    } elseif ($file['size'] > 5 * 1024 * 1024) { // 5 MB limit
        $message = "File size must not exceed 5 MB.";
    } else {
        // Move uploaded file to the uploads directory
        $upload_dir = '../uploads/certificates/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $file_name = uniqid() . '_' . basename($file['name']);
        $file_path = $upload_dir . $file_name;

        if (move_uploaded_file($file['tmp_name'], $file_path)) {
            // Insert certificate details into the database
            $stmt = $pdo->prepare("INSERT INTO certificates (user_id, title, issuer, issued_date) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$user_id, $title, $issuer, $issued_date])) {
                // Insert file details into the uploaded_certificates table
                $stmt = $pdo->prepare("INSERT INTO uploaded_certificates (user_id, certificate_title, file_path) VALUES (?, ?, ?)");
                if ($stmt->execute([$user_id, $title, $file_path])) {
                    header("Location: job_seeker_dashboard.php?certificate_uploaded=1");
                    exit();
                } else {
                    $message = "Failed to save uploaded file details. Please try again.";
                }
            } else {
                $message = "Failed to save certificate details. Please try again.";
            }
        } else {
            $message = "Failed to move uploaded file.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Certificate - JobSearch</title>
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
                <img src="../assets/images/logo.png" alt="JobSearch Logo" style="height: 40px;">
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

    <!-- Certificates Form -->
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="form-card">
                    <h4 class="text-center text-primary mb-3">Add Certificate</h4>

                    <?php if ($message): ?>
                        <div class="alert alert-danger"><?php echo $message; ?></div>
                    <?php endif; ?>

                    <form method="POST" action="certificates.php" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Certificate Title</label>
                            <input type="text" name="title" class="form-control" placeholder="Enter certificate title" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Issuer</label>
                            <input type="text" name="issuer" class="form-control" placeholder="Enter issuer name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Issued Date</label>
                            <input type="date" name="issued_date" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Upload Certificate (PDF only)</label>
                            <input type="file" name="certificate_file" class="form-control" accept=".pdf" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Add Certificate</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>