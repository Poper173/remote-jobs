<?php
// Display all errors during development (disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../includes/db.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $language_name = isset($_POST['language_name']) ? trim($_POST['language_name']) : '';

    // Validate input
    if (empty($language_name)) {
        $message = "Language name is required.";
    } else {
        // Insert language into the database
        $stmt = $pdo->prepare("INSERT INTO languages (user_id, language_name) VALUES (?, ?)");
        if ($stmt->execute([$user_id, $language_name])) {
            header("Location: job_seeker_dashboard.php?language_added=1");
            exit();
        } else {
            $message = "Failed to add language. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Language - JobSearch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/main_nav.css">
    <style>
        body {
            background: #f0f2f5;
            font-family: 'Segoe UI', sans-serif;
        }

        .form-card {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        .form-label {
            font-weight: 500;
        }
    </style>
</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="../index.php">
            <img src="/templates/pwk.png" alt="Logo" style="max-width:150px;" />            </a>
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

    <!-- Languages Form -->
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="form-card">
                    <h4 class="text-center text-primary mb-3">Add Language</h4>

                    <?php if ($message): ?>
                        <div class="alert alert-danger"><?php echo $message; ?></div>
                    <?php endif; ?>

                    <form method="POST" action="languages.php">
                        <div class="mb-3">
                            <label class="form-label">Language Name</label>
                            <select name="language_name" class="form-select" required>
                                <option value="" disabled selected>Select a language</option>
                                <option value="English">English</option>
                                <option value="Mandarin Chinese">Mandarin Chinese</option>
                                <option value="Hindi">Hindi</option>
                                <option value="Spanish">Spanish</option>
                                <option value="French">French</option>
                                <option value="Standard Arabic">Standard Arabic</option>
                                <option value="Bengali">Bengali</option>
                                <option value="Russian">Russian</option>
                                <option value="Portuguese">Portuguese</option>
                                <option value="Urdu">Urdu</option>
                                <option value="Indonesian">Indonesian</option>
                                <option value="German">German</option>
                                <option value="Japanese">Japanese</option>
                                <option value="Swahili">Swahili</option>
                                <option value="Marathi">Marathi</option>
                                <option value="Telugu">Telugu</option>
                                <option value="Turkish">Turkish</option>
                                <option value="Tamil">Tamil</option>
                                <option value="Yue Chinese (Cantonese)">Yue Chinese (Cantonese)</option>
                                <option value="Vietnamese">Vietnamese</option>
                                <option value="Korean">Korean</option>
                                <option value="Italian">Italian</option>
                                <option value="Thai">Thai</option>
                                <option value="Gujarati">Gujarati</option>
                                <option value="Javanese">Javanese</option>
                                <option value="Persian">Persian</option>
                                <option value="Punjabi">Punjabi</option>
                                <option value="Polish">Polish</option>
                                <option value="Ukrainian">Ukrainian</option>
                                <option value="Malayalam">Malayalam</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Add Language</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>