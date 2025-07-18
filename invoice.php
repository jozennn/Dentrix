<?php
require('fpdf/fpdf.php');
include 'dbcon.php'; // Include your database connection file
session_start();

// Get payment ID from query string or set manually for testing
$payment_id = isset($_GET['id']) ? $_GET['id'] : 1; // Example: ?id=1

// Fetch payment details
$sql = "SELECT * FROM appointments WHERE id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $payment_id);
$stmt->execute();
$result = $stmt->get_result();
$payment = $result->fetch_assoc();

if (!$payment) {
    die("Payment not found.");
}

$services = json_decode($payment['services'], true);
$gcash_name = $payment['name'];
$amount_paid = $payment['amount'];
$date = $payment['date'];
$time = $payment['time'];
$gcash_qr = $payment['created_at'];
$payment_method = $payment['payment_method'] == 0 ? 'Walk-in' : 'GCash';

$invoiceNumber = 'INV-' . str_pad($payment_id, 6, '0', STR_PAD_LEFT);

// FPDF setup
class PDF extends FPDF {
    function header() {
        $this->SetFillColor(0, 47, 90);
        $this->Rect(0, 20, $this->GetPageWidth(), 20, 'F');
        $this->Rect(0, 0, $this->GetPageWidth(), 20, 'F');
        $this->Ln(5);
        $this->Image('img/logo3.png', 10, 8, 25);
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 5, 'Invoice / Payment Receipt', 0, 1, 'C');
        $this->Cell(0, 5, 'Dr. Cheung Dental Clinic', 0, 1, 'C');
        $this->Cell(0, 5, 'Date: ' . date('l, F j, Y'), 0, 1, 'C');
        $this->Ln(15);
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',14);
$pdf->Cell(190,10,'MODE OF PAYMENT: ' . strtoupper($payment_method),0,1,'C');
$pdf->Ln(5);

// Invoice Details
$pdf->SetFont('Arial','',12);
$pdf->Cell(100,6,'Invoice No.: '. $invoiceNumber,0,0);
$pdf->Cell(90,6,'Date: ' . $date . ' ' . $time, 0, 1);
$pdf->Cell(100,6,'Patient Name: ' . $gcash_name,0,0);
$pdf->Ln(8);

// Table Header
$pdf->SetFont('Arial','B',12);
$pdf->Cell(90,8,'Description',1);
$pdf->Cell(20,8,'Qty',1);
$pdf->Cell(40,8,'Unit Price',1);
$pdf->Cell(40,8,'Amount',1);
$pdf->Ln();

// Table Content
$pdf->SetFont('Arial','',12);
$total = 0;
foreach ($services as $desc => $qty) {
    $unit_price = 0; // You may need to map this manually or from another table
    if ($desc == 'Dentures') $unit_price = 5500;
    if ($desc == 'Tooth Extraction') $unit_price = 1000;
    if ($desc == 'Tooth Restoration (Pasta)') $unit_price = 1300;

    $amount = $unit_price * $qty;
    $total += $amount;

    $pdf->Cell(90,8,$desc,1);
    $pdf->Cell(20,8,$qty,1,0,'C');
    $pdf->Cell(40,8,number_format($unit_price,2),1,0,'R');
    $pdf->Cell(40,8,number_format($amount,2),1,1,'R');
}

// Totals
$pdf->Ln(5);
$pdf->Cell(150,8,'Subtotal',0,0,'R');
$pdf->Cell(40,8,number_format($total,2),0,1,'R');

$pdf->Cell(150,8,'Discount',0,0,'R');
$pdf->Cell(40,8,'0.00',0,1,'R');

$pdf->Cell(150,8,'Total Amount Due',0,0,'R');
$pdf->Cell(40,8, number_format($total,2),0,1,'R');

$pdf->Cell(150,8,'Amount Paid',0,0,'R');
$pdf->Cell(40,8, number_format($amount_paid,2),0,1,'R');

$pdf->Cell(150,8,'Payment Method',0,0,'R');
$pdf->Cell(40,8, $payment_method,0,1,'R');

$pdf->Ln(10);
$pdf->Cell(190,6,'Served by: Dr. Henry Cheung',0,1);
$pdf->Cell(190,6,'Remarks: Thank you for your visit!',0,1);

$pdf->Output();
?>
