<?php
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

session_start();
require_once '../includes/db.php';

// ✅ Ensure the user is logged in and has role_id = 1 (admin)
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: ../login.php");
    exit();
}

// ✅ Fetch user preferences and related info
$sql = "
    SELECT
        u.user_id,
        u.full_name,
        u.email,
        up.preferred_location_id,
        CONCAT(l.city, ', ', l.region, ', ', l.country) AS location_name,
        up.preferred_category_id,
        c.name AS category_name
    FROM users u
    LEFT JOIN user_preferences up ON u.user_id = up.user_id
    LEFT JOIN locations l ON up.preferred_location_id = l.location_id
    LEFT JOIN categories c ON up.preferred_category_id = c.category_id
    ORDER BY u.user_id
";
$stmt = $pdo->query($sql);
$preferences = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin - User Preferences</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet"href="../assets/main_nav.css">
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
     

    <!-- Page Content -->
    <div class="container mt-5">
        <h2 class="text-center mb-4">User Preferences</h2>
        <table class="table table-bordered table-striped">
            <thead class="table-primary">
                <tr>
                    <th>User ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Preferred Location</th>
                    <th>Preferred Job Category</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($preferences) === 0): ?>
                    <tr>
                        <td colspan="5" class="text-center">No user preferences found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($preferences as $pref): ?>
                        <tr>
                            <td><?= htmlspecialchars($pref['user_id']) ?></td>
                            <td><?= htmlspecialchars($pref['full_name']) ?></td>
                            <td><?= htmlspecialchars($pref['email']) ?></td>
                            <td><?= htmlspecialchars($pref['location_name'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($pref['category_name'] ?? 'N/A') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>