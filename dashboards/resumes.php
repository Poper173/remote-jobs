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

// Fetch user data
$stmt = $pdo->prepare("SELECT * FROM resumes WHERE user_id = ?");
$stmt->execute([$user_id]);
$resumes = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT * FROM uploaded_certificates WHERE user_id = ?");
$stmt->execute([$user_id]);
$uploaded_certificates = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT * FROM languages WHERE user_id = ?");
$stmt->execute([$user_id]);
$languages = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT * FROM education WHERE user_id = ?");
$stmt->execute([$user_id]);
$education = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT * FROM certificates WHERE user_id = ?");
$stmt->execute([$user_id]);
$certificates = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT * FROM skills WHERE user_id = ?");
$stmt->execute([$user_id]);
$skills = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle resume upload
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES['resume_file'])) {
    $file = $_FILES['resume_file'];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        $message = "Error uploading file.";
    } elseif (pathinfo($file['name'], PATHINFO_EXTENSION) !== 'pdf') {
        $message = "Only PDF files are allowed.";
    } elseif ($file['size'] > 5 * 1024 * 1024) { // 5 MB limit
        $message = "File size must not exceed 5 MB.";
    } else {
        $upload_dir = '../uploads/resumes/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $file_name = uniqid() . '_' . basename($file['name']);
        $file_path = $upload_dir . $file_name;

        if (move_uploaded_file($file['tmp_name'], $file_path)) {
            $stmt = $pdo->prepare("INSERT INTO resumes (user_id, resume_url) VALUES (?, ?)");
            if ($stmt->execute([$user_id, $file_path])) {
                header("Location: resumes.php?resume_uploaded=1");
                exit();
            } else {
                $message = "Failed to save resume details. Please try again.";
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
    <title>My Resumes - JobSearch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/main_nav.css">

    <style>
        body {
            background: #f0f2f5;
            font-family: 'Segoe UI', sans-serif;
        }

        .card {
            margin-bottom: 1rem;
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="../index.php">
            <img src="/templates/pwk.png" alt="Logo" style="max-width:150px;" />            </a>
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
    <div class="container mt-5">
        <h1 class="mb-4 text-center">My Uploaded Data</h1>

        <!-- Resume Upload -->
        <div class="card">
            <div class="card-header bg-primary text-white">Upload Resume</div>
            <div class="card-body">
                <?php if ($message): ?>
                    <div class="alert alert-danger"><?php echo $message; ?></div>
                <?php endif; ?>
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Upload Resume (PDF only)</label>
                        <input type="file" name="resume_file" class="form-control" accept=".pdf" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Upload Resume</button>
                </form>
            </div>
        </div>

        <!-- Display Data -->
        <div class="card">
            <div class="card-header bg-primary text-white">Resumes</div>
            <div class="card-body">
                <?php if ($resumes): ?>
                    <ul>
                        <?php foreach ($resumes as $resume): ?>
                            <li><a href="<?= htmlspecialchars($resume['resume_url']) ?>" target="_blank">View Resume</a></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No resumes uploaded.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-primary text-white">Uploaded Certificates</div>
            <div class="card-body">
                <?php if ($uploaded_certificates): ?>
                    <ul>
                        <?php foreach ($uploaded_certificates as $certificate): ?>
                            <li><a href="<?= htmlspecialchars($certificate['file_path']) ?>" target="_blank"><?= htmlspecialchars($certificate['certificate_title']) ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No certificates uploaded.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-primary text-white">Languages</div>
            <div class="card-body">
                <?php if ($languages): ?>
                    <ul>
                        <?php foreach ($languages as $language): ?>
                            <li><?= htmlspecialchars($language['language_name']) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No languages added.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-primary text-white">Education</div>
            <div class="card-body">
                <?php if ($education): ?>
                    <ul>
                        <?php foreach ($education as $edu): ?>
                            <li><?= htmlspecialchars($edu['level']) ?> - <?= htmlspecialchars($edu['institution']) ?> (<?= htmlspecialchars($edu['start_year']) ?> - <?= htmlspecialchars($edu['end_year']) ?>)</li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No education records added.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-primary text-white">Certificates</div>
            <div class="card-body">
                <?php if ($certificates): ?>
                    <ul>
                        <?php foreach ($certificates as $certificate): ?>
                            <li><?= htmlspecialchars($certificate['title']) ?> - <?= htmlspecialchars($certificate['issuer']) ?> (<?= htmlspecialchars($certificate['issued_date']) ?>)</li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No certificates added.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-primary text-white">Skills</div>
            <div class="card-body">
                <?php if ($skills): ?>
                    <ul>
                        <?php foreach ($skills as $skill): ?>
                            <li><?= htmlspecialchars($skill['skill_name']) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No skills added.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>