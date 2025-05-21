<?php
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);
session_start();
require_once '../includes/db.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$success = $error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employer_id = intval($_POST['employer_id']);
    $rating = intval($_POST['rating']);
    $comment = trim($_POST['comment']);

    if (empty($employer_id) || empty($rating) || empty($comment)) {
        $error = "Please fill in all fields.";
    } elseif ($rating < 1 || $rating > 5) {
        $error = "Rating must be between 1 and 5.";
    } else {
        // Insert review into the database
        $stmt = $pdo->prepare("INSERT INTO employer_reviews (employer_id, reviewer_id, rating, comment) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$employer_id, $user_id, $rating, $comment])) {
            $success = "Your review has been submitted successfully!";
        } else {
            $error = "Failed to submit your review. Please try again.";
        }
    }
}

// Fetch employers for the dropdown
$employers_stmt = $pdo->query("SELECT user_id, full_name FROM users WHERE role_id = 2");
$employers = $employers_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Employer Review - JobSearch</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/main_nav.css">
</head>


<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="../index.php">JobSearch</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="../logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <div class="container mt-5">
        <h2 class="text-center mb-4">📋 Submit Employer Review</h2>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php elseif ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="employer_review.php" class="border p-4 rounded shadow-sm bg-light">
            <div class="mb-3">
                <label for="employer_id" class="form-label">Select Employer <span class="text-danger">*</span></label>
                <select name="employer_id" id="employer_id" class="form-select" required>
                    <option value="">-- Select Employer --</option>
                    <?php foreach ($employers as $employer): ?>
                        <option value="<?= htmlspecialchars($employer['user_id']) ?>">
                            <?= htmlspecialchars($employer['full_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="rating" class="form-label">Rating (1-5) <span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="rating" id="rating" min="1" max="5" required>
            </div>

            <div class="mb-3">
                <label for="comment" class="form-label">Your Review <span class="text-danger">*</span></label>
                <textarea class="form-control" name="comment" id="comment" rows="5" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary w-100">Submit Review</button>
        </form>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>