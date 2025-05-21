<?php

// filepath: /var/www/html/jobsearch/dashboards/post_category.php

session_start();
require_once '../includes/db.php';

// Ensure only super admin (role_id = 1) can access
if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
    header("Location: ../login.php");
    exit();
}

$message = '';
$messageType = '';

// Handle form submission for adding a new category
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_category'])) {
    $category_name = trim($_POST['category_name']);

    if (!empty($category_name)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (?)");
            $stmt->execute([$category_name]);
            $message = "✅ Category added successfully.";
            $messageType = "success";
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { // Duplicate entry
                $message = "❌ Category already exists.";
                $messageType = "danger";
            } else {
                $message = "❌ Error adding category: " . $e->getMessage();
                $messageType = "danger";
            }
        }
    } else {
        $message = "⚠️ Category name cannot be empty.";
        $messageType = "warning";
    }
}

// Fetch all categories
$stmt = $pdo->prepare("SELECT * FROM categories ORDER BY name ASC");
$stmt->execute();
$categories = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Categories - JobSearch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/main_nav.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }

        .container {
            margin-top: 50px;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .btn-custom {
            background-color: #007bff;
            color: white;
        }

        .btn-custom:hover {
            background-color: #0056b3;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }
    </style>
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <img src="/templates/pwk.png" alt="Logo" style="max-width:150px;" /> 
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="super_admin_dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="../logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>
     
    <div class="container">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="text-center">Manage Categories</h3>
            </div>
            <div class="card-body">
                <?php if ($message): ?>
                    <div class="alert alert-<?= $messageType ?> text-center"><?= htmlspecialchars($message) ?></div>
                <?php endif; ?>

                <!-- Add Category Form -->
                <form method="POST" class="mb-4">
                    <div class="input-group">
                        <input type="text" name="category_name" class="form-control" placeholder="Enter category name" required>
                        <button type="submit" name="add_category" class="btn btn-custom">Add Category</button>
                    </div>
                </form>

                <!-- Categories Table -->
                <h5 class="mb-3">Existing Categories</h5>
                <table class="table table-bordered table-striped">
                    <thead class="table-primary">
                        <tr>
                            <th>#</th>
                            <th>Category Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($categories): ?>
                            <?php foreach ($categories as $category): ?>
                                <tr>
                                    <td><?= htmlspecialchars($category['category_id']) ?></td>
                                    <td><?= htmlspecialchars($category['name']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="2" class="text-center">No categories found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
