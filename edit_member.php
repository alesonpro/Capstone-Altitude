<?php
// edit_member.php

// Connect to the database
$connection = mysqli_connect("localhost", "root", "", "members");

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_member'])) {
    // Sanitize user input
    $memberId = mysqli_real_escape_string($connection, $_POST['id']);

    // Retrieve member details for editing
    $editQuery = "SELECT * FROM members_list WHERE id = '$memberId'";
    $editResult = mysqli_query($connection, $editQuery);

    if ($editResult) {
        $editedMember = mysqli_fetch_assoc($editResult);

        // Display a form for editing member details
        echo "<form action='update_member.php' method='post'>";
        echo "<input type='hidden' name='id' value='" . $editedMember['id'] . "'>";
        echo "<label for='name'>Name:</label>";
        echo "<input type='text' name='name' value='" . $editedMember['name'] . "' required><br>";
        echo "<label for='joining_date'>Joining Date:</label>";
        echo "<input type='date' name='joining_date' value='" . date("m-d-Y", strtotime($editedMember['joining_date'])) . "' required><br>";
        echo "<label for='Category'>Category:</label>";
        echo "<select name='Category' required>";
        echo "<option value='Student' " . ($editedMember['Category'] == 'Student' ? 'selected' : '') . ">Student</option>";
        echo "<option value='Regular' " . ($editedMember['Category'] == 'Regular' ? 'selected' : '') . ">Regular</option>";
        echo "<option value='Regular/Coach' " . ($editedMember['Category'] == 'Regular/Coach' ? 'selected' : '') . ">Regular/Coach</option>";
        echo "</select><br>";
        echo "<button type='submit' name='update_member'>Update Member</button>";
        echo "</form>";
    } else {
        echo "Error: " . mysqli_error($connection);
    }
}

// Close the database connection
mysqli_close($connection);
?>