

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Due Date</title>
</head>
<body>
<?php
// Connect to the database
$connection = mysqli_connect("localhost", "root", "", "members");

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_dues'])) {
    // Sanitize user input
    $memberId = mysqli_real_escape_string($connection, $_POST['id']);

    // Retrieve member details for editing
    $editQuery = "SELECT * FROM members_list WHERE id = '$memberId'";
    $editResult = mysqli_query($connection, $editQuery);

    if ($editResult) {
        $editedMember = mysqli_fetch_assoc($editResult);

        echo "<div class='container card'>";
        echo "<h3>Update Due Date</h3>";
        echo "<form action='update_due_date.php' method='post'>";

        echo "<input type='hidden' name='id' value='" . $editedMember['id'] . "'>";
        echo "<div class='form-group'>";
        echo "<label for='due_date'>Due Date:</label>";
        echo "<input type='date' name='due_date' value='" . $editedMember['due_date'] . "' required><br>";
        echo "</div>";

        echo "<button type='submit' name='update_due_date'>Update Due Date</button>";
        echo "</form>";
        echo "</div>";
    } else {
        echo "Error: " . mysqli_error($connection);
    }
}

// Close the database connection
mysqli_close($connection);
?>
</body>
</html>