<?php
ini_set("display_errors",1);
ini_set("display_startup_errors",1);
error_reporting(E_ALL);
session_start();
require_once '../includes/db.php';

$message = '';

// Ensure only registered users can send messages
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('❌ You need to be registered to send a message.'); window.location.href='register.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];

// Prevent Super Admins from requesting role changes
if (isset($_SESSION['role_id']) && $_SESSION['role_id'] === 1) {
    echo "<script>alert('Super Admins cannot request role change!'); window.location.href = 'index.php';</script>";
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $subject = trim($_POST['subject']);
    $message_content = trim($_POST['message']);

    if (!empty($subject) && !empty($message_content)) {
        // Validate input length
        if (strlen($subject) > 100) {
            $message = "⚠️ Subject cannot exceed 100 characters.";
        } elseif (strlen($message_content) > 1000) {
            $message = "⚠️ Message cannot exceed 1000 characters.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO messages (user_id, subject, message) VALUES (?, ?, ?)");
            if ($stmt->execute([$user_id, htmlspecialchars($subject), htmlspecialchars($message_content)])) {
                $message = "✅ Your message has been sent.";
            } else {
                $message = "❌ Failed to send message. Please try again.";
            }
        }
    } else {
        $message = "⚠️ Both subject and message are required.";
    }
}

// Fetch sent messages
$sent_messages = [];
$stmt = $pdo->prepare("SELECT subject, message, created_at FROM messages WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$sent_messages = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Message Admin - JobSearch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/main_nav.css">
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
                    <li class="nav-item"><a class="nav-link" href="job_seeker_dashboard.php">Dashboard</a></li>

                    <li class="nav-item"><a class="nav-link" href="../index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="../logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="card p-4 shadow-sm">
            <h4 class="text-center text-primary mb-3">Message Admin / Company Admin</h4>

            <?php if ($message): ?>
                <div class="alert alert-info text-center"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>

            <form method="POST" action="message_admin.php">
                <div class="mb-3">
                    <label class="form-label">Subject</label>
                    <input type="text" name="subject" class="form-control" maxlength="100" required>
                    <small class="form-text text-muted">Max 100 characters.</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Message</label>
                    <textarea name="message" class="form-control" rows="5" maxlength="1000" required></textarea>
                    <small class="form-text text-muted">Max 1000 characters.</small>
                </div>
                <button type="submit" class="btn btn-primary w-100">Send Message</button>
            </form>

            <?php if (!empty($sent_messages)): ?>
                <h5 class="mt-4 text-primary">Your Sent Messages</h5>
                <ul class="list-group">
                    <?php foreach ($sent_messages as $msg): ?>
                        <li class="list-group-item">
                            <strong><?= htmlspecialchars($msg['subject']) ?></strong>
                            <p><?= htmlspecialchars($msg['message']) ?></p>
                            <small class="text-muted">Sent on <?= htmlspecialchars($msg['created_at']) ?></small>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</body>


</html>