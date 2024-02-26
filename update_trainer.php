<?php
// update_member.php

// Connect to the database
$connection = mysqli_connect("localhost", "root", "", "Coaches");

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_trainer'])) {
    // Sanitize user input
    $trainerId = mysqli_real_escape_string($connection, $_POST['id']);
    $newName = mysqli_real_escape_string($connection, $_POST['name']);
    $newSpecialty = mysqli_real_escape_string($connection, $_POST['specialty']);
    $newSchedStart = mysqli_real_escape_string($connection, $_POST['schedule_start']);
    $newSchedEnd = mysqli_real_escape_string($connection, $_POST['schedule_end']);
    $newTimeIn = mysqli_real_escape_string($connection, $_POST['time_in']);
    $newTimeOut = mysqli_real_escape_string($connection, $_POST['time_out']);

    // Update member details in the database
    $updateQuery = "UPDATE trainers SET name = '$newName', specialty = '$newSpecialty', schedule_start = '$newSchedStart', schedule_end = '$newSchedEnd', time_in = '$newTimeIn', time_out = '$newTimeOut' WHERE id = '$trainerId'";
    $updateResult = mysqli_query($connection, $updateQuery);

    if ($updateResult) {
        echo "<script>alert('Trainer updated successfully.'); window.location.href='trainers.php';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($connection) . "');</script>";
    }
}

// Close the database connection
mysqli_close($connection);
?>
