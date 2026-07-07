<?php
require 'includes/header.php';
require 'fpdf/fpdf.php';

if (!isset($_GET['transaction_id'])) {
    die("Invalid Request");
}

$trans_id = (int)$_GET['transaction_id'];
$user_id = $_SESSION['user_id'];

$trans = $conn->query("SELECT t.*, b.utility_type, b.provider, u.name, u.email 
                      FROM transactions t 
                      JOIN bills b ON t.bill_id = b.id 
                      JOIN users u ON t.user_id = u.id 
                      WHERE t.id = $trans_id AND t.user_id = $user_id")->fetch_assoc();

if (!$trans) {
    die("Receipt not found");
}

// Generate PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

$pdf->Cell(190, 10, 'UtilityPay - Payment Receipt', 0, 1, 'C');
$pdf->Ln(10);

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(190, 8, 'Receipt #: ' . $trans['reference'], 0, 1);
$pdf->Cell(190, 8, 'Date: ' . date('d M Y H:i', strtotime($trans['payment_date'])), 0, 1);
$pdf->Ln(5);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(190, 8, 'Customer Information', 0, 1);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(190, 8, 'Name: ' . $trans['name'], 0, 1);
$pdf->Cell(190, 8, 'Email: ' . $trans['email'], 0, 1);
$pdf->Ln(5);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(190, 8, 'Payment Details', 0, 1);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(190, 8, 'Utility: ' . ucfirst($trans['utility_type']), 0, 1);
$pdf->Cell(190, 8, 'Provider: ' . $trans['provider'], 0, 1);
$pdf->Cell(190, 8, 'Amount Paid: TZS ' . number_format($trans['amount'], 2), 0, 1);
$pdf->Cell(190, 8, 'Payment Method: ' . $trans['payment_method'], 0, 1);
$pdf->Ln(10);

$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(190, 10, 'Thank You for using UtilityPay!', 0, 1, 'C');

$pdf->Output('D', 'Receipt_' . $trans['reference'] . '.pdf'); // Download
exit();
?>