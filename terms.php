<?php
session_start();
require_once 'includes/db.php';

// Fetch the latest terms and conditions
$stmt = $pdo->prepare("SELECT content, effective_date FROM terms_conditions ORDER BY effective_date DESC LIMIT 1");
$stmt->execute();
$terms = $stmt->fetch();

if (!$terms) {
    die("No terms and conditions available.");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms and Conditions - JobSearch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center">Terms and Conditions</h2>
        <p class="text-muted text-center">Effective Date: <?= htmlspecialchars($terms['effective_date']) ?></p>

        <div class="mt-4 p-3 border rounded bg-light">
            <?= nl2br(htmlspecialchars($terms['content'])) ?>
        </div>

        <div class="text-center mt-4">
            <a href="terms_pdf.php" class="btn btn-primary">Download as PDF</a>
        </div>

        <form method="POST" action="register.php" class="mt-4 text-center">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="acceptTerms" required>
                <label class="form-check-label" for="acceptTerms">I have read and accept the terms and conditions</label>
            </div>
            <button type="submit" class="btn btn-success mt-3">Accept and Continue</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>