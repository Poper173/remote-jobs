<?php
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

session_start();
require_once '../includes/db.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$success = '';
$error = '';

// Handle form submission to add or update a language
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $language_id = $_POST['language_id'] ?? null;
    $proficiency_level = $_POST['proficiency_level'] ?? null;

    if (empty($language_id) || empty($proficiency_level)) {
        $error = "Please select a language and proficiency level.";
    } else {
        // Check if the user already has this language
        $checkStmt = $pdo->prepare("SELECT * FROM user_languages WHERE user_id = ? AND language_id = ?");
        $checkStmt->execute([$user_id, $language_id]);

        if ($checkStmt->rowCount() > 0) {
            // Update proficiency level
            $updateStmt = $pdo->prepare("UPDATE user_languages SET proficiency_level = ?, updated_at = NOW() WHERE user_id = ? AND language_id = ?");
            if ($updateStmt->execute([$proficiency_level, $user_id, $language_id])) {
                $success = "Language proficiency updated successfully.";
            } else {
                $error = "Failed to update language proficiency.";
            }
        } else {
            // Insert new language
            $insertStmt = $pdo->prepare("INSERT INTO user_languages (user_id, language_id, proficiency_level) VALUES (?, ?, ?)");
            if ($insertStmt->execute([$user_id, $language_id, $proficiency_level])) {
                $success = "Language added successfully.";
            } else {
                $error = "Failed to add language.";
            }
        }
    }
}

// Fetch all languages
$languagesStmt = $pdo->query("SELECT language_id, language_name FROM languages");
$languages = $languagesStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch user's languages
$userLanguagesStmt = $pdo->prepare("
    SELECT ul.user_language_id, l.language_name AS language_name, ul.proficiency_level
    FROM user_languages ul
    JOIN languages l ON ul.language_id = l.language_id
    WHERE ul.user_id = ?
");
$userLanguagesStmt->execute([$user_id]);
$userLanguages = $userLanguagesStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>User Languages</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
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
        <h2 class="mb-4">Manage Your Languages</h2>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php elseif ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" class="mb-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="language_id" class="form-label">Language</label>
                    <select name="language_id" id="language_id" class="form-select" required>
                        <option value="">-- Select Language --</option>
                        <?php foreach ($languages as $language): ?>
                            <option value="<?= $language['language_id'] ?>">
                                <?= htmlspecialchars($language['language_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="proficiency_level" class="form-label">Proficiency Level</label>
                    <select name="proficiency_level" id="proficiency_level" class="form-select" required>
                        <option value="">-- Select Proficiency --</option>
                        <option value="basic">Basic</option>
                        <option value="conversational">Conversational</option>
                        <option value="fluent">Fluent</option>
                        <option value="native">Native</option>
                    </select>
                </div>
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Save Language</button>
            </div>
        </form>

        <h4>Your Languages</h4>
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Language</th>
                    <th>Proficiency Level</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($userLanguages) === 0): ?>
                    <tr>
                        <td colspan="3" class="text-center">No languages added yet.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($userLanguages as $userLanguage): ?>
                        <tr>
                            <td><?= htmlspecialchars($userLanguage['language_name']) ?></td>
                            <td><?= htmlspecialchars($userLanguage['proficiency_level']) ?></td>
                            <td>
                                <form method="POST" action="delete_language.php" style="display:inline;">
                                    <input type="hidden" name="user_language_id"
                                        value="<?= $userLanguage['user_language_id'] ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>

</html>