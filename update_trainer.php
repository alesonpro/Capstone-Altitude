<?php
// update_member.php

// Connect to the database
$connection = mysqli_connect("localhost", "root", "", "Coaches");

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_trainer'])) {
    // Sanitize user input
    $trainerId = mysqli_real_escape_string($connection, $_POST['id']);
    $newName = mysqli_real_escape_string($connection, $_POST['name']);
    $newSched = mysqli_real_escape_string($connection, $_POST['schedule']);
    $newSpecialty = mysqli_real_escape_string($connection, $_POST['specialty']);

    // Update member details in the database
    $updateQuery = "UPDATE trainers SET name = '$newName', specialty = '$newSpecialty', schedule = '$newSched' WHERE id = '$trainerId'";
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
