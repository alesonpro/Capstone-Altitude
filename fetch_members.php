<?php
date_default_timezone_set('Asia/Macao');
// Connect to the database
$connection = mysqli_connect("localhost", "root", "", "attendance");

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}
// Get the selected date from the URL parameter
$date = $_GET['date'];

// Prepare the SQL query to fetch members for the selected date
$query = "SELECT * FROM archive_table WHERE DATE(date) = '$date'";
$result = mysqli_query($connection, $query);

if ($result && mysqli_num_rows($result) > 0) {
    // Start building the HTML content for the table rows
    $output = '';
    while ($row = mysqli_fetch_assoc($result)) {
        $output .= '<tr>';
        $output .= '<td>' . $row['qr_content'] . '</td>';
        $output .= '<td>' . date("h:i A", strtotime($row['time_in'])) . '</td>';
        $output .= '<td>' . ($row['time_out'] ? date("h:i A", strtotime($row['time_out'])) : 'N/A') . '</td>';
        $output .= '<td>' . date("m-d-Y", strtotime($row['date'])) . '</td>';
        $output .= '</tr>';
    }
    echo $output; // Output the HTML content
} else {
    echo "<tr><td colspan='4'>No members found for this date.</td></tr>";
}

// Close the database connection
mysqli_close($connection);
?>
