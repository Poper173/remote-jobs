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
$success = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $location_id = $_POST['preferred_location_id'] ?? null;
    $category_id = $_POST['preferred_category_id'] ?? null;

    // Check if user already has preferences
    $check = $pdo->prepare("SELECT * FROM user_preferences WHERE user_id = ?");
    $check->execute([$user_id]);

    if ($check->rowCount() > 0) {
        // Update existing preferences
        $update = $pdo->prepare("UPDATE user_preferences SET preferred_location_id = ?, preferred_category_id = ? WHERE user_id = ?");
        if ($update->execute([$location_id, $category_id, $user_id])) {
            $success = "Preferences updated successfully.";
        } else {
            $error = "Failed to update preferences.";
        }
    } else {
        // Insert new preferences
        $insert = $pdo->prepare("INSERT INTO user_preferences (user_id, preferred_location_id, preferred_category_id) VALUES (?, ?, ?)");
        if ($insert->execute([$user_id, $location_id, $category_id])) {
            $success = "Preferences saved successfully.";
        } else {
            $error = "Failed to save preferences.";
        }
    }
}

// Fetch location and category options
try {
    // Combine city, region, and country into a single display column for locations
    $locations = $pdo->query("SELECT location_id, CONCAT(city, ', ', region, ', ', country) AS location_name FROM locations")->fetchAll();
    $categories = $pdo->query("SELECT category_id, name FROM categories")->fetchAll(); // Updated to use `categories` table
} catch (PDOException $e) {
    die("Failed to fetch data: " . $e->getMessage());
}

// Get current preferences if any
$current = $pdo->prepare("SELECT preferred_location_id, preferred_category_id FROM user_preferences WHERE user_id = ?");
$current->execute([$user_id]);
$currentPref = $current->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>User Preferences</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
                    <li class="nav-item"><a class="nav-link" href="vacancies.php">Vacancies</a></li>
                    <li class="nav-item"><a class="nav-link" href="../logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <div class="container mt-5">
        <h2 class="text-center">Set Your Job Preferences</h2>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php elseif ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" class="mt-4">
            <div class="mb-3">
                <label for="preferred_location_id" class="form-label">Preferred Location</label>
                <select class="form-select" name="preferred_location_id" id="preferred_location_id">
                    <option value="">-- Select Location --</option>
                    <?php foreach ($locations as $location): ?>
                        <option value="<?= $location['location_id'] ?>" <?= ($currentPref['preferred_location_id'] ?? '') == $location['location_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($location['location_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="preferred_category_id" class="form-label">Preferred Job Category</label>
                <select class="form-select" name="preferred_category_id" id="preferred_category_id">
                    <option value="">-- Select Category --</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['category_id'] ?>" <?= ($currentPref['preferred_category_id'] ?? '') == $category['category_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($category['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Save Preferences</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>