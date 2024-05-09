<?php
require('C:\xampp\htdocs\Capstone-Altitude\fpdf186\fpdf.php');

// Create a PDF instance
$pdf = new FPDF();
$pdf->AddPage();

// Connect to the database
$connection = mysqli_connect("localhost", "root", "", "attendance");

// Check connection 
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}

// Retrieve filtered data based on the selected date
$date = $_GET['date']; // Assuming you're passing the selected date as a GET parameter
$query = "SELECT * FROM archive_table WHERE date = '$date'";
$result = mysqli_query($connection, $query);

// Initialize PDF
$pdf = new FPDF();
$pdf->AddPage();

// Set font
$pdf->SetFont('Arial', '', 12);

// Add a title
$pdf->Cell(0, 10, 'Members Logs - ' . $date, 0, 1, 'C');

// Check if there are results
if ($result && mysqli_num_rows($result) > 0) {
    // Table headers
    $pdf->Cell(60, 10, 'Name', 1, 0, 'C');
    $pdf->Cell(40, 10, 'Time In', 1, 0, 'C');
    $pdf->Cell(40, 10, 'Time Out', 1, 0, 'C');
    $pdf->Cell(40, 10, 'Date', 1, 1, 'C');

    // Table rows
    while ($row = mysqli_fetch_assoc($result)) {
        $pdf->Cell(60, 10, $row['qr_content'], 1, 0, 'C');
        $pdf->Cell(40, 10, date("h:i A", strtotime($row['time_in'])), 1, 0, 'C');
        $pdf->Cell(40, 10, ($row['time_out'] ? date("h:i A", strtotime($row['time_out'])) : 'N/A'), 1, 0, 'C');
        $pdf->Cell(40, 10, date("m-d-Y", strtotime($row['date'])), 1, 1, 'C');
    }
} else {
    // No records found message
    $pdf->Cell(0, 10, 'No records found for the selected date.', 0, 1);
}

// Close the database connection
mysqli_close($connection);

// Output the PDF
$pdf->Output();
?>