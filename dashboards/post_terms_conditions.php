<?php
session_start();
require_once '../includes/db.php';

// Ensure only super admin (role_id = 1) can access
if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
    header("Location: ../login.php");
    exit();
}

$message = '';
$messageType = '';

// Handle form submission for adding new terms
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_terms'])) {
    $content = trim($_POST['content']);
    $effective_date = $_POST['effective_date'];

    if (!empty($content) && !empty($effective_date)) {
        $stmt = $pdo->prepare("INSERT INTO terms_conditions (content, effective_date) VALUES (?, ?)");
        if ($stmt->execute([$content, $effective_date])) {
            $message = "✅ Terms and Conditions posted successfully.";
            $messageType = "success";
        } else {
            $message = "❌ Failed to post Terms and Conditions. Please try again.";
            $messageType = "danger";
        }
    } else {
        $message = "⚠️ All fields are required.";
        $messageType = "warning";
    }
}

// Handle delete request
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $stmt = $pdo->prepare("DELETE FROM terms_conditions WHERE version_id = ?");
    if ($stmt->execute([$delete_id])) {
        $message = "✅ Terms and Conditions deleted successfully.";
        $messageType = "success";
    } else {
        $message = "❌ Failed to delete Terms and Conditions. Please try again.";
        $messageType = "danger";
    }
}

// Handle update request
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_terms'])) {
    $version_id = $_POST['version_id'];
    $content = trim($_POST['content']);
    $effective_date = $_POST['effective_date'];

    if (!empty($content) && !empty($effective_date)) {
        $stmt = $pdo->prepare("UPDATE terms_conditions SET content = ?, effective_date = ? WHERE version_id = ?");
        if ($stmt->execute([$content, $effective_date, $version_id])) {
            $message = "✅ Terms and Conditions updated successfully.";
            $messageType = "success";
        } else {
            $message = "❌ Failed to update Terms and Conditions. Please try again.";
            $messageType = "danger";
        }
    } else {
        $message = "⚠️ All fields are required.";
        $messageType = "warning";
    }
}

// Fetch all terms and conditions
$stmt = $pdo->prepare("SELECT * FROM terms_conditions ORDER BY effective_date DESC");
$stmt->execute();
$terms_conditions = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Terms and Conditions - JobSearch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
    <link rel="stylesheet" href="../assets/main_nav.css">
</head>

<body class="bg-light">
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
    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white text-center">
                <h3>Manage Terms and Conditions</h3>
            </div>
            <div class="card-body">

                <?php if ($message): ?>
                    <div class="alert alert-<?php echo $messageType; ?> text-center" id="alert-msg">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>

                <!-- Add New Terms and Conditions -->
                <form method="POST" action="post_terms_conditions.php"
                    onsubmit="return confirm('Are you sure you want to post these Terms and Conditions?');">
                    <input type="hidden" name="add_terms" value="1">
                    <div class="mb-3">
                        <label for="content" class="form-label">Terms and Conditions Content</label>
                        <textarea name="content" id="content" class="form-control" rows="10" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="effective_date" class="form-label">Effective Date</label>
                        <input type="date" name="effective_date" id="effective_date" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100">📜 Post Terms and Conditions</button>
                </form>

                <!-- Existing Terms and Conditions -->
                <h4 class="mt-5">Existing Terms and Conditions</h4>
                <table class="table table-bordered table-striped mt-3">
                    <thead class="table-primary">
                        <tr>
                            <th>Version ID</th>
                            <th>Content</th>
                            <th>Effective Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($terms_conditions as $term): ?>
                            <tr>
                                <td><?= htmlspecialchars($term['version_id']) ?></td>
                                <td><?= nl2br(htmlspecialchars(substr($term['content'], 0, 100))) ?>...</td>
                                <td><?= htmlspecialchars($term['effective_date']) ?></td>
                                <td>
                                    <!-- Edit Button -->
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#editModal<?= $term['version_id'] ?>">✏️ Edit</button>

                                    <!-- Delete Button -->
                                    <a href="post_terms_conditions.php?delete_id=<?= $term['version_id'] ?>"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure you want to delete this Terms and Conditions?');">🗑️
                                        Delete</a>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editModal<?= $term['version_id'] ?>" tabindex="-1"
                                aria-labelledby="editModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel">Edit Terms and Conditions</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form method="POST" action="post_terms_conditions.php">
                                            <div class="modal-body">
                                                <input type="hidden" name="version_id"
                                                    value="<?= $term['version_id'] ?>">
                                                <div class="mb-3">
                                                    <label for="content" class="form-label">Content</label>
                                                    <textarea name="content" class="form-control"
                                                        rows="10"><?= htmlspecialchars($term['content']) ?></textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="effective_date" class="form-label">Effective Date</label>
                                                    <input type="date" name="effective_date" class="form-control"
                                                        value="<?= htmlspecialchars($term['effective_date']) ?>" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                                <button type="submit" name="update_terms"
                                                    class="btn btn-primary">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        CKEDITOR.replace('content');
        // Hide alert after 5 seconds
        setTimeout(() => {
            const alertBox = document.getElementById('alert-msg');
            if (alertBox) alertBox.style.display = 'none';
        }, 5000);
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>