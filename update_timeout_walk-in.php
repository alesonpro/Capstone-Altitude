<?php
// logout.php

// Connect to the database
$connection = mysqli_connect("localhost", "root", "", "attendance");

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_walk-in'])) {
    // Sanitize user input
    $name = mysqli_real_escape_string($connection, $_POST['name']);
    $logoutTime = mysqli_real_escape_string($connection, $_POST['logout_time']);

    // Check if the name exists in the database
    $checkQuery = "SELECT * FROM walk_in WHERE name = '$name'";
    $checkResult = mysqli_query($connection, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        // Update time_out for the provided name
        $updateQuery = "UPDATE walk_in SET time_out = '$logoutTime' WHERE name = '$name'";
        $updateResult = mysqli_query($connection, $updateQuery);

        if ($updateResult) {
            echo "<script>alert('Time out updated successfully for $name.'); window.location.href='/Capstone-Altitude/scanner/index.html';</script>";
        } else {
            echo "<script>alert('Error: " . mysqli_error($connection) . "');</script>";
        }
    } else {
        echo "<script>alert('No records found for the provided name.');</script>";
    }
}

// Close the database connection
mysqli_close($connection);
?>
