<?php
// update_member.php

// Connect to the database
$connection = mysqli_connect("localhost", "root", "", "members");

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_member'])) {
    // Sanitize user input
    $memberId = mysqli_real_escape_string($connection, $_POST['id']);
    $newName = mysqli_real_escape_string($connection, $_POST['name']);
    $newJoiningDate = mysqli_real_escape_string($connection, $_POST['joining_date']);
    $newCategory = mysqli_real_escape_string($connection, $_POST['Category']);
    $newEmail = mysqli_real_escape_string($connection, $_POST['email']);

    // Update member details in the database
    $updateQuery = "UPDATE members_list SET name = '$newName', joining_date = '$newJoiningDate', Category = '$newCategory', email = '$newEmail' WHERE id = '$memberId'";
    $updateResult = mysqli_query($connection, $updateQuery);

    if ($updateResult) {
        echo "<script>alert('Member updated successfully.'); window.location.href='members.php';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($connection) . "');</script>";
    }
}

// Close the database connection
mysqli_close($connection);
?>
