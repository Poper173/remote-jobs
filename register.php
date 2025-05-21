<?php
// Show all PHP errors during development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'includes/db.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if ($password !== $confirm_password) {
        $message = "Passwords do not match.";
    } else {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Check if email already exists
        $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            $message = "This email is already registered.";
        } else {
            $role_id = 3; // Default role for regular users

            // Register new user
            $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password_hash, role_id) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$name, $email, $password_hash, $role_id])) {
                header("Location: login.php?registered=1");
                exit();
            } else {
                $message = "Registration failed. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Account - JobSearch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/main_nav.css">
    <style>
        body {
            background: #f0f2f5;
            font-family: 'Segoe UI', sans-serif;
        }

        .register-card {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        .form-label {
            font-weight: 500;
        }

        .navbar-brand img {
            max-height: 40px;
        }
    </style>
</head>



    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="templates/pwk.png" alt="JobSearch Logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Registration Form -->
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="register-card">
                    <h3 class="text-center mb-4 text-primary">Create New Account</h3>

                    <?php if ($message): ?>
                        <div class="alert alert-warning"><?php echo $message; ?></div>
                    <?php endif; ?>

                    <form method="POST" action="register.php">
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Enter your full name"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Enter your email"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control"
                                placeholder="Enter your password" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="confirm_password" class="form-control"
                                placeholder="Confirm your password" required>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="agreeCheck" onchange="toggleSubmit()">
                            <label class="form-check-label" for="agreeCheck">
                                I have read and agree to the <a href="#" data-bs-toggle="modal"
                                    data-bs-target="#termsModal">Terms and Conditions</a>
                            </label>
                        </div>

                        <button type="submit" class="btn btn-success w-100" id="submitBtn" disabled>Register</button>
                    </form>

                    <p class="mt-3 text-center">
                        Already have an account? <a href="login.php">Login</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Terms & Conditions Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php
                    // Fetch the latest terms and conditions from DB
                    $stmt = $pdo->query("SELECT content, effective_date FROM terms_conditions ORDER BY effective_date DESC LIMIT 1");
                    if ($row = $stmt->fetch()) {
                        echo "<p><strong>Effective Date:</strong> " . htmlspecialchars($row['effective_date']) . "</p>";
                        echo "<div style='white-space: pre-line;'>" . htmlspecialchars($row['content']) . "</div>";
                    } else {
                        echo "<p>No Terms and Conditions found.</p>";
                    }
                    ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer -->
     <?php include 'footer.php'; ?>

    <!-- Bootstrap JS and custom script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSubmit() {
            const checkbox = document.getElementById('agreeCheck');
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = !checkbox.checked;
        }
    </script>

</body>

</html>