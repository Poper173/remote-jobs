<?php
require_once '../includes/db.php';
$stmt = $pdo->query("SELECT * FROM faqs ORDER BY faq_id DESC");
$faqs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>All FAQs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <h2 class="text-center mb-4">Frequently Asked Questions</h2>
        <?php if ($faqs): ?>
            <div class="accordion" id="faqAccordion">
                <?php foreach ($faqs as $faq): ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading<?= $faq['faq_id'] ?>">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapse<?= $faq['faq_id'] ?>">
                                <?= htmlspecialchars($faq['question']) ?>
                            </button>
                        </h2>
                        <div id="collapse<?= $faq['faq_id'] ?>" class="accordion-collapse collapse"
                            data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <?= nl2br(htmlspecialchars($faq['answer'])) ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-center">No FAQs found.</p>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>