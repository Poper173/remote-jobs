<?php
// post_about_us.php
require_once '../includes/db.php'; // Adjust the path to your db connection file

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $team_members = trim($_POST['team_members']);
    $contact_email = trim($_POST['contact_email']);

    if ($title && $description) {
        try {
            $stmt = $pdo->prepare("INSERT INTO about_us (title, description, team_members, contact_email) VALUES (?, ?, ?, ?)");
            $stmt->execute([$title, $description, $team_members, $contact_email]);
            $success = "About Us information posted successfully!";
        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    } else {
        $error = "Title and description are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Post About Us</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
        <h2 class="mb-4 text-center">Post About Us Information</h2>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php elseif ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="post_about_us.php" class="bg-white p-4 rounded shadow-sm">
            <div class="mb-3">
                <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                <input type="text" name="title" id="title" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                <textarea name="description" id="description" class="form-control" rows="5" required></textarea>
            </div>

            <div class="mb-3">
                <label for="team_members" class="form-label">Team Members (Optional)</label>
                <textarea name="team_members" id="team_members" class="form-control" rows="3"></textarea>
            </div>

            <div class="mb-3">
                <label for="contact_email" class="form-label">Contact Email (Optional)</label>
                <input type="email" name="contact_email" id="contact_email" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">Post</button>
            <a href="view_about_us.php" class="btn btn-secondary">View Entries</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>