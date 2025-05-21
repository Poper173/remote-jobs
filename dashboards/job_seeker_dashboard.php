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

// Fetch user details
$stmt = $pdo->prepare("SELECT full_name, email, profile_picture FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    // If no user is found, log out and redirect to login
    session_destroy();
    header("Location: ../login.php");
    exit();
}

// Check profile completion
$tables = ['education', 'certificates', 'experience', 'languages', 'skills', 'resumes', 'uploaded_certificates'];
$completed_tables = 0;

foreach ($tables as $table) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM $table WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $count = $stmt->fetchColumn();
    if ($count > 0) {
        $completed_tables++;
    }
}

$profile_completion = round(($completed_tables / count($tables)) * 100);

// Handle profile picture upload
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES['profile_picture'])) {
    $file = $_FILES['profile_picture'];

    // Validate file
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $message = "Error uploading file.";
    } else {
        $allowed_extensions = ['jpg', 'jpeg', 'png'];
        $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($file_extension, $allowed_extensions)) {
            $message = "Invalid file type. Only JPG, JPEG, and PNG are allowed.";
        } elseif ($file['size'] > 2 * 1024 * 1024) { // 2 MB limit
            $message = "File size must not exceed 2 MB.";
        } else {
            // Save file
            $upload_dir = '../uploads/profile_pictures/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $file_name = uniqid() . '.' . $file_extension;
            $file_path = $upload_dir . $file_name;

            if (move_uploaded_file($file['tmp_name'], $file_path)) {
                // Update the user's profile picture in the database
                $stmt = $pdo->prepare("UPDATE users SET profile_picture = ? WHERE user_id = ?");
                if ($stmt->execute([$file_path, $user_id])) {
                    $message = "You uploaded your profile picture successfully.";
                    // Refresh user data
                    $stmt = $pdo->prepare("SELECT full_name, email, profile_picture FROM users WHERE user_id = ?");
                    $stmt->execute([$user_id]);
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
                } else {
                    $message = "Failed to update profile picture in the database.";
                }
            } else {
                $message = "Failed to save the uploaded file.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard | JobSearch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/main_nav.css">
    <style>
        body {
            background: #f0f2f5;
            font-family: 'Segoe UI', sans-serif;
        }

        .sidebar {
            height: 100vh;
            background-color: #ffffff;
            border-right: 1px solid #dee2e6;
            padding-top: 1rem;
        }

        .sidebar .nav-link {
            color: #333;
            font-weight: 500;
            padding: 10px 15px;
        }

        .sidebar .nav-link.active {
            background-color: #007bff;
            color: white !important;
            border-radius: 5px;
        }

        .main-content {
            padding: 2rem;
        }

        .navbar-brand img {
            max-height: 40px;
        }

        .profile-image {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #007bff;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            font-weight: 500;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .progress-bar {
            background-color: #007bff;
        }

        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
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
                    <li class="nav-item"><a class="nav-link" href="../index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="../logout.php">Logout</a></li>
      
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Structure -->
    <div class="container-fluid">
        <div class="row">

            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar">
                <div class="position-sticky">
                    <ul class="nav flex-column">
                       

                        <li class="nav-item"><a class="nav-link active" href="job_seeker_dashboard.php">Dashboard</a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="message_admin.php">Message Admin</a></li>
                        <li class="nav-item"><a class="nav-link" href="experience.php">Experience</a></li>
                        <li class="nav-item"><a class="nav-link" href="education.php">Education</a></li>
                        <li class="nav-item"><a class="nav-link" href="skills.php">Skills</a></li>
                        <li class="nav-item"><a class="nav-link" href="languages.php">Languages</a></li>
                        <li class="nav-item"><a class="nav-link" href="certificates.php">Certificates</a></li>
                        <li class="nav-item"><a class="nav-link" href="resumes.php">Resumes</a></li>
                        <li class="nav-item"><a class="nav-link" href="vacancies.php">View Job Vacancies</a></li>
                        <li class="nav-item"><a class="nav-link" href="user_languages.php">language proficiency</a></li>
                        <li class="nav-item"><a class="nav-link" href="who.php">who viewed profile </a></li>
                        <li class="nav-item"><a class="nav-link" href="my_applications.php">📄 View Applications</a></li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 main-content">
                <h1 class="mb-4">Welcome, <?= htmlspecialchars($user['full_name']) ?> 👋</h1>
                <p class="mb-4">This is your personal dashboard. Use the menu to manage your job-seeking profile.</p>

                <!-- Profile Completion Progress -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Profile Completion</h5>
                    </div>
                    <div class="card-body">
                        <p>Complete your profile to start applying for jobs.</p>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: <?= $profile_completion ?>%;"
                                aria-valuenow="<?= $profile_completion ?>" aria-valuemin="0" aria-valuemax="100">
                                <?= $profile_completion ?>%</div>
                        </div>
                        <?php if ($profile_completion < 100): ?>
                            <div class="alert alert-danger mt-3">⚠️ Your profile is incomplete. Please finish registration
                                to avoid future disturbances.</div>
                        <?php else: ?>
                            <div class="alert alert-success mt-3">✅ Your profile is complete! You can now apply for jobs.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Profile Picture Section -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Profile Picture</h5>
                    </div>
                    <div class="card-body text-center">
                        <img src="<?= htmlspecialchars($user['profile_picture'] ?? '../uploads/default_profile.png') ?>"
                            alt="Profile Picture" class="img-thumbnail profile-image mb-3">
                        <form method="POST" enctype="multipart/form-data">
                            <input type="file" name="profile_picture" class="form-control mb-2" required>
                            <button type="submit" class="btn btn-primary">Upload Picture</button>
                        </form>
                        <?php if ($message): ?>
                            <div class="alert alert-success mt-3"><?= htmlspecialchars($message) ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>