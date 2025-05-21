<?php
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);
session_start();
require_once '../includes/db.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$success = $error = '';
$receiver_email = "popermwasile173@gmail.com"; // Admin's email address

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);

    if (empty($name) || empty($email) || empty($message)) {
        $error = "Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please provide a valid email address.";
    } else {
        // Store in database
        $stmt = $pdo->prepare("INSERT INTO contact_messages (user_id, name, email, message) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$user_id, $name, $email, $message])) {
            // Send email
            $headers = "From: $email\r\n";
            $headers .= "Reply-To: $email\r\n";
            $headers .= "Content-Type: text/plain; charset=UTF-8";

            if (mail($receiver_email, "JobSearch Contact Message from $name", $message, $headers)) {
                $success = "Message sent successfully!";
            } else {
                $error = "Failed to send the email. Please try again.";
            }
        } else {
            $error = "Failed to save your message. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Contact Us - JobSearch</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/main_nav.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">JobSearch</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link active" href="contact.php"><i
                                class="bi bi-envelope-fill"></i> Contact</a></li>
                    <li class="nav-item"><a class="nav-link" href="../logout.php">Logout</a></li>
                    <li class="nav-item"><a class="nav-link" href="vacancies.php">vacancies</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <div class="container mt-5">
        <h2 class="text-center mb-4">📩 Contact Us</h2>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php elseif ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="contact.php" class="border p-4 rounded shadow-sm bg-light">
            <div class="mb-3">
                <label for="name" class="form-label">Your Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="name" id="name" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Your Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control" name="email" id="email" required>
            </div>

            <div class="mb-3">
                <label for="message" class="form-label">Your Message <span class="text-danger">*</span></label>
                <textarea class="form-control" name="message" id="message" rows="5" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary w-100">Send Message</button>
        </form>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>