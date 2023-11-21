<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Connect to the database
    $connection = mysqli_connect("localhost", "root", "", "members");

    // Check connection
    if ($connection === false) {
        die("Error: Connection error. " . mysqli_connect_error());
    }

    // Sanitize user input
    $memberId = mysqli_real_escape_string($connection, $_POST['id']);

    // Delete member from the database based on the ID
    $deleteQuery = "DELETE FROM members_list WHERE id = '$memberId'";
    $result = mysqli_query($connection, $deleteQuery);

    if ($result) {
        echo "Member deleted successfully.";
    } else {
        echo "Error: " . mysqli_error($connection);
    }

    // Close the database connection
    mysqli_close($connection);
}
?>
