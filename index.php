<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'includes/db.php';

// Fetch latest 6 active job postings joining companies and locations
$stmt = $pdo->query("SELECT 
    jp.title AS post_name,
    c.name AS employer,
    COALESCE(CONCAT(l.city, ', ', l.region, ', ', l.country), 'Unknown Location') AS location
FROM 
    job_posts jp
JOIN 
    companies c ON jp.company_id = c.company_id
LEFT JOIN 
    locations l ON jp.location_id = l.location_id
WHERE 
    jp.status = 'active'
ORDER BY 
    jp.posted_at DESC
LIMIT 6");
$jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch popular job categories with count of active jobs, limit 6
$categories_stmt = $pdo->query("SELECT 
    c.category_id,
    c.name,
    COUNT(jp.job_id) AS jobs_count
FROM 
    categories c
LEFT JOIN 
    job_posts jp ON c.category_id = jp.category_id AND jp.status = 'active'
GROUP BY 
    c.category_id, c.name
ORDER BY 
    jobs_count DESC
LIMIT 6");
$categories = $categories_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Remote Job Search</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="assets/main_nav.css" />
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f4;
        }

        .hero {
            background: linear-gradient(135deg, rgb(12, 62, 172), #00d4ff);
            color: white;
            padding: 60px 30px;
            text-align: center;
        }

        .hero h1 {
            font-size: 3rem;
        }

        .card {
            margin: 20px 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .job-item {
            padding: 15px;
            border-bottom: 1px solid #ddd;
        }

        .job-item:last-child {
            border-bottom: none;
        }

        .social-icons a {
            margin: 0 10px;
            font-size: 1.5rem;
            color: rgb(43, 255, 0);
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php"><img src="templates/pwk.png" alt="Logo" height="40" /></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero">
        <h1>Welcome to Remote Job Search</h1>
        <p>Your gateway to exciting career opportunities!</p>
        <a href="login.php" class="btn btn-light btn-lg">Get Started</a>
    </div>

    <div class="container">
        <!-- Popular Categories -->
        <div class="card">
            <div class="card-body text-center">
                <h2>Popular Job Categories</h2>
                <div class="d-flex justify-content-around mt-4 flex-wrap">
                    <?php if (count($categories) > 0): ?>
                        <?php foreach ($categories as $category): ?>
                            <div class="text-center m-2">
                                <img src="../templates/pwk.png" width="100" height="60"
                                    alt="<?= htmlspecialchars($category['name']) ?>" />
                                <p class="mb-0"><?= htmlspecialchars($category['name']) ?></p>
                                <small class="text-muted"><?= $category['jobs_count'] ?> jobs</small>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No categories available at the moment.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Latest Job Listings -->
        <div class="card">
            <div class="card-body">
                <h2 class="text-center">Latest Job Postings</h2>
                <div id="job-list">
                    <?php if (count($jobs) > 0): ?>
                        <?php foreach ($jobs as $job): ?>
                            <div class="job-item">
                                <h4><?= htmlspecialchars($job['post_name']) ?></h4>
                                <p>Company: <?= htmlspecialchars($job['employer']) ?></p>
                                <p>Location: <?= htmlspecialchars($job['location']) ?></p>
                                <a href="login.php" class="btn btn-sm btn-outline-primary">Apply Now</a>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No job postings available at the moment.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Social Media Section -->
        <div class="card">
            <div class="card-body text-center">
                <h2>Connect with Us</h2>
                <div class="social-icons">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>