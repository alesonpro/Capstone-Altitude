<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Walk-in</title>
</head>
<body>
    <?php
            // Connect to the database
        $connection = mysqli_connect("localhost", "root", "", "attendance");

        // Check if the form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_walk-in'])) {
            // Sanitize user input
            $walkInId = mysqli_real_escape_string($connection, $_POST['id']);
            

            // Retrieve member details for editing
            $editQuery = "SELECT * FROM walk_in WHERE id = '$walkInId'";
            $editResult = mysqli_query($connection, $editQuery);

            if ($editResult) {
                $editedWalk_in = mysqli_fetch_assoc($editResult);


        // Display a form for editing member details
        echo "<form action='update_walk_in.php' method='post'>";
        echo "<input type='hidden' name='id' value='" . $editedWalk_in['id'] . "'>";
        echo "<label for='name'>Name:</label>";
        echo "<input type='text' name='name' value='" . $editedWalk_in['name'] . "' required><br>";
        echo "<label for='name'>time in:</label>";
        echo "<input type='time' name='time_in' value='" . $editedWalk_in['time_in'] . "' required><br>";
        echo "<label for='name'>time out:</label>";
        echo "<input type='time' name='time_out' value='" . $editedWalk_in['time_out'] . "'><br>";
        echo "<button type='submit' name='update_walk-in'>Update trainer</button>";
        echo "</form>";

                } else {
                    echo "Error: " . mysqli_error($connection);
                }
            }

            // Close the database connection
            mysqli_close($connection);
    ?>
</body>
</html>


