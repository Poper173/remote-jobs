<?php
// post_faq.php
session_start();
require_once '../includes/db.php';

$success = $error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $question = trim($_POST['question']);
    $answer = trim($_POST['answer']);

    if (!empty($question)) {
        $stmt = $pdo->prepare("INSERT INTO faqs (question, answer) VALUES (?, ?)");
        if ($stmt->execute([$question, $answer])) {
            $success = "FAQ successfully posted!";
        } else {
            $error = "Failed to post FAQ. Please try again.";
        }
    } else {
        $error = "Question field is required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Post FAQ</title>
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
        <h2 class="mb-4 text-center">Post New FAQ</h2>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php elseif ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" action="post_faq.php" class="bg-white p-4 rounded shadow-sm">
            <div class="mb-3">
                <label for="question" class="form-label">Question <span class="text-danger">*</span></label>
                <textarea name="question" id="question" class="form-control" required rows="3"></textarea>
            </div>

            <div class="mb-3">
                <label for="answer" class="form-label">Answer</label>
                <textarea name="answer" id="answer" class="form-control" rows="4"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Post FAQ</button>
            <a href="view_faqs.php" class="btn btn-secondary">View All FAQs</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>