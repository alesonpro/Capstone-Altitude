<?php

// Connect to the database
$connection = mysqli_connect("localhost", "root", "", "members");

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_due_date'])) {
    // Sanitize user input
    $memberId = mysqli_real_escape_string($connection, $_POST['id']);
    $newDueDate = mysqli_real_escape_string($connection, $_POST['due_date']);

    // Update member details in the database
    $updateQuery = "UPDATE members_list SET due_date = '$newDueDate' WHERE id = '$memberId'";
    $updateResult = mysqli_query($connection, $updateQuery);

    if ($updateResult) {
        echo "<script>alert('Dues updated successfully.'); window.location.href='dues.php';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($connection) . "');</script>";
    }
}

// Close the database connection
mysqli_close($connection);
?>