<?php
require_once 'includes/db.php';
require_once 'includes/fpdf.php'; // Include FPDF library

// Fetch the latest terms and conditions
$stmt = $pdo->prepare("SELECT content, effective_date FROM terms_conditions ORDER BY effective_date DESC LIMIT 1");
$stmt->execute();
$terms = $stmt->fetch();

if (!$terms) {
    die("No terms and conditions available.");
}

// Create a new PDF instance
$pdf = new FPDF();
$pdf->AddPage();

// Set the title and header
$pdf->SetFont('Arial', 'B', 16);
$pdf->SetTextColor(0, 0, 255); // Blue color
$pdf->Cell(0, 10, 'Terms and Conditions', 0, 1, 'C');
$pdf->SetFont('Arial', '', 12);
$pdf->SetTextColor(0, 0, 0); // Black color
$pdf->Cell(0, 10, 'Effective Date: ' . $terms['effective_date'], 0, 1, 'C');
$pdf->Ln(10);

// Add the content
$pdf->SetFont('Arial', '', 12);
$pdf->MultiCell(0, 10, $terms['content']);

// Output the PDF
$pdf->Output('I', 'terms_and_conditions.pdf');

// Fetch the latest terms and conditions
$stmt = $pdo->prepare("SELECT content, effective_date FROM terms_conditions ORDER BY effective_date DESC LIMIT 1");
$stmt->execute();
$terms = $stmt->fetch();

if (!$terms) {
    die("No terms and conditions available.");
}

// Create new PDF document
$pdf = new TCPDF();

// Set document information
$pdf->SetCreator('JobSearch');
$pdf->SetAuthor('JobSearch Admin');
$pdf->SetTitle('Terms and Conditions');
$pdf->SetSubject('Terms and Conditions');
$pdf->SetKeywords('Terms, Conditions, JobSearch');

// Set default header data
$pdf->SetHeaderData('', 0, 'Terms and Conditions', 'Effective Date: ' . $terms['effective_date'], [0, 0, 255], [255, 255, 255]);
$pdf->setFooterData([0, 0, 255], [255, 255, 255]);

// Set header and footer fonts
$pdf->setHeaderFont(['helvetica', '', 12]);
$pdf->setFooterFont(['helvetica', '', 10]);

// Set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// Set margins
$pdf->SetMargins(15, 27, 15);
$pdf->SetHeaderMargin(10);
$pdf->SetFooterMargin(10);

// Set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 25);

// Set font
$pdf->SetFont('helvetica', '', 12);

// Add a page
$pdf->AddPage();

// Add content
$html = <<<EOD
<h1 style="color: #007bff; text-align: center;">Terms and Conditions</h1>
<p style="text-align: center; font-size: 12px; color: #555;">Effective Date: {$terms['effective_date']}</p>
<div style="margin-top: 20px; font-size: 12px; line-height: 1.6;">
    {$terms['content']}
</div>
EOD;

$pdf->writeHTML($html, true, false, true, false, '');

// Output PDF (inline view in browser)
$pdf->Output('terms_and_conditions.pdf', 'I');