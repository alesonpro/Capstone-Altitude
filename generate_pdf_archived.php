<?php
require('C:\xampp\htdocs\Capstone-Altitude\fpdf186\fpdf.php');

// Create a PDF instance
$pdf = new FPDF();
$pdf->AddPage();

// Fetch member data from the database and add it to the PDF
$connection = mysqli_connect("localhost", "root", "", "attendance");
$query = "SELECT * FROM archive_table ORDER BY date DESC";
$result = mysqli_query($connection, $query);

if ($result) {
    // Table headers
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(60, 10, 'Name', 1, 0, 'C');
    $pdf->Cell(40, 10, 'Time In', 1, 0, 'C');
    $pdf->Cell(40, 10, 'Time Out', 1, 0, 'C');
    $pdf->Cell(40, 10, 'Date', 1, 1, 'C');

    // Table rows
    $pdf->SetFont('Arial', '', 12);
    while ($row = mysqli_fetch_assoc($result)) {
        $pdf->Cell(60, 10, $row['qr_content'], 1, 0, 'C');
        $pdf->Cell(40, 10, date("h:i A", strtotime($row['time_in'])), 1, 0, 'C');
        $pdf->Cell(40, 10, ($row['time_out'] ? date("h:i A", strtotime($row['time_out'])) : 'N/A'), 1, 0, 'C');
        $pdf->Cell(40, 10, date("m-d-Y", strtotime($row['date'])), 1, 1, 'C');
    }
    mysqli_free_result($result);
} else {
    $pdf->Cell(0, 10, 'Error: ' . mysqli_error($connection), 0, 1);
}

// Close the database connection
mysqli_close($connection);

// Centering the table within the page
$pdf->SetXY(($pdf->GetPageWidth() - 160) / 2, $pdf->GetY());

// Output the PDF
$pdf->Output();
?>