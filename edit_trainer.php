<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Trainer</title>
</head>
<body>
    <?php
            // Connect to the database
        $connection = mysqli_connect("localhost", "root", "", "Coaches");

        // Check if the form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_trainer'])) {
            // Sanitize user input
            $trainerId = mysqli_real_escape_string($connection, $_POST['id']);

            // Retrieve member details for editing
            $editQuery = "SELECT * FROM trainers WHERE id = '$trainerId'";
            $editResult = mysqli_query($connection, $editQuery);

            if ($editResult) {
                $editedTrainer = mysqli_fetch_assoc($editResult);


        // Display a form for editing member details
        echo "<form action='update_trainer.php' method='post'>";
        echo "<input type='hidden' name='id' value='" . $editedTrainer['id'] . "'>";
        echo "<label for='name'>Name:</label>";
        echo "<input type='text' name='name' value='" . $editedTrainer['name'] . "' required><br>";
        echo "<button type='submit' name='update_trainer'>Update trainer</button>";
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


