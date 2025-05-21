<?php
// Show all PHP errors during development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../includes/db.php';

// Ensure only super admin (role_id = 1) can access
if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
    header("Location: ../login.php");
    exit();
}

$message = '';
$messageType = 'success'; // Default message type

// Handle role assignment
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id'], $_POST['role_id'])) {
    $user_id = $_POST['user_id'];
    $new_role_id = $_POST['role_id'];

    $stmt = $pdo->prepare("UPDATE users SET role_id = ? WHERE user_id = ?");
    $stmt->execute([$new_role_id, $user_id]);

    // Log the action in the audit_logs table
    $log_stmt = $pdo->prepare("INSERT INTO audit_logs (user_id, action) VALUES (?, ?)");
    $log_stmt->execute([$_SESSION['user_id'], "Assigned role ID $new_role_id to user ID $user_id"]);

    $message = "Role updated successfully.";
}

// Handle delete user request
if (isset($_GET['delete_user_id'])) {
    $delete_user_id = $_GET['delete_user_id'];

    try {
        // Delete related rows in dependent tables (e.g., employer_profiles)
        $stmt = $pdo->prepare("DELETE FROM employer_profiles WHERE employer_id = ?");
        $stmt->execute([$delete_user_id]);

        // Delete the user from the users table
        $stmt = $pdo->prepare("DELETE FROM users WHERE user_id = ?");
        if ($stmt->execute([$delete_user_id])) {
            $message = "User deleted successfully.";
            $messageType = 'success';
        } else {
            $message = "Failed to delete user. Please try again.";
            $messageType = 'danger';
        }
    } catch (PDOException $e) {
        // Handle foreign key constraint violation
        if ($e->getCode() == 23000) {
            $message = "Cannot delete user. There are related records in other tables.";
            $messageType = 'danger';
        } else {
            $message = "An error occurred: " . $e->getMessage();
            $messageType = 'danger';
        }
    }
}

// Handle search query
$search_query = $_GET['search'] ?? '';
$search_sql = $search_query ? "WHERE full_name LIKE ? OR email LIKE ?" : '';
$stmt = $pdo->prepare("SELECT user_id, full_name, email, role_id FROM users $search_sql");

if ($search_query) {
    $stmt->execute(["%$search_query%", "%$search_query%"]); // Bind the same value twice for positional placeholders
} else {
    $stmt->execute(); // No parameters needed if no search query
}

$users = $stmt->fetchAll();

// Fetch roles
$roles = $pdo->query("SELECT role_id, role_name FROM roles")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Assign Roles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/main_nav.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }

        h2 {
            font-weight: 600;
            color: #0d6efd;
        }

        .table td,
        .table th {
            vertical-align: middle;
        }

        .search-bar {
            margin-bottom: 20px;
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
    </style>
</head>

<body>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
        <div class="container">
            <a class="navbar-brand" href="../index.php">
                <img src="/templates/pwk.png" alt="Logo" style="max-width:150px;" />
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="super_admin_dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="../index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="../logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar">
                <div class="position-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item"><a class="nav-link active" href="super_admin_dashboard.php">Dashboard</a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="post_terms_conditions.php">Post Terms and
                                Conditions</a></li>
                        <li class="nav-item"><a class="nav-link" href="post_alert.php">Post Alert</a></li>
                        <li class="nav-item"><a class="nav-link" href="view_audit_logs.php">View System Activities</a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="post_category.php"><i class="bi bi-grid"></i> Category
                        </a></li>
                    

                        <li class="nav-item"><a class="nav-link" href="post_news.php">Post news letter</a></li>
                        <li class="nav-item"><a class="nav-link" href="settings.php">settings</a></li>
                        <li class="nav-item"><a class="nav-link" href="post_privacy_policy.php">Post Privacy Policy</a></li>
                        <li class="nav-item"><a class="nav-link" href="post_faq.php">Post faqs</a></li>
                        <li class="nav-item"><a class="nav-link" href="post_about_us.php">about us</a></li>
                        <li class="nav-item"><a class="nav-link" href="user_preferences.php">see user preferences</a></li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <h2 class="text-center mb-4">Admin: Assign User Roles</h2>

                <?php if ($message): ?>
                    <div class="alert alert-<?= $messageType ?> text-center"><?php echo $message; ?></div>
                <?php endif; ?>

                <!-- Search Bar -->
                <form method="GET" class="search-bar">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search by name or email"
                            value="<?= htmlspecialchars($search_query) ?>">
                        <button class="btn btn-primary" type="submit">Search</button>
                    </div>
                </form>

                <!-- User Table -->
                <table class="table table-bordered bg-white shadow-sm">
                    <thead class="table-primary">
                        <tr>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Current Role</th>
                            <th>Assign New Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($users): ?>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?= htmlspecialchars($user['full_name']) ?></td>
                                    <td><?= htmlspecialchars($user['email']) ?></td>
                                    <td>
                                        <?php
                                        $currentRole = array_filter($roles, fn($r) => $r['role_id'] == $user['role_id']);
                                        echo $currentRole ? reset($currentRole)['role_name'] : 'Unknown';
                                        ?>
                                    </td>
                                    <td>
                                        <form method="POST" class="d-flex">
                                            <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                                            <select name="role_id" class="form-select me-2">
                                                <?php foreach ($roles as $role): ?>
                                                    <option value="<?= $role['role_id'] ?>" <?= $role['role_id'] == $user['role_id'] ? 'selected' : '' ?>>
                                                        <?= ucfirst($role['role_name']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <button class="btn btn-sm btn-primary">Assign</button>
                                        </form>
                                    </td>
                                    <td>
                                        <a href="super_admin_dashboard.php?delete_user_id=<?= $user['user_id'] ?>"
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">No users found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
<?php include '../footer.php'; ?>

</html>