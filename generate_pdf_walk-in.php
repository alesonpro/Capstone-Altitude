<?php
require('C:\xampp\htdocs\Capstone-Altitude\fpdf186\fpdf.php');

// Create a PDF instance
$pdf = new FPDF();
$pdf->AddPage();

// Fetch member data from the database and add it to the PDF
$connection = mysqli_connect("localhost", "root", "", "attendance");
$query = "SELECT * FROM walk_in ORDER BY name";
$result = mysqli_query($connection, $query);

if ($result) {
   while ($row = mysqli_fetch_assoc($result)) {
      $pdf->SetFont('Arial', 'B', 12);
      $pdf->Cell(0, 10, 'Name: ' . $row['name'], 0, 1);
      $pdf->SetFont('Arial', '', 12);
      $pdf->Cell(0, 10, 'Time in: ' . date("h:i A", strtotime($row['time_in'])), 0, 1);
      $pdf->Cell(0, 10, 'Time out: ' . date("h:i A", strtotime($row['time_out'])), 0, 1);
      $pdf->Ln(5);
   }
   mysqli_free_result($result);
} else {
   $pdf->Cell(0, 10, 'Error: ' . mysqli_error($connection), 0, 1);
}

// Close the database connection
mysqli_close($connection);

// Output the PDF
$pdf->Output();
