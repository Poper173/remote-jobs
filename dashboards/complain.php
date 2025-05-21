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

// Handle complaint submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['complaint_message'])) {
    $complaint_message = trim($_POST['complaint_message']);

    if (empty($complaint_message)) {
        $message = "❌ Complaint message cannot be empty.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO complaints (user_id, message) VALUES (?, ?)");
        if ($stmt->execute([$user_id, $complaint_message])) {
            $message = "✅ Your complaint has been submitted successfully.";
        } else {
            $message = "❌ Failed to submit your complaint. Please try again.";
        }
    }
}

// Fetch scheduled interviews
$interviews_stmt = $pdo->prepare("
    SELECT i.interview_id, i.scheduled_time, j.title AS job_title, c.name AS company_name
    FROM interviews i
    JOIN applications a ON i.application_id = a.application_id
    JOIN job_posts j ON a.job_id = j.job_id
    JOIN companies c ON j.company_id = c.company_id
    WHERE a.jobseeker_id = ? AND i.scheduled_time >= NOW()
    ORDER BY i.scheduled_time ASC
");
$interviews_stmt->execute([$user_id]);
$interviews = $interviews_stmt->fetchAll(PDO::FETCH_ASSOC);
$interview_count = count($interviews);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaints & Notifications</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/main_nav.css">
    <style>
        .notification-icon {
            position: relative;
            cursor: pointer;
        }

        .notification-icon .badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: red;
            color: white;
            font-size: 12px;
        }

        .notification-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 300px;
            display: none;
            z-index: 1000;
        }

        .notification-dropdown.active {
            display: block;
        }

        .notification-dropdown .dropdown-item {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .notification-dropdown .dropdown-item:last-child {
            border-bottom: none;
        }
    </style>
</head>

<body class="bg-light">
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
                    <li class="nav-item"><a class="nav-link" href="company_dashboard.php">Dashboard</a></li>

                    <li class="nav-item"><a class="nav-link" href="../index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="../logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>
   
                        <div class="notification-icon" id="notificationIcon">
                            <i class="bi bi-bell text-white fs-4"></i>
                            <?php if ($interview_count > 0): ?>
                                <span class="badge"><?= $interview_count ?></span>
                            <?php endif; ?>
                            <div class="notification-dropdown">
                                <?php if ($interview_count > 0): ?>
                                    <?php foreach ($interviews as $interview): ?>
                                        <div class="dropdown-item">
                                            <strong><?= htmlspecialchars($interview['job_title']) ?></strong><br>
                                            <?= htmlspecialchars($interview['company_name']) ?><br>
                                            <small><?= htmlspecialchars($interview['scheduled_time']) ?></small>
                                        </div>
                                    <?php endforeach; ?>
                                    <a href="interview.php" class="dropdown-item text-center text-primary">View All</a>
                                <?php else: ?>
                                    <div class="dropdown-item text-center">No upcoming interviews.</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    
 
                
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container py-5">
        <h2 class="mb-4">Submit a Complaint</h2>

        <?php if ($message): ?>
            <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="complaint_message" class="form-label">Your Complaint</label>
                <textarea name="complaint_message" id="complaint_message" class="form-control" rows="5"
                    required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit Complaint</button>
        </form>
    </div>

    <script>
        document.getElementById('notificationIcon').addEventListener('click', function () {
            const dropdown = document.querySelector('.notification-dropdown');
            dropdown.classList.toggle('active');
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>