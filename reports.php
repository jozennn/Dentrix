<?php
require('fpdf/fpdf.php');
include('dbcon.php');

session_start();

// Fetch data from the normalized `audit_trail` table

$result = mysqli_query($con, "
    SELECT 
        u.name AS user_name, 
        a.action_name AS action, 
        at.details, 
        at.created_at 
    FROM audit_trail at
    JOIN users u ON at.user_id = u.id
    JOIN audit_actions a ON at.action_id = a.action_id
    ORDER BY at.created_at DESC
");

if (!$result) {
    die("Query failed: " . mysqli_error($con));
}

if (mysqli_num_rows($result) === 0) {
    die("No data found in the audit trail.");
}

$mode = $_GET['mode'] ?? 'view';

class PDF extends FPDF
{
    function header()
    {
        $this->SetFillColor(0, 47, 90); 
        $this->Rect(0, 20, $this->GetPageWidth(), 20, 'F'); // RGB
        $this->Rect(0, 0, $this->GetPageWidth(), 20, 'F'); 
        $this->Ln(5);
        $this->Image('img/logo3.png', 10, 8, 25);
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 5, 'Audit Trail Report', 0, 1, 'C');
        $this->Cell(0, 5, 'Dr. Cheung Dental Clinic', 0, 1, 'C');
        $this->Cell(0, 5, 'Date: ' . date('l, F j, Y'), 0, 1, 'C');
        $this->Ln(15);
    }

    function footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 12);


// Define fixed column widths
$timestampWidth = 40;
$userWidth = 50;
$actionWidth = 30;
$detailsWidth = 70; // New column for details

// Table headers
$pdf->Cell($timestampWidth, 10, 'Timestamp', 1, 0, 'C');
$pdf->Cell($userWidth, 10, 'User', 1, 0, 'C');
$pdf->Cell($actionWidth, 10, 'Action', 1, 0, 'C');
$pdf->Cell($detailsWidth, 10, 'Details', 1, 1, 'C'); // New column

$pdf->SetFont('Arial', '', 12);

// Table rows
while ($row = $result->fetch_assoc()) {
    // Define column widths
    $timestampWidth = 40;
    $userWidth = 50;
    $actionWidth = 30;
    $detailsWidth = 70;

    // Row height
    $rowHeight = 10;

    // Save the current X and Y positions
    $x = $pdf->GetX();
    $y = $pdf->GetY();

    // Calculate the maximum height of the row based on the content
    $maxHeight = max(
        calculateMultiCellHeight($pdf, $timestampWidth, $rowHeight, $row['created_at']),
        calculateMultiCellHeight($pdf, $userWidth, $rowHeight, $row['user_name']),
        calculateMultiCellHeight($pdf, $actionWidth, $rowHeight, $row['action']),
        calculateMultiCellHeight($pdf, $detailsWidth, $rowHeight, $row['details'])
    );

    // Render each column
    $pdf->MultiCell($timestampWidth, $rowHeight, $row['created_at'], 1, 'C', false);
    $pdf->SetXY($x + $timestampWidth, $y);

    $pdf->MultiCell($userWidth, $rowHeight, $row['user_name'], 1, 'C', false);
    $pdf->SetXY($x + $timestampWidth + $userWidth, $y);

    $pdf->MultiCell($actionWidth, $rowHeight, $row['action'], 1, 'C', false);
    $pdf->SetXY($x + $timestampWidth + $userWidth + $actionWidth, $y);

    $pdf->MultiCell($detailsWidth, $rowHeight, $row['details'], 1, 'C', false);

    // Move to the next row
    $pdf->SetY($y + $maxHeight);
}

// Function to calculate the height of a MultiCell
function calculateMultiCellHeight($pdf, $width, $lineHeight, $text)
{
    $lines = $pdf->GetStringWidth($text) / $width;
    $lines = ceil($lines);
    return $lines * $lineHeight;
}
if ($mode === 'download') {
    // Force download
    $pdf->Output('D', 'audit_trail_report.pdf'); // 'D' = Download
} else {
    // Open in browser
    $pdf->Output('I', 'audit_trail_report.pdf'); // 'I' = Inline view
}
?>