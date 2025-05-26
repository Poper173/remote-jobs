<?php
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
    $job_title = trim($_POST["job_title"]);
    $job_description = trim($_POST["job_description"]);
    $job_type = $_POST["job_type"];
    $category_id = $_POST["job_category"];
    $city = trim($_POST["job_location"]);
    $region = trim($_POST["region"]);
    $country = trim($_POST["country"]);
    $salary_range = trim($_POST["salary_range"]);
    $company_name = trim($_POST["company_name"]);
    $company_description = trim($_POST["company_description"]);
    $post_number = trim($_POST["post_number"]);
    $skills_input = trim($_POST["skills"]); // Get skills as a comma-separated string
    $application_start = $_POST["application_start"]; // Get application start date
    $application_end = $_POST["application_end"]; // Get application end date

    // Process skills into an array
    $skills = array_map('trim', explode(',', $skills_input));

    try {
        $pdo->beginTransaction();

        // Insert or fetch company
        $stmt = $pdo->prepare("SELECT company_id FROM companies WHERE name = ?");
        $stmt->execute([$company_name]);
        $company_id = $stmt->fetchColumn();

        if (!$company_id) {
            $stmt = $pdo->prepare("INSERT INTO companies (name, description, created_at, verified) VALUES (?, ?, NOW(), 0)");
            $stmt->execute([$company_name, $company_description]);
            $company_id = $pdo->lastInsertId();
        }

        // Insert or fetch location
        $stmt = $pdo->prepare("SELECT location_id FROM locations WHERE city = ? AND region = ? AND country = ?");
        $stmt->execute([$city, $region, $country]);
        $location_id = $stmt->fetchColumn();

        if (!$location_id) {
            $stmt = $pdo->prepare("INSERT INTO locations (city, region, country) VALUES (?, ?, ?)");
            $stmt->execute([$city, $region, $country]);
            $location_id = $pdo->lastInsertId();
        }

        // Insert employer profile if not exists
        $user_id = $_SESSION['user_id'];
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM employer_profiles WHERE employer_id = ?");
        $stmt->execute([$user_id]);
        $employer_exists = $stmt->fetchColumn();

        if (!$employer_exists) {
            $stmt = $pdo->prepare("INSERT INTO employer_profiles (employer_id, company_id, employer_name, created_at) VALUES (?, ?, ?, NOW())");
            $stmt->execute([$user_id, $company_id, $company_name]);
        }

        // Insert job post
        $stmt = $pdo->prepare("INSERT INTO job_posts (employer_id, employer_name, title, category_id, location_id, salary_range, company_id, post_number, application_start, application_end, duties, qualifications, posted_at, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), 'active')");
        $stmt->execute([$user_id, $company_name, $job_title, $category_id, $location_id, $salary_range, $company_id, $post_number, $application_start, $application_end, $job_description, $job_description]);
        $job_id = $pdo->lastInsertId();

        // Insert job skills
        foreach ($skills as $skill) {
            if (!empty($skill)) {
                // Insert skill if not exists
                $stmt = $pdo->prepare("SELECT skill_id FROM skills WHERE skill_name = ?");
                $stmt->execute([$skill]);
                $skill_id = $stmt->fetchColumn();

                if (!$skill_id) {
                    $stmt = $pdo->prepare("INSERT INTO skills (skill_name, user_id) VALUES (?, ?)");
                    $stmt->execute([$skill, $user_id]);
                    $skill_id = $pdo->lastInsertId();
                }

                // Link skill to job
                $stmt = $pdo->prepare("INSERT INTO job_skills (job_id, skill_id) VALUES (?, ?)");
                $stmt->execute([$job_id, $skill_id]);
            }
        }

        $pdo->commit();
        $message = "✅ Job posted successfully!";
    } catch (Exception $e) {
        $pdo->rollBack();
        $message = "❌ Error posting job: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Dashboard - Post Job</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/main_nav.css">
    <link rel="stylesheet" href="../assets/sidebar.css">
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

        .card {
            border-radius: 10px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .alert-info {
            color: #0c5460;
            background-color: #d1ecf1;
            border-color: #bee5eb;
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
                        <li class="nav-item"><a class="nav-link active" href="company_dashboard.php">📤 Post Job</a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="view_applicants.php">📄 View Applications</a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="post_job_locations.php">📍 Job Locations</a></li>
                        <li class="nav-item"><a class="nav-link" href="views.php">👁️ Job Viewers</a></li>
                        <li class="nav-item"><a class="nav-link" href="post_employer_profiles.php">👤 Employer
                                Profiles</a></li>
                        <li class="nav-item"><a class="nav-link" href="interview.php">📅 Schedule Interview</a></li>
                        <li class="nav-item"><a class="nav-link" href="complain.php">📢 Complaints</a></li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 main-content">
                <?php if ($message): ?>
                    <div class="alert alert-info text-center"><?= htmlspecialchars($message) ?></div>
                <?php endif; ?>

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Post a New Job</h5>
                    </div>
                    <div class="card-body">
                        <form method="post">
                            <div class="mb-3">
                                <label class="form-label">Job Title</label>
                                <input type="text" class="form-control" name="job_title" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Job Description</label>
                                <textarea class="form-control" name="job_description" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Job Type</label>
                                <select class="form-select" name="job_type" required>
                                    <option value="Full-time">Full-time</option>
                                    <option value="Part-time">Part-time</option>
                                    <option value="Contract">Contract</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Job Category</label>
                                <select class="form-select" name="job_category" required>
                                    <option value="">Select a Category</option>
                                    <?php
                                    $stmt = $pdo->query("SELECT category_id, name FROM categories");
                                    while ($row = $stmt->fetch()) {
                                        echo "<option value='{$row['category_id']}'>{$row['name']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">City</label>
                                <input type="text" class="form-control" name="job_location" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Region</label>
                                <input type="text" class="form-control" name="region">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Country</label>
                                <input type="text" class="form-control" name="country">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Salary Range</label>
                                <input type="text" class="form-control" name="salary_range" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Post Number</label>
                                <input type="text" class="form-control" name="post_number" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Company Name</label>
                                <input type="text" class="form-control" name="company_name" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Company Description</label>
                                <textarea class="form-control" name="company_description" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Application Start Date</label>
                                <input type="date" class="form-control" name="application_start" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Application End Date</label>
                                <input type="date" class="form-control" name="application_end" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Required Skills (Separate skills with commas)</label>
                                <input type="text" class="form-control" name="skills"
                                    placeholder="e.g., HTML, CSS, JavaScript" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Post Job</button>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
