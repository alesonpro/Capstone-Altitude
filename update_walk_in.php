<?php
// update_member.php

// Connect to the database
$connection = mysqli_connect("localhost", "root", "", "attendance");

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_walk-in'])) {
    // Sanitize user input
    $memberId = mysqli_real_escape_string($connection, $_POST['id']);
    $newName = mysqli_real_escape_string($connection, $_POST['name']);
    $newTimeIn = mysqli_real_escape_string($connection, $_POST['time_in']);
    $newTimeOut = mysqli_real_escape_string($connection, $_POST['time_out']);

    // Update member details in the database
    $updateQuery = "UPDATE walk_in SET name = '$newName', time_in = '$newTimeIn', time_out = '$newTimeOut' WHERE id = '$memberId'";
    $updateResult = mysqli_query($connection, $updateQuery);

    if ($updateResult) {
        echo "<script>alert('Member updated successfully.'); window.location.href='walk-in.php';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($connection) . "');</script>";
    }
}

// Close the database connection
mysqli_close($connection);
?>
